
<div class="row">
	<div class="col-lg-4 offset-lg-4">
		<form method="POST" name="loginForm">
			<div class="form-group">
		    	<label for="username">Username</label>
		    	<input type="text" name="loginForm[username]" id="username" class="form-control username">
		    </div>

		 	<div class="form-group">
		 		<label for="password">Password</label>
		    	<input type="password" name="loginForm[password]" id="password" class="form-control password">
		    </div>

		    <?php if (!empty($loginForm->errors)): ?>

			    <div class="alert alert-danger text-center">
			        <?php echo $loginForm->getFirstError() ?>
			    </div>

		    <?php endif ?>

		    <input type="submit" class="btn btn-block btn-lg btn-success" value="Submit">
		</form>
	</div>
</div>