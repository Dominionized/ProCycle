<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php');
	include('includes/fonctions.php');
?>
<title>ProCycle - Configuration Cycle</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		


<!--  A METTRE AVANT APRES LES INCLUDES VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1 && isset($_SESSION['type']) && $_SESSION['type']==1)
{ ?>
<!------------------------------------------------------->





<?php
$requete = $bdd->query('SELECT * FROM options ORDER BY groupe_option') or die(print_r($bdd->errorInfo()));
$alloptions = $requete->fetchall();
//var_dump($alloptions);
?>


<?php 
	$titre_page = 'Configuration Cycle';
	include('includes/title.php');
?>
	<div id="contenu">
			
			<h2>Assignation des professeurs</h2>
			<p>
			<form method="post" action="gererhoraire.php">
					<select name="niveau" id="niveau">
						<option value="1" <?php if(isset($_POST['niveau']) AND $_POST['niveau'] == 1){ echo 'selected'; } ?>>Protic 1</option>
						<option value="2" <?php if(isset($_POST['niveau']) AND $_POST['niveau'] == 2){ echo 'selected'; } ?>>Protic 2</option>
						<option value="3" <?php if(isset($_POST['niveau']) AND $_POST['niveau'] == 3){ echo 'selected'; } ?>>Protic 3</option>
						<option value="4" <?php if(isset($_POST['niveau']) AND $_POST['niveau'] == 4){ echo 'selected'; } ?>>Protic 4</option>
						<option value="5" <?php if(isset($_POST['niveau']) AND $_POST['niveau'] == 5){ echo 'selected'; } ?>>Protic 5</option>
					</select>
					<select name="groupe" id="groupe">
						<option value="31" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 31){ echo 'selected'; } ?>>Groupe 31</option>
						<option value="32" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 32){ echo 'selected'; } ?>>Groupe 32</option>
						<option value="33" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 33){ echo 'selected'; } ?>>Groupe 33</option>
						<option value="34" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 34){ echo 'selected'; } ?>>Groupe 34</option>
						<option value="35" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 35){ echo 'selected'; } ?>>Groupe 35</option>
						<option value="36" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 36){ echo 'selected'; } ?>>Groupe 36</option>
						<option value="37" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 37){ echo 'selected'; } ?>>Groupe 37</option>
						<option value="38" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 38){ echo 'selected'; } ?>>Groupe 38</option>
						<option value="39" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 39){ echo 'selected'; } ?>>Groupe 39</option>
						<option value="40" <?php if(isset($_POST['groupe']) AND $_POST['groupe'] == 40){ echo 'selected'; } ?>>Groupe 40</option>
					</select>
					
				<input type="submit" value="Modifier" id="connexion"/>
			</form>
			</p>
			<?php
				// Si la grille horaire a été affichée et remplie
				if(isset($_POST['prof_1-1']))
				{	
					echo 'Informations enregistr&eacute;s avec succ&egrave;s!';
					enregistrerGrilleHoraire('admin', 'horairedefault', $_POST['niveau'], $_POST['groupe']);		
				}
				// Sinon, si les options ont étés envoyés, affiche la grille horaire
				elseif(isset($_POST['niveau']) AND isset($_POST['groupe']))
				{
					// Inclure les fonctions, puis générer la grille horaire avec la fonction "genererGrilleHoraire" (voir fonctions.php)
					genererGrilleHoraire('admin', 'gererhoraire.php', $_POST['niveau'], $_POST['groupe']);
				}
			?>
	</div>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
	
}
?>
<!------------------------------------------------------->