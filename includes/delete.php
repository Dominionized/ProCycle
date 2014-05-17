<?php 
	include('sqlconnect.php');

if(isset($_POST['codefiche']) && isset($_POST['password']))
	{
	$requete = $bdd->query('SELECT password FROM utilisateurs_eleves WHERE codefiche ='.$_POST['codefiche']) or die(print_r($bdd->errorInfo()));
			$verifpassword = $requete->fetch();
			if($verifpassword['password'] == crypt($_POST['password'], $_POST['codefiche']))
				{
				/*update le cookie avec une dur&Eacute;e de 0 seconde*/
				setcookie("codefiche","",0,"/","",0); // On delete un cookie
				setcookie("password","",0,"/","",0); // On delete un cookie
				$bdd->exec('DELETE FROM utilisateurs_eleves WHERE codefiche='.$_POST['codefiche']);
				$_SESSION['delete'] = 'good';
				echo "<meta http-equiv='Refresh' content='0; URL=../options.php'>";
				}
			else
			{
				echo 'Votre mot de passe ne correspond pas avec votre codefiche';
				$_SESSION['delete'] = 'bad';
				echo "<meta http-equiv='Refresh' content='0; URL=../options.php'>";
			}
	}
else	
	{ ?>
		<div id="delete">
		Supprimer votre compte:
		<br/>
		<form method="post" action="includes/delete.php">
		<label for="codefiche">Code fiche:</label><br />
			<input title="Votre code fiche" type="text" name="codefiche" placeholder="5097712" id="codefiche" required maxlength="7"
			value="<?php 
			/*si le cookie code fiche existe affiche le dans value*/
			if (isset($_COOKIE['codefiche']))
				{
					echo $_COOKIE['codefiche'];
				}
			else /*sinon affiche rien pour le laisser afficher le placeholder*/
				{
				echo '';
				}
			?>"<?php if(isset($_COOKIE['codefiche'])){ echo 'readonly'; } ?>/>
			
			<br />
			<label for="password">Mot de passe:</label><br />
			<input title="Votre mot de passe" type="password" name="password" placeholder="QUERTY" required id="password" maxlength="14"
			value=""/>
			<br />
			<input id="supprimer" type="submit" value="Supprimer" />
		</form>
		</div>
	<?php }

?>

