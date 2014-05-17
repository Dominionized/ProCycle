<?php 
	session_start();
	include('includes/sqlconnect.php');
	include('includes/navigation.php');
	include('includes/fonctions.php');
?>
<title>ProCycle - Aide</title>
<meta http-equiv="refresh" content="600; URL=connect.php?e=1"> 
<link rel="stylesheet" type="text/css" href="style.css"/>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />		


<!--  A METTRE AVANT APRES LES INCLUDES VERIFICATION DE SÉCURITÉ IMPORTANT IMPORTANT IMPORTANT-->
<?php if(isset($_SESSION['connect']) && $_SESSION['connect']==1 && isset($_SESSION['type']))
{ ?>
<!------------------------------------------------------->
<?php 
	$titre_page = 'Aide';
	include('includes/title.php'); 
?>
<div id="contenu">
	<div id="blockaide">
	<?php 
		if($_SESSION['type'] == 0)
		{
		?>
		<h3>Accueil</h3>
		<p>L'accueil est la premi&egrave;re page que vous voyez apr&egrave;s votre connexion. Sur cette page, une seule fonction est pr&eacute;sente, soit le bouton temp&ecirc;te. Il suffit de cliquer sur le bouton &laquo; Signaler une temp&ecirc;te &raquo; pour signaler une temp&ecirc;te de neige. Cela aura pour effet d'inscrire &laquo; Temp&ecirc;te &raquo; sur chaque case de la journée. Cette action ne supprime pas les cases des enseignants et est r&eacute;versible. Il suffit de cliquer sur le bouton rouge &laquo; Annuler la temp&ecirc;te &raquo; pour annuler la temp&ecirc;te.</p>
		<h3>Mon Profil</h3>
		<p>Cette page vous permet de modifier les informations de votre compte, soit votre mot de passe, votre nom, votre email et votre couleur. Pour ce faire, il suffit d'entrer votre mot de passe dans la section appropri&eacute;e, puis de modifier les champs d&eacute;sir&eacute;s et finalement de cliquer sur &laquo; Modifier &raquo;.</p>
		<h3>Mon Horaire</h3>
		<p>La page &laquo; Mon Horaire &raquo; est la page la plus importante. C'est sur celle-ci que vous pourrez voir les p&eacute;riodes qui vous sont attribu&eacute;es, puis y inscrire les informations voulues. Il suffit de remplir les cases &laquo; Description &raquo; voulues. Les cases &laquo; Lien &raquo; peuvent &eacute;galement &ecirc;tre remplies, si vous d&eacute;sirez que la case cliqu&eacute;e redirige vers un site en particulier (comme une description du projet sur le portail, par exemple). Vous pouvez &eacute;galement cocher la case &laquo; Cong&eacute; &raquo; afin de signaler une p&eacute;dagogique, &agrave; la mani&egrave;re du bouton temp&ecirc;te. Cette action est &eacute;galement r&eacute;versible et ne supprime aucune case : il suffit de d&eacute;cocher les cases voulues pour annuler un cong&eacute;. Une fois les champs remplis, il suffit de cliquer sur &laquo; Soumettre &raquo;.</p>
		<h3>Email</h3>
		<p>Cette page vous permet d'envoyer un courriel &agrave; un groupe ou &agrave; un niveau particulier d'&eacute;l&egrave;ves Protic. Il suffit de s&eacute;lectionner le niveau et le groupe appropri&eacute; et de cliquer sur &laquo; Envoyer un courriel &raquo;. Cela aura pour effet d'ouvrir une fen&ecirc;tre &laquo; Nouveau Message &raquo; dans votre logiciel de courriel favori. Il est &eacute;galement possible d'envoyer un message &agrave; tous les groupes ou &agrave; tous les niveaux de Protic, soit en s&eacute;lectionnant l'option &laquo; Tous &raquo;.</p>
		<?php }
		elseif($_SESSION['type'] == 1)
		{ ?>
		<h3>Accueil</h3>
		<p>L'accueil est la premi&egrave;re page que vous voyez apr&egrave;s votre connexion. La premi&egrave;re fonction que vous verrez est celle du changement des couleurs des horaires. Selon le niveau de l'&eacute;l&egrave;ve, son horaire aura une couleur diff&eacute;rente selon les informations de cette fonction. Il suffit d'inscrire un code de couleur hexad&eacute;cimal pr&eacute;c&eacute;d&eacute; par un di&egrave;se &agrave; c&ocirc;t&eacute; du niveau d&eacute;sir&eacute;, puis de cliquer sur &laquo; Soumettre &raquo;. Pour la fonction &laquo; Temp&ecirc;te &raquo;, il suffit de cliquer sur le bouton &laquo; Signaler une temp&ecirc;te &raquo; pour signaler une temp&ecirc;te de neige. Cela aura pour effet d'inscrire &laquo; Temp&ecirc;te &raquo; sur chaque case de la journ&eacute;e. Cette action ne supprime pas les cases des enseignants et est r&eacute;versible. Il suffit de cliquer sur le bouton rouge &laquo; Annuler la temp&ecirc;te &raquo; pour annuler la temp&ecirc;te. La fonction &laquo; URL du site &raquo; permet &agrave; la section &laquo; Email &raquo; de fonctionner. Il suffit de s'assurer que le bon URL du site y soit inscrit, puis de cliquer sur &laquo; Actualiser &raquo; pour enregistrer un changement.</p>
		<h3>Mon Profil</h3>
		<p>Cette page vous permet de modifier les informations de votre compte, soit votre mot de passe, votre nom, votre email et votre couleur. Pour ce faire, il suffit d'entrer votre mot de passe dans la section appropri&eacute;e, puis de modifier les champs d&eacute;sir&eacute;s et finalement de cliquer sur &laquo; Modifier &raquo;.</p>
		<h3>Mon Horaire</h3>
		<p>La page &laquo; Mon Horaire &raquo; est la page la plus importante. C'est sur celle-ci que vous pourrez voir les p&eacute;riodes qui vous sont attribu&eacute;es, puis y inscrire les informations voulues. Il suffit de remplir les cases &laquo; Description &raquo; voulues. Les cases &laquo; Lien &raquo; peuvent &eacute;galement &ecirc;tre remplies, si vous d&eacute;sirez que la case cliqu&eacute;e redirige vers un site en particulier (comme une description du projet sur le portail, par exemple). Vous pouvez &eacute;galement cocher la case &laquo; Cong&eacute; &raquo; afin de signaler une p&eacute;dagogique, &agrave; la mani&egrave;re du bouton temp&ecirc;te. Cette action est &eacute;galement r&eacute;versible et ne supprime aucune case, il suffit de d&eacute;cocher les cases voulues pour annuler un cong&eacute;. Une fois les champs remplis, il suffit de cliquer sur &laquo; Soumettre &raquo;.</p>
		<h3>Courriel</h3>
		<p>Cette page vous permet d'envoyer un courriel &agrave; un groupe ou &agrave; un niveau particulier d'&eacute;l&egrave;ves Protic. Il suffit de s&eacute;lectionner le niveau et le groupe appropri&eacute; et de cliquer sur &laquo; Envoyer un courriel &raquo;. Cela aura pour effet d'ouvrir une fen&ecirc;tre &laquo; Nouveau Message &raquo; dans votre logiciel de courriel favori. Il est &eacute;galement possible d'envoyer un message &agrave; tous les groupes ou &agrave; tous les niveaux de Protic, soit en s&eacute;lectionnant l'option &laquo; Tous &raquo;.</p>
		<h3>Calendrier</h3>
		<p>Cette fonction n'est utile qu'&agrave; chaque d&eacute;but d'ann&eacute;e scolaire. Elle sert &agrave; d&eacute;terminer les jours scolaires afin que la section &laquo; Mon Horaire &raquo; puisse fonctionner. &Agrave; l'aide du formulaire, on sp&eacute;cifie d'abord le premier jour scolaire ainsi que le dernier, avant de cliquer sur &laquo; Soumettre &raquo;. ATTENTION! En cliquant sur ce bouton, vous &eacute;craserez le calendrier scolaire pr&eacute;c&eacute;dant, il est donc primordial d'utiliser cette fonction seulement au d&eacute;but de l'ann&eacute;e. Les cases des professeurs ne sont pas atteintes eux-m&ecirc;mes, alors l'erreur peut &ecirc;tre rectifi&eacute;e en remplissant les bonnes cases &agrave; l'&eacute;tape suivante: on coche tous les cong&eacute;s de la commission scolaire (avec la lettre &laquo; c &raquo;) et tous les autres cong&eacute;s de l'&eacute;cole avec la lettre &laquo; e &raquo;, puis on soumet le formulaire une seconde fois. </p>
		<h3>Configuration Cycle</h3>
		<p>Cette fonction sert &agrave; attribuer les enseignants aux bonnes p&eacute;riodes d'un cycle. Elle n'est normalement utilis&eacute;e qu'en d&eacute;but d'ann&eacute;e, mais peut &eacute;galement servir &agrave; modifier les enseignants en cours d'ann&eacute;e. On choisit d'abord un niveau et un groupe &agrave; modifier, puis on clique sur &laquo; modifier &raquo;. On s&eacute;lectionne ensuite le bon enseignant pour chaque p&eacute;riode &agrave; l'aide de la liste d&eacute;roulante. Dans le cas d'un cours optionnel, on choisi le groupe mati&egrave;re appropri&eacute; ex: Options 1 (ex: Chimie). Les cases vides d&eacute;signent, pour l'&eacute;l&egrave;ve, une p&eacute;riode d'option quelconque (&Eacute;ducation physique, Multisports, Arts, usique, etc.). On clique finalement sur &laquo; Soumettre &raquo; pour enregistrer les informations. </p>
		<h3>Groupes Mati&egrave;res</h3>
		<p>Cette section sert &agrave; cr&eacute;er des mati&egrave;res en option. Il suffit de sp&eacute;cifier le groupe de la mati&egrave;re (par exemple, chimie et PUM sont dans le m&ecirc;me groupe, donc ils doivent avoir le m&ecirc;me groupe mati&egrave;re) avec la liste d&eacute;roulante. On inscrit &eacute;galement le nom de la mati&egrave;re, puis on s&eacute;lectionne l'enseignant qui enseigne la mati&egrave;re. On clique sur &laquo; Ajouter l'option &raquo; pour enregistrer la mati&egrave;re. Un tableau est pr&eacute;sent avec les mati&egrave;res en option d&eacute;j&agrave; existantes. On en supprimer en cliquant sur &laquo; Supprimer &raquo;. </p>
		<h3>&Eacute;l&egrave;ves</h3>
		<p>Cette section comporte un tableau avec chaque &eacute;l&egrave;ve inscrit, ainsi que les informations de ces derniers (code fiche, niveau, groupe, nom, pr&eacute;nom et email). Il est possible de supprimer un &eacute;l&egrave;ve en cliquant sur le bouton &laquo; Supprimer &raquo; &agrave; c&ocirc;t&eacute; de l'&eacute;l&egrave;ve d&eacute;sir&eacute;.</p>
		<h3>Enseignants</h3>
		<p>Cette section permet de voir les enseignants actuels avec un tableau similaire &agrave; celui de la section &laquo; &eacute;l&egrave;ves &raquo;. Il est &eacute;galement possible de supprimer un enseignant de la m&ecirc;me fa&ccedil;on que l'on supprime un &eacute;l&egrave;ve. On peut &eacute;galement ajouter un enseignant, il suffit de sp&eacute;cifier si lenseignant aura les droits administrateurs (soit en cochant la case &laquo; Admin &raquo;), puis remplir le reste des informations avant de cliquer sur &laquo; Ajouter &raquo;. Veuillez noter que lenseignant pourra changer ses informations lui m&ecirc;me plus tard s'il le d&eacute;sire, &agrave; l'exception de son pseudonyme et de son droit d'administration. </p>
		<?php } ?>
	</div>
</div>

<!--  A METTRE APRES TOUT LE CODE VERIFICATION DE S&eacute;CURIT&eacute; IMPORTANT IMPORTANT IMPORTANT-->
<?php }
else
{
	echo "<meta http-equiv='Refresh' content='0; URL=connect.php'>";
} ?>
<!------------------------------------------------------->
