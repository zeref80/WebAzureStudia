use chat_db;

SELECT * from messages;

select * from messagestype;

select * from users;

select CAST(AES_DECRYPT(username,'ChatApp') AS char) AS user from users;

#audit log
#pokazac sql injection
#szyfrowanie ssl php z bazą danych
#backupy i restory
#wywalic tabele messageType
#szyfrowanie danych w spoczynku - czy mozliwe na poziomie serwera (ustawienia)

select * from login;

#' OR '1'='1

#private_chat.php?private_id=1; DROP TABLE messages;

#private_chat.php?private_id=1 OR 1=1 --
