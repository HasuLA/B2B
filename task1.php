<?php

$sql = <<< SQL
DROP TABLE IF EXISTS phone_numbers;
DROP TABLE IF EXISTS users;

CREATE TABLE `users` (
`id`         INT(11) NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(255) DEFAULT NULL,
    `gender`     INT(11) NOT NULL COMMENT '0 - не указан, 1 - мужчина, 2 - женщина.',
    `birth_date` INT(11) NOT NULL COMMENT 'Дата в unixtime.',
    PRIMARY KEY (`id`)
);
CREATE TABLE `phone_numbers` (
`id`      INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `phone`   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
);


# Оптимизация таблиц
ALTER TABLE `users` MODIFY name VARCHAR(30) DEFAULT NULL;
ALTER TABLE `phone_numbers` MODIFY `phone` VARCHAR(20);
ALTER TABLE `phone_numbers` ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE `phone_numbers` ADD INDEX `idx_phone_numbers-user_id` (user_id);


INSERT INTO `users` (`name`, `gender`, `birth_date`) VALUES
('Jonh', 1, 971669170),
    ('Jade', 2, 971669170),
    ('Tom', 1, 340520770),
    ('Ann', 2, 340520770),
    ('Sam', 0, 1008479170),
    ('Lisa', 2, 1008479170),
    ('Catrin', 2, 1008479170),
    ('Isabell', 2, 1008479170),
    ('Lolita', 2, 566629570),
    ('Beth', 2, 566629570);

INSERT INTO `phone_numbers` (`user_id`, `phone`) VALUES
(1, '+79998887766'),
    (1, '+79998887765'),
    (2, '+79988887777'),
    (4, '+79888887777'),
    (4, '+79888887776'),
    (4, '+79888887775'),
    (5, '+78888887775'),
    (5, '+78888887777'),
    (7, '+78888887774'),
    (7, '+78888887773'),
    (7, '+78888887772'),
    (7, '+78888887771'),
    (8, '+78888887771'),
    (10, '+78888887771');


# 567648000 - количество секунд в 18 годах, 693792000 - в 22х годах. 
# Как дату рождения выбираем промежуток между 18 и 22 годами от текущего момента. 
# Так мы получим девушек, которым сейчас между 18 и 22 годами.

SELECT name, IFNULL(COUNT(phone), 0) as phone_count 
FROM users LEFT JOIN phone_numbers ON phone_numbers.user_id = users.id 
WHERE birth_date < unix_timestamp() - 567648000 AND birth_date > unix_timestamp() - 693792000
AND gender = 2
GROUP BY name;
SQL;