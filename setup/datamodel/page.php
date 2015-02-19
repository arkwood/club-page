<?php
if ($drop) {
    $tables = array('navigationentry', 'section', 'sectionparameter');
    foreach ($tables as $table) {
        echo 'DROP TABLE `' . table . + '`<br/>';
        mysql_query("DROP TABLE `" + table + "`");
        echo mysql_error() . '<br/>';
    }
}

$tableCreation = array(
        // create table navigation entry
        "CREATE TABLE IF NOT EXISTS `navigationentry` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `label` varchar(64),
          `icon` varchar(32),
          `target` varchar(255) NOT NULL,
          `active` tinyint NOT NULL,
          `position` int NOT NULL,
          `parent` int,
          `defaultnav` tinyint NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;",
        // create table section
        "CREATE TABLE IF NOT EXISTS `section` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `pageid` int(11) NOT NULL,
            `width` tinyint NOT NULL,
            `type` varchar(255) NOT NULL,
            `subpage` int(11),
            `view` varchar(255),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
        // create table section parameter
        "CREATE TABLE IF NOT EXISTS `sectionparameter` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
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
        // main navigation
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav) 
            values (1, 'News', null, 'news', 1, 1, null, 1);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav) 
            values (2, 'Mannschaften', null, 'mannschaften', 1, 2, null, 0);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav)
            values (3, 'Ergebnisse', null, 'ergebnisse', 1, 3, null, 0);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav)
            values (4, 'Kontakt', null, 'kontakt', 1, 4, null, 0);",
        // level 2 navigation
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav)
            values (5, 'Herren', null, 'mannschaft/herren', 1, 1, 2, 0);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav)
            values (6, 'Damen', null, 'mannschaft/damen', 1, 2, 2, 0);",
    );
    foreach ($data as $query) {
        echo $query . '<br/>';
        mysql_query($query);
        echo mysql_error() . '<br/>';
    }
}
?>