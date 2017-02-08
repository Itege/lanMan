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
		if(isset($_POST['voteDocket']) || isset($_POST['addDocket'])){
			if(isset($_POST['voteDocket'])){
				$voteArray = $_POST['voteDocket'];
			}else{
				$voteArray=[];
			}
			array_push($voteArray,$_POST['addDocket']);
			castVote('activity',$voteArray);
		}elseif(isset($_POST['voteFood']) || isset($_POST['addFood'])){
			if(isset($_POST['voteFood'])){
				$voteArray=$_POST['voteFood'];
			}else{
				$voteArray=[];
			}
			array_push($voteArray,$_POST['addFood']);
			castVote('food',$voteArray);
		}
		include 'includes/index.php';
	}else{
		header("Location: ./login.php");
		die();
	}	
	$GLOBALS['conn']->close();
?>
