 

<?php 
session_start();
if( isset($_SESSION['user']) && isset($_SESSION['uid']) && isset($_SESSION['email']) && isset($_SESSION['dbstatus']) ) {
	unset($_SESSION['user']);
	unset($_SESSION['uid']);
	unset($_SESSION['email']);
	unset($_SESSION['dbstatus']);
   session_destroy();
   header('Location: index.php');   

   
  } else {
   session_destroy();
	header('Location: index.php');
}
?>

