<?php
	session_start();
	include 'functions/functions.php';
	if(isset($_POST['username'])){
		if(isset($_POST['name'])){
			try{
				createUser($_POST['name'],$_POST['username'],$_POST['password']);
			}catch(Exception $e){
				echo $e;
			}
		}else{
			connectUser($_POST['username'],$_POST['password']);
		}
	}
	if(isset($_SESSION['userId']) && $_SESSION['userId'] != ''){
		if(isset($_POST['voteFor']) and $_POST['voteFor'] == 'activity'){
			castVote('activity',$_POST['voteactivity']);
		}elseif(isset($_POST['voteFor']) and $_POST['voteFor'] == 'food'){
			castVote('food',$_POST['votefood']);
		}
		include 'includes/index.php';
	}else{
		header("Location: ./login.php");
		die();
	}	
	$GLOBALS['conn']->close();
?>
