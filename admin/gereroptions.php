<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php');
?>
<title>ProCycle - Groupes Mati&egrave;res</title>
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
	$titre_page = 'Groupes Mati&egrave;res';
	include('includes/title.php');
?>
	<div id="contenu">
			<table id="tableGererOptions">
			   <caption>Mati&egrave;res en option</caption>
			 
			   <thead> <!-- En-tête du tableau -->
				   <tr>
					   <th>Groupe</th>
					   <th>Mati&egrave;re</th>
					   <th>Prof</th>
					   <th>Action</th>
				   </tr>
			   </thead>
			   <tbody> <!-- Corps du tableau -->
				<?php foreach($alloptions as $cle => $valeur)
					{
					echo '<tr><td>'.$valeur['groupe_option'].'</td><td>'.$valeur['option'].'</td><td>'.$valeur['prof'].'</td><td><a id="deconnexion" href=gereroptions.php?id='.$valeur['id'].'>Supprimer</a></td></tr>';
					}
				   ?>
			</table>
			<?php 
			/* Si il a recu des données*/
			if (isset($_POST['groupe_option']) && isset($_POST['option']))
				{
					$req = $bdd->prepare('INSERT INTO options(groupe_option, `option`, `prof`) VALUES(:groupe_option, :option, :prof)');
								$req->execute(array(
									'groupe_option' => $_POST['groupe_option'],
									'option' => ucfirst($_POST['option']),
									'prof' => $_POST['prof']
									)) or die(print_r($req->errorInfo()));
									echo "<meta http-equiv='Refresh' content='0; URL=gereroptions.php'>";
									
				}
			/*S'il na pas recu de données*/
			elseif(isset($_GET['id']))
				{	
				$bdd->exec('DELETE FROM options WHERE id='.$_GET['id']);
				echo "<meta http-equiv='Refresh' content='0; URL=gereroptions.php'>";
				}
			else
			{ ?>
			<form method="post" action="gereroptions.php" id="form_gereroptions">
				<label for="groupe_option">Groupe mati&egrave;re: </label><br />
				<select name="groupe_option">
					<option value="1" id="1">1</option>
					<option value="2" id="2">2</option>
					<option value="3" id="3">3</option>
					<option value="4" id="4">4</option>
					<option value="5" id="5">5</option>
					<option value="6" id="6">6</option>
					<option value="7" id="7">7</option>
					<option value="8" id="8">8</option>
					<option value="9" id="9">9</option>
					<option value="10" id="10">10</option>
				</select><br /><br />
				
				<label for="option">Nom de la mati&egrave;re: </label><br />
				<input type="text" name="option" value="" placeholder="Chimie" required/><br/><br />
				
				<label for="prof">Enseignant: </label><br />
				<select name="prof">
				<?php
					$reqSelectProf = $bdd->prepare('SELECT utilisateur, prenom, nom FROM utilisateurs WHERE utilisateur != :utilisateur ORDER BY nom');
					$reqSelectProf->execute(array(
													'utilisateur' => 'administrateur'
													)) or die(print_r($reqSelectProf->errorInfo()));
													
					while($fetch = $reqSelectProf->fetch())
					{
						?>
							<option value="<?php echo $fetch['utilisateur']; ?>" id="<?php echo $fetch['utilisateur']; ?>"><?php echo $fetch['nom'].' '.$fetch['prenom']; ?></option>
						<?php
					}
				?>
				</select><br /><br />
				
				<input type="submit" value="Ajouter l'option" id="connexion"/>
			</form>
			<?php } ?>
	</div>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
}
?>
<!------------------------------------------------------->