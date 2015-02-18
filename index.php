
<?php
// development mode
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_PATH', dirname(__FILE__) . '/');

// initialize template engine
require_once BASE_PATH . 'lib/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
$twigLoader = new Twig_Loader_Filesystem(BASE_PATH . 'templates');
$twig = new Twig_Environment($twigLoader, array(
    'cache' => BASE_PATH . 'tpl_compile/cache',
));

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
				<div id="banner"></div>
				<nav role="navigation">
					<div id="navigation">
					    <?php 
					    $nav = new Navigation();
					    echo $twig->render('layout/navigation.html', array("entries" => $nav->entries));
					    ?>						
					</div>
				</nav>
			</div>
		</header>
		<div id="content" class="container">
			<?php 
			if (!isset($GLOBALS[GLOBALSCRIPTCONTENT]))
				require_once DEFAULT_CONTENT;
			
			echo $GLOBALS[GLOBALSCRIPTCONTENT]; 
			?>
		</div>
	</body>
</html>
