
<header>
		<div id="title_block">
			<?php
				include('sqlconnect.php');
			
				$reqSelectNomProf = $bdd->prepare('SELECT prenom, nom FROM utilisateurs WHERE utilisateur = :utilisateur');
				$reqSelectNomProf->execute(array(
												'utilisateur' => $_SESSION['utilisateur']
												)) or die(print_r($reqSelectNomProf->errorInfo())); 
												
				$fetch = $reqSelectNomProf->fetch();
				$nomProf = $fetch['prenom'].' '.$fetch['nom'];
				
				$reqSelectNomProf->closeCursor();
			?>
			<h1 id="titre"><?php echo $nomProf.' - '; ?><?php if(isset($titre_page)){ echo $titre_page; } else{ echo 'Administration'; } ?></h1>
		</div>

		<div id="decobox">
		<a href="deconnexion.php" id="deconnexion">D&eacute;connexion</a>
		</div>
</header>