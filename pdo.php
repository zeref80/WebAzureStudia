<?php
    $cert = 'DigiCertGlobalRootCA.crt.pem';
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => $cert,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $pdo = new PDO('mysql:host=chatapp2.mysql.database.azure.com;port=3306;dbname=chat_db', 'fgoccwpufa', 'Chatappdb1', $options);
?>
