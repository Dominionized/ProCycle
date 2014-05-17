<?php include('includes/sqlconnect.php');?>
<?php


if(isset($_POST['codefiche']))
{
	$autorisation = false;
	
	$reqSelectCodefiche = $bdd->query('SELECT * FROM utilisateurs_eleves') or die(print_r($bdd->errorInfo()));

	while($fetchCodefiche = $reqSelectCodefiche->fetch())
	{
		if($fetchCodefiche['codefiche'] == $_POST['codefiche'])
		{
			$autorisation = true;
		}
	}
}


	if(isset($autorisation) AND $autorisation == true)/*si la variable codefiche_post existe et n'&eacute;gale pas rien*/
		{
				setCookie('codefiche', $_POST['codefiche'], time() + 365*24*3600, null, null, false, true); // On &eacute;crit un cookie
				setCookie('password', 'on', time() + 365*24*3600, null, null, false, true); // On &eacute;crit un cookie 
				echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
		}
	elseif(isset($_POST['#']))
		{
		setCookie("codefiche","", 1); // On delete un cookie
		setCookie("password","", 1); // On delete un cookie
		echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
		}
	elseif(isset($autorisation) AND $autorisation == false)
	{
		echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
	}
	else
	{
		if (isset($_COOKIE['codefiche']))
		{ ?>  
			<div id="loginlogout">
				<!--si l'utilisateur est connect&eacute; affiche la deconnection-->
				<form method="post" action="login_logout.php">
				<input type="HIDDEN" name="#" value="#">
				<input id="deconnexion" type="submit" name="deconnexion" value="D&eacute;connexion"/>
				</form>
			</div>
			<?php 
		}
		else/*sinon affiche le formulaire pour se connecter*/
		{ ?>
			<div id="loginlogout">
				<form method="post" action="login_logout.php">
				<label for="codefiche">Code fiche :</label>
					<input title="Code fiche" type="text" name="codefiche" placeholder="5097712" id="codefiche" maxlength="7" value="" required/>
					<input id="connexion" type="submit" name="connexion" value="Connexion"/>
				</form>
			</div>
		<?php 
		}
	
	}

	?>


		
	
	
	