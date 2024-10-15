    <?php
	error_log("0", 0);
    session_start();
	error_log("1", 0);
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once 'pdo.php';
	error_log("2", 0);
    if (isset($_GET['private_id'])) {
        $private_id = intval($_GET['private_id']);

        $stmt = $pdo->prepare('SELECT COUNT(*) AS count FROM users WHERE id = :private_id');
        $stmt->bindParam(':private_id', $private_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            $_SESSION['private_id'] = $private_id;
        } else {
            header('Location: index.php');
            exit;
        }
    }
	error_log("3", 0);
    $stmt = $pdo->query('SELECT id, AES_DECRYPT(username,"ChatApp") AS decrypted FROM users');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($row['id']==$private_id){
            $private_name = $row['decrypted'];
        }
    }

    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT id, AES_DECRYPT(username,"ChatApp") AS decrypted from users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $user['decrypted'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
        $content = $_POST['content'];
        $iv = 'a1b2c3d4e5f6g7h8';
        $key = 'ChatApp';
        $encrypted_content = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);

        $stmt = $pdo->prepare('INSERT INTO messages (messageType, sender_id, receiver_id, content, timestamp) VALUES (2, :user_id, :private_id, :encrypted_content, CURRENT_TIMESTAMP)');

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':private_id', $_SESSION['private_id'], PDO::PARAM_INT);
        $stmt->bindParam(':encrypted_content', $encrypted_content, PDO::PARAM_STR);

        $stmt->execute();
    }
	error_log("5", 0);
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
                        url: 'get_private.php',
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
                    $.post('private_chat.php', formData, function(){
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
            <div class="row p-2 bg-color-purple">
                <a href="logout.php" style="color: white">Logout</a>
                <h2 class="col-12">ChatApp</h2>
                <h4 class="col-12"><?php echo "Welcome $username!"; ?></h4>
                <form method="get" action="private_chat.php">
                <button type='button' class='btn btn-secondary'><a href='index.php' style='text-decoration: none; color: white';>General</a></button>
                <?php
                    $stmt = $pdo->prepare('SELECT id, AES_DECRYPT(username,"ChatApp") AS username FROM users WHERE id != ?');
                    $stmt->execute([$user_id]);
                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($users as $user) {
                        echo "<button type='button' class='btn btn-secondary'><a href='private_chat.php?private_id=".$user['id']."' style='text-decoration: none;
                        color: white';>".$user['username']."</a></button> ";
                    }
                ?>
                </form>
                <div>You're talking with <b><?php echo $private_name ?></b></div>
            </div>
            <div id="chat-box" class="row p-2 bg-color-gray">
                <!-- Wyświetlanie wiadomości -->
            </div>
        <form method="post" class="row p-2 bg-color-purple send-message" name="msg">
            <div class="col-9 col-md-10">
                <input type="text" name="content" class="form-control" placeholder="Write your message" required>
            </div>
            <div class="col-3 col-md-2">
                <button type="submit" class="btn btn-light form-control">Send</button>
            </div>
        </div>
        </form>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    </body>
    </html>