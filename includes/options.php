<?php
	include('sqlconnect.php');
?>
<?php
	if(isset($_POST['codefiche']) AND is_numeric($_POST['codefiche'])) //si lutilisateur essaye de modifier
		{
			if(isset($_POST['opt1'])){$opt1=$_POST['opt1'];}else{$opt1='opt1';}
			if(isset($_POST['opt2'])){$opt2=$_POST['opt2'];}else{$opt2='opt2';}
			if(isset($_POST['opt3'])){$opt3=$_POST['opt3'];}else{$opt3='opt3';}
			if(isset($_POST['opt4'])){$opt4=$_POST['opt4'];}else{$opt4='opt4';}
			if(isset($_POST['opt5'])){$opt5=$_POST['opt5'];}else{$opt5='opt5';}
			if(isset($_POST['opt6'])){$opt6=$_POST['opt6'];}else{$opt6='opt6';}
			if(isset($_POST['opt7'])){$opt7=$_POST['opt7'];}else{$opt7='opt7';}
			if(isset($_POST['opt8'])){$opt8=$_POST['opt8'];}else{$opt8='opt8';}
			if(isset($_POST['opt9'])){$opt9=$_POST['opt9'];}else{$opt9='opt9';}
			if(isset($_POST['opt10'])){$opt10=$_POST['opt10'];}else{$opt10='opt10';}
			$verifmail="!^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$!";
			$requete = $bdd->query('SELECT password FROM utilisateurs_eleves WHERE codefiche ='.$_POST['codefiche']) or die(print_r($bdd->errorInfo()));
			$verifpassword = $requete->fetch();
			$reqSelectCodefiche = $bdd->query('SELECT codefiche FROM utilisateurs_eleves') or die(print_r($bdd->errorInfo()));
				
				$codeficheExiste = false;
				
				while($fetch = $reqSelectCodefiche->fetch())
				{
					if($_POST['codefiche'] == $fetch['codefiche'])
					{
						$codeficheExiste = true;
					}
				}
				
				$reqSelectCodefiche->closeCursor();
				
			
				
			if(isset($_COOKIE['codefiche']) AND preg_match($verifmail,$_POST['email']) AND $verifpassword['password'] == crypt($_POST['password'], $_POST['codefiche']))
				{
					$cas=2;
		
					/*met a jour les variable dans le bdd*/	
					$req = $bdd->prepare('UPDATE utilisateurs_eleves SET niveau = :niveau, password = :password, email = :email, prenom = :prenom, nom = :nom, groupe = :groupe, option1 = :option1, option2 = :option2, option3 = :option3, option4 = :option4, option5 = :option5, option6 = :option6, option7 = :option7, option8 = :option8, option9 = :option9, option10 = :option10 WHERE codefiche = :codefiche');
						$req->execute(array(
							'niveau' => $_POST['niveau'],
							'password' => crypt($_POST['password'], $_POST['codefiche']),
							'email' => $_POST['email'],
							'prenom' => ucfirst(STRTOLOWER($_POST['prenom'])),
							'nom' => ucfirst(STRTOLOWER($_POST['nom'])),
							'groupe' => $_POST['groupe'],
							'option1' => $opt1,
							'option2' => $opt2,
							'option3' => $opt3,
							'option4' => $opt4,
							'option5' => $opt5,
							'option6' => $opt6,
							'option7' => $opt7,
							'option8' => $opt8,
							'option9' => $opt9,
							'option10' => $opt10,						
							'codefiche' => $_POST['codefiche']
							))or die(print_r($req->errorInfo()));
							
					if(isset($_POST['password']) AND $_POST['password'] != '' AND isset($_POST['password2']) AND $_POST['password2'] != '' AND isset($_POST['password3']) AND$_POST['password3'] != '' AND $verifpassword['password'] == crypt($_POST['password'], $_POST['codefiche']) AND $_POST['password2'] == $_POST['password3']) /*si les champs mdp sont remplis*/
						{
							/*met le nouveau mot de passe*/
							$req = $bdd->prepare('UPDATE utilisateurs_eleves SET password = :password WHERE codefiche = :codefiche');
								$req->execute(array(
									'password' => crypt($_POST['password2'], $_POST['codefiche']),
									'codefiche' => $_POST['codefiche']
									));	
						}
							
				}
				
				
			elseif(preg_match($verifmail,$_POST['email']) AND $_POST['password'] == $_POST['password2'] AND isset($codeficheExiste) AND $codeficheExiste != true)
				{
					$cas=2;
					
					
					
					/*insert ces informations dans la bdd*/
					$req = $bdd->prepare('INSERT INTO utilisateurs_eleves(codefiche, password, email, prenom, nom, niveau, groupe, option1, option2, option3, option4, option5, option6, option7, option8, option9, option10) VALUES(:codefiche, :password, :email, :prenom, :nom, :niveau, :groupe, :option1, :option2, :option3, :option4, :option5, :option6, :option7, :option8, :option9, :option10)') or die(print_r($bdd->errorInfo()));
					$req->execute(array(
						'codefiche' => $_POST['codefiche'],
						'password' => crypt($_POST['password'], $_POST['codefiche']),
						'email' => $_POST['email'],
						'prenom' => ucfirst(STRTOLOWER($_POST['prenom'])),
						'nom' => ucfirst(STRTOLOWER($_POST['nom'])),
						'niveau' => $_POST['niveau'],
						'groupe' => $_POST['groupe'],
						'option1' => $opt1,
						'option2' => $opt2,
						'option3' => $opt3,
						'option4' => $opt4,
						'option5' => $opt5,
						'option6' => $opt6,
						'option7' => $opt7,
						'option8' => $opt8,
						'option9' => $opt9,
						'option10' => $opt10
						)) or die(print_r($req->errorInfo()));
						
				}
				
				
				if(isset($cas) AND $cas==2)
					{
						setcookie('codefiche', $_POST['codefiche'], time() + 365*24*3600, null, null, false, true); // On &eacute;crit un cookie
						setcookie('password', 'on', time() + 365*24*3600, null, null, false, true); // On &eacute;crit un cookie
						echo "<meta http-equiv='Refresh' content='0; URL=../options.php?e=2&codefiche=".$_POST['codefiche']."'>";
						
					}
				else
					{
						echo "<meta http-equiv='Refresh' content='0; URL=../options.php?e=1'>";
					}
				
				
		
		}
