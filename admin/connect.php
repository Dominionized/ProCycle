<?php
session_start();// Ã€ placer obligatoirement avant tout code HTML.
include('includes/sqlconnect.php');
?>
<title>Horaire Protic</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		
<?php
if(isset($_GET['e']) AND $_GET['e']==1)
	{
		echo "<script>alert(\"Vous avez été déconnecté après 10 minutes d'innactiviter\")</script>";   
	}
$_SESSION['connect']=0; //Initialise la variable 'connect'.
  
if(isset($_POST['utilisateur']) AND isset($_POST['motdepasse'])) //si il a envoyer des donnes du formulaire
	{
			// On enregistre les informations sur l'utilisateur dans un array (info_utilisateurs)
			$requete = $bdd->prepare('SELECT * FROM utilisateurs WHERE utilisateur = :utilisateur');
			$requete ->execute(array('utilisateur' => $_POST['utilisateur'])) or die(print_r($requete->errorInfo()));
			$info_utilisateur = $requete->fetch(); 
				
			// Ici on encrypte le mot de passe de la meme faÃ§on que celui dans la bdd afin de tester s'il est correct
			$motdepasse = crypt($_POST['motdepasse'], $_POST['utilisateur']);

			// Si les infos entr&Eacute;s sont &Eacute;gales aux infos de la bdd, autorise l'acc&egrave;s
			if($_POST['utilisateur'] == $info_utilisateur['utilisateur'] AND $motdepasse == $info_utilisateur['motdepasse'])
			{
				
				$_SESSION['connect']=1; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
				$_SESSION['type']=$info_utilisateur['admin'];// Permet de r&Eacute;cup&Eacute;rer le login afin de personnaliser la navigation.
				$_SESSION['utilisateur'] = $_POST['utilisateur'];
				echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
			}
			else
			{
				echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
			}
	}
else // affiche le formulaire de connexion
	{?>
	<?php 
	
	?>
	
	<body>
		<div id="connect">
			<img id="logoadmin" src="images/logo.png" />
			<form action="connect.php" method="post">
				<div id="username" >Votre nom d'utilisateur : <input type="text" name="utilisateur" placeholder="patryw" required /></div>
				<div id="password">Votre mot de passe : <input type="password" name="motdepasse" placeholder="*******" required /></div>
				<input type="submit" value="Connexion" id="connexion"/>
			</form>
		<a style="text-decoration: none; color: #FFFFFF;" href="forget.php">Mot de passe oubli&eacute;?</a><br />
		<a href="../index.php" style="color:#FFFFFF; text-decoration:none; padding-bottom:15px;">< Retour</a>
		</div>
	</body
	<?php } ?>