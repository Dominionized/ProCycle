<?php
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php')
?>
<title>ProCycle - Professeurs</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />	
<link rel="stylesheet" type="text/css" href="style.css"/>	
<!--  A METTRE AVANT LES INCLUDES VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1)
{ ?>
<!------------------------------------------------------->

<?php 
	$titre_page = 'Professeurs';
	include('includes/title.php');
?>
<?php
		if(isset($_POST['envoyeremail']))
			{
				$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs') or die(print_r($bdd->errorInfo()));
							while($email = $reqSelectEmail->fetch()){
									$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
								}
								$reqSelectEmail->closeCursor();
				echo "<meta http-equiv='Refresh'  content='0; URL=mailto:".$listeEmail."'>";
			}
		$requete = $bdd->query('SELECT * FROM utilisateurs ORDER BY admin DESC, utilisateur ASC, nom ASC, prenom ASC') or die(print_r($bdd->errorInfo()));
		$allprofs = $requete->fetchall();
		if(isset($_GET['iddelete']) AND $_GET['iddelete'] != 1)
			{	
			$bdd->exec('DELETE FROM utilisateurs WHERE id='.$_GET['iddelete']);
			echo "<meta http-equiv='Refresh' content='0; URL=gererprofs.php'>";
			}
		?>
		<div id="contenu">
		
			<div id="tableProfs">
			<?php 
			if(isset($_POST['id']) AND isset($_POST['password']) AND isset($_POST['user']))
				{
					$req = $bdd->prepare('UPDATE utilisateurs SET motdepasse = :motdepasse WHERE user = :user')or die(print_r($bdd->errorInfo()));
					$req->execute(array(
									'motdepasse' => crypt($_POST['password'], $_POST['user']),
									'user' => $_POST['user']
									))or die(print_r($bdd->errorInfo()));	
				
				}
			if(isset($_GET['idpassword']) AND isset($_SESSION['type']) AND $_SESSION['type']==1)
				{
				$requetepassword = $bdd->query('SELECT * FROM utilisateurs WHERE id = '.$_GET['idpassword']) or die(print_r($bdd->errorInfo()));
				$infopassword = $requetepassword->fetch();
					echo 'Nouveau mot de passe pour : ' .$infopassword['prenom'].' '.$infopassword['nom'];
					echo'</br><form method="post" action="gererprofs.php">
						<input type="hidden" value="'.$_GET['user'].'" id="user" name="user"/>
						<input type="hidden" value="'.$_GET['idpassword'].'" id="id" name="id"/>
						<input type="password" id="password" name="password" placeholder="querty"/>
						<input type="submit" value="Soumettre" id="connexion"/>
					</form>';
				}
			
			
			?>
				<table>
				   <caption>Professeurs Protic</caption>
				 
				   <thead> <!-- En-tête du tableau -->
					   <tr>
						   <th>Utilisateur</th>
						   <th>Nom</th>
						   <th>Pr&eacute;nom</th>
						   <th>Courriel</th>
						   <th>Admin</th>
						   <th>Couleur</th>
						   <?php if(isset($_SESSION['type']) && $_SESSION['type']==1){echo '<th>Action</th>';} ?>
					   </tr>
				   </thead> 
				   <tbody> <!-- Corps du tableau -->
					<?php foreach($allprofs as $cle => $valeur)
						{ ?>
						<tr>
								<td><?php echo $valeur['utilisateur']; ?> </td>
								<td><?php echo $valeur['nom']; ?> </td>
								<td><?php echo $valeur['prenom']; ?> </td>
								<td><?php echo '<a style="text-decoration:none; color:#565656;" href="mailto:'.$valeur['email'].'">'.$valeur['email'].'</a>'; ?> </td>
								<td><?php if($valeur['admin']==1){echo 'Oui';} else{echo 'Non';}; ?> </td>
								<td><?php echo '<a style="background-color:' .$valeur['couleur']. '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</a>'; ?></td>
								<?php if(isset($_SESSION['type']) && $_SESSION['type']==1){ echo '<td><a id="connexion" style="text-decoration: none;" href=gererprofs.php?idpassword='.$valeur['id'].'&user='.$valeur['utilisateur'].'>Mot de passe</a>  <a id="deconnexion" href=gererprofs.php?iddelete='.$valeur['id'].'>Supprimer</a></td>';} ?>
								
							</tr>
							
						<?php }
					   ?>
					<tbody>
				</table><br /> 
				<form method="post" action="gererprofs.php">
						<input type="hidden" value="envoyeremail" id="envoyeremail" name="envoyeremail"/>
						<input type="submit" value="Envoyer un courriel aux professeurs" id="connexion"/>
				</form>
				</div>
			
<?php

	// Si les informations ont étés envoyés, enregistre, sinon affiche le formulaire
	if(isset($_SESSION['type']) && $_SESSION['type']==1){
			if(isset($_POST['utilisateur']) AND isset($_POST['motdepasse']) AND $_POST['utilisateur'] != '' AND $_POST['motdepasse'] != '')
			{
				$reqSelect = $bdd->query('SELECT utilisateur FROM utilisateurs');
				
				$autorisation = true;
				
				while($get = $reqSelect->fetch())
				{	
					if($_POST['utilisateur'] == $get['utilisateur'])
					{
						$autorisation = false;
					}
				}
				
				if($autorisation == true)
				{	

					if(isset($_POST['type']) AND $_POST['type'] == 'on')
					{
						$admin = true;
					}
					else
					{
						$admin = false;
					}
				
					$reqInsert = $bdd->prepare('INSERT INTO utilisateurs(utilisateur, prenom, nom, motdepasse, admin, email, couleur) VALUES(:utilisateur, :prenom, :nom, :motdepasse, :admin, :email, :couleur)');
					$reqInsert->execute(array(
											'utilisateur' => STRTOLOWER($_POST['utilisateur']),
											'prenom' => ucfirst(STRTOLOWER($_POST['prenom'])),
											'nom' => ucfirst(STRTOLOWER($_POST['nom'])),
											'motdepasse' => crypt($_POST['motdepasse'], STRTOLOWER($_POST['utilisateur'])),
											'admin' => $admin,
											'email'=>$_POST['email'],
											'couleur'=>$_POST['couleur']
											));			
										echo "<meta http-equiv='Refresh' content='0; URL=gererprofs.php'>";
										
				}
				else
				{
				?>	
				
					
						<div id="ajouterProf">	
						<form method="post" action="gererprofs.php">
					
						<label for="utilisateur">Nom d'utilisateur: </label> <br />
						<input type="text" name="utilisateur" id="utilisateur" placeholder="patryw" /> <input type="checkbox" name="type" id="type" required/> <label for="type">Admin</label>
						
						<br /><br />
						
						<label for="motdepasse">Mot de passe: </label> <br />
						<input type="password" name="motdepasse" id="motdepasse" placeholder="5097712" required/>
						
						<br /><br />
						
						<label for="prenom">Pr&eacute;nom: </label> <br />
						<input type="text" name="prenom" id="prenom" placeholder="William" required/>
						
						<br /><br />
						
						<label for="nom">Nom: </label> <br />
						<input type="text" name="nom" id="nom" placeholder="Patry" required/>
						
						<br /><br />
						
						<label for="nom">Email: </label> <br />
						<input type="text" name="email" id="email" placeholder="info@legroupepp.ca" required/>
						
						<br /><br />
						
						<label for="color">Couleur : </label><br/>
							<div id="rangee1">
								<input type="radio" name="couleur" value="#ff0f2a"><span id="rouge">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#4a6cff"><span id="bleu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#a7df00"><span id="vertlime">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#2db814"><span id="vertfonce">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								
							</div>
							<div id="rangee2">
							
								<input type="radio" name="couleur" value="#ef7200"><span id="orange">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#f00084"><span id="rose">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#cccccc"><span id="gris">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#ffffff"><span id="blanc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
							</div>
							<div id="rangee3">
							
								<input type="radio" name="couleur" value="#e6b950"><span id="beige">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#cd44ff"><span id="mauve">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#77d9ff"><span id="bleuclair">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
								<input type="radio" name="couleur" value="#ffed0f"><span id="jaune">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
							</div>
							
							<br /><br />
						
						<input id="connexion" type="submit" value="Ajouter" />
						</form>					
						<p style="color: #ff0000;">Une erreur s'est produite, veuillez r&eacute;essayer.</p>
					</div>	
						<?php
						}
					
					}
			else
			{ 
			?>
					
				<div id="ajouterProf">	
					
					<form method="post" action="gererprofs.php">
					<h2>Ajouter un professeur</h2>
					<label for="utilisateur">Nom d'utilisateur: </label> <br />
					<input type="text" name="utilisateur" id="utilisateur" placeholder="patryw" required /> <input type="checkbox" name="type" id="type"/> <label for="type">Admin</label>
					
					<br /><br />
					
					<label for="motdepasse">Mot de passe: </label> <br />
					<input type="password" name="motdepasse" id="motdepasse" placeholder="5097712" required />
					
					<br /><br />
					
					<label for="prenom">Pr&eacute;nom: </label> <br />
					<input type="text" name="prenom" id="prenom" placeholder="William" required />
					
					<br /><br />
					
					<label for="nom">Nom: </label> <br />
					<input type="text" name="nom" id="nom" placeholder="Patry" required />
					
					<br /><br />
					
					<label for="nom">Email: </label> <br />
					<input type="text" name="email" id="email" placeholder="info@legroupepp.ca" required/>
				
					<br /><br />
					
					<label for="color">Couleur : </label><br/>
					<div id="rangee1">
						<input type="radio" name="couleur" value="#ff0f2a"><span id="rouge">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#4a6cff"><span id="bleu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#a7df00"><span id="vertlime">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#2db814"><span id="vertfonce">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						
					</div>
					<div id="rangee2">
					
						<input type="radio" name="couleur" value="#ef7200"><span id="orange">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#f00084"><span id="rose">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#cccccc"><span id="gris">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#ffffff"><span id="blanc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
					</div>
					<div id="rangee3">
					
						<input type="radio" name="couleur" value="#e6b950"><span id="beige">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#cd44ff"><span id="mauve">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#77d9ff"><span id="bleuclair">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#ffed0f"><span id="jaune">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
					</div>
						
					<br /><br />
					
					<input id="connexion" type="submit" value="Ajouter" />
					</form>
				</div>
				
			</div>
<?php
	}
}
?>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
}
?>
<!------------------------------------------------------->
