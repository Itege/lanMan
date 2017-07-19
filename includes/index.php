<!DOCTYPE html>
<?php $userHasRsvp = hasRsvp()?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>LAN Manager</title>
		<link href='bootstrap4/css/bootstrap.min.css' rel='stylesheet'/>
		<script src='js/jquery.js'></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src='bootstrap4/js/bootstrap.min.js'></script>
		<style>
			.navbar-static-top{
				padding-right: 20px;
			}
			.has-table{
				overflow-y: auto;
				height: 185px;
				border: 1px solid #ddd;
				border-top: none;
				border-radius: 3px;
			}
			.has-table .table tr td{
				vertical-align: middle;
			}
			.gravatar{
				padding: 1px;
			}
		</style>
		<script type='text/javascript'>
			$(function(){
				$('[data-toggle="popover"]').popover();
				$('.add-btn.food').on('click', function(e){
					$parent = $(this).closest('.card');
					newDescription = $parent.find('input[name="add"]').val().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
					$parent.find('input[name="add"]').val("");
					if(newDescription != ''){
						var $newItem = $parent.find('table').first().find('tr').first().clone();
						$newItem.find('input[type="checkbox"]').first().val(newDescription).prop('checked',true);
						$newItem.find('td').first().siblings().first().text(newDescription);
						newItem="<tr><td><input checked name='votefood[]' type='checkbox' value='"+newDescription+"'></td><td>"+newDescription+"</td><td></td></tr>";
						$parent.find('table').prepend(newItem);
					}
				});
				$('.add-btn.activity').on('click', function(e){
					$parent = $(this).closest('.card');
					newDescription = $parent.find('input[name="add"]').val().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
					$parent.find('input[name="add"]').val("");
					if(newDescription != ''){
						var $newItem = $parent.find('table').first().find('tr').first().clone();
						$newItem.find('input[type="checkbox"]').first().val(newDescription).prop('checked',true);
						$newItem.find('td').first().siblings().first().text(newDescription);
						newItem="<tr><td><input checked name='voteactivity[]' type='checkbox' value='"+newDescription+"'></td><td>"+newDescription+"</td><td></td></tr>";
						$parent.find('table').prepend(newItem);
					}
				});
				$('.alert-dismissible').on('closed.bs.alert',function(){
					var date= new Date();
					document.cookie = $(this).attr("id")+"=true; expires="+new Date(+date+(6-(date.getDay())%6)*86400000).toString().replace(/[0-9]{2}:[0-9]{2}:[0-9]{2}/,"00:00:00")+"path=/";	
				});
				if(document.cookie.toString().indexOf("alert-docket") != -1){
					$('#alert-docket').remove();
				}
				if(document.cookie.toString().indexOf("alert-food") != -1){
					$('#alert-food').remove();
				}
				try{
					$('iframe').first().css('height', (.5625*$('iframe').first().css('width').replace("px","")));
				}catch(e){}
			});
		</script>
	</head>
	<body>
		<nav class="navbar sticky-top navbar-inverse bg-inverse navbar-toggleable-lg">
			<div class="container">
				<button type="button" class="navbar-toggler navbar-toggler-left" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation"><span class='navbar-toggler-icon'></span></button>
				<a class="navbar-brand" href=".">lanMan</a>
				<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
					<ul class="navbar-nav ml-auto">
						<li class='nav-item'>
							<a href="update.php" class="nav-link"><?= getUserName() ?></a>
						</li>
						<li class='nav-item'>
							<a href="logout.php" class="btn btn-outline-danger" role='button'>Logout</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container" style='margin-top: 10px;'>
			<div class="row">
				<div class="col-lg-12">
					<?= getDocket() ?>
					<?= getFood() ?>
				</div>
			</div>
			<?php 
				if(!isset($_GET['t'])){
					include "includes/dashboard.php";
				}else{
					include "includes/".$_GET['t'].".php";
				}
			?>
		</div>
	</body>
</html>
