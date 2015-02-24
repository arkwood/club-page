<?php
if ($drop) {
    $tables = array('news', 'newscategory');
    dropTables($tables);
}

$tableCreation = array(
        // create table news
        "CREATE TABLE IF NOT EXISTS `news` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(255) NOT NULL,
          `published` datetime,
          `categoryid` int(11) NOT NULL,
          `authorid` int(11) NOT NULL,
		  `text` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;",
        // create table news category
        "CREATE TABLE IF NOT EXISTS `newscategory` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
    );

executeSQL($tableCreation);  

if ($demo) {
    // navigation
    $data = array(
        // insert news
        "insert into news (id, title, published, categoryid, authorid, text) 
            values (1, 'New Homepage Online', now(), 1, 1, 'We have just released the new homepage. Have fun!');",
        "insert into newscategory (id, name) 
        	values (1, 'General');",
        "insert into newscategory (id, name) 
        	values (2, 'Sport');",
        "insert into newscategory (id, name) 
        	values (3, 'Club');"
    );
    executeSQL($data);
}
?>