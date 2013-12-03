<?php

// Start the named session
session_name('loginSession');
session_start();

// Allow the included files to be executed
define('inc_file', TRUE);

?>

<!DOCTYPE html>
<html>

<head>
	<title>Snap-2-Ask | About Us</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="res/favicon.ico">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
	<?php include_once("ganalytics.php") ?>
	<header class="tall">
		<a href="index.php"> <img id="logoTall" src="res/logo.png" alt="Snap-2-Ask Logo"/> </a>
	</header>

	<div id="content">

		<div id="aboutUsContainer" >

			<h1>About Us</h1>
			
			<h3>Raz Friman</h3>
			
			<h3>Raymond Martin</h3>
			
			<h3>Roman Stolyarov</h3>
			
			<h3>Elena Villamil</h3>
			
			<h3>Vipul Kohli</h3>

		</div>
	</div>
	
	<?php include('footer.php') ?>
</body>
</html>