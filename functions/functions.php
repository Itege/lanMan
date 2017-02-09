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
		$username = mysqli_real_escape_string($GLOBALS['conn'],$username); 
		$password = password_hash(mysqli_real_escape_string($GLOBALS['conn'],$password),PASSWORD_BCRYPT); 
		$createUser = sprintf("insert into lu_users(name,username,password) values('$name','$username','$password')");
		executeInsertOrUpdate($createUser);
		$_SESSION['userId']=$GLOBALS['conn']->insert_id;
	}
	function connectUser($username,$password){
		$username = mysqli_real_escape_string($GLOBALS['conn'],$username); 
		$password = mysqli_real_escape_string($GLOBALS['conn'],$password); 
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
		$query = "select * from lu_$table";
		$result=executeQuery($query);
		$built="";
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$id = $row['id']; 
				$description = $row['description'];
				$selected = '';
				$voted = '';
				$query = "select d.user_id, d.".$lookup."_id, u.name from db_".$lookup."_votes d join lu_users u on u.id = d.user_id where ".$lookup."_id = $id";
				$votes=executeQuery($query);
				if($votes->num_rows > 0){
					while($vote = $votes->fetch_assoc()){
						if($selected == '' && $vote['user_id'] == $_SESSION['userId']){
							$selected = 'checked';
						}
						$voted = $voted."<span class='label label-default'>".substr($vote['name'],0,1)."</span>";
					}
				}
				$voted="<td class='text-right'>".$voted."</td>";
				$built=$built."<tr><td><input name='vote".$lookup."[]' type='checkbox' value='".htmlspecialchars($description,ENT_QUOTES)."' $selected></td><td>$description</td>$voted</tr>";
			}
		}
		return $built;
	}
	function castVote($table,$description){		
		executeInsertOrUpdate("delete from db_".$table."_votes where user_id = ".$_SESSION['userId']);
		if($description != ''){
			$id=$_SESSION['userId'];
			$table = mysqli_real_escape_string($GLOBALS['conn'],$table);
			$description = array_map('htmlspecialchars',$description); 
			$built="";
			if($table=='activity'){
				$lookup='activities';
			}else{
				$lookup = $table;
			}
			foreach($description as &$value){
				executeInsertOrUpdate("insert ignore into lu_".$lookup."(description) values('$value')");
				$getId = executeQuery("select id from lu_".$lookup." where description = '$value'");
				if($getId->num_rows == 1){
					while($anId = $getId->fetch_assoc()){
						$itemId = $anId['id'];
					}
				}
				$built=$built."($id,$itemId)";
			}
			$built= str_replace(")(", "),(",$built);
			$castVote = sprintf("insert ignore into db_".$table."_votes(user_id,".$table."_id) values$built");
			executeInsertOrUpdate($castVote);
		}
	}
?>
