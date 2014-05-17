<?php include('includes/sqlconnect.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>ProCycle</title>	
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		
		<?php include('includes/sqlconnect.php');?>
		<?php include('style.php'); ?>
	</head>
	
	<body>
		
		<header>
			<?php include('includes/title.php');?>
			<?php include('includes/navigation.php');?>
		</header>
		<div id="contenu">
			<?php include('includes/info_user.php');?>
			<?php include('login_logout.php');?>
			<?php
					if(isset($_POST['codefichepsw']) AND isset($_POST['password1']) AND isset($_POST['password2']))
						{
							if($_POST['password1'] == $_POST['password2'])
								{
									/*Si les deux mot de passe correspondes, met les dans la bdd*/
									$req = $bdd->prepare('UPDATE utilisateurs_eleves SET password = :password WHERE codefiche = :codefiche');
									$req->execute(array(
												'password' => crypt($_POST['password1'], $_POST['codefichepsw']),
												'codefiche' => $_POST['codefichepsw']
									));	
									
									/*Supprimer la cle actuel et met la par mdpoubli&Eacute;*/
									$req = $bdd->prepare('UPDATE utilisateurs_eleves SET cle = :cle WHERE codefiche = :codefiche');
									$req->execute(array(
											'cle' => '',
											'codefiche' => $_POST['codefichepsw']
									));	
									echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
									
								}
							else
								{
								/*Si les mot de passe correspondes pas envoye le a l'autre page avec un e=1*/
								/* header('Location: forget.php?cle='.$_POST['cle'].'&cf='.$_POST['codefichepsw'].'&e=1 */
								echo "<meta http-equiv='Refresh' content='0; URL=forget.php?cle=".$_POST['cle']."&cf=".$_POST['codefichepsw']."&e=1'>";
								}	
						
						}
					
					
					
					
					elseif(isset($_GET['cle']) AND isset($_GET['cf']))
						{ 
						
						/*Requete pour aller chercher la cl&eacute;*/
						$reqSelectCle = $bdd->prepare('SELECT cle FROM utilisateurs_eleves WHERE codefiche= :codefiche');
						$reqSelectCle->execute(array(
													'codefiche' => $_GET['cf']
						)) or die(print_r($reqSelectCle->errorIndo()));
						$fetch = $reqSelectCle->fetch();
						$cle = $fetch['cle'];
						
								if($_GET['cle'] == $cle)
									{
										/*Les informations correspondes, affiche le formulaire de nouveaux mot de passe*/
									if(isset($_GET['e']) AND $_GET['e']==1){ echo '<a style="color: #ff0000;" >Un erreur s\'est produit, veuillez inscrire vos informations &agrave; nouveau</a>';}
									?>
									<h2 id="mdp">Mot de passe oubli&eacute;e</h2>
									<form method="post" action="forget.php">
										<label for="password1"><h4>Changez votre mot de passe :</h4></label><br />
											<input type="password" name="password1" placeholder="QUERTY" id="password1" maxlength="20" value="" required/>
										</br>
											<input type="password" name="password2" placeholder="QUERTY" id="password2" maxlength="20" value="" required/>
										</br>
											<input type="HIDDEN" name="codefichepsw" value="<?php echo $_GET['cf']; ?>" id="codefichepsw" /> 
											<input type="HIDDEN" name="cle" value="<?php echo $_GET['cle']; ?>" id="cle" /> 
											<input id="connexion" type="submit" name="connexion" value="Soumettre"/>
									</form>
									<?php }
								else
									{
										/*Les information correspondes pas, affiche une message d'erreur*/
										echo 'Malheureusement un erreur de s&eacute;curit&eacute; est survenu, veuillez r&eacute;essayer.';
									}
						
						
						
						}
					elseif(isset($_POST['codefichepsw']) AND !isset($_GET['cle']))
						{ 
							/*ICI ONT GENERE UNE CLE ET ONT LINSERT DANS LA BDD */
							$cle=md5(uniqid(rand(), true)); 
							$req = $bdd->prepare('UPDATE utilisateurs_eleves SET cle = :cle WHERE codefiche = :codefiche');
							$req->execute(array(
												'cle' => $cle,
												'codefiche' => $_POST['codefichepsw']
								));	
							
							/*ICI ONT FECTCH LURL DU SITE */
							
							$reqSelectUrl = $bdd->prepare('SELECT url FROM siteinfo WHERE id= :id');
							$reqSelectUrl->execute(array(
														'id'=> 1
							)) or die(print_r($reqSelectUrl->errorInfo()));
							$fetchurl = $reqSelectUrl -> fetch();
							$url= $fetchurl['url'];
							
							/*ICI ONT FETCH LE EMAIL DU USER*/
							$reqSelectEmail = $bdd->prepare('SELECT email FROM utilisateurs_eleves WHERE codefiche= :codefiche');
							$reqSelectEmail->execute(array(
														'codefiche' => $_POST['codefichepsw']
							)) or die(print_r($reqSelectEmail->errorInfo())); 
							$fetchemail = $reqSelectEmail->fetch();
							$email = $fetchemail['email'];
							 
							$headers ='From: "ProCycle"<noreply@procycle.ca>'."\n"; 
							$headers .='Reply-To: noreply@procycle.net'."\n"; 
							$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
							$headers .='Content-Transfer-Encoding: 8bit'; 

							 $message ='<html><head><title>Récupération de mot de passe</title></head><body>Vous pouvez récupérer votre mot de passe à l\'adresse suivante :<a href="'.$url.'/forget.php?cle='.$cle.'&cf='.$_POST['codefichepsw'].'">'.$url.'/forget.php?cle='.$cle.'&cf='.$_POST['codefichepsw'].'</a> </body></html>'; 

							 if(mail($email, 'ProCycle', $message, $headers)) 
							 { ?>
								 <h2 id="mdp">Mot de passe oubli&eacute;</h2>
								<h4>Les informations de r&eacute;initialisation de votre mot de passe vous ont &eacute;t&eacute; envoy&eacute;es par courriel.</h4>
							 <?php } 
							 else 
							 { ?>
								 <h2 id="mdp">Mot de passe oubli&eacute;</h2>
								<h4>Un erreur est survenu, veuillez r&eacute;essayer, si l'erreur persiste, contactez votre enseignant.</h4>
							 <?php }
						 }
					else
						{ ?>
							<h2 id="mdp">Mot de passe oubli&eacute;</h2>
							<h4>Veuillez nous fournir votre code fiche afin que nous puissions vous envoyer un courriel avec les informations de r&eacute;cup&eacute;ration de votre compte.</h4>
							<form method="post" action="forget.php">
									<label for="codefichepsw">Code fiche :</label>
									</br>
									<input type="text" name="codefichepsw" placeholder="5097712" id="codefichepsw" maxlength="7" value="" required/>
									<input id="connexion" type="submit" name="connexion" value="Soumettre"/>
							</form>
							
						<?php }
			?>
			
		</div>
		<?php include('includes/credits.php');?>
	</body>

</html>

