<html>    
    <head>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="bootstrap4/css/bootstrap.min.css" rel="stylesheet" />
		<script src='js/jquery.js'></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src='bootstrap4/js/bootstrap.min.js'></script>
        <style>
            body {
                padding-top: 65px;
            }
        </style>
    </head>
    
    <body>
        <nav class="navbar fixed-top navbar-inverse bg-inverse navbar-toggleable-sm">
            <div class="container">
               <a class="navbar-brand" href=".">
					lanMan
				</a>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    	<h3 class="text-center">Login</h3>
                    <form method="post" action=".">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username"
                            name="username" placeholder="username" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password"
                            name="password" required="required" />
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Login</button>
						<a href="create.php" class="btn btn-outline-primary">Create Account</a>
                    </form>
                </div>
                <div class="col-lg-3"></div>
            </div>
        </div>
    </body>
</html>
