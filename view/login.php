<?php if(LOGGED_IN) { header("location: ".URL); } ?>
<?php require_once("header.php"); ?>

<div id="content">
	
	<div class="inner">
	
		<div class="login_error">Wrong username or password</div>
	
		<div class="login">
			
			<label> Email </label>
			<input type="text" />
			<label> Password </label>
			<input type="password" />
			
			<button class="btn green"> Login </button>
			
		</div>
	
	</div>
	
</div>

<?php require_once("home_footer.php"); ?>
