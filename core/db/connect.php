<?php
//$db = new mysqli('host', 'username', 'password', 'table');

//$host="127.0.0.1";
//$port=3306;
//$socket="";
//$user="root";
//$password="";
//$dbname="cs4014";

//$host="127.0.0.1";
//$port=3306;
//$socket="";
//$user="root";
//$password="";
//$dbname="cs4014populatedtest";

//ul
$host="p:193.1.101.7";
$port=3307;
$socket="";
$user="group22";
$password="LdoOq0a0P";
$dbname="group22DB";

$db = new mysqli($host, $user, $password, $dbname, $port, $socket)
or die ('Could not connect to the database server' . mysqli_connect_error());


function db_setup() {

    db_create_tables();
    db_fill_tables();

}

function db_create_tables() {
    global $db;
    global $dbname;

    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`roles` (
          `role_id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` VARCHAR(20) NOT NULL,
          `weight` TINYINT(2) UNSIGNED NOT NULL,
          `delete_users` BIT(1) NOT NULL DEFAULT b'0',
          `ban_users` BIT(1) NOT NULL DEFAULT b'0',
          `edit_users` BIT(1) NOT NULL DEFAULT b'0',
          `list_users` BIT(1) NOT NULL DEFAULT b'0',
          `edit_others_profile` BIT(1) NOT NULL DEFAULT b'0',
          `delete_others_profile` BIT(1) NOT NULL DEFAULT b'0',
          `view_admin_dashboard` BIT(1) NOT NULL DEFAULT b'0',
          `create_profile` BIT(1) NOT NULL DEFAULT b'0',
          `edit_profile` BIT(1) NOT NULL DEFAULT b'0',
          `view_profiles` BIT(1) NOT NULL DEFAULT b'0',
          `send_messages` BIT(1) NOT NULL DEFAULT b'0',
          `read_messages` BIT(1) NOT NULL DEFAULT b'0',
          `delete_profile` BIT(1) NOT NULL DEFAULT b'0',
          `search_profiles` BIT(1) NOT NULL DEFAULT b'0',
          `edit_settings` BIT(1) NOT NULL DEFAULT b'0',
          `view_user_dashboard` BIT(1) NOT NULL DEFAULT b'0',
          PRIMARY KEY (`role_id`),
          UNIQUE INDEX `name_UNIQUE` (`name` ASC),
          UNIQUE INDEX `weight_UNIQUE` (`weight` ASC)
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`users` (
          `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `email` VARCHAR(40) NOT NULL,
          `password` CHAR(64) NOT NULL,
          `role_id` TINYINT(2) UNSIGNED NOT NULL DEFAULT '2',
          PRIMARY KEY (`user_id`),
          UNIQUE INDEX `email_UNIQUE` (`email` ASC),
          INDEX `role_id_idx` (`role_id` ASC),
          CONSTRAINT `users_role_id`
            FOREIGN KEY (`role_id`)
            REFERENCES `cs4014`.`roles` (`role_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`notification_type` (
          `type_id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
          `type` VARCHAR(30) NOT NULL,
          PRIMARY KEY (`type_id`),
          UNIQUE INDEX `type_UNIQUE` (`type` ASC)
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`notifications` (
          `notification_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` INT(10) UNSIGNED NOT NULL,
          `sender_id` INT(10) UNSIGNED NULL DEFAULT NULL,
          `seen` BIT(1) NOT NULL DEFAULT b'0',
          `content` TEXT NOT NULL,
          `link` TEXT NULL DEFAULT NULL,
          `type_id` TINYINT(2) UNSIGNED NOT NULL,
          `date_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`notification_id`, `user_id`),
          INDEX `user_id_idx` (`user_id` ASC),
          INDEX `sender_id_idx` (`sender_id` ASC),
          INDEX `notifications_notification_type` (`type_id` ASC),
          CONSTRAINT `notifications_notification_type`
            FOREIGN KEY (`type_id`)
            REFERENCES `cs4014`.`notification_type` (`type_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `notifications_sender_id`
            FOREIGN KEY (`sender_id`)
            REFERENCES `cs4014`.`users` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `notifications_user_id`
            FOREIGN KEY (`user_id`)
            REFERENCES `cs4014`.`users` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`user_relataionship_status` (
          `status_id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
          `status` VARCHAR(30) NOT NULL,
          PRIMARY KEY (`status_id`),
          UNIQUE INDEX `status_UNIQUE` (`status` ASC)
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`user_relationships` (
          `user_id` INT(10) UNSIGNED NOT NULL,
          `target_user_id` INT(10) UNSIGNED NOT NULL,
          `status_id` TINYINT(2) UNSIGNED NOT NULL,
          PRIMARY KEY (`user_id`, `target_user_id`),
          INDEX `status_id_idx` (`status_id` ASC),
          INDEX `targetuserid_idx` (`target_user_id` ASC),
          CONSTRAINT `user_relationships_status_id`
            FOREIGN KEY (`status_id`)
            REFERENCES `cs4014`.`user_relataionship_status` (`status_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `user_relationships_target_user_id`
            FOREIGN KEY (`target_user_id`)
            REFERENCES `cs4014`.`users` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `user_relationships_user_id`
            FOREIGN KEY (`user_id`)
            REFERENCES `cs4014`.`users` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`interests` (
          `interests_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `content` VARCHAR(30) NOT NULL,
          PRIMARY KEY (`interests_id`),
          UNIQUE INDEX `content_UNIQUE` (`content` ASC)
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`profiles` (
          `user_id` INT(10) UNSIGNED NOT NULL,
          `first_name` VARCHAR(30) NOT NULL,
          `last_name` VARCHAR(30) NOT NULL,
          `DOB` DATE NOT NULL,
          `sex` BIT(1) NOT NULL,
          `description` TEXT NULL DEFAULT NULL,
          `country` VARCHAR(30) NULL DEFAULT NULL,
          `county` VARCHAR(30) NULL DEFAULT NULL,
          `looking_for` BIT(1) NULL DEFAULT NULL,
          `min_age` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
          `max_age` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
          PRIMARY KEY (`user_id`),
          CONSTRAINT `profiles_user_id`
            FOREIGN KEY (`user_id`)
            REFERENCES `cs4014`.`users` (`user_id`)
            ON DELETE CASCADE
            ON UPDATE NO ACTION
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`profile_likes` (
          `like_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` INT(10) UNSIGNED NOT NULL,
          `interests_id` INT(10) UNSIGNED NOT NULL,
          PRIMARY KEY (`like_id`, `user_id`),
          INDEX `profile_likes_interests_id_idx` (`interests_id` ASC),
          INDEX `profile_likes_user_id_idx` (`user_id` ASC),
          CONSTRAINT `profile_likes_user_id`
            FOREIGN KEY (`user_id`)
            REFERENCES `cs4014`.`profiles` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `profile_likes_interests_id`
            FOREIGN KEY (`interests_id`)
            REFERENCES `cs4014`.`interests` (`interests_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();


    $prepared = $db->prepare("
        CREATE TABLE IF NOT EXISTS `?`.`profile_dislikes` (
          `dislike_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
          `user_id` INT(10) UNSIGNED NOT NULL,
          `interests_id` INT(10) UNSIGNED NOT NULL,
          PRIMARY KEY (`dislike_id`, `user_id`),
          INDEX `profile_dislikes_interests_id_idx` (`interests_id` ASC),
          INDEX `profile_dislikes_user_id_idx` (`user_id` ASC),
          CONSTRAINT `profile_dislikes_user_id`
            FOREIGN KEY (`user_id`)
            REFERENCES `cs4014`.`profiles` (`user_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
          CONSTRAINT `profile_dislikes_interests_id`
            FOREIGN KEY (`interests_id`)
            REFERENCES `cs4014`.`interests` (`interests_id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
        )
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = latin1
    ");

    $prepared->bind_param('s', $dbname); //s - string
    $prepared->execute();
    $prepared->free_result();

}



function db_fill_tables() {
    global $db;
    global $dbname;
}

?>