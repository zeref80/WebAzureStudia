<?php
session_start();

header("Cache-Control: no-cache, must-revalidate");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'pdo.php';

if (!isset($_SESSION['general_id'])) {
    $_SESSION['general_id'] = 1;
}

if (isset($_POST['gen1'])) {
    $_SESSION['general_id'] = 1;
} elseif (isset($_POST['gen2'])) {
    $_SESSION['general_id'] = 2;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT AES_DECRYPT(username,"ChatApp") AS decrypted from users WHERE id = :user_id;');
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $user['decrypted'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];

    $iv = 'a1b2c3d4e5f6g7h8';
    $key = 'ChatApp';
    $encrypted_content = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);

    $stmt = $pdo->prepare('INSERT INTO messages (messageType, sender_id, content, timestamp) VALUES (1, :user_id, :encrypted_content, CURRENT_TIMESTAMP)');
    
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':encrypted_content', $encrypted_content, PDO::PARAM_STR);
    
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChatApp</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            function refreshChat() {
                $.ajax({
                    url: 'get_messages.php',
                    type: 'GET',
                    success: function(data) {
                        $('#chat-box').html(data);
                    }
                });
            }

            setInterval(refreshChat, 500);

            $('msg').submit(function(event){
                event.preventDefault();
                var formData = $(this).serialize();
                $.post('index.php', formData, function(){
                    refreshChat();
                    $('msg')[0].reset();
                });
            });

            refreshChat();
        });

    </script>
    
</head>
<body>
    <div class="container-fluid">
        <div class="row p-2 bg-color-purple justify-content-center">
            <a href="logout.php" style="color: white">Logout</a>
            <h2 class="col-12">ChatApp</h2>
            <h1>Current Session Value: <?php echo $_SESSION['general_id']; ?></h1>
            <h4 class="col-5"><?php echo "Welcome $username!"; ?></h4>
            <form method="post" action="">
                <button type='submit' name='gen1' class='btn btn-secondary'><style='text-decoration: none; color: white';>General</a></button>
                <button type='submit' name='gen2' class='btn btn-secondary'><style='text-decoration: none; color: white';>General2</a></button>
            </form>
            <div>You're talking on <b>General channel</b></div>
        </div>
        <div id="chat-box" class="row p-2 bg-color-gray">
            <!-- Wyświetlanie wiadomości -->
        </div>
    <form method="post" class="row p-2 bg-color-purple send-message" name="msg">
        <div class="col-9 col-md-10">
            <input type="text" id="content" name="content" class="form-control" placeholder="Write your message" required>
        </div>
        <div class="col-3 col-md-2">
            <button type="submit" class="btn btn-light form-control">Send</button>
        </div>
    </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
