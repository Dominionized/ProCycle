<?php
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php')
?>
<!--  A METTRE AVANT LES INCLUDES VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1)
{ ?>
<?php 
	$titre_page = 'Mon Profil';
	include('includes/title.php');
?>
<!------------------------------------------------------->
<!--ON RÉCUPÈRE LES DONNÉES D L'UTILISATEUR -->
<title>ProCycle - Mon Profil</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		
<?php
$requete = $bdd->prepare('SELECT * FROM utilisateurs WHERE utilisateur = :utilisateur');
$requete->execute(array(
					'utilisateur' => $_SESSION['utilisateur']	
					)) or die(print_r($bdd->errorInfo()));
$informations = $requete->fetch();
?>
<!--ON TERMINE DE RÉCUPÉRER LES DONNÉES DE L'UTILISATEURS--->

<?php
	if(isset($_POST['utilisateur']))
	{/*Si quelquun a deja entrer des donnes dans un formulaire*/
		if($_POST['couleur'] == '#000000'){$couleur = '#ffffff';}else{$couleur = $_POST['couleur'];}
		if(crypt($_POST['motdepasse'], STRTOLOWER($_POST['utilisateur'])) == $informations['motdepasse'] AND $_POST['nouveaumotdepasse'] != '')
		{
			$requete = $bdd->prepare('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, motdepasse = :motdepasse, couleur = :couleur, email = :email WHERE utilisateur = :utilisateur');
			$requete->execute(array(
								'prenom' => ucfirst(STRTOLOWER($_POST['prenom'])),
								'nom' => ucfirst(STRTOLOWER($_POST['nom'])),
								'motdepasse' => crypt($_POST['nouveaumotdepasse'], STRTOLOWER($_POST['utilisateur'])),
								'couleur' => $couleur,
								'email' => $_POST['email'],
								'utilisateur' => STRTOLOWER($_POST['utilisateur'])
								)) or die(print_r($bdd->errorInfo()));
			echo "<meta http-equiv='Refresh' content='0; URL=modifier.php?e=1'>";
		}
		elseif(crypt($_POST['motdepasse'], STRTOLOWER($_POST['utilisateur'])) == $informations['motdepasse'])
		{
			$requete = $bdd->prepare('UPDATE utilisateurs SET prenom = :prenom, nom = :nom, motdepasse = :motdepasse, couleur = :couleur, email = :email WHERE utilisateur = :utilisateur');
			$requete->execute(array(
								'prenom' => ucfirst(STRTOLOWER($_POST['prenom'])),
								'nom' => ucfirst(STRTOLOWER($_POST['nom'])),
								'motdepasse' => crypt($_POST['motdepasse'], $_POST['utilisateur']),
								'couleur' => $couleur,
								'email' => $_POST['email'],
								'utilisateur' => STRTOLOWER($_POST['utilisateur'])
								)) or die(print_r($bdd->errorInfo()));
			
			echo "<meta http-equiv='Refresh' content='0; URL=modifier.php?e=1'>";
		}
		else
		{ ?>
		<div id="contenu">
			<div id="informations">
					<p style="color: #ff0000;">Une erreur s'est produite, veuillez r&egrave;essayer.</p>
					<form method="post" action="modifier.php">
				
					<label for="utilisateur">Nom d'utilisateur: </label> <br />
					<input type="text" name="utilisateur" id="utilisateur" placeholder="patryw" value="<?php echo $informations['utilisateur'];?>" readonly required /> <input type="checkbox" name="type" id="type" disabled="disabled" <?php if($informations['admin'] == 1){echo 'checked';}?>/> <label for="type">Admin</label>
					
					<br /><br />
					
					<label for="motdepasse">Mot de passe: </label> <br />
					<input type="password" name="motdepasse" id="motdepasse" placeholder="5097712" required />
					
					<br /><br />
					
					<label for="motdepasse">Nouveau mot de passe: </label> <br />
					<input type="password" name="nouveaumotdepasse" id="nouveaumotdepasse" placeholder="5097712"/>
					
					<br /><br />
					
					<label for="prenom">Pr&eacute;nom: </label> <br />
					<input type="text" name="prenom" id="prenom" placeholder="William" value="<?php echo $informations['prenom'];?>" required />
					
					<br /><br />
					
					<label for="nom">Nom: </label> <br />
					<input type="text" name="nom" id="nom" placeholder="Patry" value="<?php echo $informations['nom'];?>" required />
					
					<br /><br />
					
					<label for="nom">Email: </label> <br />
					<input type="text" name="email" id="email" placeholder="info@legroupepp.ca" value="<?php echo $informations['email'];?>" required />
					
					<br /><br />
				
					<label for="color">Modifier votre couleur : </label><br/>
					<div id="rangee1">
						<input type="radio" name="couleur" value="#F67400" <?php if($informations['couleur']== '#F67400'){ echo 'checked';}?> ><span id="orange">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#6B8CFF" <?php if($informations['couleur']== '#6B8CFF'){ echo 'checked';}?> ><span id="bleu" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#3CFF4E" <?php if($informations['couleur']== '#3CFF4E'){ echo 'checked';}?> ><span id="vert" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#D9482B" <?php if($informations['couleur']== '#D9482B'){ echo 'checked';}?> ><span id="rouge" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
					</div>
					<div id="rangee2">
						<input type="radio" name="couleur" value="#FF6EA0" <?php if($informations['couleur']== '#FF6EA0'){ echo 'checked';}?> ><span id="rose" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#FFD853" <?php if($informations['couleur']== '#FFD853'){ echo 'checked';}?> ><span id="jaune" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#00E0AD" <?php if($informations['couleur']== '#00E0AD'){ echo 'checked';}?> ><span id="turquoise" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#DE45FF" <?php if($informations['couleur']== '#DE45FF'){ echo 'checked';}?> ><span id="mauve" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
					</div>
					<div id="rangee3">
						<input type="radio" name="couleur" value="#FA003E" <?php if($informations['couleur']== '#FA003E'){ echo 'checked';}?> ><span id="magenta" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#FFA085" <?php if($informations['couleur']== '#FFA085'){ echo 'checked';}?> ><span id="saumon" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#CFCFCF" <?php if($informations['couleur']== '#CFCFCF'){ echo 'checked';}?> ><span id="gris" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#33BDFF" <?php if($informations['couleur']== '#33BDFF'){ echo 'checked';}?> ><span id="bleupale" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
					</div>
					<br /><br />
					
					
					<input type="submit" value="Modifier" id="connexion" />
					
					</form>	
				</div>
			</div>
		<?php }
	}
	else
	{ ?>
	<div id="contenu">
			<div id="informations">
				<?php if(isset($_GET['e']) AND $_GET['e']==1){ echo '<a style="color: green;">Les informations ont &egrave;t&egrave; enregistr&egrave;es avec succ&egrave;s!</a>'; } ?>

				<form method="post" action="modifier.php">
			
				<label for="utilisateur">Nom d'utilisateur: </label> <br />
				<input type="text" name="utilisateur" id="utilisateur" placeholder="patryw" value="<?php echo $informations['utilisateur'];?>" readonly required /> </br><input type="checkbox" name="type" id="type" disabled="disabled" <?php if($informations['admin'] == 1){echo 'checked';}?>/><label for="type">Admin?</label>
				
				<br /><br />
				
				<label for="motdepasse">Mot de passe: </label> <br />
				<input type="password" name="motdepasse" id="motdepasse" placeholder="5097712" required />
				
				<br /><br />
				
				<label for="motdepasse">Nouveau mot de passe: </label> <br />
				<input type="password" name="nouveaumotdepasse" id="nouveaumotdepasse" placeholder="5097712"/>
				
				<br /><br />
				
				<label for="prenom">Pr&eacute;nom: </label> <br />
				<input type="text" name="prenom" id="prenom" placeholder="William" value="<?php echo $informations['prenom'];?>" required />
				
				<br /><br />
				
				<label for="nom">Nom: </label> <br />
				<input type="text" name="nom" id="nom" placeholder="Patry" value="<?php echo $informations['nom'];?>" required />
				
				<br /><br />
				
				<label for="nom">Email: </label> <br />
					<input type="text" name="email" id="email" placeholder="info@legroupepp.ca" value="<?php echo $informations['email'];?>" required />
					
				<br /><br />
				
				<label for="color">Modifier votre couleur : </label><br/>
					<div id="rangee1">
						<input type="radio" name="couleur" value="#ff0f2a" <?php if($informations['couleur']== '#ff0f2a'){ echo 'checked';}?> ><span id="rouge">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#4a6cff" <?php if($informations['couleur']== '#4a6cff'){ echo 'checked';}?> ><span id="bleu" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#a7df00" <?php if($informations['couleur']== '#a7df00'){ echo 'checked';}?> ><span id="vertlime" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#2db814" <?php if($informations['couleur']== '#2db814'){ echo 'checked';}?> ><span id="vertfonce" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
					</div>
					<div id="rangee2">
						<input type="radio" name="couleur" value="#ef7200" <?php if($informations['couleur']== '#ef7200'){ echo 'checked';}?> ><span id="orange" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#f00084" <?php if($informations['couleur']== '#f00084'){ echo 'checked';}?> ><span id="rose" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#cccccc" <?php if($informations['couleur']== '#cccccc'){ echo 'checked';}?> ><span id="gris" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#ffffff" <?php if($informations['couleur']== '#ffffff'){ echo 'checked';}?> ><span id="blanc" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
					</div>
					<div id="rangee3">
						<input type="radio" name="couleur" value="#e6b950" <?php if($informations['couleur']== '#e6b950'){ echo 'checked';}?> ><span id="beige" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#cd44ff" <?php if($informations['couleur']== '#cd44ff'){ echo 'checked';}?> ><span id="mauve" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#77d9ff" <?php if($informations['couleur']== '#77d9ff'){ echo 'checked';}?> ><span id="bleuclair" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
						<input type="radio" name="couleur" value="#ffed0f" <?php if($informations['couleur']== '#ffed0f'){ echo 'checked';}?> ><span id="jaune" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
					</div>
				
				<br /><br />
				
				<input type="submit" value="Modifier" id="connexion" />
				</form>	
			</div>
		</div>
	<?php }

?>






<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
}
?>
<!------------------------------------------------------->
