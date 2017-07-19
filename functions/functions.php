<?php

	include '../database/lanMan.php';
	require 'mailer/PHPMailer-master/PHPMailerAutoload.php';
	$GLOBALS['conn'] = new mysqli($host, $username, $password, $db);
	if ($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	} 
	function executeInsertOrUpdate($statement){
		if($GLOBALS['conn']->query($statement) === TRUE){
			return;
		}else{
			echo "Error: " . $statement . "<br>" . $GLOBALS['conn']->error;
		}
	}
	function executeQuery($query){
		$result = $GLOBALS['conn']->query($query);
		return $result;
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
						if(date("N") == 5 && date("G") >= 17 || $vote['user_id'] == $_SESSION["userId"]){
							$voted = $voted."<img class='gravatar' data-container='body' data-toggle='popover' data-content='Profile Links Coming Soon&trade;' data-placement='bottom' data-trigger='hover' title='".$vote['name']."' src='https://www.gravatar.com/avatar/".md5($vote['email'])."?s=22&d=identicon' alt='".substr($vote['name'],0,1)."'>";
						}
					}
				}
				$voted="<td class='text-right'>".$voted."</td>";
				$built=$built."<tr><td><input name='vote".$lookup."[]' type='checkbox' value='".htmlspecialchars($description,ENT_QUOTES)."' $selected></td><td>$description</td>$voted</tr>";
			}
		}
		return $built;
	}
	function castActivityVote($description){		
		executeInsertOrUpdate("delete from db_activity_votes where user_id = ".$_SESSION['userId']);
		if($description != ''){
			$id=$_SESSION['userId'];
			$built="";
			foreach($description as &$value){
				$rawValue=$value;
				$value=mysqli_real_escape_string($GLOBALS['conn'],$value);
				try{
					executeInsertOrUpdate("insert ignore into lu_activities(description) values('$value')");
					if($GLOBALS['conn']->insert_id != 0){
						$user = executeQuery("select name from lu_users where id = $id");
						if($user->num_rows == 1){
							while($aUser = $user->fetch_assoc()){
								sendMail("New Item Added to Activities", "<b>".$aUser['name']." has added \"$rawValue\" to the list of activities.</b>","select u.email from lu_users u join db_rsvp r on u.id=r.user_id where u.notify = 1");
							}
						}
					}
					$getId = executeQuery("select id from lu_activities where description = '$value'");
				}catch(Exception $e){
					$getId = executeQuery("select id from lu_activities where description = '$value'");
				}
				if($getId->num_rows == 1){
					while($anId = $getId->fetch_assoc()){
						$itemId = $anId['id'];
					}
				}
				$built=$built."($id,$itemId)";
			}
			$built= str_replace(")(", "),(",$built);
			$castVote = sprintf("insert ignore into db_activity_votes(user_id, activity_id) values$built");
			executeInsertOrUpdate($castVote);
		}
	}
	function castFoodVote($description){		
		executeInsertOrUpdate("delete from db_food_votes where user_id = ".$_SESSION['userId']);
		if($description != ''){
			$id=$_SESSION['userId'];
			$built="";
			foreach($description as &$value){
				$rawValue=$value;
				$value=mysqli_real_escape_string($GLOBALS['conn'],$value);
				try{
					executeInsertOrUpdate("insert ignore into lu_food(description) values('$value')");
					if($GLOBALS['conn']->insert_id != 0){
						$user = executeQuery("select name from lu_users where id = $id");
						if($user->num_rows == 1){
							while($aUser = $user->fetch_assoc()){
								sendMail("New Food Item Added", "<b>".$aUser['name']." has added \"$rawValue\" to the list of dining options.</b>","select u.email from lu_users u join db_rsvp r on u.id=r.user_id where u.notify = 1");
							}
						}
					}
					$getId = executeQuery("select id from lu_food where description = '$value'");
				}catch(Exception $e){
					$getId = executeQuery("select id from lu_food where description = '$value'");
				}
				if($getId->num_rows == 1){
					while($anId = $getId->fetch_assoc()){
						$itemId = $anId['id'];
					}
				}
				$built=$built."($id,$itemId)";
			}
			$built= str_replace(")(", "),(",$built);
			$castVote = sprintf("insert ignore into db_food_votes(user_id,food_id) values$built");
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
	function updateUserInfo($name, $email,$notify){
		$notify = ($notify != ""? 1:0);
		$name = mysqli_real_escape_string($GLOBALS['conn'],$name);
		$email = mysqli_real_escape_string($GLOBALS['conn'], $email);
		$notify = mysqli_real_escape_string($GLOBALS['conn'], $notify);
		$id=$_SESSION['userId'];
		$query="update lu_users set name='$name', email='$email', notify=$notify where id = $id";
		executeInsertOrUpdate($query);
	}
	function rsvp($attending,$comment){		
		executeInsertOrUpdate("delete from db_rsvp where user_id = ".$_SESSION['userId']);
		if($attending != ''){
			$id=$_SESSION['userId'];
			$unescapedComment = $comment;
			$comment = mysqli_real_escape_string($GLOBALS['conn'],$comment);
			$rsvp = sprintf("insert into db_rsvp(user_id,comment) values($id,'$comment')");
			executeInsertOrUpdate($rsvp);
			$user = executeQuery("select name from lu_users where id = $id");
			if($user->num_rows == 1){
				while($aUser = $user->fetch_assoc()){
					sendMail($aUser['name']." joins the battle!", "<b>".$aUser['name']." has confirmed their attendance with the comment: \"$unescapedComment\".</b>","select u.email from lu_users u join db_rsvp r on u.id=r.user_id where u.notify = 1");
					sendMail($aUser['name']." joins the battle!", "<b>".$aUser['name']." has confirmed their attendance with the comment: \"$unescapedComment\".</b><br>Click <a href='https://t3gs.ninja/lanPartay/'>here</a> to RSVP and vote.","select u.email from lu_users u left join db_rsvp r on u.id=r.user_id where u.notify = 1 and r.user_id is null");
				}
			}
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
		if(date("N") == 5 && date("G") >= 17){
			$docket = '';
			$query = "select l.* from lu_activities l join (select *, count(*) as votes from db_activity_votes group by activity_id) d on l.id = d.activity_id order by votes desc limit 3";
			$results = executeQuery($query);
			if($results->num_rows > 0){
				while($row = $results->fetch_assoc()){
					$docket = $docket.$row['description'].", ";
				}
				$docket = rtrim($docket,", ");
			}
			return "<div id='alert-docket' class='alert alert-success alert-dismissible fade show' role='alert'><b>Today's Docket</b> - ".$docket."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		}
	}
	function getFood(){
		if(date("N") == 5 && date("G") >= 17){
			$food = '';
			$query = "select l.* from lu_food l join (select *, count(*) as votes from db_food_votes group by food_id) d on l.id = d.food_id order by votes desc limit 1";
			$results = executeQuery($query);
			if($results->num_rows > 0){
				while($row = $results->fetch_assoc()){
					$food = $food.$row['description'].", ";
				}
				$food = rtrim($food,", ");
			}
			return "<div id='alert-food' class='alert alert-success alert-dismissible fade show' role='alert'><b>Today's Cuisine</b> - ".$food."<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
		}
	}
	function hasRsvp(){
		$id=$_SESSION['userId'];
		$query="select * from db_rsvp where user_id = $id";
		$results = executeQuery($query);
		$rsvp = '';
		if($results->num_rows == 1){
			$rsvp = array('','');
		}else{
			$rsvp = array('collapse','aria-expanded="false"');
		}
		return $rsvp;
	}
	function getHistorical(){
		$query="(select date_format(date,'%Y-%m-%d') as date from old_activity_votes order by date) union distinct (select date_format(date, '%Y-%m-%d') as date from old_food_votes order by date) order by date desc";
		$dates=executeQuery($query);
		if($dates->num_rows > 0){
			while($date = $dates->fetch_assoc()){
				$aDate = substr($date['date'],0,10);
				$query = "select l.description, count(o.activity_id) as votes from old_activity_votes o join lu_activities l on o.activity_id = l.id where o.date like '$aDate %' group by o.activity_id order by count(o.activity_id) desc";
				$activities=executeQuery($query);
				$built="<tr><td>".$aDate."</td><td><table class='table table-sm table-bordered'><thead><th>Item</th><th>Votes</th><tbody>";
				if($activities->num_rows > 0){
					while($activity = $activities->fetch_assoc()){
						$item = htmlspecialchars($activity['description']);
						$votes = htmlspecialchars($activity['votes']);
						$built=$built."<tr><td>$item</td><td>$votes</td></tr>";
					}
				}
				$built=$built."</tbody></table></td><td><table class='table table-sm table-bordered'><thead><th>Item</th><th>Votes</th><tbody>";
				$query = "select l.description, count(o.food_id) as votes from old_food_votes o join lu_food l on o.food_id = l.id where o.date like '$aDate %' group by o.food_id order by count(o.food_id) desc";
				$food=executeQuery($query);
				if($food->num_rows > 0){
					while($option = $food->fetch_assoc()){
						$item = htmlspecialchars($option['description']);
						$votes = htmlspecialchars($option['votes']);
						$built=$built."<tr><td>$item</td><td>$votes</td></tr>";
					}
				}
				$built=$built.'</tbody></table></td></tr>';
				echo $built;
			}
		}
	}

	function sendMail($title,$text,$query){
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->Host = "ssl://smtp.gmail.com";
		$mail->SMTPAuth=true;
		$mail->SMTPSecure="ssl";
		$mail->Port=465;
		$mail->Encoding='8bit';
		$mail->isHTML(true);
		$mail->Username = $mail_user;
		$mail->Password = $mail_pass;
		$mail->AddAddress("");

		$mail->setFrom("notifications@t3gs.ninja","Notification System");
		$results = executeQuery($query);
		if($results->num_rows > 0){
			while($row = $results->fetch_assoc()){
				$mail->AddBCC($row['email']);
			}
		}
		$mail->Subject = $title;
		$mail->Body=$text;
		$mail->WordWrap =50;
		if(!$mail->Send()) {
			echo 'Mailer error: ' . $mail->ErrorInfo;
		}
	}
?>
