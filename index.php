<?php
// initialize system
require_once 'loader.php';
// defaults
$title = 'Club Page';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="author" content="Peter Ilfrich" />
		<meta name="keywords" content="<?php echo $metaKeywords; ?>" />
		<meta name="description" content="<?php echo $metaDescription; ?>" />
		<link rel="alternate" type="application/rss+xml" title="Club Page" href="/rss" />
		<title><?php echo $title; ?></title>
		<?php  echo $GLOBAL[GLOBALSTATICCONTENT]; ?>
	</head>
	<body>		
		<header role="banner">
			<div class="container">
				<div id="banner" class="hidden-xs row">
				    <div class="col-xs-2 center">
				        <i class="fa fa-soccer-ball-o fa-4x"></i>    
				    </div>
				    <div class="col-xs-5">
				        <b>ClubSport</b> - Content-Management-System<br/>and Club-Management    
				    </div>
				</div>
				<div id="navigation">
				    <?php 
				    $nav = new Navigation(true);
				    echo $twig->render('layout/navigation.html', array("basepath" => BASEURL, "entries" => $nav->entries));
				    ?>						
				</div>
				
			</div>
		</header>
		<div id="main" class="container">
			<?php 
			if (!isset($GLOBALS[GLOBALSCRIPTCONTENT]))
				require_once DEFAULT_CONTENT;
			
			echo $GLOBALS[GLOBALSCRIPTCONTENT]; 
			?>
		</div>
	</body>
</html>
