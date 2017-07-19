<?php
	session_start();
	include 'functions/functions.php';
	if(isset($_POST['username'])){
		if(isset($_POST['name'])){
			try{
				createUser($_POST['name'],$_POST['username'],$_POST['password'],$_POST['email']);
			}catch(Exception $e){
				echo $e;
			}
		}else{
			connectUser($_POST['username'],$_POST['password']);
		}
		header("Location: .");
	}else if(isset($_POST['page']) and $_POST['page'] == 'update'){
		updateUserInfo($_POST['name'],$_POST['email'],$_POST['notify']);
	}
	if(isset($_SESSION['userId']) && $_SESSION['userId'] != ''){
		if(isset($_POST['voteFor']) and $_POST['voteFor'] == 'activity'){
			castActivityVote($_POST['voteactivity']);
			header("Location: .");
		}elseif(isset($_POST['voteFor']) and $_POST['voteFor'] == 'food'){
			castFoodVote($_POST['votefood']);
			header("Location: .");
		}elseif(isset($_POST['action']) && $_POST['action']=='rsvp'){
			rsvp($_POST['attending'],$_POST['comment']);
			header("Location: .");
		}
		$_POST = array();
		include 'includes/index.php';
	}else{
		header("Location: ./login.php");
		die();
	}	
	$GLOBALS['conn']->close();
?>
