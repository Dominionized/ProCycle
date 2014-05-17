<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/fonctions.php'); 
?>
<title>ProCycle - Accueil</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		


<!--  A METTRE AVANT APRES LES INCLUDES VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1 && isset($_SESSION['type']))
{ ?>
<!------------------------------------------------------->
<?php include('includes/navigation.php'); ?>
<?php
	$titre_page = 'Accueil';
	include('includes/title.php');
?>
	<div id="contenu">
			
			
			<?php
			
				
				
			
			?>
			<div id="colonne1">
			<h2>Temp&ecirc;te</h2>
			<?php
				boutonTempete('index.php');
			if(isset($_SESSION['type']) AND $_SESSION['type'] == 1)
				{
				?>
			
			<div id="colonne2">
			<h2>Couleur des horaires</h2>
			<?php
				formulaireCouleur('index.php');
			?>
			</div>
			<?php } ?>
			</div>
			<div id="colonne3">
			<?php
			if($_SESSION['type']==1)
				{ 
				
					if(isset($_POST['url']))
					{
						$url = curPageURL();
						$url= str_replace("/admin/index.php","",$url);
						$req = $bdd->prepare('UPDATE siteinfo SET url = :url WHERE id = :id');
						$req->execute(array(
											'url' => $url,
											'id' => 1
							));	
						echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
					}
					else
					{ 
					$reqSelectUrl = $bdd->prepare('SELECT url FROM siteinfo WHERE id= :id');
						$reqSelectUrl->execute(array(
													'id' => 1
						)) or die(print_r($reqSelectUrl->errorInfo())); 
						
						$fetch = $reqSelectUrl->fetch();
						
						$url = $fetch['url'];
					?>			
						<h2>URL</h2>
						<form method="post" action="index.php">
							<label for="url">Url du site:</label>
							</br>
							<input type="text" style="width:300;" name="url" placeholder="http://www.protic.net/horaireprotic" readonly id="url" value="<?php if(isset($url)){ echo $url; } ?>" required/>
							<input id="connexion" type="submit" name="connexion" value="Actualiser"/>
						</form>
					<?php }
				
				
				}
			?>
			</div>
	</div>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
}
?>
<!------------------------------------------------------->
