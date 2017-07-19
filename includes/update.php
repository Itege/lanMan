<html>
	<?php $userInfo = getUserInfo()?>
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
                <a class="navbar-brand" href=".">lanMan</a>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <h3 class="text-center">Update Info</h3>
                    <form method="post" action=".">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="name" required="required" value='<?= $userInfo["name"]?>' />
						</div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required="required" value='<?= $userInfo["email"]?>' />
						</div>
						<div class='form-group'>
							<div class="form-checked">
								<input type="checkbox" class="form-check-input" id="notify" name="notify" value='1' <?= $userInfo["notify"] != 0? "checked" : ""?> />
								Get Email Notifications
							</div>
						</div>
                        <button type="submit" class="btn btn-primary" value="update" name="page">Update</button>
                    </form>
                </div>
                <div class="col-lg-3"></div>
            </div>
        </div>
    </body>
</html>
