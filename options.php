<?php include('includes/session_start.php'); ?>
<?php if(isset($_GET['codefiche']))
		{
		setcookie('codefiche', $_GET['codefiche'], time() + 365*24*3600, null, null, false, true); // On &eacute;crit un cookie
		setcookie('password', 'on', time() + 365*24*3600, null, null, false, true); // On &eacute;crit un cookie
		echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
		} 
?>
<!DOCTYPE html>
<html>
	<head>
		<title>ProCycle - Options</title>	
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		
		<?php include('includes/sqlconnect.php'); ?>
		<?php include('style.php'); ?>
		
		
	</head>
	
	<body>
		
		<header>
			<?php include('includes/title.php'); ?>
			<?php include('includes/navigation.php'); ?>
		</header>
		<div id="contenu">
			<?php 
				include('includes/info_user.php');
				include('login_logout.php');
				include('includes/options.php');
				if(isset($_COOKIE['codefiche'])){include('includes/delete.php');}
			?>
			
			
			
		</div>
		<?php include('includes/credits.php');?>	
	</body>

</html>