else
	{ ?>
		<div id="options">
		<form method="post" action="includes/options.php">
			<p>
				<div id="informations">
					<?php if(isset($_GET['e']) AND $_GET['e']==1) { echo '<a style="color: #ff0000;" >Une erreur s\'est produite, veuillez r&eacute;essayer</a>'; }?>
					<?php if(isset($_GET['e']) AND $_GET['e']==2) { echo '<a style="color: green;" >Les informations ont bien &eacute;t&eacute; enregistr&eacute;es</a>'; }?>
					<!-- ATTENTION ICI ON DONNE A LA VARIABLE CODEFICHE LA VALEUR DU COOKIE SIL EXISTE-->
					<?php 
					if(isset($_COOKIE['codefiche']))/*si le cookie codefiche existe donne y la valeur a la variable codefiche*/
						{
						$codefiche = $_COOKIE['codefiche'];
						}
					else /*sinon donne lui la valeur nul*/
						{
						$codefiche = '';
						}
					?>
					<!------------------------------------------------------->
					
					
					
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LE CODEFICHE-->
					<br />
					<label for="codefiche">Code fiche:</label><br />
					<input title="Votre code fiche" type="text" name="codefiche" placeholder="5097712" id="codefiche" maxlength="7"
					value="<?php 
					/*si le cookie code fiche existe affiche le dans value*/
					if (isset($_COOKIE['codefiche']))
						{
							echo $_COOKIE['codefiche'];
						}
					else /*sinon affiche rien pour le laisser afficher le placeholder*/
						{
						echo '';
						}?>" <?php if(isset($_COOKIE['codefiche'])){echo 'readonly ';}?>required/>
					<br />
					<!------------------------------------------------------->
					
					
					
					
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LE EMAIL-->
					<label for="email">Votre email:</label><br />
					<input title="Votre email" type="text" name="email" placeholder="info@legroupepp.ca" id="email" maxlength="50"
					value="<?php $requete = $bdd->query('SELECT id, email FROM utilisateurs_eleves WHERE codefiche ='.$codefiche.'');
							/*si codefiche existe pas &eacute;crit rien dans value*/ 
						if($requete == false)
							{
							echo '';
							}
						else /*met dans value la valeur deja choisi pour prenom*/
							{ 
							$information = $requete->fetch();
							echo $information['email'];
							}
						?>" required />
					<br />
					<!------------------------------------------------------->
					
					
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LE MOT DE PASSE-->
					<?php  if(isset($_COOKIE['password']) && $_COOKIE['password'] == 'on')
					{ ?>
						<!-- si le cookie password existe et qu'il a la valeur on, affiche l'option changer de mdp -->
						<label for="password">Mot de passe:</label><br />
						<input title="Votre mot de passe" type="password" name="password" placeholder="QUERTY" id="password" maxlength="20"
						value=""/required>
						<br />
						<label for="password2">Changer mon mot de passe :</label><br />
						<input <?php if(isset($_COOKIE['codefiche'])){echo 'title="Nouveau mot de passe"';}else{echo 'title="Confirmation du mot de passe"';}?>type="password" name="password2" placeholder="QUERTY" id="password2" maxlength="20"
						value=""/>
						
						<label for="password3"></label><br />
						<input title="Confirmation du nouveau mot de passe" type="password" name="password3" placeholder="QUERTY" id="password3" maxlength="20"
						value=""/>
					
						<br />
					<?php } 
					else
					{ ?>
						<!--si le cookie password existe pas -->
						<label for="password">Mot de passe:</label><br />
						<input tile="Mot de passe" type="password" name="password" placeholder="QUERTY" id="password" maxlength="20"
						value=""/ required>
					
						<label for="password2"></label><br />
						<input title="Confimation du mot de passe" type="password" name="password2" placeholder="QUERTY" id="password2" maxlength="20"
						value="" required />
						<br />
					<?php } ?>
					<!------------------------------------------------------->
					
					
					
					
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LE PRENOM-->
					<label for="prenom">Pr&eacute;nom:</label><br />
					<input title="Votre pr&eacute;nom" type="text" name="prenom" placeholder="William" id="prenom" maxlength="25" value="<?php $requete = $bdd->query('SELECT id, prenom FROM utilisateurs_eleves WHERE codefiche ='.$codefiche);
							/*si codefiche existe pas &eacute;crit rien dans value*/ 
						if($requete == false)
							{
							echo '';
							}
						else /*met dans value la valeur deja choisi pour prenom*/
							{ 
							$information = $requete->fetch();
							echo $information['prenom'];
							}
						?>" required />
					
					<br />
					<!------------------------------------------------------->
					
					
					
					
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LE NOM-->
					<label for="nom">Nom:</label><br />
					<input title="Votre nom" type="text" name="nom" placeholder="Patry" id="nom" value="<?php $requete = $bdd->query('SELECT id, nom FROM utilisateurs_eleves WHERE codefiche ='.$codefiche);
							/*si codefiche existe pas &eacute;crit rient dans value*/ 
						if($requete == false)
							{
							echo '';
							}
						else /*si codefiche existe &eacute;crit la valeur choisis pour nom dans value*/
							{ 
							$information = $requete->fetch();
							echo $information['nom'];
							}
							?>" maxlength="25" required />
					
					<br />
					<!------------------------------------------------------->
					
					
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LE NIVEAU-->
					<?php
						$requete = $bdd->query('SELECT id, niveau FROM utilisateurs_eleves WHERE codefiche ='.$codefiche);
						if($requete == false) 
							{
							$niveau_selected = 1;
							}
						else
							{
							$requete_fetched = $requete->fetch(); $niveau_selected = $requete_fetched['niveau'];
							}
					?>
					<label for="niveau">Niveau:</label><br />
					<select name="niveau" id="niveau">
						<option value="1"<?php if($niveau_selected == 1){echo ' selected';} ?>>Protic 1</option>
						<option value="2"<?php if($niveau_selected == 2){echo ' selected';} ?>>Protic 2</option>
						<option value="3"<?php if($niveau_selected == 3){echo ' selected';} ?>>Protic 3</option>
						<option value="4"<?php if($niveau_selected == 4){echo ' selected';} ?>>Protic 4</option>
						<option value="5"<?php if($niveau_selected == 5){echo ' selected';} ?>>Protic 5</option>
					</select>
					<br />
					<!------------------------------------------------------->
					
					
					
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LE GROUPE-->
					<?php
						$requete = $bdd->query('SELECT id, groupe FROM utilisateurs_eleves WHERE codefiche ='.$codefiche);
						if($requete == false) 
							{
							$groupe_selected = '31';
							}
						else
							{
							$requete_fetched = $requete->fetch(); $groupe_selected = $requete_fetched['groupe'];
							}
					?>
					<label for="groupe">Groupe:</label><br />
					<select name="groupe" id="groupe">
						<option value="31"<?php if($groupe_selected == '31'){echo ' selected';} ?>>31</option>
						<option value="32"<?php if($groupe_selected == '32'){echo ' selected';} ?>>32</option>
						<option value="33"<?php if($groupe_selected == '33'){echo ' selected';} ?>>33</option>
						<option value="34"<?php if($groupe_selected == '34'){echo ' selected';} ?>>34</option>
						<option value="35"<?php if($groupe_selected == '35'){echo ' selected';} ?>>35</option>
						<option value="36"<?php if($groupe_selected == '36'){echo ' selected';} ?>>36</option>
						<option value="37"<?php if($groupe_selected == '37'){echo ' selected';} ?>>37</option>
						<option value="38"<?php if($groupe_selected == '38'){echo ' selected';} ?>>38</option>
						<option value="39"<?php if($groupe_selected == '39'){echo ' selected';} ?>>39</option>
						<option value="40"<?php if($groupe_selected == '40'){echo ' selected';} ?>>40</option>
					</select>
				
					<br /><br />
					<!------------------------------------------------------->
				</div>
				
				<div id="matieres">
					<!-- ATTENTION ICI ON MET LE FORMULAIRE POUR LES OPTIONS-->
					<?php
						$requete_fetched = '';
					if(isset($_COOKIE['codefiche']) && isset($_COOKIE['password']))
					{
						$requete = $bdd->query('SELECT id, option1, option2, option3, option4, option5, option6, option7, option8, option9, option10 FROM utilisateurs_eleves WHERE codefiche ='.$codefiche);
							if($requete == false) 
								{
								$requete_fetched = 'null';
								}
							else
								{
								$requete_fetched = $requete->fetch(); 		
								}
							
					}
						$reqSelect = $bdd->query('SELECT * FROM options ORDER BY groupe_option');

						$incrOption = 1;
						while($get = $reqSelect->fetch())
						{	
							$option[$get['groupe_option'].'-'.$incrOption] = $get['option'];
							$groupe_option[$incrOption] = $get['groupe_option'];
							$prof[$incrOption] = $get['prof'];
							
							// ici on fetch le nom du professeur						
							$reqSelectProfNom = $bdd->prepare('SELECT prenom, nom FROM utilisateurs WHERE utilisateur = :utilisateur');
							$reqSelectProfNom->execute(array(
															'utilisateur' => $prof[$incrOption]
															)) or die(print_r($reqSelectProfNom->errorInfo()));
							$fetch = $reqSelectProfNom->fetch();
							$profNom = $fetch['prenom'].' '.$fetch['nom'];
							// ---------------------------------
							
							
							if(isset($groupe_option[$incrOption - 1]) AND $groupe_option[$incrOption - 1] != $groupe_option[$incrOption])
								{
									?>
									<br />
									<?php
								} 
									?>
							<input type="radio" name="opt<?php echo $groupe_option[$incrOption]; ?>" value="<?php echo $prof[$incrOption]; ?>"<?php if(isset($_COOKIE['codefiche']) && isset($_COOKIE['password']) && $requete_fetched['option'+$groupe_option[$incrOption]] == $prof[$incrOption]){echo 'checked';}?> ><?php echo $option[$groupe_option[$incrOption].'-'.$incrOption].' - '.$profNom;?></input> <br />
							<?php	

							$incrOption++ ;
						}
					?>
					<br />
					<!------------------------------------------------------->
				</div>

				<!-- ATTENTION ICI ON MET LE BOUTON SOUMETTRE DU FORMULAIRE-->
				<div id="soumettre"><input type="submit" value="Soumettre" /></div>
				<!------------------------------------------------------->

		</form>
		<?php if(isset($_COOKIE['codefiche'])){ echo ''; }else{echo'<a  href="forget.php" style="text-decoration: none; " ><h4>J\'ai oubli&eacute; mon mot de passe</h4></a>'; } ?> 
		</div>
	<?php }
?>