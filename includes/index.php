<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='bootstrap/css/bootstrap.min.css' rel='stylesheet'/>
		<script src='js/jquery.js'></script>
		<script src='bootstrap/js/bootstrap.min.js'></script>
		<style>
			.navbar-static-top{
				padding-right: 20px;
			}
			.has-table{
				overflow-y: auto;
				height: 150px;
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
				$('[data-toggle="popover"]').popover()
				$('[data-toggle="tooltip"]').tooltip()
				$('.add-btn.food').on('click', function(e){
					$parent = $(this).closest('.panel');
					newDescription = $parent.find('input[name="add"]').val().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
					if(newDescription != ''){
						var $newItem = $parent.find('table').first().find('tr').first().clone();
						$newItem.find('input[type="checkbox"]').first().val(newDescription).prop('checked',true);
						$newItem.find('td').first().siblings().first().text(newDescription);
						newItem="<tr><td><input checked name='votefood[]' type='checkbox' value='"+newDescription+"'></td><td>"+newDescription+"</td><td></td></tr>";
						$parent.find('table').append(newItem);
					}
				});
				$('.add-btn.activity').on('click', function(e){
					$parent = $(this).closest('.panel');
					newDescription = $parent.find('input[name="add"]').val().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
					if(newDescription != ''){
						var $newItem = $parent.find('table').first().find('tr').first().clone();
						$newItem.find('input[type="checkbox"]').first().val(newDescription).prop('checked',true);
						$newItem.find('td').first().siblings().first().text(newDescription);
						newItem="<tr><td><input checked name='voteactivity[]' type='checkbox' value='"+newDescription+"'></td><td>"+newDescription+"</td><td></td></tr>";
						$parent.find('table').append(newItem);
					}
				});
				$('#tablist a').click(function (e) {
					e.preventDefault();
					$(this).tab('show');
					$('iframe').first().css('height', (.5625*$('iframe').first().css('width').replace("px","")));
				});
			});
		</script>
	</head>
	<body>
		<nav class='navbar navbar-static-top navbar-inverse'>
			<div class='container'>
				<div class='navbar-header'>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class='navbar-brand' href='.'>
						lanMan
					</a>
				</div>
				<div class='collapse navbar-collapse' id="bs-example-navbar-collapse-1">
					 <div class="nav nav-bar navbar-right">
					 	<p class='navbar-text'>
					 		<a href='update.php' class='navbar-link'><?= getUserName() ?></a>
						</p>
						<a href='logout.php' class='btn btn-default navbar-btn'>Logout</a>
					</div>
				</div>
			</div>
		</nav>
		<div class='container'>
			<div class='row'>
				<div class='col-md-12'>
					<?= getDocket() ?>
					<?= getFood() ?>
				</div>
				<div class='col-md-12'>
					<ul class="nav nav-tabs" id='tablist' role="tablist">
						<li role="presentation" class="active"><a href="#dashboard" aria-controls="dashboard" role="tab" data-toggle="tab">Dashboard</a></li>
						<li role="presentation"><a href="#stream" aria-controls="stream" role="tab" data-toggle="tab">Stream</a></li>
					</ul>
				</div>
				<div class='tab-content'>
					<div role='tabpanel' class='tab-pane active' id='dashboard'>
						<div class='col-md-6'>
							<div class='panel panel-default'>
								<div class='panel-heading'>Docket</div>
								<div class='panel-body'>
									<form>
										<div class='row'>
											<div class='col-md-9'>
												<input name='add' type='text' class='form-control'>
											</div>
											<div class='col-md-3 text-right'>
												<button type='button' class='btn btn-primary add-btn activity'>Add</button>
											</div>
										</div>
									</form>
									<br>
									<form method='post'>
										<input type='hidden' value='activity' name='voteFor'/>
										<div class='has-table'>
											<table class='table table-condensed'>	
												<?= buildVotes("activities") ?>
											</table>
										</div>
										<br>
										<button type="submit" class="btn btn-success">Vote</button>
									</form>
								</div>
							</div>
						</div>
						<div class='col-md-6'>
							<div class='panel panel-default'>
								<div class='panel-heading'>Food</div>
								<div class='panel-body'>
									<form>
										<div class='row'>
											<div class='col-md-9'>
												<input name='add' type='text' class='form-control'>
											</div>
											<div class='col-md-3 text-right'>
												<button type='button' class='btn btn-primary add-btn food'>Add</button>
											</div>
										</div>
									</form>
									<br>
									<form method='post'>
										<input type='hidden' value='food' name='voteFor'/>
										<div class='has-table'>
											<table class='table table-condensed'>	
												<?= buildVotes("food") ?>
											</table>
										</div>
										<br>
										<button type="submit" class="btn btn-success">Vote</button>
									</form>
								</div>
							</div>
						</div>
						<div class='col-md-12'>
							<div class='panel panel-default'>
								<div class='panel-heading'>RSVP</div>
								<div class='panel-body'>
									<form method='post'>
										<table class='table table-condensed'>
											<thead>
												<tr>
													<th>
														Attending
													</th>
													<th>
														Comment
													</th>
													<th>
														RSVP
													</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<?php $userRsvpStatus = getUserStatus() ?>
													<td>
														<input type='checkbox' name='attending' <?php echo ($userRsvpStatus['user_id'] != '' ? 'checked' : '') ?>/>
													</td>
													<td>
														<input type='text' class='form-control' name='comment' value='<?= htmlspecialchars($userRsvpStatus["comment"],ENT_QUOTES)?>'/>
													</td>
													<td>
														<button type='submit' class='btn btn-success' name='action' value='rsvp'>RSVP</button>
													</td>
												</tr>
											</tbody>
										</table>
									</form>
									<div class='has-table'>
										<table class='table'>
											<tbody>
												<?= getRsvpUsers() ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div role='tabpanel' class='tab-pane' id='stream'>
						<div class='col-md-12'>
							<div class='panel panel-default'>
								<div class='panel-heading'>Stream</div>
								<div class='panel-body'>
									<iframe src="https://player.twitch.tv/?channel=itege" style="width: 100%" frameborder="0" scrolling="no" allowfullscreen="true">
									</iframe>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
