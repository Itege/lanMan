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
	function createUser($name, $username, $password, $email){
		$name = mysqli_real_escape_string($GLOBALS['conn'],$name);
		$username = mysqli_real_escape_string($GLOBALS['conn'], $username);
		$email = mysqli_real_escape_string($GLOBALS['conn'], $email);
		$password = mysqli_real_escape_string($GLOBALS['conn'], password_hash($password, PASSWORD_BCRYPT));
		$createUser = sprintf("insert into lu_users(name, username, password,email) values('$name', '$username', '$password','$email')");
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
		$query = "select l.* from lu_$table l left outer join (select *, count(*) as votes from db_".$lookup."_votes group by ".$lookup."_id) d on l.id = d.".$lookup."_id order by d.votes desc";
		$result=executeQuery($query);
		$built="";
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$id = $row['id']; 
				$description = $row['description'];
				$selected = '';
				$voted = '';
				$query = "select d.user_id, u.email, d.".$lookup."_id, u.name from db_".$lookup."_votes d join lu_users u on u.id = d.user_id where d.".$lookup."_id = $id";
				$votes=executeQuery($query);
				if($votes->num_rows > 0){
					while($vote = $votes->fetch_assoc()){
						if($selected == '' && $vote['user_id'] == $_SESSION['userId']){
							$selected = 'checked';
						}
						$voted = $voted."<img class='gravatar' data-container='body' data-toggle='popover' data-content='Profile Links Coming Soon&trade;' data-placement='bottom' data-trigger='hover' title='".$vote['name']."' src='https://www.gravatar.com/avatar/".md5($vote['email'])."?s=22&d=identicon' alt='".substr($vote['name'],0,1)."'>";
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
			$built="";
			if($table=='activity'){
				$lookup='activities';
			}else{
				$lookup = $table;
			}
			foreach($description as &$value){
				$value=mysqli_real_escape_string($GLOBALS['conn'],$value);
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
	function getUserInfo(){
		$id=$_SESSION['userId'];
		$query="select * from lu_users where id = $id";
		$results = executeQuery($query);
		if($results->num_rows == 1){
			while($row = $results->fetch_assoc()){
				return $row;
			}
		}
	}
	function updateUserInfo($name, $email){
		$name = mysqli_real_escape_string($GLOBALS['conn'],$name);
		$email = mysqli_real_escape_string($GLOBALS['conn'], $email);
		$id=$_SESSION['userId'];
		$query="update lu_users set name='$name', email='$email' where id = $id";
		executeInsertOrUpdate($query);
	}
	function rsvp($attending,$comment){		
		executeInsertOrUpdate("delete from db_rsvp where user_id = ".$_SESSION['userId']);
		if($attending != ''){
			$id=$_SESSION['userId'];
			$comment = mysqli_real_escape_string($GLOBALS['conn'],$comment);
			$rsvp = sprintf("insert into db_rsvp(user_id,comment) values($id,'$comment')");
			executeInsertOrUpdate($rsvp);
		}
	}
	function getUserStatus(){
		$id=$_SESSION['userId'];
		$query="select * from db_rsvp where user_id = $id";
		$results = executeQuery($query);
		if($results->num_rows == 1){
			while($row = $results->fetch_assoc()){
				return $row;
			}
		}
	}
	function getRsvpUsers(){
		$query="select r.comment, u.name, u.email from db_rsvp r join lu_users u on r.user_id = u.id";
		$results = executeQuery($query);
		$rsvp = '';
		if($results->num_rows > 0){
			while($row = $results->fetch_assoc()){
				$name = htmlspecialchars($row['name'],ENT_QUOTES);
				$comment = htmlspecialchars($row['comment'],ENT_QUOTES);
				$email= $row['email'];
				$rsvp = $rsvp."<tr vertical-align='center'><td><img data-container='body' data-toggle='popover' data-content='Profile Links Coming Soon&trade;' data-placement='bottom' data-trigger='hover' title='".$row['name']."' class='gravatar' src='https://www.gravatar.com/avatar/".md5($email)."?s=30&d=identicon'></td><td><b>$name</b></td><td>$comment</td></tr>";
			}
		}
		return $rsvp;
	}
	function getDocket(){
		if(date("w", time()) == 5){
			$docket = '';
			$query = "select l.* from lu_activities l join (select *, count(*) as votes from db_activity_votes group by activity_id) d on l.id = d.activity_id order by votes desc limit 3";
			$results = executeQuery($query);
			if($results->num_rows > 0){
				while($row = $results->fetch_assoc()){
					$docket = $docket.$row['description'].", ";
				}
				$docket = rtrim($docket,", ");
			}
			return "<div class='alert alert-success' role='alert'><b>Today's Docket</b> - ".$docket."</div>";
		}
	}
	function getFood(){
		if(date("w", time()) == 5){
			$food = '';
			$query = "select l.* from lu_food l join (select *, count(*) as votes from db_food_votes group by food_id) d on l.id = d.food_id order by votes desc limit 1";
			$results = executeQuery($query);
			if($results->num_rows > 0){
				while($row = $results->fetch_assoc()){
					$food = $food.$row['description'].", ";
				}
				$food = rtrim($food,", ");
			}
			return "<div class='alert alert-success' role='alert'><b>Today's Cuisine</b> - ".$food."</div>";
		}
	}
?>
