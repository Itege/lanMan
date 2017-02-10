<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='bootstrap/css/bootstrap.min.css' rel='stylesheet'/>
		<script src='js/jquery.js'></script>
		<script src='bootstrap/js/bootstrap.min.js'></script>
		<style>
			body{
				padding-top: 65px;
			}
		</style>
	</head>
	<body>
		<nav class='navbar navbar-fixed-top navbar-inverse'>
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
			</div>
		</nav>
		<div class='container'>
			<div class='row'>
				<div class='col-md-3'>
				</div>
				<div class='col-md-6'>
					<h3 class='text-center'>Create Account</h3>
					<form method='post' action='.'>
						<div class='form-group'>
							<label for='name'>Name</label>
							<input type='text' class='form-control' id='name' name='name' placeholder='Name' required/>
						</div>
						<div class='form-group'>
							<label for='email'>Email</label>
							<input type='email' class='form-control' id='email' name='email' placeholder='example@example.com' required/>
						<div class='form-group'>
							<label for='username'>Username</label>
							<input type='text' class='form-control' id='username' name='username' placeholder='username' required/>
						</div>
						<div class='form-group'>
							<label for='password'>Password</label>
							<input type='password' class='form-control' id='password' name='password' required/>
						</div>
						<button type='submit' class='btn btn-default'>Create Account</button>
					</form>
				</div>
				<div class='col-md-3'>
				</div>
			</div>
		</div>
	</body>
</html>
