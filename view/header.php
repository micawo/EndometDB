<?php if(!defined('LOGGED_IN')) { exit; } ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=2.0, user-scalable=1" />
<title>Endomet - Turku Endometriosis Database</title>
<?php if($_GET["sivu"] == "status") { ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>css/datatables.css" />
<?php } ?>
<script> var logged = true; </script>
<style>
@font-face {
	font-family: "unified";
	src: url("<?php echo URL; ?>fonts/unified.woff") format("woff");
	font-style: normal;
	font-weight: normal;
}
@font-face {
  font-family: 'Open Sans';
  src: url("<?php echo URL; ?>fonts/OpenSans-Light.woff?v=1.1.0") format("woff");
  font-weight: 300;
  font-style: normal;
}
@font-face {
	font-family: 'Open Sans';
	src: url("<?php echo URL; ?>fonts/OpenSans-Regular.woff?v=1.1.0") format("woff");
	font-weight: normal;
	font-style: normal;
}
@font-face {
    font-family: 'Open Sans';
    src: url("<?php echo URL; ?>fonts/OpenSans-Semibold.woff?v=1.1.0") format("woff");
    font-weight: 600;
    font-style: normal;
}
</style>
<link rel="stylesheet" href="<?php echo URL; ?>css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo URL; ?>css/app.min.css" />
<link rel="shortcut icon" href="<?php echo URL; ?>favicon.ico" type="image/x-icon" />
<link rel="icon" href="<?php echo URL; ?>favicon.ico" type="image/x-icon" />
<script>const base_url = "<?php echo URL; ?>";</script>
</head>
<body class="logged">

	<header id="nav">
        <div class="inner wide">
		<a href="<?php echo URL."home/" ?>" style="margin: 0; padding: 0; background: none !important;"><div class="logo">
			<?php /*<h1>End<figure></figure>met</h1>*/ ?>
			<figure class="logo_img"></figure>
			<h2>Turku Endometriosis Database</h2>
		</div></a>

		<?php

			$selected = array("", "", "", "", "", "");

			if(isset($_GET["sivu"])) {

				switch($_GET["sivu"]) {

					case "analytics":
						$selected[1] = ' class="selected"';
					break;
					case "admin": case "user":
						$selected[2] = ' class="selected"';
					break;
					case "patient":
						$selected[3] = ' class="selected"';
					break;
					case "import":
						$selected[4] = ' class="selected"';
					break;
					case "login":
						$selected[5] = ' class="selected"';
					break;
					case "status":
						$selected[6] = ' class="selected"';
					break;
				}

			} else {

				$selected[0] = ' class="selected"';
			}

		?>

		<a href="<?php echo URL; ?>"<?php echo $selected[0]; ?>><i class="fa fa-home"></i> Home </a>
		<?php if($_GET["sivu"] != "login") { ?>
			<a href="<?php echo URL; ?>analytics/"<?php echo $selected[1]; ?>><i class="fa fa-bar-chart"></i> Analytics </a>
			<a href="<?php echo URL; ?>patient/" <?php echo $selected[3]; ?>><i class="fa fa-users"></i> Patients </a>
			<a href="#" <?php echo $selected[4]; ?>><i class="fa fa-upload"></i> Import </a>
			<a href="<?php echo URL; ?>status/" <?php echo $selected[6]; ?>><i class="fa fa-exclamation-circle"></i> Status </a>
		<?php } ?>
		<div class="right">
		<?php if(LOGGED_IN) { ?>
			<a href="#" id="user_menu_link"><i class="fa fa-user"></i><?php echo NAME; ?></a>
			<?php /*<a href="<?php echo URL; ?>logout/"><i class="fa fa-sign-out"></i> Logout </a> */ ?>
			<div id="user_menu">
				<a href="<?php echo URL; ?>admin/user/<?php echo USER_ID; ?>/"><i class="fa fa-cog"></i> User settings </a>
				<?php if(LOGGED_IN && IS_ADMIN) { ?>
				<a href="<?php echo URL; ?>admin/"<?php echo $selected[2]; ?>><i class="fa fa-lock"></i> Admin </a>
				<?php } ?>
				<a href="<?php echo URL; ?>logout/"><i class="fa fa-sign-out"></i> Logout </a>
			</div>
		<?php } else { ?>
			<a href="<?php echo URL; ?>login/"<?php echo $selected[5]; ?>><i class="fa fa-sign-in"></i> Login </a>
		<?php } ?>
		</div>

	</div>

	</header>
