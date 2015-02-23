<?php
if ($drop) {
    $tables = array('user', 'permission');
    dropTables($tables);
}

$tableCreation = array(
        // create table navigation entry
        "CREATE TABLE IF NOT EXISTS `user` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `email` varchar(255) NOT NULL,
          `login` varchar(255) NOT NULL,
          `password` varchar(255) NOT NULL,
          `active` tinyint NOT NULL,
          `lastlogin` datetime,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;",
        // create table section
        "CREATE TABLE IF NOT EXISTS `permission` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `userid` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
            `value` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
    );

executeSQL($tableCreation);  

if ($demo) {
    // navigation
    $data = array(
        // admin user
        "insert into user (id, name, email, login, password, active, lastlogin) 
            values (1, 'Administrator', 'admin@isworks.de', 'admin', md5('admin'), 1, now());",
        // permissions
        "insert into permission (id, userid, name, value) 
            values (1, 1, 'news', 2)",
        "insert into permission (id, userid, name, value) 
            values (2, 1, 'cms', 2)"
    );
    executeSQL($data);
}
?>