<!--OPTION POUR ADMINISTRATEUR -->
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />	

<?php
 if(isset($_SESSION['type']) && ($_SESSION['type']== 0 || $_SESSION['type']==1))
{ ?>
	<nav>
		<ul id="navprof" style="padding-left: 0px;">
			<li><a href="index.php">Accueil</a></li>
			<li><a href="modifier.php">Mon Profil</a></li>
			<li><a href="horaire.php">Mon Horaire</a></li>
			<li><a href="gererusers.php">&Eacute;l&egrave;ves</a></li>
			<li><a href="gererprofs.php">Enseignants</a></li>
			<?php if($_SESSION['type'] == 0) { ?> <li><a href="aide.php">Aide</a></li> <?php } ?>	
		</ul>
	</nav>
<?php } 

?>

<?php if(isset($_SESSION['type']) && $_SESSION['type']== 1)
{ ?>
	<nav>
		<ul id="navadmin" style="padding-left: 0px;">
			<li><a href="gerercalendrier.php">Calendrier</a></li>
			<li><a href="gererhoraire.php">Configuration Cycle</a></li>
			<li><a href="gereroptions.php">Groupes Mati&egrave;res</a></li>
			<?php if($_SESSION['type'] == 1) { ?> <li><a href="aide.php">Aide</a></li> <?php } ?>
		</ul>
	</nav>
<?php } ?>

<!--OPTION POUR PROFESSEURS -->
