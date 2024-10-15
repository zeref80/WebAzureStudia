DROP DATABASE IF EXISTS chat_db;
CREATE DATABASE chat_db DEFAULT CHARACTER SET utf8;
USE chat_db;

CREATE TABLE users (
   id INTEGER AUTO_INCREMENT PRIMARY KEY,
   username VARBINARY(1000) NOT NULL,
   password VARBINARY(1000) NOT NULL
);

CREATE TABLE messagesType (
   id INTEGER AUTO_INCREMENT PRIMARY KEY,
   typeName VARCHAR(50)
);

CREATE TABLE messages (
   id INTEGER AUTO_INCREMENT PRIMARY KEY,
   messageType INTEGER NOT NULL,
   sender_id INTEGER NOT NULL,
   receiver_id INTEGER DEFAULT NULL,
   content VARCHAR(1000),
   timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (messageType) REFERENCES messagesType(id),
   FOREIGN KEY (sender_id) REFERENCES users(id),
   FOREIGN KEY (receiver_id) REFERENCES users(id)
);

CREATE TABLE login (
    user_id INTEGER,
    isLogged INTEGER DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (username, password) VALUES
(AES_ENCRYPT('admin','ChatApp'), AES_ENCRYPT('admin','ChatApp')),
(AES_ENCRYPT('chat_bot','ChatApp'), AES_ENCRYPT('qwerty','ChatApp')),
(AES_ENCRYPT('user','ChatApp'), AES_ENCRYPT('en63bpax','ChatApp')),
(AES_ENCRYPT('test','ChatApp'), AES_ENCRYPT('test123!','ChatApp'));

INSERT INTO messagesType (typeName) VALUES ('general'),('private');

INSERT INTO login (user_id) VALUES (1),(2),(3),(4);