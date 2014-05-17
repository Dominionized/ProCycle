<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/fonctions.php');
?>
<title>Horaire Protic</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		



	<header>
		<div id="title_block">
			<h1 id="titre">Administration</h1>
		</div>
	</header>
	
	<div id="contenu">
	<?php
			
			
			
			
			
			if(isset($_POST['utilisateur']) AND isset($_POST['password1']) AND isset($_POST['password2']))
						{
							if($_POST['password1'] == $_POST['password2'])
								{
									/*Si les deux mot de passe correspondes, met les dans la bdd*/
									$req = $bdd->prepare('UPDATE utilisateurs SET motdepasse = :motdepasse WHERE utilisateur = :utilisateur');
									$req->execute(array(
												'motdepasse' => crypt($_POST['password1'], $_POST['utilisateur']),
												'utilisateur' => $_POST['utilisateur']
									));	
									
									/*Supprimer la cle actuel et met la par mdpoubli&eacute;*/
									$req = $bdd->prepare('UPDATE utilisateurs SET cle = :cle WHERE utilisateur = :utilisateur');
									$req->execute(array(
											'cle' => '',
											'utilisateur' => $_POST['utilisateur']
									));	
									echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
								}
							else
								{
								/*Si les mot de passe correspondes pas envoye le a l'autre page avec un e=1*/
								/* header('Location: forget.php?cle='.$_POST['cle'].'&user='.$_POST['utilisateur'].'&e=1'); */
								echo "<meta http-equiv='Refresh' content='0; URL=forget.php?cle=".$_POST['cle']."&user=".$_POST['utilisateur']."&e=1'>";
								}	
						
						}
			elseif(isset($_GET['cle']) AND isset($_GET['user']))
						{ 
						
						/*Requete pour aller chercher la cl&eacute;*/
						$reqSelectCle = $bdd->prepare('SELECT cle FROM utilisateurs WHERE utilisateur= :utilisateur');
						$reqSelectCle->execute(array(
													'utilisateur' => $_GET['user']
						)) or die(print_r($reqSelectCle->errorIndo()));
						$fetch = $reqSelectCle->fetch();
						$cle = $fetch['cle'];
						
								if($_GET['cle'] == $cle)
									{
										/*Les informations correspondes, affiche le formulaire de nouveaux mot de passe*/
									if(isset($_GET['e']) AND $_GET['e']==1){ echo '<a style="color: #ff0000;" >Un erreur s\'est produit, veuillez inscrire vos informations &agrave; nouveau</a>';}
									?>
									<h2 id="mdp">Mot de passe oubli&eacute;</h2>
									<form method="post" action="forget.php">
										<label for="password1"><h4>Changez votre mot de passe :</h4></label><br />
											<input type="password" name="password1" placeholder="QUERTY" id="password1" maxlength="20" value="" required/>
										</br>
											<input type="password" name="password2" placeholder="QUERTY" id="password2" maxlength="20" value="" required/>
										</br>
											<input type="HIDDEN" name="utilisateur" value="<?php echo $_GET['user']; ?>" id="utilisateur" /> 
											<input type="HIDDEN" name="cle" value="<?php echo $_GET['cle']; ?>" id="cle" /> 
											<input id="connexion" type="submit" name="connexion" value="Soumettre"/>
									</form>
									<?php }
								else
									{
										/*Les information correspondes pas, affiche une message d'erreur*/
										echo '<h4>Malheureusement un erreur de s&eacute;curit&eacute; est survenu, veuillez r&eacute;essayer.</h4>';
									}
						
						
						
						}
			elseif(isset($_POST['utilisateur']) AND !isset($_GET['cle']))
						{
						/*Generer une cl&Eacute;*/
						$cle=md5(uniqid(rand(), true));
						$req = $bdd->prepare('UPDATE utilisateurs SET cle = :cle WHERE utilisateur = :utilisateur');
						$req->execute(array(
											'cle' => $cle,
											'utilisateur' => $_POST['utilisateur']
							));	
						
						 /*Envoye le courriel et AFFICHE LE FORMULAIRE #2*/
						$reqSelectEmail = $bdd->prepare('SELECT email FROM utilisateurs WHERE utilisateur= :utilisateur');
						$reqSelectEmail->execute(array(
													'utilisateur' => $_POST['utilisateur']
						)) or die(print_r($reqSelectEmail->errorInfo())); 
						$fetch = $reqSelectEmail->fetch();
						$email = $fetch['email'];
						
						$reqSelectUrl = $bdd->prepare('SELECT url FROM siteinfo WHERE id= :id');
						$reqSelectUrl->execute(array(
												'id'=> 1
						)) or die(print_r($reqSelectUrl->errorInfo()));
						$fetchurl = $reqSelectUrl -> fetch();
						$url= $fetchurl['url'];
						
						$headers ='From: "ProCycle"<noreply@procycle.ca>'."\n"; 
							$headers .='Reply-To: noreply@procycle.net'."\n"; 
							$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
							$headers .='Content-Transfer-Encoding: 8bit'; 

							 $message ='<html><head><title>Récupération de mot de passe</title></head><body>Vous pouvez récupérer votre mot de passe à l\'adresse suivante :<a href="'.$url.'/admin/forget.php?cle='.$cle.'&user='.$_POST['utilisateur'].'">'.$url.'/admin/forget.php?cle='.$cle.'&user='.$_POST['utilisateur'].' </a></body></html>'; 

							 if(mail($email, 'ProCycle', $message, $headers)) 
							 { ?>
								 <h2 id="mdp">Mot de passe oubli&eacute;</h2>
								<h4>Les informations de r&eacute;initialisation de votre mot de passe vous ont &eacute;t&eacute; envoy&eacute;es par courriel.</h4>
							 <?php } 
							 else 
							 { ?>
								 <h2 id="mdp">Mot de passe oubli&eacute;</h2>
								<h4>Un erreur est survenu, veuillez r&eacute;essayer, si l'erreur persiste, contactez un administrateur.</h4>
							 <?php }				
						
					}
		else
			{ ?>
				<h4>Veuillez nous fournir votre nom d'utilisateur afin que nous puissions vous envoyer un courriel avec les informations de r&eacute;cup&eacute;ration de votre compte.</h4>
				<form method="post" action="forget.php">
					<label for="codefichepsw">Nom d'utilisateur :</label>
					</br>
					<input type="text" name="utilisateur" placeholder="patryw" id="utilisateur" required/>
					<input id="connexion" type="submit" name="connexion" value="Soumettre"/>
				</form>
			<?php } ?>
			
			<a href="connect.php" style="text-decoration:none;"id="connexion">Retour</a>
	</div>

