<?php
	session_start();
	include 'functions/functions.php';
	if(isset($_SESSION['userId']) && $_SESSION['userId'] != ''){
		include 'includes/update.php';
	}else{
		header("Location: ./login.php");
		die();
	}	
	$GLOBALS['conn']->close();
?>
