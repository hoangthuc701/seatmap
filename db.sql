USE `seatmap`

CREATE TABLE `admin` (
  `id` INT NOT NULL ,
  `username` VARCHAR (100) NOT NULL,
  `password` VARCHAR (100) NOT NULL,
  `email` VARCHAR (100) NOT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)  ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


CREATE TABLE `user` (
  `id` INT NOT NULL  ,
  `username` VARCHAR (100) NOT NULL,
  `email` VARCHAR (100) NOT NULL,
  `avatar` VARCHAR (100) NOT NULL,
  `created_at`  TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)  ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `seatmap` (
  `id` INT NOT NULL ,
  `content` VARCHAR (1000) NOT NULL,
  `file` VARCHAR (200) NOT NULL,
  `owner` INT NOT NULL,
  `created_at`  TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)  ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `seatmap_user` (
  `id` INT NOT NULL ,
  `user_id` INT ,
  `seat_id` INT NOT NULL,
  `coordinates_x` FLOAT NOT NULL,
  `coordinates_y` FLOAT NOT NULL,
  `group` INT,
  `note` VARCHAR (100),
  `created_at`  TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)  ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `group` (
  `id` INT ,
  `name` VARCHAR(255),
  `color` VARCHAR(255),
  `created_at`  TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)  ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- Add primary key
ALTER TABLE `admin` ADD PRIMARY KEY (`id`)
ALTER TABLE `user` ADD PRIMARY KEY (`id`)
ALTER TABLE `seatmap` ADD PRIMARY KEY (`id`)
ALTER TABLE `seatmap_user` ADD PRIMARY KEY (`id`)
ALTER TABLE `group` ADD PRIMARY KEY (`id`)


ALTER TABLE `admin` MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1

ALTER TABLE `user` MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1

ALTER TABLE `seatmap` MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1

ALTER TABLE `seatmap_user` MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1

ALTER TABLE `group` MODIFY `id` INT NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1





-- add foreign key
ALTER TABLE `seatmap_user` ADD FOREIGN KEY (`user_id`) REFERENCES `user`(id) ON DELETE CASCADE;
ALTER TABLE `seatmap_user` ADD FOREIGN KEY (`seat_id`) REFERENCES `seatmap`(id) ON DELETE CASCADE;


-- insert sample data
INSERT INTO `admin` (`username`, `password`,`email`) VALUES ('admin', 'e10adc3949ba59abbe56e057f20f883e','admin@seatmap.com')


