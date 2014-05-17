<?php
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=jdesmara_wpatry', 'jdesmara_wpatry', '32057patryw');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>
