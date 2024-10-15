<?php
    session_start();
    require_once 'pdo.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    
    }
    $stmt = $pdo->prepare('UPDATE login SET isLogged = 0 WHERE user_id = ?');
    $stmt->bindParam(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    session_destroy();
    header('Location: login.php');
    exit;
?>