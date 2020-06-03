<?php if(!defined('LOGGED_IN')) { exit; } ?>
<?php require_once("header.php"); ?>

<div id="content">
	
	<div class="inner">
		
		<header class="no_padding">
			<h1 style="margin-bottom: 20px;"> System status overview </h1>							
		</header>

		<article class="content main">
                
            <?php echo $this->getIncidents(); ?>                
    
		</article>
		
	</div>
		
</div>

<?php require_once("home_footer.php"); ?>
