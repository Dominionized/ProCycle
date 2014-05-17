<div id="info_user">
			<?php /*ont affiche le titre de la page*/
			if(isset($_COOKIE['codefiche']))/*si le cookie codefiche existe donne au titre la valeur de son niveau*/
				{
					$requete = $bdd->query('SELECT * FROM utilisateurs_eleves WHERE codefiche ='.$_COOKIE['codefiche']);
					$info_user = $requete->fetch();
						
					echo '<h3>'. $info_user['prenom'] .' '. $info_user['nom'] .' / Protic '.$info_user['niveau'].' / Groupe '. $info_user['groupe'] .'</h3>';
				}
			else /*sinon donne lui la valeur nul*/
				{
					echo '<h3>Vous n\'&ecirc;tes pas inscrit ? inscrivez-vous !</h3>';
				}
			?>
			</div>