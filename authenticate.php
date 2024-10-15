<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'pdo.php';

    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare('SELECT id, AES_DECRYPT(username,"ChatApp") AS us, AES_DECRYPT(password,"ChatApp") AS passw FROM users WHERE AES_DECRYPT(username,"ChatApp") = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password == $user['passw']) {
            $_SESSION['user_id'] = $user['id'];
            $stmt2 = $pdo->prepare('SELECT user_id, isLogged FROM login WHERE user_id = ?');
            $stmt2->execute([$_SESSION['user_id']]);
            $login = $stmt2->fetch(PDO::FETCH_ASSOC);

            if($login['isLogged']==1){
                header('Location: login.php?error=2');
                exit;
            }
            else{
                $stmt3 = $pdo->prepare('UPDATE login SET isLogged = 1 WHERE user_id = ?');
                $stmt3->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt3->execute();
                header('Location: index.php');
                exit;
            }
        }
    }
}

header('Location: login.php?error=1');
exit;
?>
