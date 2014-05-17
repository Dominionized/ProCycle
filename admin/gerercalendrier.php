<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php');
	include('includes/fonctions.php');
?>
<title>ProCycle - Calendrier</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		


<!--  A METTRE AVANT APRES LES INCLUDES VERIFICATION DE S&eacute;CURIT&eacute; IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1 && isset($_SESSION['type']) && $_SESSION['type']==1)
{ ?>
<!------------------------------------------------------->





<?php
$requete = $bdd->query('SELECT * FROM options ORDER BY groupe_option') or die(print_r($bdd->errorInfo()));
$alloptions = $requete->fetchall();
//var_dump($alloptions);
?>

<?php 
	$titre_page = 'Calendrier';
	include('includes/title.php');
?>
	<div id="contenu">
			<h2>Cr&eacute;er le calendrier scolaire</h2>
			<?php			
				if(isset($_POST['etape3']))
				{
					genererGrilleAnneeScolaire('4', 'gerercalendrier.php');
				}
				elseif(isset($_POST['premierjour']))
				{
					genererGrilleAnneeScolaire('2', 'gerercalendrier.php');
					if(isset($autorisation) AND $autorisation == false)
					{
						genererGrilleAnneeScolaire('1', 'gerercalendrier.php');
					}
					else
					{		
						genererGrilleAnneeScolaire('3', 'gerercalendrier.php');
					}
				}
				else
				{
					genererGrilleAnneeScolaire('1', 'gerercalendrier.php');
				}
			?>
	</div>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE S&Eacute;CURIT&eacute; IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
}
?>
<!------------------------------------------------------->