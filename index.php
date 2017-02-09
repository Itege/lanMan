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
	}else if(isset($_POST['page']) and $_POST['page'] == 'update'){
		updateUserInfo($_POST['name'],$_POST['email']);
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
