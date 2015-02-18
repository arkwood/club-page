<?php
$sql = "CREATE TABLE IF NOT EXISTS `navigationentries` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `label` varchar(64),
          `icon` varchar(32),
          `target` varchar(255) NOT NULL,
          `active` tinyint NOT NULL,
          `position` int NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

if ($drop) {
    mysql_query("DROP TABLE `navigationentries`");    
}       
mysql_query($sql);

if ($demo) {
    $data = "insert into navigationentries (label, icon, target, active, position) values ('Mannschaften', null, '/mannschaften', 1, 1);";
    mysql_query(data);
}
?>