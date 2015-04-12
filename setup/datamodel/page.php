<?php
if ($drop) {
    $tables = array('page', 'section', 'sectionparameter', 'layout', 'container');
    dropTables($tables);
}

$tableCreation = array(
        // create table navigation entry
        "CREATE TABLE IF NOT EXISTS `page` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `rootpage` tinyint NOT NULL,
          `defaultpage` tinyint NOT NULL,
          `active` tinyint NOT NULL,
		  `parentpageid` int(11),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;",
		
		// create container
		"CREATE TABLE IF NOT EXISTS `container` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`pageid` int(11) NOT NULL,
			`position` int(11) NOT NULL,
			`layout` text NOT NULL,
			`layoutidentifier` varchar(255),
            PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
		
        // create table section
        "CREATE TABLE IF NOT EXISTS `section` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `containerid` int(11) NOT NULL,
            `sectiontype` varchar(255) NOT NULL,
			`sectionview` varchar(255) NOT NULL,
            `position` int(11) NOT NULL,
			`label` varchar(255),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
		
        // create table section parameter
        "CREATE TABLE IF NOT EXISTS `sectionparameter` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sectionid` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
            `textvalue` text,
			`datevalue` datetime,
            `value` varchar(255),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
		
		// create layout storage
		"CREATE TABLE IF NOT EXISTS `layout` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`identifier` varchar(255) NOT NULL,
			`include` tinyint NOT NULL,
            `position` int(11),
            `rows` text NOT NULL,
            PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    );

executeSQL($tableCreation);  


/*
 * Handle Layout Data
 */
 if (demo) {
	echo '<p><b>Processing Layout Data</b></p>';
	
	$layoutData = json_decode(file_get_contents(dirname(__FILE__) . '/data/layout.json'));
	$layouts = $layoutData->layouts;
	$layoutSQL = array();
	
	for ($i = 0; $i < sizeof($layouts); $i++) {
		$layout = $layouts[$i];
		array_push($layoutSQL, "insert into layout (id, identifier, include, position, rows) 
			values (" . ($i + 1) . ", '" . $layout->{'identifier'} . "', " . ($layout->isInclude ? "1" : "0") . ", " . $i . ", '" . json_encode($layout->rows) . "')");
	}
	executeSQL($layoutSQL);
	echo '<br/>';
	
	$homeLayout = '[{"cols":[{"width": 6},{"width": 6} ]}, {"cols": [ {"width": 12} ]} ]';
	$newsLayout = '[{"cols":[{"width":12}]}]';
	$herrenLayout = '[{"cols":[{"width": 6},{"width": 6} ]}, {"cols": [ {"width": 12} ]} ]';

	/*
	 * Handle the rest of the data
	 */
    // navigation
    $data = array(
        /*
         * Root Pages
         */ 
        "insert into page (id, active, name, rootpage, defaultpage) 
            values (1, 1, 'Home', 1, 1);",
        "insert into page (id, active, name, rootpage, defaultpage) 
            values (2, 1, 'News', 1, 0);",
        "insert into page (id, active, name, rootpage, defaultpage) 
            values (3, 1, 'Herren', 1, 0);",
    	
    	/*
    	 * Container
    	 */ 
    	
    	// home
    	"insert into container (id, pageid, position, layout, layoutidentifier)
    		values (1, 1, 0, '" . $homeLayout . "', 'top-split')",
    	// news
    	"insert into container (id, pageid, position, layout, layoutidentifier)
    		values (2, 2, 0, '" . $newsLayout . "', 'main')",
    	// herren
    	"insert into container (id, pageid, position, layout, layoutidentifier)
    		values (3, 3, 0, '" . $herrenLayout . "', 'top-split')",
    	
    	/*
    	 * Sections
    	 */
    		
        // sections for home
        //
        "insert into section (id, containerid, sectiontype, sectionview, position, label) 
            values (1, 1, 'wysiwyg', 'views/content/wysiwyg', 0, 'Home Left')",
            // section parameter
            "insert into sectionparameter (id, sectionid, name, textvalue) 
                values (1, 1, 'wysiwyg', 'This is the content of the home page')",
    	//
    	"insert into section (id, containerid, sectiontype, sectionview, position, label)
            values (2, 1, 'wysiwyg', 'views/content/wysiwyg', 1, 'Home Right')",
            // section parameter
    		"insert into sectionparameter (id, sectionid, name, textvalue)
                values (2, 2, 'wysiwyg', 'This is the content of the home page')",
    	//
    	"insert into section (id, containerid, sectiontype, sectionview, position, label)
            values (3, 1, 'wysiwyg', 'views/content/wysiwyg', 2, 'Home Bottom')",
			// section parameter
    		"insert into sectionparameter (id, sectionid, name, textvalue)
                values (3, 3, 'wysiwyg', 'This is the content of the home page')",
    	// section for news
    	//
        "insert into section (id, containerid, sectiontype, sectionview, position, label) 
            values (4, 2, 'newslist', 'views/news/newslist', 0, 'News List')",
            // section parameter
            "insert into sectionparameter (id, sectionid, name, value)
                values (4, 4, 'category', 1)"
    );
    executeSQL($data);
}
?>