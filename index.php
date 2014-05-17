<?php include('includes/sqlconnect.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>ProCycle - Accueil</title>
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		
		<?php include('includes/sqlconnect.php'); ?>
		<?php include('style.php'); ?>
	</head>
	
	<body>
		
		<header>
			<?php include('includes/title.php');?>
			<?php include('includes/navigation.php');?>
		</header>
		<div id="contenu">
			<?php include('includes/info_user.php');?>
			<?php include('login_logout.php');?>
			<?php if (isset($_COOKIE['codefiche'])){include('includes/horaire.php');}else{include('includes/options.php');} ?>
		</div>
		
	</body>
		<?php include('includes/credits.php');?>	
</html>

