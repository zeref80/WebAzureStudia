<?php
require_once 'pdo.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

header('index.php');

$stmt = $pdo->prepare('SELECT messages.content, messages.timestamp, AES_DECRYPT(username,"ChatApp") AS us, messages.sender_id, messages.receiver_id 
                       FROM messages
                       INNER JOIN users ON messages.sender_id = users.id
                       ORDER BY messages.timestamp ASC');
$stmt->execute();

$iv = 'a1b2c3d4e5f6g7h8';
$key = 'ChatApp';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['receiver_id'] === null) {
        $messageDate = strtotime($row['timestamp']);
        $currentDate = strtotime(date('Y-m-d'));
        $diff = $currentDate - $messageDate;
        $daysDiff = floor($diff / (60 * 60 * 24)) + 1;
        $decrypted_content = openssl_decrypt($row['content'], 'AES-256-CBC', $key, 0, $iv);
        if ($daysDiff == 0) {
            $echoDate = 'Today, ' . date('H:i', $messageDate);
        } elseif ($daysDiff == 1) {
            $echoDate = 'Yesterday, ' . date('H:i', $messageDate);
        } else {
            $echoDate = date('Y-m-d', $messageDate);
        }
        if ($row['sender_id'] == $_SESSION['user_id']) {
            echo '<div class="col-12">
                <div class="d-flex">
                   <div class="message my-message">
                      <div style="font-size: 12px">' . $echoDate . '</div>
                      <b class="message-header">Me </b> 
                      <div>' . $decrypted_content . '</div>
                   </div>
                </div>
             </div>';
        } else {
            echo '<div class="col-12">
                <div class="d-flex">
                   <div class="message others-message">
                      <div style="font-size: 12px">' . $echoDate . '</div>
                      <b class="message-header">' . $row['us'] . '</b>
                      <div>' . $decrypted_content . '</div>
                   </div>
                </div>
             </div>';
        }
    }
}
?>
