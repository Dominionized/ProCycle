<link rel="stylesheet" type="text/css" href="fonctions.css" />
<?php
	// Fonction pour générer la grille horaire
	function genererGrilleHoraire($type, $action, $niveau, $groupe)
	{
		// Générer la grille horaire pour un compte administrateur
		if($type == 'admin')
		{ 
			include('sqlconnect.php');
		?>
			<link rel="stylesheet" type="text/css" href="horaire.css" />
		
			<h1><?php echo('Protic '.$niveau.' - Groupe '.$groupe); ?></h1>
		
			<form method="post" action="<?php echo($action); ?>">
			<input type="text" name="niveau" value="<?php echo $niveau ?>" id="nodisplay" style="display: none;" readonly />
			<input type="text" name="groupe" value="<?php echo $groupe ?>" id="nodisplay" style="display: none;" readonly /> 
			<table id="horaire">
				<thead>
					<tr>
						<th id="topleft">Jour</th>
						<th>P&eacute;riode 1</th>
						<th>P&eacute;riode 2</th>
						<th>Midi</th>
						<th>P&eacute;riode 3</th>
						<th>P&eacute;riode 4</th>
						<th>&Eacute;tude</th>
					</tr>
				</thead>
				
				<tbody>
				<?php
					$jour = 1;
					while($jour <= 9)
					{
				?>
					<tr>
						<td id="left"><?php echo $jour; ?></td>
						<?php
							$periode = 1;
							while($periode <= 6)
							{
							
							$reqSelect = $bdd->query('SELECT prof FROM horairedefault WHERE niveau = '.$niveau.' AND groupe = '.$groupe.' AND jour = '.$jour.' AND periode = '.$periode.'');
							
							$profCaseActuelle = $reqSelect->fetch();
						?>
						<td>
							<label for="<?php echo 'prof_'.$jour.'-'.$periode ?>">Professeur :</label><br />
								<select name="<?php echo 'prof_'.$jour.'-'.$periode ?>" id="<?php echo 'prof_'.$jour.'-'.$periode ?>">
									<option value="" <?php if($profCaseActuelle[0] == ''){ echo('selected'); } ?> >(aucun)</option>
									<?php
										// Liste les groupes d'option
										
										$reqSelectOption = $bdd->query('SELECT * FROM options') or die(print_r($bdd->errorInfo()));
										
										$i = 1;
										while($fetchOption = $reqSelectOption->fetch())
										{
											if($fetchOption['id'] == 1)
											{
												?>
													<option value="opt<?php echo $i; ?>" <?php if($profCaseActuelle[0] == 'opt'.$i){ echo('selected'); } ?> >Options <?php echo $i.' (ex: '.$fetchOption['option'].' )'; ?></option>
												<?php
												
												$i++ ;
											}
											elseif($fetchOption['groupe_option'] == $i)
											{
												?>
													<option value="opt<?php echo $i; ?>" <?php if($profCaseActuelle[0] == 'opt'.$i){ echo('selected'); } ?> >Options <?php echo $i.' (ex: '.$fetchOption['option'].' )'; ?></option>
												<?php	
												
												$i++ ;
											}
										}
									
										// Liste les professeurs
										
										$requete = $bdd->query('SELECT * FROM utilisateurs ORDER BY utilisateur');
									
										$valeur = 1;								
										while($professeurs = $requete->fetch())
										{ 
										?>
											<option value="<?php echo $professeurs['utilisateur']; ?>" <?php if($profCaseActuelle[0] == $professeurs['utilisateur']){ echo('selected'); } ?> ><?php echo $professeurs['nom'].' '.$professeurs['prenom']; ?></option>
										<?php 
											$valeur ++;
										}
										
										$requete->closeCursor();	
										
										$reqSelect->closeCursor();
									?>
									
								</select>
						</td>
						<?php
							$periode++ ;
							}
						?>
						
					</tr>
				<?php
					$jour++ ;
					}
				?>
				</tbody>
			</table>
			<input type="submit" value="Soumettre"  id="connexion"/>
			
			</form>
			
		<?php 
			return $groupe;
		}
		
		// Générer la grille horaire pour un compte professeur
		elseif($type == 'prof')
		{
			include('sqlconnect.php');
		}
	
	}
	
	// Fonction pour enregistrer les informations de genererGrilleHoraire()
	function enregistrerGrilleHoraire($type, $table, $niveau, $groupe)
	{
		include('sqlconnect.php');		
		
		if($type == 'admin')
		{
			
			$jour = 1;
			while($jour <= 9)
			{
				$periode = 1;
				while($periode <= 6)
				{
					// On reprend le prof de la case spécifique reçu de la fonction genererGrilleHoraire() 
					$prof = $_POST['prof_'.$jour.'-'.$periode.''];
				
					$select = $bdd->query('SELECT * FROM '.$table.' WHERE niveau = '.$niveau.' AND groupe = '.$groupe.' AND jour = '.$jour.' AND periode = '.$periode.'') or die(print_r($bdd->errorInfo()));
					
					$fetch = $select->fetch();
					
					$select->closeCursor();
					
					// Création de l'array $prof, exemple: $prof[pr5-gr33_1-1]
					$profs['pr'.$fetch['niveau'].'-gr'.$fetch['groupe'].'_'.$fetch['jour'].'-'.$fetch['periode']] = $fetch['prof'];
					
					if($niveau == $fetch['niveau'] AND $groupe == $fetch['groupe'] AND $jour == $fetch['jour'] AND $periode == $fetch['periode'])
					{	
						if($periode != 3)
						{
							$reqUpdate = $bdd->prepare('UPDATE horairedefault SET prof = :prof WHERE niveau = :niveau AND groupe = :groupe AND jour = :jour AND periode = :periode');
							$reqUpdate ->execute(array(
													'prof' => $prof,
													'niveau' => $niveau,
													'groupe' => $groupe,
													'jour' => $jour,
													'periode' => $periode
													)) or die(print_r($reqUpdate->errorInfo()));												
							$reqUpdate->closeCursor();
						}
						// Si la période actuelle est une récup, la préiode sera pareille pour tous les groupes
						else
						{
							$reqUpdate = $bdd->prepare('UPDATE horairedefault SET prof = :prof WHERE niveau = :niveau AND jour = :jour AND periode = :periode');
							$reqUpdate ->execute(array(
													'prof' => $prof,
													'niveau' => $niveau,
													// pas de groupe
													'jour' => $jour,
													'periode' => $periode
													)) or die(print_r($reqUpdate->errorInfo()));												
							$reqUpdate->closeCursor();
							
							$g = 31;
							while($g <= 40)
							{
								$reqSelectGroupe = $bdd->prepare('SELECT * FROM horairedefault WHERE niveau = :niveau AND groupe = :groupe AND jour = :jour AND periode = :periode');
								$reqSelectGroupe->execute(array(
																'niveau' => $niveau,
																'groupe' => $g,
																'jour' => $jour,
																'periode' => $periode
																)) or die(print_r($reqSelectGroupe->errorInfo()));
								
								$fetch = $reqSelectGroupe->fetch();
								$profModifier = $fetch['prof'];
								
								$reqSelectGroupe->closeCursor();
								
								if($fetch == false)
								{
									$reqInsert = $bdd->prepare('INSERT INTO horairedefault(niveau , groupe, jour , periode , prof) VALUES(:niveau , :groupe , :jour , :periode , :prof)');
									$reqInsert ->execute(array(
															'niveau' => $niveau,
															'groupe' => $g,
															'jour' => $jour,
															'periode' => $periode,
															'prof' => $prof
															)) or die(print_r($reqInsert->errorInfo()));
																											
									$reqInsert->closeCursor();
								}
								else
								{
									$reqUpdate = $bdd->prepare('UPDATE horairedefault SET prof = :prof WHERE niveau = :niveau AND jour = :jour AND periode = :periode');
									$reqUpdate ->execute(array(
															'prof' => $prof,
															'niveau' => $niveau,
															// pas de groupe
															'jour' => $jour,
															'periode' => $periode
															)) or die(print_r($reqUpdate->errorInfo()));												
									$reqUpdate->closeCursor();
								}
								
								$g++;								
								
							}
						}
					}
					else
					{
						if($periode != 3)
						{					
							$reqInsert = $bdd->prepare('INSERT INTO horairedefault(niveau , groupe , jour , periode , prof) VALUES(:niveau , :groupe , :jour , :periode , :prof)');
							$reqInsert ->execute(array(
													'niveau' => $niveau,
													'groupe' => $groupe,
													'jour' => $jour,
													'periode' => $periode,
													'prof' => $prof
													)) or die(print_r($reqInsert->errorInfo()));
																									
							$reqInsert->closeCursor();
						}
						// Si la période actuelle est une récup, la préiode sera pareille pour tous les groupes
						else
						{
							$reqInsert = $bdd->prepare('INSERT INTO horairedefault(niveau , groupe, jour , periode , prof) VALUES(:niveau , :groupe, :jour , :periode , :prof)');
							$reqInsert ->execute(array(
													'niveau' => $niveau,
													'groupe' => $groupe,
													'jour' => $jour,
													'periode' => $periode,
													'prof' => $prof
													)) or die(print_r($reqInsert->errorInfo()));
																									
							$reqInsert->closeCursor();
							
							$g = 31;
							while($g <= 40)
							{
								$reqSelectGroupe = $bdd->prepare('SELECT * FROM horairedefault WHERE niveau = :niveau AND groupe = :groupe AND jour = :jour AND periode = :periode');
								$reqSelectGroupe->execute(array(
																'niveau' => $niveau,
																'groupe' => $g,
																'jour' => $jour,
																'periode' => $periode
																)) or die(print_r($reqSelectGroupe->errorInfo()));
								
								$fetch = $reqSelectGroupe->fetch();
								$profModifier = $fetch['prof'];
								
								$reqSelectGroupe->closeCursor();
								
								if($fetch == false)
								{
									$reqInsert = $bdd->prepare('INSERT INTO horairedefault(niveau , groupe, jour , periode , prof) VALUES(:niveau , :groupe , :jour , :periode , :prof)');
									$reqInsert ->execute(array(
															'niveau' => $niveau,
															'groupe' => $g,
															'jour' => $jour,
															'periode' => $periode,
															'prof' => $prof
															)) or die(print_r($reqInsert->errorInfo()));
																											
									$reqInsert->closeCursor();
								}
								else
								{
									$reqUpdate = $bdd->prepare('UPDATE horairedefault SET prof = :prof WHERE niveau = :niveau AND jour = :jour AND periode = :periode');
									$reqUpdate ->execute(array(
															'prof' => $prof,
															'niveau' => $niveau,
															// pas de groupe
															'jour' => $jour,
															'periode' => $periode
															)) or die(print_r($reqUpdate->errorInfo()));												
									$reqUpdate->closeCursor();
								}
								
								$g++;								
								
							}

						}
					}		

					$fetch = NULL;
					
				
					$periode++ ;
				}
				
		
				$jour++ ;
			}
						
			
		}
		
		
	}

	// Fonction pour créer un compte professeur
	function genererGrilleAnneeScolaire($etape, $action)
	{
			// Array du nombre de jours de chaque mois
		
			// Janvier
			$jourMois[1] = 31;
			
			// Février
			if(isset($_POST['dernierebissextile']) AND $_POST['dernierebissextile'] == 'on')
			{
				$jourMois[2] = 29;
			}
			else
			{
				$jourMois[2] = 28;
			}
			
			// Mars
			$jourMois[3] = 31;
			
			// Avril
			$jourMois[4] = 30;
			
			// Mai
			$jourMois[5] = 31;
			
			// Juin
			$jourMois[6] = 30;
			
			// Juillet
			$jourMois[7] = 31;
			
			// Aout
			$jourMois[8] = 31;
			
			// Septembre
			$jourMois[9] = 30;
			
			// Octobre
			$jourMois[10] = 31;
			
			// Novembre
			$jourMois[11] = 30;
			
			// Décembre
			$jourMois[12] = 31;
			
	
		if($etape == 1)
		{
		?>
			<p>
			<form method="post" action="<?php echo $action; ?>">
				
				<!-- Formulaire de la date du premier jour d'école -->
				
				<label for="premierjour">Date du premier jour d'&eacute;cole: </label><br />
				
				<select name="premierjoursemaine" id="premierjoursemaine">
					<option value="1">Lundi</option>
					<option value="2">Mardi</option>
					<option value="3">Mercredi</option>
					<option value="4">Jeudi</option>
					<option value="5">Vendredi</option>
				</select>
				
				<select name="premierjour" id="premierjour">
				
					<?php
				
						$i = 1;
						while($i <= 31)
						{
						?>
							<option value="<?php echo $i; ?>" <?php if($i == 1) { echo 'selected'; } ?> ><?php echo $i; ?></option>
						<?php
						$i++ ;
						}
					
					?>
					
				</select>
				
				<select name="premiermois" id="premiermois">
				
					<option value="1">Janvier</option>
					<option value="2">F&eacute;vrier</option>
					<option value="3">Mars</option>
					<option value="4">Avril</option>
					<option value="5">Mai</option>
					<option value="6">Juin</option>
					<option value="7">Juillet</option>
					<option value="8">Ao&ucirc;t</option>
					<option value="9" selected >Septembre</option>
					<option value="10">Octobre</option>
					<option value="11">Novembre</option>
					<option value="12">D&eacute;cembre</option>
				
				</select>
				
				<label for="premierjourcycle">jour: </label>
				<select name="premierjourcycle" id="premierjourcycle">
					
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					
				</select>
				
				<input type="text" name="premiereannee" id="premiereannee" maxlength="4" placeholder="Ann&eacute;e" />
				
				<br /><br />
				
				<!-- Date du dernier jour d'école -->
				
				<label for="dernierjour">Date du dernier jour d'&eacute;cole: </label><br />
				
				<select name="dernierjour" id="dernierjour">
				
					<?php
				
						$i = 1;
						while($i <= 31)
						{
						?>
							<option value="<?php echo $i; ?>" <?php if($i == 21) { echo 'selected'; } ?> ><?php echo $i; ?></option>
						<?php
						$i++ ;
						}
					
					?>
					
				</select>
				
				<select name="derniermois" id="derniermois">
				
					<option value="1">Janvier</option>
					<option value="2">F&eacute;vrier</option>
					<option value="3">Mars</option>
					<option value="4">Avril</option>
					<option value="5">Mai</option>
					<option value="6" selected >Juin</option>
					<option value="7">Juillet</option>
					<option value="8">Ao&ucirc;t</option>
					<option value="9">Septembre</option>
					<option value="10">Octobre</option>
					<option value="11">Novembre</option>
					<option value="12">D&eacute;cembre</option>
				
				</select>
				
				<input type="text" name="derniereannee" id="derniereannee" maxlength="4" placeholder="Ann&eacute;e" />
				
				<input type="checkbox" name="dernierebissextile" id="dernierebissextile" /> <label for="dernierebissextile">Ann&eacute;e bissextile</label>
				
				<br /><br />
				
				<input type="submit" value="Soumettre"  id="connexion"/> <a style="color:#FF0000;">Attention! En cliquant sur &laquo; soumettre &raquo;, vous allez remplacer le calendrier actuel</a>
				
	 		</form>
			</p>
		
		<?php
		}
		elseif($etape == 2)
		{
			if($_POST['premierjour'] <= $jourMois[$_POST['premiermois']] AND $_POST['dernierjour'] <= $jourMois[$_POST['derniermois']] AND $_POST['premiereannee'] +1 == $_POST['derniereannee'])
			{
			
				// Array des jours de la semaine
					
				$jourSemaine[1] = 'Lundi';
				$jourSemaine[2] = 'Mardi';
				$jourSemaine[3] = 'Mercredi';
				$jourSemaine[4] = 'Jeudi';
				$jourSemaine[5] = 'Vendredi';
				$jourSemaine[6] = 'Samedi';
				$jourSemaine[7] = 'Dimanche';
			
				if(isset($_POST['derniermois']))
				{
					// On supprime les données de l'année passée
					
					include('sqlconnect.php');
					$reqDelete = $bdd->exec('DELETE FROM cyclesannee');
						
					// Boucle qui détermine et enregistre chaque cycle et la date de chaque jour dans la bdd
					
					$jourCycle = $_POST['premierjourcycle']; 			// Jour 1, Jour 2, Jour 3...
					$cycle = 1; 										// Cycle 1, Cycle 2, Cycle 3...
					$jourSemaineActuel = $_POST['premierjoursemaine']; 	// Lundi, Mardi, Mercredi...
					$jourActuel = $_POST['premierjour']; 				// 1, 2, 3, 4, 5, 6...
					$moisActuel = $_POST['premiermois']; 				// Janvier, Février, Mars...
					$anneeActuelle = $_POST['premiereannee']; 			// 2012, 2013, 2014, 2015...
					
					if($_POST['dernierjour'] == $jourMois[$_POST['derniermois']])
					{
						$jourSpec = 1;
						$moisSpec = $_POST['derniermois'] +1;
					}
					else
					{
						$jourSpec = $_POST['dernierjour'] +1;
						$moisSpec = $_POST['derniermois'];
					}
					
					while($jourActuel.'-'.$moisActuel != $jourSpec.'-'.$moisSpec)
					{
						
						if($jourSemaineActuel <= 5)
						{
							
						
							$reqInsert = $bdd->prepare('INSERT INTO cyclesannee(cycle, jourcycle, joursemaine, jour, mois, annee) VALUES(:cycle, :jourcycle, :joursemaine, :jour, :mois, :annee)');
							$reqInsert ->execute(array(
													'cycle' => $cycle,
													'jourcycle' => $jourCycle,
													'joursemaine' => $jourSemaineActuel,
													'jour' => $jourActuel,
													'mois' => $moisActuel,
													'annee' => $anneeActuelle
													));
						}
						
						// Increment du jour de la semaine actuel
						if($jourSemaineActuel == 7)
						{
							$jourSemaineActuel = 1;
						}
						else
						{
							$jourSemaineActuel++ ;
						}
						
						
						
						// Increment du jour, du mois et de l'année en fonction du nombre de jours par mois
						if($jourActuel == $jourMois[$moisActuel])
						{				
							if($moisActuel == 12)
							{
								$anneeActuelle++ ;
								$moisActuel = 0; // On le fait égaler, puisqu'il y a un increment un peu plus bas
							}
						
							$jourActuel = 1;
							$moisActuel++ ;
						}
						else
						{
							$jourActuel++ ;
						}
						
				
						
						// Increment du jour cycle et du cycle actuel
						if($jourSemaineActuel <= 5)
						{
							if($jourCycle == 9)
							{
								$cycle++ ;
								$jourCycle = 1;
							}
							else
							{
								$jourCycle++ ;
							}
						}
					
					}
				
				}
				else
				{
					echo 'Une erreur est survenue.';
					$autorisation = false;
				}
				
				
			}
			else
			{
				echo 'La date entr&eacute;e est invalide, veuillez recommencer';
				$autorisation = false;
			}
		}
		elseif($etape == 3)
		{
		
			include('sqlconnect.php');
			
			// Fetch la première ligne
			$reqSelectPremier = $bdd->query('SELECT * FROM cyclesannee');
			$arrayMois = $reqSelectPremier->fetchall();
			$premier = $arrayMois[0];
			
			// Fetch la dernìère ligne
			$reqSelectDernier = $bdd->query('SELECT * FROM cyclesannee ORDER BY id DESC');
			$arrayMois = $reqSelectDernier->fetchall();
			$dernier = $arrayMois[0];
			
			
			$mois[1] = 'Janvier';
			$mois[2] = 'F&eacute;vrier';
			$mois[3] = 'Mars';
			$mois[4] = 'Avril';
			$mois[5] = 'Mai';
			$mois[6] = 'Juin';
			$mois[7] = 'Juillet';
			$mois[8] = 'Ao&ucirc;t';
			$mois[9] = 'Septembre';
			$mois[10] = 'Octobre';
			$mois[11] = 'Novembre';
			$mois[12] = 'D&eacute;cembre';
			
			// Création des tableaux de chaque mois de l'année
			?>
				<form method="post" action="<?php echo $action; ?>">
				<input type="text" name="etape3" value="true" style="display: none;"/>
			<?php
			$moisActuel = $premier['mois'];
			while($moisActuel != $dernier['mois']+1)
			{
				
				$e = 0;
				$enchainement = false;
			
				?>
				<h1 id="titre_table">
				<?php
									
					$reqSelectCeMois = $bdd->prepare('SELECT * FROM cyclesannee WHERE mois= :mois');
					$reqSelectCeMois->execute(array(
											'mois' => $moisActuel
											));
					$i = 0;
					while($infosActuelles = $reqSelectCeMois->fetch())
					{
						$jourArray[$i] = $infosActuelles['jour'];
						$joursemaineArray[$i] = $infosActuelles['joursemaine'];
						
						$i++ ;
					}
					$reqSelectCeMois->closeCursor();
					
					$reqSelectJourMois = $bdd->prepare('SELECT jour FROM cyclesannee WHERE mois= :mois ORDER BY jour DESC');
					$reqSelectJourMois->execute(array(
											'mois' => $moisActuel
											));
					$dernierJourMois = $reqSelectJourMois->fetch();
												
					echo($mois[$moisActuel].' '.$infosActuelles['annee']); 
					
				?>
				</h1>
				<p>
				<table id="annee">
					<thead>
						<tr>
							<th>Lun</th>
							<th>Mar</th>
							<th>Mer</th>
							<th>Jeu</th>
							<th>Ven</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						$s = 1;
						while($s <= 5)
						{ 
							?>
								<tr>
									<?php
										$j = 1;
										while($j <= 5)
										{											
											?>
																					
											<td><?php
												
												
												if(isset($jourArray[$e]) AND $moisActuel == $_POST['derniermois'] AND $jourArray[$e] == $_POST['dernierjour'] + 1)
												{
													$enchainement = false;
												}												
												elseif(isset($enchainement) AND $enchainement == true AND isset($jourArray[$e]) AND $jourArray[$e] <= $dernierJourMois['jour'] AND isset($joursemaineArray[$e]))
												{		

													echo $jourArray[$e];
													
													?>
													<select name="<?php echo $moisActuel.'-'.$jourArray[$e]; ?>" id="<?php echo $moisActuel.'-'.$jourArray[$e]; ?>">
														<option value="j"> </option>
														<option value="c">c</option>
														<option value="e">e</option>
													</select>
													<?php
													
																								
													
													
													if($jourArray[$e] == $jourMois[$moisActuel])
													{
														$e = 420;
													}
													
													$e++ ;
												}
												elseif(isset($jourArray[$e]) AND $j == $joursemaineArray[0] AND isset($enchainement) AND $enchainement == false AND isset($joursemaineArray[$e]))
												{
													echo $jourArray[$e];
												
													?>
													<select name="<?php echo $moisActuel.'-'.$jourArray[$e]; ?>" id="<?php echo $moisActuel.'-'.$jourArray[$e]; ?>">
														<option value="j"> </option>
														<option value="c">c</option>
														<option value="e">e</option>
													</select>
													<?php
													
													$e++ ;
																									
													$enchainement = true;
												}
												
											
											?></td>
											
											<?php
												
											$j++ ;
										}
									
									?>
								</tr>
							<?php 
						$s++ ;
						} 
						?>
					</tbody>
				</table>
				
				<br /> <br />			
				<?php
				
				if($moisActuel == 12)
				{
					$moisActuel = 0;
				}
				
				$moisActuel++ ;
			}
			
			?>
				<input type="submit" value="Soumettre" id="connexion" />
				</p>
				</form>
			<?php
			
			
			
		}
		elseif($etape == 4)
		{
			include('sqlconnect.php');
		
			// Increment du décallage des cycles (et non de la boucle elle-même)
			$i = 0;
			
			// Increment 
			$j = 0;
			
			// Boucle qui fetch tout le contenu actuel de cyclesannee et l'insert dans plusieurs arrays
			$reqSelectTout = $bdd->query('SELECT * FROM cyclesannee');
			while($arrayTable = $reqSelectTout->fetch())
			{	
				$cycle[$j] = $arrayTable['cycle'];
				$jourcycle[$j] = $arrayTable['jourcycle'];
			
				if($_POST[$arrayTable['mois'].'-'.$arrayTable['jour']] == 'c')
				{
					$reqUpdateStatut = $bdd->prepare('UPDATE cyclesannee SET cycle = :cycle, jourcycle = :jourcycle, statut = \'c\' WHERE id = :id');
					$reqUpdateStatut->execute(array(
													'cycle' => '0',
													'jourcycle' => '0',
													'id' => $arrayTable['id']
													)) or die(print_r($reqUpdateStatut->errorInfo()));
					$reqUpdateStatut->closeCursor();
					
					$i++ ;
				}
				elseif($_POST[$arrayTable['mois'].'-'.$arrayTable['jour']] == 'e')
				{
					$reqUpdateStatut = $bdd->prepare('UPDATE cyclesannee SET statut = \'e\' WHERE id = :id');
					$reqUpdateStatut->execute(array(
													'id' => $arrayTable['id']
													)) or die(print_r($reqUpdateStatut->errorInfo()));
					$reqUpdateStatut->closeCursor();
				}
				
				$cyclePrecedant = $cycle[$j - $i];
				$jourcyclePrecedant = $jourcycle[$j - $i];
			
				if($i != 0)
				{	
					
				
					$reqSelect = $bdd->prepare('SELECT * FROM cyclesannee WHERE id = :id');
					$reqSelect->execute(array(
											'id' => $arrayTable['id'] - $i
											));
					$fetchPrecedant = $reqSelect->fetch() or die(print_r($reqSelect->errorInfo()));
					$reqSelect->closeCursor();
					
					$reqUpdate = $bdd->prepare('UPDATE cyclesannee SET cycle = :cycle, jourcycle = :jourcycle WHERE id = :id');
					$reqUpdate->execute(array(
											'cycle' => $cyclePrecedant,
											'jourcycle' => $jourcyclePrecedant,
											'id' => $arrayTable['id']
											)) or die(print_r($reqUpdate->errorInfo()));
					$reqUpdate->closeCursor();			
											
					
					
				}
				
				
				
				$j++ ;
				
			}
			
		
			echo 'Le calendrier scolaire a &eacute;t&eacute; g&eacute;n&eacute;r&eacute; avec succ&egrave;s!';
			
			$reqUpdate = $bdd->prepare('UPDATE cyclesannee SET cycle = :cycle, jourcycle = :jourcycle WHERE statut = :statut');
			$reqUpdate->execute(array(
										'cycle' => 0,
										'jourcycle' => 0,
										'statut' => 'c'
										)) or die(print_r($reqUpdate->errorInfo()));
			$reqUpdate->closeCursor();
		}
		
		
	}
	
	function genererGrilleCycle($type, $action)
	{
		include('sqlconnect.php');
		
		if($type == 'prof')
		{
		
			// On fetch le numéro du dernier cycle

			$reqSelectDernierCycle = $bdd->query('SELECT cycle FROM cyclesannee ORDER BY cycle DESC') or die(print_r($bdd->errorInfo()));
			$fetchCycle = $reqSelectDernierCycle->fetch();
			
			$dernierCycle = $fetchCycle[0];
			
			$reqSelectDernierCycle->closeCursor();
			
			
			// Si l'utilisateur a sélectionné un cycle en particulier à afficher
			if(isset($_GET['cycle']) AND is_numeric($_GET['cycle']) AND $_GET['cycle'] <= $dernierCycle AND $_GET['cycle'] >= 1)
			{
				$cycle = $_GET['cycle'];
			}
			// Sinon, le cycle sera sélectionné en fonction de la date
			else
			{
			
				// On fetch le cycle actuel en fonction de la date
				
				$dateActuelle = getdate();
				
				$reqSelectCycleDateActuelle = $bdd->prepare('SELECT cycle FROM cyclesannee WHERE annee = :annee AND mois = :mois AND jour = :jour');
				$reqSelectCycleDateActuelle->execute(array(
														'annee' => $dateActuelle['year'],
														'mois' => $dateActuelle['mon'],
														'jour' => $dateActuelle['mday']
														)) or die(print_r($reqSelectCycleDateActuelle->errorInfo()));
														
				$fetchCycleDateActuelle = $reqSelectCycleDateActuelle->fetch();
				
				// Boucle qui modifie le jour actuel si le jour n'est pas dans la bdd
				
				$i = 0;
				$jourActuel = $dateActuelle['mday'];
				while($fetchCycleDateActuelle[0] == false AND $i < 20)
				{
					if($dateActuelle['mday'] > 27)
					{
						$jourActuel-- ;
					}
					else
					{
						$jourActuel++;
					}
					$i++ ;
					
					$reqSelectCycleDateActuelle = $bdd->prepare('SELECT cycle FROM cyclesannee WHERE annee = :annee AND mois = :mois AND jour = :jour');
					$reqSelectCycleDateActuelle->execute(array(
															'annee' => $dateActuelle['year'],
															'mois' => $dateActuelle['mon'],
															'jour' => $jourActuel
															)) or die(print_r($reqSelectCycleDateActuelle->errorInfo()));
															
					$fetchCycleDateActuelle = $reqSelectCycleDateActuelle->fetch();
				}
				
				if($i >= 20)
				{
					$cycle = 1;
				}
				else
				{
					$cycle = $fetchCycleDateActuelle[0];
				}
			}
			
			$reqSelectTableCycle = $bdd->prepare('SELECT * FROM cyclesannee WHERE cycle = :cycle');
			$reqSelectTableCycle->execute(array(
											'cycle' => $cycle
											)) or die(print_r($reqSelectTableCycle->errorInfo()));
			
			$mois[1] = 'janvier';
			$mois[2] = 'f&eacute;vrier';
			$mois[3] = 'mars';
			$mois[4] = 'avril';
			$mois[5] = 'mai';
			$mois[6] = 'juin';
			$mois[7] = 'juillet';
			$mois[8] = 'ao&ucirc;t';
			$mois[9] = 'septembre';
			$mois[10] = 'octobre';
			$mois[11] = 'novembre';
			$mois[12] = 'd&eacute;cembre';
			
			
			$reqSelect = $bdd->query('SELECT * FROM horaireprof');
			while($fetchHoraire = $reqSelect->fetch())
			{
				$c = $fetchHoraire['cycle'];
				$j = $fetchHoraire['jourcycle'];
				$p = $fetchHoraire['periode'];
				
				$prof[$c.'-'.$j.'-'.$p] = $fetchHoraire['prof'];
				$horaireTitre[$c.'-'.$j.'-'.$p] = $fetchHoraire['titre'];
				$horaireDescription[$c.'-'.$j.'-'.$p] = $fetchHoraire['description'];
				$horaireLien[$c.'-'.$j.'-'.$p] = $fetchHoraire['lien'];
			}
			
			$reqSelect->closeCursor();
			
			// Si le formulaire a été envoyé
			if(isset($_POST['cycle']))
			{
				// Si le professeur a coché deux cases pour un échange de période
				if(isset($_POST['echange1']) AND isset($_POST['echange2']) AND $_POST['echange2'] != 'annuler')
				{
					$cycle1 = substr($_POST['echange1'], 0, -4);
					$cycle2 = substr($_POST['echange2'], 0, -4);
				
					$jour1 = substr($_POST['echange1'], -3, 1);
					$jour2 = substr($_POST['echange2'], -3, 1);
				
					$periode1 = substr($_POST['echange1'], -1);
					$periode2 = substr($_POST['echange2'], -1);
				
					$reqSelectEchange1 = $bdd->query('SELECT echange1, echange2 FROM horaireprof WHERE cycle = '.$cycle1.' AND jourcycle = '.$jour1.' AND periode = '.$periode1.'') or die(print_r($bdd->errorInfo()));
					$moi = $reqSelectEchange1->fetch();
					$reqSelectEchange1->closeCursor();
					
					$reqSelectEchange2 = $bdd->query('SELECT echange1, echange2 FROM horaireprof WHERE cycle = '.$cycle2.' AND jourcycle = '.$jour2.' AND periode = '.$periode2.'') or die(print_r($bdd->errorInfo()));
					$toi = $reqSelectEchange1->fetch();
					$reqSelectEchange2->closeCursor();
					
								
					$e = false;
					if($periode1 == 5 AND $moi['echange1'] == false)
					{			
						//INSERT (avec période étude)
						$reqInsertEchange = $bdd->prepare('INSERT INTO horaireprof(echange1) VALUES(:echange1) WHERE jourcycle = :jourcycle AND cycle = :cycle AND periode = :periode') ;
						$reqInsertEchange->execute(array(
														'echange1' => $_POST['echange2'],
														'jourcycle' => $jour1,
														'cycle' => $cycle1,
														'periode' => $periode1
						)) or die(print_r($reqInsertEchange->errorInfo()));
					}
					elseif($periode1 == 3 AND $moi['echange1'] == false)
					{
						if($periode2 == 3)
						{
							//INSERT
						}
						else
						{
							//ERREUR (peux pas échanger une récup avec une période)
							$e = true;
						}
					}
					elseif($moi['echange1'] == false)
					{
						//INSERT
					}
					else
					{
						$e = true;
					}
					
					if($periode2 == 5 AND $moi['echange1'] == false AND $e == false)
					{
						//INSERT (avec période d'étude)
					}
					elseif($moi['echange1'] == false AND $e == false)
					{
						//INSERT
					}
					else
					{
						//MESSAGE D'ERREUR
					}
					
					
				}
			
				echo '<img src="images/chargement.gif" alt="Chargement..." id="chargement" style="position: fixed; left: 50%; top: 50%; border: 15px solid #ededed; border-radius: 10px; background-color: #ededed;"/>';
				
				$c = htmlspecialchars($_POST['cycle']);
				
				$search[0] = "\'";
				$search[1] = '\"';
				
				$replace[0] = "'";
				$replace[1] = '"';
				
				
				$j = 1;
				while($j <= 9)
				{
					$reqSelectStatut = $bdd->prepare('SELECT statut FROM cyclesannee WHERE cycle = :cycle AND jourcycle = :jourcycle');
					$reqSelectStatut->execute(array(
												'cycle' => $c,
												'jourcycle' => $j,
					)) or die(print_r($reqSelectStatut->errorInfo()));
					$fetch = $reqSelectStatut->fetch();
					$reqSelectStatut->closeCursor();
					$statut = $fetch['statut'];
				
					if(isset($_POST['conge_'.$c.'-'.$j]) AND $_POST['conge_'.$c.'-'.$j] == 'on')
					{
						if($statut != 'e' AND $statut != 'c')
						{
							$reqUpdateStatut = $bdd->prepare('UPDATE cyclesannee SET statut = :statut WHERE cycle = :cycle AND jourcycle = :jourcycle');
							$reqUpdateStatut->execute(array(
														// SET
														'statut' => 'e',
														
														// WHERE
														'cycle' => $c,
														'jourcycle' => $j
							)) or die(print_r($reqUpdateStatut->errorInfo()));
							$reqUpdateStatut->closeCursor();
						}
						
						
					}
					else
					{
						if($statut != '' AND $statut != 'c')
						{
							$reqUpdateStatut = $bdd->prepare('UPDATE cyclesannee SET statut = :statut WHERE cycle = :cycle AND jourcycle = :jourcycle');
							$reqUpdateStatut->execute(array(
														// SET
														'statut' => '',
														
														// WHERE
														'cycle' => $c,
														'jourcycle' => $j
							)) or die(print_r($reqUpdateStatut->errorInfo()));
							$reqUpdateStatut->closeCursor();
						}
					}
				
					$p = 1;
					while($p <= 6)
					{
						if(isset($_POST['lien_'.$j.'-'.$p]) AND $_POST['lien_'.$j.'-'.$p] != '' AND $_POST['lien_'.$j.'-'.$p] != ' ' AND $_POST['lien_'.$j.'-'.$p] != '#')
						{
							$string = $_POST['lien_'.$j.'-'.$p];
							$substring = 'http://';

							$pos = strpos(' '.$string, $substring);

							if($pos == 1)
							{
								$lien = $string;
							}
							elseif($pos == false)
							{
								$lien = $substring.$string;					
							}
							else
							{
								$lien = '';
							}
						}
						else
						{
							$lien = '';
						}	
						
						
						if(isset($_POST['description_'.$j.'-'.$p]))
						{
							if($p == 3)
							{
								$titre = 'R&eacute;cup&eacute;ration';
							}
							elseif($p == 6)
							{
								$titre = '&Eacute;tude';
							}
							else
							{
								$titre = '';
							}
						
						
							if(isset($prof[$c.'-'.$j.'-'.$p]) AND $prof[$c.'-'.$j.'-'.$p] == $_SESSION['utilisateur'] AND $_POST['description_'.$j.'-'.$p] != '')
							{
								if($p == 3)
								{
									$groupe = 31;
									while($groupe <= 40)
									{
										$reqUpdate = $bdd->prepare('UPDATE horaireprof SET titre = :titre, description = :description, lien = :lien, prof = :prof WHERE niveau = :niveau AND groupe = :groupe AND cycle = :cycle AND jourcycle = :jourcycle AND periode = :periode');
										$reqUpdate->execute(array(
																// SET
																'titre' => str_replace($search, $replace, $titre),
																'description' => str_replace($search, $replace, $_POST['description_'.$j.'-'.$p]),
																'lien' => htmlspecialchars($lien),
																'prof' => $_SESSION['utilisateur'],
																
																// WHERE
																'niveau' => $_POST['niveau_'.$j.'-'.$p],
																'groupe' => $groupe,
																'cycle' => $c,
																'jourcycle' => $j,
																'periode' => $p
										)) or die(print_r($reqUpdate->errorInfo()));
										
										$reqUpdate->closeCursor();
										
										$groupe++ ;
									}
								}
								else
								{
									$reqUpdate = $bdd->prepare('UPDATE horaireprof SET titre = :titre, description = :description, lien = :lien, prof = :prof WHERE niveau = :niveau AND groupe = :groupe AND cycle = :cycle AND jourcycle = :jourcycle AND periode = :periode');
									$reqUpdate->execute(array(
															// SET
															'titre' => str_replace($search, $replace, $titre),
															'description' => str_replace($search, $replace, $_POST['description_'.$j.'-'.$p]),
															'lien' => $lien,
															'prof' => $_SESSION['utilisateur'],
															
															// WHERE
															'niveau' => $_POST['niveau_'.$j.'-'.$p],
															'groupe' => $_POST['groupe_'.$j.'-'.$p],
															'cycle' => $c,
															'jourcycle' => $j,
															'periode' => $p
									)) or die(print_r($reqUpdate->errorInfo()));
									
									$reqUpdate->closeCursor();

								}
							
							}
							elseif($_POST['description_'.$j.'-'.$p] == '' AND $_POST['lien_'.$j.'-'.$p] == '')
							{
								if($p == 3)
								{
									$groupe = 31;
									while($groupe <= 40)
									{
										$reqDelete = $bdd->prepare('DELETE FROM horaireprof WHERE niveau = :niveau AND groupe = :groupe AND jourcycle = :jourcycle AND cycle = :cycle AND periode = :periode');
										$reqDelete->execute(array(
																// WHERE
																'niveau' => $_POST['niveau_'.$j.'-'.$p],
																'groupe' => $groupe,
																'cycle' => $c,
																'jourcycle' => $j,
																'periode' => $p
										)) or die(print_r($reqDelete->errorInfo()));
										
										$reqDelete->closeCursor();
										
										$groupe++ ;
									}
								}
								else
								{
									$reqDelete = $bdd->prepare('DELETE FROM horaireprof WHERE niveau = :niveau AND groupe = :groupe AND jourcycle = :jourcycle AND cycle = :cycle AND periode = :periode');
									$reqDelete->execute(array(
															// WHERE
															'niveau' => $_POST['niveau_'.$j.'-'.$p],
															'groupe' => $_POST['groupe_'.$j.'-'.$p],
															'cycle' => $c,
															'jourcycle' => $j,
															'periode' => $p
									)) or die(print_r($reqDelete->errorInfo()));
									
									$reqDelete->closeCursor();
								}
								
							}
							else
							{
								
								// INSERT
								if($p == 3)
								{
									$groupe = 31;
									while($groupe <= 40)
									{
										$reqInsert = $bdd->prepare('INSERT INTO horaireprof(titre, description, lien, prof, niveau, groupe, cycle, jourcycle, periode) VALUES(:titre, :description, :lien, :prof, :niveau, :groupe, :cycle, :jourcycle, :periode)');
										$reqInsert->execute(array(
															// SET
															'titre' => str_replace($search, $replace, $titre),
															'description' => str_replace($search, $replace, $_POST['description_'.$j.'-'.$p]),
															'lien' => htmlspecialchars($lien),
															'prof' => $_SESSION['utilisateur'],
															'niveau' => $_POST['niveau_'.$j.'-'.$p],
															'groupe' => $groupe,
															'cycle' => $c,
															'jourcycle' => $j,
															'periode' => $p
										)) or die(print_r($reqInsert->errorInfo())); 
										
										$reqInsert->closeCursor();
										
										$groupe++ ;
									}
								}
								else
								{
									$reqInsert = $bdd->prepare('INSERT INTO horaireprof(titre, description, lien, prof, niveau, groupe, cycle, jourcycle, periode) VALUES(:titre, :description, :lien, :prof, :niveau, :groupe, :cycle, :jourcycle, :periode)');
									$reqInsert->execute(array(
														// SET
														'titre' => str_replace($search, $replace, $titre),
														'description' => str_replace($search, $replace, $_POST['description_'.$j.'-'.$p]),
														'lien' => htmlspecialchars($lien),
														'prof' => $_SESSION['utilisateur'],
														'niveau' => $_POST['niveau_'.$j.'-'.$p],
														'groupe' => $_POST['groupe_'.$j.'-'.$p],
														'cycle' => $c,
														'jourcycle' => $j,
														'periode' => $p
									)) or die(print_r($reqInsert->errorInfo())); 
									
									$reqInsert->closeCursor();
								}
							}
						}
						
						
						$p++ ;
					}
					
					$j++ ;
				}
				echo "<meta http-equiv='Refresh' content='0; URL=horaire.php?cycle=".$_POST['cycle']."'>";
				
			}
			else
			{
			
				?>
			
				<form method="post" action="horaire.php">
				
				<p>				
				
				<input type="text" name="cycle" id="formset" value="<?php echo $cycle; ?>" hidden readonly />
				
				<ul id="nav-cycles">
					<li><a href="#"><h2>Cycle <?php echo $cycle; ?></h2></a>
						<ul><?php
							// Navigation des cycles
				
							$cycleActuel = 1;
							while($cycleActuel <= $dernierCycle)
							{
								?>
								
									<li><a href="<?php echo $action; ?>?cycle=<?php echo $cycleActuel; ?>">Cycle <?php echo $cycleActuel; ?></a></li>
								
								<?php
								
								$cycleActuel++ ;
							}
						?></ul>
					</li>
				</ul>
				<table>
					<thead>
						<tr>
							<th class="topleft">Date</th>
							<th class="topleft">Jour</th>
							<th>P&eacute;riode 1</th>
							<th>P&eacute;riode 2</th>
							<th>Midi</th>
							<th>P&eacute;riode 3</th>
							<th>P&eacute;riode 4</th>
							<th>&Eacute;tude</th>
							<th class="topleft">Cong&eacute</th>
						</tr>
					</thead>
					
					<tbody>
				
				<?php
				
				$reqSelectHoraireDefault = $bdd->query('SELECT * FROM horairedefault') or die(print_r($bdd->errorInfo()));
				
				$optArray = array('opt1', 'opt2', 'opt3', 'opt4', 'opt5', 'opt6', 'opt7', 'opt8', 'opt9', 'opt10');
				
				while($fetchHoraireDefault = $reqSelectHoraireDefault->fetch())
				{
					$j = $fetchHoraireDefault['jour'];
					$p = $fetchHoraireDefault['periode'];
					
					$niveau = $fetchHoraireDefault['niveau'];
					$groupe = $fetchHoraireDefault['groupe'];
					
					if($_SESSION['utilisateur'] == $fetchHoraireDefault['prof'])
					{
						$profOccupe[$j.'-'.$p] = true;
						$cetteCase[$j.'-'.$p] = 'pr'.$niveau.', gr'.$groupe;
						$cetteCaseNiveau[$j.'-'.$p] = 'pr'.$niveau;
						
						$ceNiveau[$j.'-'.$p] = $niveau;
						$ceGroupe[$j.'-'.$p] = $groupe;
					}
					elseif($fetchHoraireDefault['prof'] == $optArray[0] OR $fetchHoraireDefault['prof'] == $optArray[1] OR $fetchHoraireDefault['prof'] == $optArray[2] OR $fetchHoraireDefault['prof'] == $optArray[3] OR $fetchHoraireDefault['prof'] == $optArray[4] OR $fetchHoraireDefault['prof'] == $optArray[5] OR $fetchHoraireDefault['prof'] == $optArray[6] OR $fetchHoraireDefault['prof'] == $optArray[7] OR $fetchHoraireDefault['prof'] == $optArray[8] OR $fetchHoraireDefault['prof'] == $optArray[9])
					{
						$groupeOption = filter_var($fetchHoraireDefault['prof'], FILTER_SANITIZE_NUMBER_INT);
					
						$reqSelectProfOpt = $bdd->prepare('SELECT * FROM options WHERE groupe_option = :groupe_option');
						$reqSelectProfOpt->execute(array(
														'groupe_option' => $groupeOption
														)) or die(print_r($reqSelectProfOpt->errorInfo()));
						while($fetch = $reqSelectProfOpt->fetch())
						{
							if($_SESSION['utilisateur'] == $fetch['prof'])
							{
								$profOccupe[$j.'-'.$p] = true;
								$cetteCase[$j.'-'.$p] = 'pr'.$niveau.', gr'.$groupe;
								$cetteCaseNiveau[$j.'-'.$p] = 'pr'.$niveau;
								
								$matiere[$j.'-'.$p] = $fetch['option'];
								$ceNiveau[$j.'-'.$p] = $niveau;
								// on ne set pas le groupe
							}
						}
						$reqSelectProfOpt->closeCursor();
					}
				}
				
				
				$reqSelectHoraireDefault->closeCursor();
				
				$j = 1;
				while($tableCycle = $reqSelectTableCycle->fetch())
				{
					if($tableCycle['statut'] == 'c')
					{
					
					}
					elseif($tableCycle['statut'] == 'e')
					{
					
					?>
					
						<tr>
							<td class="left"><?php echo $tableCycle['jour'].'<br />'.$mois[$tableCycle['mois']]; ?></td>
							<td class="jour"><?php echo $tableCycle['jourcycle']; ?></td>
							<td>Cong&eacute;</td>
							<td>Cong&eacute;</td>
							<td>Cong&eacute;</td>
							<td>Cong&eacute;</td>
							<td>Cong&eacute;</td>
							<td>Cong&eacute;</td>
							<td class="conge"><input type="checkbox" name="<?php echo 'conge_'.$cycle.'-'.$j; ?>" id="<?php echo 'conge_'.$cycle.'-'.$j; ?>" checked /></td>
						</tr>
					
					<?php
					
					$j++ ;
					
					}
					else
					{
				
					?>
					
						<tr>
							<td class="left"><?php echo $tableCycle['jour'].'<br />'.$mois[$tableCycle['mois']]; ?></td>
							<td class="jour"><?php echo $tableCycle['jourcycle']; ?></td>
							
							
							<?php
							
							$p = 1;
							while($p <= 6)
							{
							
								if(isset($profOccupe[$j.'-'.$p]) AND $profOccupe[$j.'-'.$p] == true)
								{
									$search[0] = "\'";
									$search[1] = '\"';
									
									$replace[0] = "'";
									$replace[1] = '"';
								
									$formSet = true;
									?>
										<td>
											<?php if($p != 3 AND isset($ceGroupe[$j.'-'.$p])){ echo $cetteCase[$j.'-'.$p]; } elseif($p == 3){ echo $cetteCaseNiveau[$j.'-'.$p].', r&eacute;cup.'; } else{ echo $cetteCaseNiveau[$j.'-'.$p].', '.$matiere[$j.'-'.$p]; } ?>
											<br />
											<?php if($p == 6 AND isset($ceGroupe[$j.'-'.$p]))
												{ ?>
													<input type="text" name="niveau_<?php echo $j.'-'.$p; ?>" id="niveau_<?php echo $j.'-'.$p; ?>" value="<?php echo $ceNiveau[$j.'-'.$p]; ?>" hidden readonly />
													<input type="text" name="groupe_<?php echo $j.'-'.$p; ?>" id="groupe_<?php echo $j.'-'.$p; ?>" value="<?php echo $ceGroupe[$j.'-'.$p]; ?>" hidden readonly />
												
													<textarea id="description_<?php echo $j.'-'.$p; ?>" name="description_<?php echo $j.'-'.$p; ?>" placeholder="Description"><?php if(isset($prof[$cycle.'-'.$j.'-'.$p]) AND $prof[$cycle.'-'.$j.'-'.$p] == $_SESSION['utilisateur'] AND isset($horaireDescription[$cycle.'-'.$j.'-'.$p])){ echo str_replace($search, $replace, $horaireDescription[$cycle.'-'.$j.'-'.$p]); } else { echo ''; } ?></textarea><br />
													<input type="text" name="lien_<?php echo $j.'-'.$p; ?>" id="lien_<?php echo $j.'-'.$p; ?>" placeholder="Lien" value="<?php if(isset($prof[$cycle.'-'.$j.'-'.$p]) AND $prof[$cycle.'-'.$j.'-'.$p] == $_SESSION['utilisateur'] AND isset($horaireLien[$cycle.'-'.$j.'-'.$p])){ echo $horaireLien[$cycle.'-'.$j.'-'.$p]; } else { echo ''; } ?>" />
												<?php }
												elseif(isset($ceGroupe[$j.'-'.$p]))
												{ ?>
													<input type="text" name="niveau_<?php echo $j.'-'.$p; ?>" id="niveau_<?php echo $j.'-'.$p; ?>" value="<?php echo $ceNiveau[$j.'-'.$p]; ?>" hidden readonly />
													<input type="text" name="groupe_<?php echo $j.'-'.$p; ?>" id="groupe_<?php echo $j.'-'.$p; ?>" value="<?php echo $ceGroupe[$j.'-'.$p]; ?>" hidden readonly />
												
													<textarea id="description_<?php echo $j.'-'.$p; ?>" name="description_<?php echo $j.'-'.$p; ?>" placeholder="Description"><?php if(isset($prof[$cycle.'-'.$j.'-'.$p]) AND $prof[$cycle.'-'.$j.'-'.$p] == $_SESSION['utilisateur'] AND isset($horaireDescription[$cycle.'-'.$j.'-'.$p])){ echo str_replace($search, $replace, $horaireDescription[$cycle.'-'.$j.'-'.$p]); } else { echo ''; } ?></textarea><br />
													<input type="text" name="lien_<?php echo $j.'-'.$p; ?>" id="lien_<?php echo $j.'-'.$p; ?>" placeholder="Lien" value="<?php if(isset($prof[$cycle.'-'.$j.'-'.$p]) AND $prof[$cycle.'-'.$j.'-'.$p] == $_SESSION['utilisateur'] AND isset($horaireLien[$cycle.'-'.$j.'-'.$p])){ echo $horaireLien[$cycle.'-'.$j.'-'.$p]; } else { echo ''; } ?>" />
												<?php }
												else
												{ ?>
													<input type="text" name="niveau_<?php echo $j.'-'.$p; ?>" id="niveau_<?php echo $j.'-'.$p; ?>" value="<?php echo $ceNiveau[$j.'-'.$p]; ?>" hidden readonly />
													<input type="text" name="groupe_<?php echo $j.'-'.$p; ?>" id="groupe_<?php echo $j.'-'.$p; ?>" value="<?php echo '0'; ?>" hidden readonly />
												
													<textarea id="description_<?php echo $j.'-'.$p; ?>" name="description_<?php echo $j.'-'.$p; ?>" placeholder="Description"><?php if(isset($prof[$cycle.'-'.$j.'-'.$p]) AND $prof[$cycle.'-'.$j.'-'.$p] == $_SESSION['utilisateur'] AND isset($horaireDescription[$cycle.'-'.$j.'-'.$p])){ echo str_replace($search, $replace, $horaireDescription[$cycle.'-'.$j.'-'.$p]); } else { echo ''; } ?></textarea><br />
													<input type="text" name="lien_<?php echo $j.'-'.$p; ?>" id="lien_<?php echo $j.'-'.$p; ?>" placeholder="Lien" value="<?php if(isset($prof[$cycle.'-'.$j.'-'.$p]) AND $prof[$cycle.'-'.$j.'-'.$p] == $_SESSION['utilisateur'] AND isset($horaireLien[$cycle.'-'.$j.'-'.$p])){ echo $horaireLien[$cycle.'-'.$j.'-'.$p]; } else { echo ''; } ?>" />
												<?php }
											?>
										</td>
									<?php
								}
								else
								{	?>
									
									<td></td>
									
								<?php }
							
							$p++ ;
							}
							
							
							
							
							?>
							
							<td class="conge"><input type="checkbox" name="<?php echo 'conge_'.$cycle.'-'.$j; ?>" id="<?php echo 'conge_'.$cycle.'-'.$j; ?>" /></td>
						</tr>
					
					<?php
					
					$j++ ;
					
					}
				}
				?>
					</tbody>
				</table>
				</p>
				
						
						<input type="submit" value="Soumettre" id="connexion" />
				<?php
			
			}
			
			?>
			</form>


		</div>
		<?php
		
		}
		elseif($type == 'eleve')
		{
		
			// On fetch le numéro du dernier cycle

			$reqSelectDernierCycle = $bdd->query('SELECT cycle FROM cyclesannee ORDER BY cycle DESC') or die(print_r($bdd->errorInfo()));
			$fetchCycle = $reqSelectDernierCycle->fetch();
			
			$dernierCycle = $fetchCycle[0];
			
			$reqSelectDernierCycle->closeCursor();
			
			
			// Si l'utilisateur a sélectionner un cycle en particulier à afficher
			if(isset($_GET['cycle']) AND is_numeric($_GET['cycle']) AND $_GET['cycle'] <= $dernierCycle AND $_GET['cycle'] >= 1)
			{
				
				$cycle = $_GET['cycle'];
				$dateActuelle = getDate();
			}
			// Sinon, le cycle sera sélectionné en fonction de la date
			else
			{
			
				// On fetch le cycle actuel en fonction de la date
				
				$dateActuelle = getdate();
				
				$reqSelectCycleDateActuelle = $bdd->prepare('SELECT cycle FROM cyclesannee WHERE annee = :annee AND mois = :mois AND jour = :jour');
				$reqSelectCycleDateActuelle->execute(array(
														'annee' => $dateActuelle['year'],
														'mois' => $dateActuelle['mon'],
														'jour' => $dateActuelle['mday']
														)) or die(print_r($reqSelectCycleDateActuelle->errorInfo()));
														
				$fetchCycleDateActuelle = $reqSelectCycleDateActuelle->fetch();
				
				// Boucle qui modifie le jour actuel si le jour n'est pas dans la bdd
				
				$i = 0;
				$jourActuel = $dateActuelle['mday'];
				while($fetchCycleDateActuelle[0] == false AND $i < 20)
				{
					if($dateActuelle['mday'] > 27)
					{
						$jourActuel-- ;
					}
					else
					{
						$jourActuel++;
					}
					$i++ ;
					
					$reqSelectCycleDateActuelle = $bdd->prepare('SELECT cycle FROM cyclesannee WHERE annee = :annee AND mois = :mois AND jour = :jour');
					$reqSelectCycleDateActuelle->execute(array(
															'annee' => $dateActuelle['year'],
															'mois' => $dateActuelle['mon'],
															'jour' => $jourActuel
															)) or die(print_r($reqSelectCycleDateActuelle->errorInfo()));
															
					$fetchCycleDateActuelle = $reqSelectCycleDateActuelle->fetch();
				}
				
				if($i >= 20)
				{
					$cycle = 1;
				}
				else
				{
					$cycle = $fetchCycleDateActuelle[0];
				}
			}
			
			$reqSelectTableCycle = $bdd->prepare('SELECT * FROM cyclesannee WHERE cycle = :cycle');
			$reqSelectTableCycle->execute(array(
											'cycle' => $cycle
											)) or die(print_r($reqSelectTableCycle->errorInfo()));
			
			$mois[1] = 'janvier';
			$mois[2] = 'f&eacute;vrier';
			$mois[3] = 'mars';
			$mois[4] = 'avril';
			$mois[5] = 'mai';
			$mois[6] = 'juin';
			$mois[7] = 'juillet';
			$mois[8] = 'ao&ucirc;t';
			$mois[9] = 'septembre';
			$mois[10] = 'octobre';
			$mois[11] = 'novembre';
			$mois[12] = 'd&eacute;cembre';
			
			if(isset($_COOKIE['codefiche']))/*si le cookie codefiche existe donne au titre la valeur de son niveau*/
				{
					$requete = $bdd->query('SELECT * FROM utilisateurs_eleves WHERE codefiche ='.$_COOKIE['codefiche']) or die(print_r($bdd->errorInfo()));
					$info_user = $requete->fetch();
					
					$requete->closeCursor();
				}
			else /*sinon donne lui la valeur nul*/
				{
					$info_user['niveau'] = 0;
					$info_user['groupe'] = 0;
				}
			
			$reqSelect = $bdd->prepare('SELECT * FROM horaireprof WHERE niveau = :niveau AND groupe = :groupe AND cycle = :cycle');
			$reqSelect->execute(array(
									'niveau' => $info_user['niveau'],
									'groupe' => $info_user['groupe'],
									'cycle' => $cycle
									)) or die(print_r($reqSelect->errorInfo()));
									
			while($fetch = $reqSelect->fetch())
			{
				$j = $fetch['jourcycle'];
				$p = $fetch['periode'];
				
				$titre[$j.'-'.$p] = $fetch['titre'];
				$description[$j.'-'.$p] = $fetch['description'];
				$lien[$j.'-'.$p] = $fetch['lien'];
				
				$prof[$j.'-'.$p] = $fetch['prof'];
				
			}
									
			$reqSelect->closeCursor();
			
			?>
			
			<p>
			<ul id="nav-cycles">
				<li><a href="#"><h2>Cycle <?php echo $cycle; ?></h2></a>
					<ul><?php
						// Navigation des cycles
			
						$cycleActuel = 1;
						while($cycleActuel <= $dernierCycle)
						{
							?>
							
								<li><a href="<?php echo $action; ?>?cycle=<?php echo $cycleActuel; ?>">Cycle <?php echo $cycleActuel; ?></a></li>
							
							<?php
							
							$cycleActuel++ ;
						}
					?></ul>
				</li>
			</ul>
			<table id="horaire">
				<thead>
					<tr>
						<th class="topleft">Date</th>
						<th class="topleft">Jour</th>
						<th>P&eacute;riode 1</th>
						<th>P&eacute;riode 2</th>
						<th>Midi</th>
						<th>P&eacute;riode 3</th>
						<th>P&eacute;riode 4</th>
						<th>&Eacute;tude</th>
					</tr>
				</thead>
				
				<tbody>
			
			<?php
			
			$j = 1;
			while($tableCycle = $reqSelectTableCycle->fetch())
			{
				if($tableCycle['statut'] == 'c')
				{
				
				}
				elseif($tableCycle['statut'] == 'e')
				{
				
				?>
				
					<tr <?php if($tableCycle['jour'] == $dateActuelle['mday']) { ?> id="actuel" <?php } ?> >
						<td class="left"><?php echo $tableCycle['jour'].'<br />'.$mois[$tableCycle['mois']]; ?></td>
						<td class="jour"><?php echo $tableCycle['jourcycle']; ?></td>
						<td>Cong&eacute;</td>
						<td>Cong&eacute;</td>
						<td>Cong&eacute;</td>
						<td>Cong&eacute;</td>
						<td>Cong&eacute;</td>
						<td>Cong&eacute;</td>
					</tr>
				
				<?php
				
				$j++ ;
				
				}
				elseif($tableCycle['statut'] == 't')
				{
				
				?>
				
					<tr <?php if($tableCycle['jour'] == $dateActuelle['mday']) { ?> id="actuel" <?php } ?> >
						<td class="left"><?php echo $tableCycle['jour'].'<br />'.$mois[$tableCycle['mois']]; ?></td>
						<td class="jour"><?php echo $tableCycle['jourcycle']; ?></td>
						<td>Temp&ecirc;te</td>
						<td>Temp&ecirc;te</td>
						<td>Temp&ecirc;te</td>
						<td>Temp&ecirc;te</td>
						<td>Temp&ecirc;te</td>
						<td>Temp&ecirc;te</td>
					</tr>
				
				<?php
				
				$j++ ;
				
				}
				else
				{
			
				?>
				
					<tr <?php if($tableCycle['jour'] == $dateActuelle['mday']) { ?> id="actuel" <?php } ?> >
						<td class="left"><?php echo $tableCycle['jour'].'<br />'.$mois[$tableCycle['mois']]; ?></td>
						<td class="jour"><?php echo $tableCycle['jourcycle']; ?></td>
						<?php
							$p = 1;
							while($p <= 6)
							{
								
								
									if(isset($prof[$j.'-'.$p]) AND strlen($prof[$j.'-'.$p]) > 1)
									{
										$prof = $prof[$j.'-'.$p];
									}
									else
									{
										
										$reqSelectOption = $bdd->prepare('SELECT prof FROM horairedefault WHERE niveau = :niveau AND groupe = :groupe AND jour = :jour AND periode = :periode');
										$reqSelectOption->execute(array(
																		'niveau' => $info_user['niveau'],
																		'groupe' => $info_user['groupe'],
																		'jour' => $j,
																		'periode' => $p
																		)) or die(print_r($reqSelectOption->errorInfo()));
										$fetch = $reqSelectOption->fetch();
										
										$optArray = array('opt1', 'opt2', 'opt3', 'opt4', 'opt5', 'opt6', 'opt7', 'opt8', 'opt9', 'opt10');
										
										if($fetch['prof'] == $optArray[0] OR $fetch['prof'] == $optArray[1] OR $fetch['prof'] == $optArray[2] OR $fetch['prof'] == $optArray[3] OR $fetch['prof'] == $optArray[4] OR $fetch['prof'] == $optArray[5] OR $fetch['prof'] == $optArray[6] OR $fetch['prof'] == $optArray[7] OR $fetch['prof'] == $optArray[8] OR $fetch['prof'] == $optArray[9])
										{											
											$groupe_option = filter_var($fetch['prof'], FILTER_SANITIZE_NUMBER_INT);
											
											$reqSelectOption = $bdd->query('SELECT option'.$groupe_option.' FROM utilisateurs_eleves WHERE codefiche = '.$_COOKIE['codefiche'].'') or die(print_r($bdd->errorInfo()));
											$fetch = $reqSelectOption->fetch();
											
											$profOption = $fetch['option'.$groupe_option];
											
											$reqSelect = $bdd->prepare('SELECT * FROM horaireprof WHERE prof = :prof AND niveau = :niveau AND groupe = :groupe AND cycle = :cycle AND jourcycle = :jourcycle AND periode = :periode');
											$reqSelect->execute(array(
																	'prof' => $profOption,
																	'niveau' => $info_user['niveau'],
																	'groupe' => 0,
																	'cycle' => $cycle,
																	'jourcycle' => $j,
																	'periode' => $p
																	)) or die(print_r($reqSelect->errorInfo()));
																	
												$fetch = $reqSelect->fetch();
												
												$titre[$j.'-'.$p] = $fetch['titre'];
												$description[$j.'-'.$p] = $fetch['description'];
												$lien[$j.'-'.$p] = $fetch['lien'];
												
												
											$prof = $profOption;
											
										}
										
										else
										{										
											$reqSelectDefault = $bdd->prepare('SELECT prof FROM horairedefault WHERE niveau = :niveau AND groupe = :groupe AND jour = :jour AND periode = :periode');
											$reqSelectDefault->execute(array(
																			'niveau' => $info_user['niveau'],
																			'groupe' => $info_user['groupe'],
																			'jour' => $j,
																			'periode' => $p
																			)) or die(print_r($reqSelectDefault->errorInfo()));
											$fetch = $reqSelectDefault->fetch();
											
											$prof = $fetch['prof'];
											
											$reqSelectDefault->closeCursor();
										}
										$reqSelectOption->closeCursor();
									}
									
									$reqSelectCouleur = $bdd->prepare('SELECT couleur FROM utilisateurs WHERE utilisateur = :utilisateur');
									$reqSelectCouleur->execute(array(
																	'utilisateur' => $prof
																	)) or die(print_r($reqSelectCouleur->errorInfo()));
																	
									$fetch = $reqSelectCouleur->fetch();
									
									if($fetch['couleur'] != '')
									{
										$couleur = $fetch['couleur'];
									}
									else
									{							
										$couleur = '#f2f2f2';
									}
									
									$reqSelectCouleur->closeCursor();
								
								if(isset($titre[$j.'-'.$p]))
								{											
									echo '<td style="background-color: '.$couleur.';'; if(isset($lien[$j.'-'.$p]) AND $lien[$j.'-'.$p] != ''){ echo ' box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.5); padding-top: 0px; margin-top: 0px;'; } echo '">';
									if(isset($lien[$j.'-'.$p]) AND $lien[$j.'-'.$p] != ''){ echo '<a style="color: #000000; text-decoration: none; display: inline-block; width: 100%; height: 100%;" href="'.$lien[$j.'-'.$p].'" target="_blank" >'; }
									echo '<class id="titre" >'.$titre[$j.'-'.$p].'</class><br />';
									echo '<class id="description" >'.$description[$j.'-'.$p].'</class><br />';
									if(isset($lien[$j.'-'.$p]) AND $lien[$j.'-'.$p] != ''){ echo '</a>'; }
									echo '</td>';
								}
								else
								{
									$reqSelectNom = $bdd->prepare('SELECT prenom, nom FROM utilisateurs WHERE utilisateur = :utilisateur');
									$reqSelectNom->execute(array(
																'utilisateur' => $prof
																)) or die(print_r($reqSelectNom->errorInfo())); 
									$fetch = $reqSelectNom->fetch();
									
									$prenom = $fetch['prenom'];
									$nom = $fetch['nom'];
									
									$reqSelectNom->closeCursor();
									
									if($p != 3 AND $p != 6)
									{
										echo '<td style="background-color: '.$couleur.';" ><class id="titre" >'.$prenom.' '.$nom.'</class></td>';
									}
									elseif($p == 6)
									{
										echo '<td style="background-color: '.$couleur.';" ><class id="titre" >&Eacute;tude</class><br /><class id="description"></class></td>';
									}
									else
									{
										if(isset($prenom) AND isset($nom) AND $prenom != '' AND $nom != '')
										{
											echo '<td style="background-color: '.$couleur.';" ><class id="titre" >R&eacute;cup&eacute;ration<br /><br />'.$prenom.' '.$nom.'</class></td>';
										}
										else
										{
											echo '<td></td>';
										}
									}
								}
								
								$p++ ;
							}
								
						?>
					</tr>
				
				<?php
				
				$j++ ;
				
				}
			}
			?>
				</tbody>
			</table>
			</p>


		</div>
		<?php
		
		}
	}
	
	function boutonTempete($action)
	{
		include('sqlconnect.php');
		
		
		// Pour que le getDate() marche, il faut modifier php.ini du serveur et remplacer date.timezone = UTC par date.timezone = America/Montreal
		$dateActuelle = getDate();
			
		$anneeActuelle = $dateActuelle['year'];
		$moisActuel = $dateActuelle['mon'];
		$jourActuel = $dateActuelle['mday'];	
		
		
		if(isset($_POST['statut']) AND $_POST['statut'] == '')
		{
			$reqUpdateStatut = $bdd->prepare('UPDATE cyclesannee SET statut = :statut WHERE jour = :jour AND mois = :mois AND annee = :annee');
			$reqUpdateStatut->execute(array(
											'statut' => 't',
											'jour' => $jourActuel,
											'mois' => $moisActuel,
											'annee' => $anneeActuelle
											)) or die(print_r($reqUpdateStatut->errorInfo()));
			$reqUpdateStatut->closeCursor();
		}
		elseif(isset($_POST['statut']) AND $_POST['statut'] == 't')
		{
			$reqUpdateStatut = $bdd->prepare('UPDATE cyclesannee SET statut = :statut WHERE jour = :jour AND mois = :mois AND annee = :annee');
			$reqUpdateStatut->execute(array(
											'statut' => '',
											'jour' => $jourActuel,
											'mois' => $moisActuel,
											'annee' => $anneeActuelle
											)) or die(print_r($reqUpdateStatut->errorInfo()));
			$reqUpdateStatut->closeCursor();
		}
		
		$reqSelectStatut = $bdd->prepare('SELECT statut FROM cyclesannee WHERE jour = :jour AND mois = :mois AND annee = :annee');
		$reqSelectStatut->execute(array(
										'jour' => $jourActuel,
										'mois' => $moisActuel,
										'annee' => $anneeActuelle
										)) or die(print_r($reqSelectStatut->errorInfo()));
										
		$fetch = $reqSelectStatut->fetch();
		
		$statut = $fetch['statut'];
		
		$reqSelectStatut->closeCursor();
		
		?>
			<form method="post" action="<?php echo $action; ?>">
				<input type="text" name="statut" value="<?php if($statut == 't'){ echo 't'; } else{ echo ''; }?>" readonly hidden />
				<input type="submit" value="<?php if($statut == 't'){ echo 'Annuler la temp&ecirc;te'; } else{ echo 'Signaler une temp&ecirc;te'; }?>" id="<?php if($statut == 't'){ echo 'deconnexion'; } else{ echo 'connexion'; }?>" />
			</form>
		<?php
		
		
		
		
	}
	
	function formulaireCouleur($action)
	{
		include('sqlconnect.php');
	
		$reqSelectCouleur = $bdd->query('SELECT * FROM couleurs') or die(print_r($bdd->errorInfo()));
		while($fetch = $reqSelectCouleur->fetch())
		{	
			$niveauActuel = $fetch['niveau'];
			$couleurPr[$niveauActuel] = $fetch['couleur'];
		}
		$reqSelectCouleur->closeCursor();
		
		$niv = 1;
		while($niv <= 5)
		{
			if(isset($couleurPr[$niv]) AND $couleurPr[$niv] == false)
			{				
				$couleurPr[$niv] = '#0087EF';
				$issetPr[$niv] = false;
			}
			elseif(isset($couleurPr[$niv]))
			{				
				$couleurPr[$niv] = $couleurPr[$niv];
				$issetPr[$niv] = true;
			}
			else
			{				
				$couleurPr[$niv] = '#0087EF';
				$issetPr[$niv] = false;
			}
			
			$niv++ ;
		}
		
		if(isset($_POST['pr1']) AND isset($_POST['pr2']) AND isset($_POST['pr3']) AND isset($_POST['pr4']) AND isset($_POST['pr5']))
		{
			$erreur = false;
			$niv = 1;
			while($niv <= 5)
			{
				if(strlen($_POST['pr'.$niv]) == 7 AND strpos('#', $_POST['pr'.$niv]) == 0 AND ctype_xdigit(substr($_POST['pr'.$niv], 1)) == true)
				{
					if(isset($issetPr[$niv]) AND $issetPr[$niv] == false)
					{
						$reqInsert = $bdd->prepare('INSERT INTO couleurs(niveau, couleur) VALUES(:niveau, :couleur)');
						$reqInsert->execute(array(
												'niveau' => $niv,
												'couleur' => $_POST['pr'.$niv]
												)) or die(print_r($reqInsert->errorInfo()));
						$reqInsert->closeCursor();
					}
					elseif(isset($issetPr[$niv]) AND $issetPr[$niv] == true)
					{
						$reqUpdate = $bdd->prepare('UPDATE couleurs SET niveau = :niveau, couleur = :couleur WHERE niveau = :niveau');
						$reqUpdate->execute(array(
												// SET
												'niveau' => $niv,
												'couleur' => $_POST['pr'.$niv],
												
												// WHERE
												'niveau' => $niv
												)) or die(print_r($reqUpdate->errorInfo()));
						$reqUpdate->closeCursor();
					}
				}
				else
				{
					$erreur = true;
				}
				
				$niv++ ;
			}
			
			$reqSelectCouleur = $bdd->query('SELECT * FROM couleurs') or die(print_r($bdd->errorInfo()));
			while($fetch = $reqSelectCouleur->fetch())
			{	
				$niveauActuel = $fetch['niveau'];
				$couleurPr[$niveauActuel] = $fetch['couleur'];
			}
			$reqSelectCouleur->closeCursor();
			
			$niv = 1;
			while($niv <= 5)
			{
				if(isset($couleurPr[$niv]) AND $couleurPr[$niv] == false)
				{					
					$couleurPr[$niv] = '#0087EF';
					$issetPr[$niv] = false;
				}
				elseif(isset($couleurPr[$niv]))
				{					
					$couleurPr[$niv] = $couleurPr[$niv];
					$issetPr[$niv] = true;
				}
				else
				{					
					$couleurPr[$niv] = '#0087EF';
					$issetPr[$niv] = false;
				}
				
				$niv++ ;
			}
			
			if($erreur == true)
			{
				echo '<p style="color: #FF0000;">Un ou plusieurs champs n\'ont pas &eacute;t&eacute; remplis correctement, veuillez recommencer</p>';
			}
			elseif($erreur == false)
			{
				echo '<p>Les couleurs ont &eacute;t&eacute;s mises &agrave; jour avec succ&egrave;s</p>';
			}
		}
		
		
		?>
			<p>Bleu de base: #0087EF
			<form method="post" action="<?php echo $action; ?>">
				<label for="pr1">Protic 1: </label><input type="color" required name="pr1" id="pr1" value="<?php echo $couleurPr[1]; ?>" maxlength="7" placeholder="#FFFFFF" /><a style="display:inline-block; height:25px; width:25px; color:#FFFFFF; margin:0px; padding:0px; text-align:center; background-color:<?php echo $couleurPr[1]; ?>;">a</a><br />
				<label for="pr2">Protic 2: </label><input type="color" required name="pr2" id="pr2" value="<?php echo $couleurPr[2]; ?>" maxlength="7" placeholder="#FFFFFF" /><a style="display:inline-block; height:25px; width:25px; color:#FFFFFF; margin:0px; padding:0px; text-align:center; background-color:<?php echo $couleurPr[2]; ?>;">a</a><br />
				<label for="pr3">Protic 3: </label><input type="color" required name="pr3" id="pr3" value="<?php echo $couleurPr[3]; ?>" maxlength="7" placeholder="#FFFFFF" /><a style="display:inline-block; height:25px; width:25px; color:#FFFFFF; margin:0px; padding:0px; text-align:center; background-color:<?php echo $couleurPr[3]; ?>;">a</a><br />
				<label for="pr4">Protic 4: </label><input type="color" required name="pr4" id="pr4" value="<?php echo $couleurPr[4]; ?>" maxlength="7" placeholder="#FFFFFF" /><a style="display:inline-block; height:25px; width:25px; color:#FFFFFF; margin:0px; padding:0px; text-align:center; background-color:<?php echo $couleurPr[4]; ?>;">a</a><br />
				<label for="pr5">Protic 5: </label><input type="color" required name="pr5" id="pr5" value="<?php echo $couleurPr[5]; ?>" maxlength="7" placeholder="#FFFFFF" /><a style="display:inline-block; height:25px; width:25px; color:#FFFFFF; margin:0px; padding:0px; text-align:center; background-color:<?php echo $couleurPr[5]; ?>;">a</a><br /><br />
			<input type="submit" value="Soumettre" id="connexion" />
			</form>
			</p>
		<?php
	}
	
?>
