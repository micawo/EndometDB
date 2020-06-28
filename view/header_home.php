<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=2.0, user-scalable=1" />
<title>Endomet - Turku Endometriosis Database</title>
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
<body>

	<?php if($_GET["sivu"] == "gene_analysis") { ?>

		<header id="nav">
			<div class="inner wide padding">
				<div class="logo">
					<figure class="logo_img"></figure>
					<h2>Turku Endometriosis Database</h2>
				</div>
      			<a href="<?php echo URL; ?>"><i class="fa fa-home"></i> Home </a>
			</div>
		</header>

	<?php } ?>

	<?php /*<header id="nav">

        <div class="inner">

			<?php

				$selected = array("", "");

				if(isset($_GET["sivu"])) {

					switch($_GET["sivu"]) {

						case "analytics":
							$selected[1] = ' class="selected"';
						break;
	                    default:
	                        $selected[0] = ' class="selected"';
					}

				} else {

					$selected[0] = ' class="selected"';
				}

			?>

			<a href="<?php echo URL; ?>"<?php echo $selected[0]; ?>><i class="fa fa-home"></i> Home </a>
			<a href="<?php echo URL; ?>analytics/"<?php echo $selected[1]; ?>><i class="fa fa-bar-chart"></i> Analytics </a>
			<div class="right">
	        <?php if(LOGGED_IN) { ?>
				<a href="<?php echo URL; ?>logout/"><i class="fa fa-sign-out"></i> Logout </a>
			<?php } else { ?>
				<a href="<?php echo URL; ?>login/"<?php echo $selected[4]; ?>><i class="fa fa-sign-in"></i> Login </a>
			<?php } ?>
			</div>

        </div>

	</header> */ ?>
