<?php if(!defined('IS_ADMIN')) { exit; } ?>
<?php require_once("header.php"); ?>

<div id="content">
	
	<div class="inner">
		
		<header>
			<h1> Turku Endometriosis Database - Admin </h1>							
		</header>

		<article class="content">
			
			<?php if(!isset($_GET["osio"])) { ?>
			
			<h2> Users </h2>
			
			<?php echo $this->getUsers(); ?>
			
			<a href="<?php echo URL; ?>admin/user/"><button class="btn green" data-name="add-user" style="margin-top: 20px;"> Add user </button></a>
			
			<button id="test_r" style="display: none;"></button>
					
			<?php } else if($_GET["osio"] == "user") { ?>
				
				<?php $user = $this->getUser(); ?>
				
				<h2><?php echo ($user->id > 0) ? 'Edit user' : 'Add user'; ?></h2>

				<div class="login_error">Wrong username or password</div>
				
				<div class="user_edit" data-name="<?php echo ($user->id > 0) ? 'edit-user' : 'add-user'; ?>">
					
					<label> Name </label>
					<input type="text" name="name" value="<?php echo $user->name; ?>" />

					<label> Email </label>
					<input type="email" name="email" value="<?php echo $user->username; ?>" />

					<label> Password </label>
					<input type="password" name="passwd"  />				

					<label> Password again </label>
					<input type="password" name="passwd2" />

					<div class="fl checkbox<?php echo ($user->admin == 1) ? ' checked' : ''; ?>" data-name="admin" style="margin-top: 20px;">
						<figure><i class="fa fa-check"></i></figure>Admin
					</div>		
					
					<div class="fl checkbox<?php echo ($user->active == 1) ? ' checked' : ''; ?>" data-name="active">
						<figure><i class="fa fa-check"></i></figure>Active
					</div>	

					<?php if($user->id > 0) { ?>
					<button class="btn green" data-name="remove-user" style="float: right; margin-top: 20px;">Remove user</button>						
					<?php } ?>					
					<button class="btn green" data-name="save-user" style="margin-top: 20px;">Save</button>	

				</div>		
					
			<?php } ?>

					
		</article>
		
	</div>
		
</div>

<?php require_once("home_footer.php"); ?>
