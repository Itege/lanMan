<?php
	include '../database/lanMan.php';
	$GLOBALS['conn'] = new mysqli($host, $username, $password, $db);
	if ($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	} 
	function executeInsertOrUpdate($statement){
		if($GLOBALS['conn']->query($statement) === TRUE){
			return;
		}else{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	function executeQuery($query){
		try{
			$result = $GLOBALS['conn']->query($query);
			return $result;
		}catch(Exception $e){
			echo $e;
		}
	}
	function createUser($name, $username, $password){
		$name = mysqli_real_escape_string($GLOBALS['conn'],$name);
		$username = mysqli_real_escape_string($GLOBALS['conn'], $username);
		$password = mysqli_real_escape_string($GLOBALS['conn'], password_hash($password, PASSWORD_BCRYPT));
		$createUser = sprintf("insert into lu_users(name, username, password) values('$name', '$username', '$password')");
		executeInsertOrUpdate($createUser);
		$_SESSION['userId']=$GLOBALS['conn']->insert_id;
	}
	function connectUser($username, $password){
		$username = mysqli_real_escape_string($GLOBALS['conn'],$username); 
		$createUser = sprintf("select id,password from lu_users where username='$username'");
		$result = executeQuery($createUser);
		if($result->num_rows == 1){
			while($row = $result->fetch_assoc()){
				if(password_verify($password, $row['password'])){	
					$_SESSION['userId']=$row["id"];
				}
			}
		}
	}
	function getUserName(){
		$id = $_SESSION['userId'];
		$query = sprintf("select name from lu_users where id=$id");
		$result=executeQuery($query);
		if($result->num_rows == 1){
			while($row = $result->fetch_assoc()){
				return $row['name'];
			}
		}
	}
	/*
	function buildList($table){
		$id = $_SESSION['userId'];
		if($table=='activities'){
			$lookup='activity';
		}else{
			$lookup = $table;
		}
		$query = sprintf("select l.description, count(v.$lookup_id) from lu_$table l join db_$lookup_votes v on l.id = v.$lookup_id group by description having count(v.$lookup_id) = 0");
		$result=executeQuery($query);
		$built="";
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$description = $row['description'];
				$built = $built."<option value='$description'>\n</option>";
			}
		}
		return $built;
	}
	*/
	function buildVotes($table){
		$id = $_SESSION['userId'];
		if($table=='activities'){
			$lookup='activity';
		}else{
			$lookup = $table;
		}
		$query = "select description from lu_$table";
		echo $query;
		$result=executeQuery($query);
		$built="";
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$description = $row['description'];
				$selected = '';
				if(strpos($row['users'],$_SESSION['userId'])!==false){
					$selected = 'selected';
				}
				$built=$built."<option value='$description' $selected>$description</option>\n";
			}
		}
		return $built;
	}
	function castVote($table,$description){		
			executeInsertOrUpdate("delete from db_".$table."_votes where user_id = ".$_SESSION['userId']);
		if(implode($description,'') != ''){
			$id=$_SESSION['userId'];
			$table = mysqli_real_escape_string($GLOBALS['conn'],$table);
			$description = mysqli_real_escape_string($GLOBALS['conn'],implode(", ",$description)); 
			$description = explode(", ", $description);
			$built="";
			if($table=='activity'){
				$lookup='activities';
			}else{
				$lookup = $table;
			}
			foreach($description as &$value){
				executeInsertOrUpdate("insert ignore into lu_".$lookup."(description) values('$value')");
				$built=$built."($id,".$GLOBALS['conn']->insert_id.")";
			}
			$built= str_replace(")(", "),(",$built);
			$castVote = sprintf("insert ignore into db_".$table."_votes(user_id,".$table."_id) values$built");
			executeInsertOrUpdate($castVote);
		}
	}
?>
