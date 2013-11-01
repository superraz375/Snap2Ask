<?php

if (!defined('inc_file')) {
	die('Direct access is forbidden');
}

// Require the functions file
require_once('functions.php');

$responseObj = getUserInfo(true);
$categories = getCategories();

// Load these from the session (set during log-in)
$first_name = '';
$balance = 0;

if (isset($responseObj['first_name'])) {
	$first_name = $responseObj['first_name'];
}

if (isset($responseObj['balance'])) {
	$balance = $responseObj['balance'];
}

$searchQuery = '';

if(isset($_GET['search'])) {
	$searchQuery = $_GET['search'];
}


?>

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="js/search.js" type="text/javascript"></script>

<header>
	<div id="cash">
		<h3>SnapCash</h3>
		<h2>
		<?php echo money_format('%i', $balance); ?></h2>
	</div>

	<a href="http://www.snap2ask.com"><img src="res/new_logo.png" alt="Snap-2-Ask Logo" id="logoSmall"/></a>
	
	<div id="rightHeader">		
	
		<div id="welcomeTab">
			<p><?php echo 'Welcome, ' . $first_name; ?></p>	
			<a id="logoutLink" href="logout.php">Log-out</a>
		</div>
					
		<form id="search" method="POST" action="#">
			<input type="text" name="searchQuery" id="searchQuery" list = "suggestionlist" value="<?php echo $searchQuery; ?>" placeholder="Search" title="Enter a search query" x-webkit-speech />
			<datalist id='suggestionlist'>
			<?php 
			
			// Add all categories and subcategories to the suggestion list
			foreach($categories as $category)
			{
				echo sprintf('<option value="%s">%s</option>', htmlspecialchars($category['name']), htmlspecialchars($category['name']));
				
				foreach($category['subcategories'] as $subcategory)
				{
					echo sprintf('<option value="%s">%s</option>', htmlspecialchars($subcategory['name']), htmlspecialchars($subcategory['name']));
				}
			}
						
			?>
			</datalist>
			
			<input type="submit" value="Search"/>
			
		</form>		
	</div>	
</header>
