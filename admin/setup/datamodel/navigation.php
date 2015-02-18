<?php
$sql = "CREATE TABLE IF NOT EXISTS `navigationentries` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `label` varchar(64),
          `icon` varchar(32),
          `target` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
        
mysql_query($sql);
?>