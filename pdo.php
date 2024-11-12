<?php
    $cert = 'DigiCertGlobalRootCA.crt.pem';
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => $cert,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $pdo = new PDO('mysql:host=chatapp-db.mysql.database.azure.com;port=3306;dbname=chat_db', 'adm_cht54', 'QCG77aqGLyVrYPM', $options);
?>
