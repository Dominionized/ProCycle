<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php');
?>
<title>ProCycle - &Eacute;l&egrave;ves</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		

<!--  A METTRE AVANT APRES LES INCLUDES VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1)
{ ?>
<!------------------------------------------------------->
<?php 
	$titre_page = '&Eacute;l&egrave;ves';
	include('includes/title.php');
?>


		<?php
		$requete = $bdd->query('SELECT * FROM utilisateurs_eleves ORDER BY niveau ASC, groupe ASC, nom ASC, prenom ASC') or die(print_r($bdd->errorInfo()));
		$alleleves = $requete->fetchall();
		if(isset($_GET['iddelete']))
			{	
			$bdd->exec('DELETE FROM utilisateurs_eleves WHERE id='.$_GET['iddelete']);
			echo "<meta http-equiv='Refresh' content='0; URL=gererusers.php'>";
			}
		if(isset($_POST['codefiche']))
			{
				if($_POST['password']=='')
				{
					$req = $bdd->prepare('UPDATE utilisateurs_eleves SET email = :email, prenom = :prenom, nom = :nom WHERE codefiche = :codefiche');
							$req->execute(array(
								'email' => $_POST['email'],
								'prenom' => ucfirst(STRTOLOWER($_POST['prenom'])),
								'nom' => ucfirst(STRTOLOWER($_POST['nom'])),				
								'codefiche' => $_POST['codefiche']
								))or die(print_r($req->errorInfo()));
					echo "<meta http-equiv='Refresh' content='0; URL=gererusers.php'>";
				}
				else 
				{
					$req = $bdd->prepare('UPDATE utilisateurs_eleves SET password = :password, email = :email, prenom = :prenom, nom = :nom WHERE codefiche = :codefiche');
							$req->execute(array(
								'password' => crypt($_POST['password'], $_POST['codefiche']),
								'email' => $_POST['email'],
								'prenom' => ucfirst(STRTOLOWER($_POST['prenom'])),
								'nom' => ucfirst(STRTOLOWER($_POST['nom'])),				
								'codefiche' => $_POST['codefiche']
								))or die(print_r($req->errorInfo()));
					echo "<meta http-equiv='Refresh' content='0; URL=gererusers.php'>";			
				}
			}
		?>
		<div id="contenu">
			<div id="gererusers_gauche">
				<?php if(isset($_GET['idmodifier']))
			{
				$requeteidmodifier = $bdd->query('SELECT * FROM utilisateurs_eleves WHERE id = '.$_GET['idmodifier']) or die(print_r($bdd->errorInfo()));
				$infoeleve = $requeteidmodifier->fetch();
				?>
			<form method="post" action="gererusers.php">
				<label for="codefiche">Code fiche:</label><br />
					<input title="Son code fiche" type="text" name="codefiche" placeholder="5097712" id="codefiche" maxlength="7" value="<?php echo $infoeleve['codefiche']; ?>" readonly />
				<br />
				<label for="prenom">Pr&eacute;nom de l'&eacute;l&egrave;ve:</label><br />
					<input title="Son pr&eacute;nom" type="text" name="prenom" placeholder="William" id="prenom" maxlength="25" value="<?php echo $infoeleve['prenom']; ?>" required />
				<br />
				<label for="nom">Nom de l'&eacute;l&egrave;ve:</label><br />
					<input title="Son nom" type="text" name="nom" placeholder="Patry" id="nom" value="<?php echo $infoeleve['nom']; ?>" required /> 
				<br />
				<label for="email">Email de l'&eacute;l&egrave;ve:</label><br />
					<input title="Son email" type="text" name="email" placeholder="info@legroupepp.ca" id="email" maxlength="50" value="<?php echo $infoeleve['email']; ?>" required />
				<br />
				<label for="password">Nouveau mot de passe:</label><br />
						<input title="Son nouveau mot de passe (optionnel)" type="password" name="password" placeholder="QUERTY" id="password" maxlength="20"
						value="" />
				<br />
				<input id="connexion" type="submit" value="Soumettre" />
			</form>	
			<?php }
		else { ?>
					<table>
					   <caption>&Eacute;l&egrave;ves Protic</caption>
					 
					   <thead> <!-- En-tête du tableau -->
						   <tr>
							   <th>Codefiche</th>
							   <th>Niveau</th>
							   <th>Groupe</th>	
							   <th>Nom</th>						   
							   <th>Pr&eacute;nom</th>
							   <th>Email</th>
							   <th>Action</th>
						   </tr>
					   </thead> 
					   <tbody> <!-- Corps du tableau -->
						<?php foreach($alleleves as $cle => $valeur)
							{
							echo '<tr><td>'.$valeur['codefiche'].'</td><td>'.$valeur['niveau'].'</td><td>'.$valeur['groupe'].'</td><td>'.$valeur['nom'].'</td><td>'.$valeur['prenom'].'</td><td><a style="text-decoration:none; color:#565656;" href="mailto:'.$valeur['email'].'">'.$valeur['email'].'</a></td><td><a style="text-decoration:none;" id="connexion" href=gererusers.php?idmodifier='.$valeur['id'].'>Modifier</a> <a id="deconnexion" href=gererusers.php?iddelete='.$valeur['id'].'>Supprimer</a></td></tr>';
							}
						   ?>
					</table>
		</div><?php } ?>
		<div id="gererusers_droite">
			<div id="informations">
			<?php 
				if(isset($_POST['niveau']) && isset($_POST['groupe']))
				{
					if($_POST['niveau']== 'tous' AND $_POST['groupe'] != 'tous')
						{ 
							$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves WHERE groupe ='.$_POST['groupe']) or die(print_r($bdd->errorInfo()));
							while($email = $reqSelectEmail->fetch()){
									$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
								}
								$reqSelectEmail->closeCursor();
						}
					elseif($_POST['niveau'] == 'tous' AND $_POST['groupe'] == 'tous')
						{
							$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves') or die(print_r($bdd->errorInfo()));
								$listeEmail = '';
								
								while($email = $reqSelectEmail->fetch()){
									$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
								}
								$reqSelectEmail->closeCursor();
						}
					elseif($_POST['groupe']== 'tous')
						{ 
							$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves WHERE niveau ='.$_POST['niveau']) or die(print_r($bdd->errorInfo()));
							while($email = $reqSelectEmail->fetch()){
									$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
								}
								$reqSelectEmail->closeCursor();
						}
					else
						{	
							$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves WHERE niveau ='.$_POST['niveau'].' AND groupe='.$_POST['groupe']) or die(print_r($bdd->errorInfo()));
							while($email = $reqSelectEmail->fetch()){
									$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
								}
								$reqSelectEmail->closeCursor();
						} 
					if($listeEmail == ''){ $listeEmail = 'gererusers.php?e=1'; } else { $listeEmail = 'mailto:'.$listeEmail; } 
					echo "<meta http-equiv='Refresh'  content='0; URL=".$listeEmail."'>";
					?>
	
				
						<h2>Courriel</h2>
						<?php if(isset($_GET['e']) AND $_GET['e'] == 1){ echo '<div style="color:red;">Il n\'y a aucun email qui concorde avec votre s&eacute;lection</div>'; }?>
						<form method="post" action="gererusers.php">
							<select name="niveau" id="niveau">
								<option value="1">Protic 1</option>
								<option value="2">Protic 2</option>
								<option value="3">Protic 3</option>
								<option value="4">Protic 4</option>
								<option value="5">Protic 5</option>
							</select>
							<select name="groupe" id="groupe">
								<option value="31">31</option>
								<option value="32">32</option>
								<option value="33">33</option>
								<option value="34">34</option>
								<option value="35">35</option>
								<option value="36">36</option>
								<option value="37">37</option>
								<option value="38">38</option>
								<option value="39">39</option>
								<option value="40">40</option>
							</select>
						<input type="submit" value="Envoyer un courriel" id="connexion"/>
						</form>
						<?php
					
					 }
						else
						{ ?>
						<h2>Courriel</h2>
						<?php if(isset($_GET['e']) AND $_GET['e'] == 1){ echo '<div style="color:red;">Il n\'y a aucun email qui concorde avec votre s&eacute;lection</div>'; }?>
						<form method="post" action="gererusers.php">
							<select name="niveau" id="niveau">
								<option value="1">Protic 1</option>
								<option value="2">Protic 2</option>
								<option value="3">Protic 3</option>
								<option value="4">Protic 4</option>
								<option value="5">Protic 5</option>
							</select>
							<select name="groupe" id="groupe">
								<option value="31">31</option>
								<option value="32">32</option>
								<option value="33">33</option>
								<option value="34">34</option>
								<option value="35">35</option>
								<option value="36">36</option>
								<option value="37">37</option>
								<option value="38">38</option>
								<option value="39">39</option>
								<option value="40">40</option>
							</select>
						<input type="submit" value="Envoyer un courriel" id="connexion"/>
						</form>
						<?php } ?>
			</div>
			
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