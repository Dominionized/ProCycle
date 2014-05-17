<?php

include('includes/sqlconnect.php');

if(isset($_COOKIE['codefiche']))
{
	$reqSelectNiveau = $bdd->query('SELECT niveau FROM utilisateurs_eleves WHERE codefiche ='.$_COOKIE['codefiche']) or die(print_r($bdd->errorInfo()));
	$fetch = $reqSelectNiveau->fetch();
	$niveau = $fetch['niveau'];
	$reqSelectNiveau->closeCursor();

	$reqSelectCouleur = $bdd->prepare('SELECT couleur FROM couleurs WHERE niveau = :niveau');
	$reqSelectCouleur->execute(array(
		'niveau' => $niveau
	)) or die(print_r($reqSelectCouleur->errorInfo()));
	$fetch = $reqSelectCouleur->fetch();
	$couleur = $fetch['couleur'];
	$reqSelectCouleur->closeCursor();

	if($couleur == '')
	{
		$couleur = '#0087EF';
	}
}
else
{
	$couleur = '#0087EF';
}
?>

<style>

/* !!! CSS PRINCIPAL !!! */

/* Polices personnalisées */
@import url(http://fonts.googleapis.com/css?family=Open+Sans);

@font-face {font-family:"Butter unsalted";src:url("polices/ButterUnsalted.eot?") format("eot"),url("polices/ButterUnsalted.woff") format("woff"),url("polices/ButterUnsalted.ttf") format("truetype"),url("polices/ButterUnsalted.svg#Butter-unsalted") format("svg");font-weight:normal;font-style:normal;}


/* Alignement général */

html{
	background-image: url('images/linedpaper.png');
	background-color: none;
}
body{
	width: 800px;
	background-position: top-left;
	background-repeat: repeat;
	margin: 40px auto;
	box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
	background-color: <?php echo $couleur; ?>;
}
html{
	background-image: url('images/linedpaper.png');
	background-color: none;
}
#contenu{
	background-color: #ededed;
	padding: 30px;

}

/*TABLEAU*/
table{
	border-collapse: collapse;
	text-align: center;
}

th{
	background-color: #212121;
	color: white;
	font-family: "Open Sans", sans;
	font-size: 10pt;
	padding: 5px;
	border: 1px solid rgba(255,255,255,0.2);
}
td{
	font-family: "Open Sans", sans;
	font-size: 10pt;
	border: 1px solid rgba(97,97,97,0.2);
	width: 100px;
	height: 100px;
	background-color: #f2f2f2;
	padding: 10px;
}

#hover{
	box-shadow: inset 0px 0px 10px rgba(0,0,0,0.4); 
}
#actuel{
}

.jour{
	width: 20px;
	font-size: 15pt;
}

/* Couleur et dimensions options */
#options{
	background-color: white;
	border-radius: 2px;
	padding: 10px;
	background-color: white;
}

/* Polices pour presque (sauf le titre) tous les éléments*/
h2, h3, h4, h5, h6{
	font-family: 'Open Sans', sans-serif;
	font-weight: 400;
	color: #565656;
	text-shadow: 0px 1px 0px white;
}
#contenu{
	font-family: 'Open Sans', sans-serif;
	font-size: 15px;
}
/*navigation*/
nav {  
	text-align: right;
	position:relative;
	display: inline-block;
    padding: 0px 0px 0px 0px; margin: 0px;  
	width:430px;	
}

header img{
	vertical-align: bottom;
	margin-top: 15px;
}
nav li {  
    display: inline;  
    list-style: none;  
}  
nav a {  
    display:inline-block;  
    margin-left:30px;
	font-family: "Butter unsalted", sans;
	font-size: 15pt;
	color: white;
	text-decoration: none;
	padding-bottom: 3px;
	border-bottom: 2px solid <?php echo $couleur; ?>;
}  

nav a:hover{
	padding: 0px;
	text-shadow: 0px 0px 5px rgba(0,0,0,0.3);
	height: 25px;
}

header{
	background-color: <?php echo $couleur; ?>;
	padding-left: 30px;
	padding-right: 30px;
}
#title_block{
	width: 300px;
	display: inline-block;
}

#title_block h1{
	color: white;
	font-family: "Butter unsalted", sans;

}

/* Navigation des cycles */

#nav-cycles ul, #nav-cycles{
  list-style: none;
  margin: 0px; padding: 0px;
  margin-bottom: 10px;
  min-width: 80px;
  max-width: 100px;
}

#nav-cycles h2{
  margin: 0px; padding: 0px;
  background-color: #D3D3D3;
  min-width: 80px;
  max-width: 100px;
  border-radius: 3px;
}

#nav-cycles h2:hover{
	background-color: <?php echo $couleur; ?>;
	color: white;
	text-shadow: none;
}

#nav-cycles a{
	text-decoration: none;
	text-align: center;
}

#nav-cycles li ul{
	position: absolute;
}

#nav-cycles li ul li{
	display: none;
}

#nav-cycles li:hover ul li{
	display: inline; 
}

#nav-cycles ul li a{
  text-align: left;
  text-decoration: none;
  color: #000000;
  display: block;
  height: 23px;
  background-color: #ededed;
  padding-left: 10px;
  border-right: 1px solid #c1c1c1;
  border-left: 1px solid #c1c1c1;
  border-bottom: 1px solid #c1c1c1;
}

#nav-cycles ul li:hover a{
	color: #ffffff;
	background-color: <?php echo $couleur; ?>;
}

/* STYLE DES OPTIONS (MATIÈRES ET INFORMATIONS ET BOUTON SOUMETTRE) */

#informations{
	display: inline-block;
	width: 200px;
	vertical-align: middle;
	padding: 20px;
}

#matieres{
	display: inline-block;
	vertical-align: middle;
	padding: 20px;
}

#soumettre{
	display: inline-block;
	vertical-align: bottom;
}

#soumettre input{
	width: 200px;
	height: 50px;
	font-family: "butter unsalted";
	font-weight: bold;
	font-size: 20pt;
	background-color: #ededed;
	text-decoration: none;
	text-shadow: 0px 1px 1px white;
	border: none;
}

#soumettre input:hover{
	background-color: <?php echo $couleur; ?>;
	color: white;
	text-shadow: none;
}

#connexion{
	font-family: "butter unsalted";
	font-weight: bold;
	border: none;
	color: white;
	background-color: <?php echo $couleur; ?>;
	text-shadow: none;
	font-size: 12pt;
}

#deconnexion{
	font-family: "butter unsalted";
	font-weight: bold;
	border: none;
	color: white;
	background-color: #C90000;
	text-shadow: none;
	font-size: 12pt;
	text-decoration: none;
	padding: 1px;
}

#info_user{
	display: inline-block;
	width: 368px;
	right: 0px;
}

#loginlogout{
	display: inline-block;
	right: 0px;
	padding: 0px;
	text-align: right;
	width: 368px;
}
#supprimer{
	font-family: "butter unsalted";
	font-weight: bold;
	border: none;
	color: white;
	background-color: #C90000;
	text-shadow: none;
	font-size: 12pt;
}

/*COULEUR DES CHAMPS REQUIS*/
:required
{
	border: 1px solid <?php echo $couleur; ?>;
}

#credits{
	text-align:center; background-color: rgba( 255, 255, 255, 0); font-size: 10pt; font-family: "Open Sans", sans;
}
</style>
