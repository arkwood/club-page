<?php
if ($drop) {
    $tables = array('page', 'section', 'sectionparameter');
    foreach ($tables as $table) {
        $sql = "DROP TABLE `" . $table . "`"; 
        echo $sql .'<br/>';
        mysql_query($sql);
        echo mysql_error() . '<br/>';
    }
}

$tableCreation = array(
        // create table navigation entry
        "CREATE TABLE IF NOT EXISTS `page` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `rootpage` tinyint NOT NULL,
          `defaultpage` tinyint NOT NULL,
          `active` tinyint NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;",
        // create table section
        "CREATE TABLE IF NOT EXISTS `section` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `pageid` int(11) NOT NULL,
            `width` tinyint NOT NULL,
            `sectiontype` varchar(255) NOT NULL,
            `position` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
        // create table section parameter
        "CREATE TABLE IF NOT EXISTS `sectionparameter` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sectionid` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
            `textvalue` text,
            `value` varchar(255),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
    );

foreach ($tableCreation as $sql) {
    echo $sql . '<br/>';
    mysql_query($sql);
    echo mysql_error() . '<br/>';
}
  

if ($demo) {
    // navigation
    $data = array(
        // root pages
        "insert into page (id, active, name, rootpage, defaultpage) 
            values (1, 1, 'Home', 1, 1);",
        "insert into page (id, active, name, rootpage, defaultpage) 
            values (2, 1, 'News', 1, 0);",
        "insert into page (id, active, name, rootpage, defaultpage) 
            values (3, 1, 'Herren', 1, 0);",
        // sections for root pages
        "insert into section (id, pageid, width, sectiontype, position) 
            values (1, 1, 12, 'views/content/wysiwyg', 1)",
            // section parameter
            "insert into sectionparameter (id, sectionid, name, textvalue) 
                values (1, 1, 'wysiwyg', 'This is the content of the home page')",
        "insert into section (id, pageid, width, sectiontype, position) 
            values (2, 2, 12, 'views/news/newslist', 1)",
            // section parameter
            "insert into sectionparameter (id, sectionid, name, value)
                values (2, 2, 'category', 1)"
    );
    foreach ($data as $query) {
        echo $query . '<br/>';
        mysql_query($query);
        echo mysql_error() . '<br/>';
    }
}
?>