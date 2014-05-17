<?php
session_start();
  
$_SESSION = array();
if (isset($_COOKIE[session_name()]))
{setcookie(session_name(),'',time()-4200,'/');}
  
session_destroy();
echo "<meta http-equiv='Refresh' content='0; URL=../index.php'>";

?>
<title>Horaire Protic</title>