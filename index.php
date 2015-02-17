<?php
// initialize
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
						<div class="row">
							<div class="col-md-10">
								<a class="navi_link" href="<?php echo BASEURL . CURRENT_LOCALE . '/blog'; ?>">Blog</a>
								<a class="navi_link" href="<?php echo BASEURL . CURRENT_LOCALE . '/archive'; ?>"><?php echo Localization::$MENU_ARCHIVE[CURRENT_LOCALE]; ?></a>
								<a class="navi_link" href="<?php echo BASEURL . CURRENT_LOCALE . '/disclaimer'; ?>">Disclaimer</a>
							</div>
							<div class="col-md-2 right lang">
								<!-- 
								<form action="<?php echo BASEURL . 'search' ?>" method="post">
									<input type="text" name="searchTerm" />
									<button type="submit" class="btn btn-secondary btn-sml" name="search">Search</button>
								</form>
								 -->
								<a href="<?php echo ((strpos($_SERVER["REQUEST_URI"], "/en/") !== false || strpos($_SERVER["REQUEST_URI"], "/de/") !== false) ? str_ireplace('/en/', '/de/', $_SERVER["REQUEST_URI"]) : $_SERVER["REQUEST_URI"] . 'de/') . (isset($_POST["searchTerm"]) ? '/' . urlencode($_POST["searchTerm"])  : '');  ?>">
									<img src="<?php echo BASEURL; ?>img/icon_de.gif" alt="de" title="Wechsel Sprache zu Deutsch"/>
								</a>
								<a href="<?php echo ((strpos($_SERVER["REQUEST_URI"], "/de/") !== false || strpos($_SERVER["REQUEST_URI"], "/en/") !== false) ? str_ireplace('/de/', '/en/', $_SERVER["REQUEST_URI"]) : $_SERVER["REQUEST_URI"] . 'en/') . (isset($_POST["searchTerm"]) ? '/' . urlencode($_POST["searchTerm"])  : ''); ?>">
									<img src="<?php echo BASEURL; ?>img/icon_en.gif" alt="en" title="Change language to English"/>
								</a>								
							</div>
						</div>						
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