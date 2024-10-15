<?php
    $pdo = new PDO('mysql:host=chatapp2.mysql.database.azure.com;port=3306;dbname=chat_db', 'fgoccwpufa', 'Chatappdb1');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>