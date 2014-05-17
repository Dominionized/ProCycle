<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php');
	include('includes/fonctions.php');
?>
<title>ProCycle - Courriel</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		


<!--  A METTRE AVANT APRES LES INCLUDES VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1 && isset($_SESSION['type']))
{ ?>
<!------------------------------------------------------->
<?php 
	$titre_page = 'Courriel';
	include('includes/title.php');
?>
<div id="contenu">
<?php 
	if(isset($_POST['niveau']) && isset($_POST['groupe']))
	{
		if($_POST['niveau']== 'tous' AND $_POST['groupe'] != 'tous')
			{ 
				$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves WHERE groupe ='.$_POST['groupe']) or die(print_r($bdd->errorInfo()));
				while($email = $reqSelectEmail->fetch()){
						$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
					}
					$reqSelectEmail->closeCursor();
			}
		elseif($_POST['niveau'] == 'tous' AND $_POST['groupe'] == 'tous')
			{
				$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves') or die(print_r($bdd->errorInfo()));
					$listeEmail = '';
					
					while($email = $reqSelectEmail->fetch()){
						$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
					}
					$reqSelectEmail->closeCursor();
			}
		elseif($_POST['groupe']== 'tous')
			{ 
				$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves WHERE niveau ='.$_POST['niveau']) or die(print_r($bdd->errorInfo()));
				while($email = $reqSelectEmail->fetch()){
						$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
					}
					$reqSelectEmail->closeCursor();
			}
		else
			{	
				$reqSelectEmail = $bdd->query('SELECT email FROM utilisateurs_eleves WHERE niveau ='.$_POST['niveau'].' AND groupe='.$_POST['groupe']) or die(print_r($bdd->errorInfo()));
				while($email = $reqSelectEmail->fetch()){
						$listeEmail = $listeEmail.''.$email['email'].';&nbsp;';
					}
					$reqSelectEmail->closeCursor();
			} 
		if($listeEmail == ''){ $listeEmail = 'email.php?e=1'; } else { $listeEmail = 'mailto:'.$listeEmail; } 
		echo "<meta http-equiv='Refresh'  content='0; URL=".$listeEmail."'>";
		?>
		<h2>Courriel</h2>
		<?php if(isset($_GET['e']) AND $_GET['e'] == 1){ echo '<div style="color:red;">Il n\'y a aucun email qui concorde avec votre s&eacute;lection</div>'; }?>
		<form method="post" action="email.php">
			<select name="niveau" id="niveau">
				<option value="1">Protic 1</option>
				<option value="2">Protic 2</option>
				<option value="3">Protic 3</option>
				<option value="4">Protic 4</option>
				<option value="5">Protic 5</option>
				<option value="tous">Tous</option>
			</select>
			<select name="groupe" id="groupe">
				<option value="31">31</option>
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="36">36</option>
				<option value="37">37</option>
				<option value="38">38</option>
				<option value="39">39</option>
				<option value="40">40</option>
				<option value="tous">Tous</option>
			</select>
		<input type="submit" value="Envoyer un courriel" id="connexion"/>
		</form>
		<?php
	
	 }
		else
		{ ?>
		<h2>Courriel</h2>
		<?php if(isset($_GET['e']) AND $_GET['e'] == 1){ echo '<div style="color:red;">Il n\'y a aucun email qui concorde avec votre s&eacute;lection</div>'; }?>
		<form method="post" action="email.php">
			<select name="niveau" id="niveau">
				<option value="1">Protic 1</option>
				<option value="2">Protic 2</option>
				<option value="3">Protic 3</option>
				<option value="4">Protic 4</option>
				<option value="5">Protic 5</option>
				<option value="tous">Tous</option>
			</select>
			<select name="groupe" id="groupe">
				<option value="31">31</option>
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="36">36</option>
				<option value="37">37</option>
				<option value="38">38</option>
				<option value="39">39</option>
				<option value="40">40</option>
				<option value="tous">Tous</option>
			</select>
		<input type="submit" value="Envoyer un courriel" id="connexion"/>
		</form>
		<?php } ?>
</div>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php } 
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
}
?>
<!------------------------------------------------------->
