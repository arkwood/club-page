<?php
$sql = "CREATE TABLE IF NOT EXISTS `navigationentry` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `label` varchar(64),
          `icon` varchar(32),
          `target` varchar(255) NOT NULL,
          `active` tinyint NOT NULL,
          `position` int NOT NULL,
          `parent` int,
          `defaultnav` tinyint NOT NULL,
          `pageid` int,
          `external` tinyint NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

if ($drop) {
    mysql_query("DROP TABLE `navigationentry`");
    echo ("DROP TABLE `navigationentry`");
    echo mysql_error() . '<br/>';    
}       
mysql_query($sql);
echo $sql . '<br/>';
echo mysql_error() . '<br/>';

if ($demo) {
    $data = array(
        // main navigation
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav, pageid, external) 
            values (1, 'News', null, 'news', 1, 1, null, 1, 1, 0);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav, pageid, external) 
            values (2, 'Mannschaften', null, 'mannschaften', 1, 2, null, 0, 1, 0);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav, pageid, external)
            values (3, 'Ergebnisse', null, 'ergebnisse', 1, 3, null, 0, 1, 0);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav, pageid, external)
            values (4, 'Kontakt', null, 'kontakt', 1, 4, null, 0, 1, 0);",
        // level 2 navigation
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav, pageid, external)
            values (5, 'Herren', null, 'mannschaft/herren', 1, 1, 2, 0, 1, 0);",
        "insert into navigationentry (id, label, icon, target, active, position, parent, defaultnav, pageid, external)
            values (6, 'Damen', null, 'mannschaft/damen', 1, 2, 2, 0, 1, 0);",
    );
    foreach ($data as $query) {
        mysql_query($query);
        echo mysql_error() . '<br/>';
        echo $query . '<br/>';
    }
}
?>