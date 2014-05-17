<?php
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php');
	include('includes/fonctions.php');
?>
<title>ProCycle - Mon Horaire</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />	
<link rel="stylesheet" type="text/css" href="style.css"/>	
<!--  A METTRE AVANT LES INCLUDES VERIFICATION DE S�CURIT� IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1)
{ ?>
<?php 
	$titre_page = 'Mon Horaire';
	include('includes/title.php');
?>
<!------------------------------------------------------->
<!--ON R�CUP�RE LES DONN�ES D L'UTILISATEUR -->

<?php
$requete = $bdd->prepare('SELECT * FROM utilisateurs WHERE utilisateur = :utilisateur');
$requete->execute(array(
					'utilisateur' => $_SESSION['utilisateur']	
					)) or die(print_r($bdd->errorInfo()));
$informations = $requete->fetch();
?>
<!--ON TERMINE DE R�CUP�RER LES DONN�ES DE L'UTILISATEURS--->

<div id="contenu">

<?php

	genererGrilleCycle('prof', 'horaire.php');

?>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE S�CURIT� IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
}
?>
<!------------------------------------------------------->