<?php if(!defined('LOGGED_IN')) { exit; } ?>
<?php require_once("header.php"); ?>

<?php $forms = $this->drawForms(); ?>

<div class="patient_arrows disabled">
	
	<div class="arrow"></div>
	<div class="arrow right"></div>
	
</div>

<div class="patient_tabs">
	<div class="filter_tabs"></div>
	<div class="filter_tabs">
        <?php echo $forms->tabs; ?>						
	</div>		
</div>	

<div id="wrapper">

	<section id="filters" class="loading">
		
		<div class="filters_inner">

			<div class="filter open" data-name="patient">					
				<header class="filter-header">
					<div class="filter-toggle"></div>				
					<h3 class="filter-title">Select Patient</h3>
				</header>
				<div class="content"><select data-name="patient"></select></div>		
			</div>

			<div class="filter open" data-name="physician">					
				<header class="filter-header">
					<div class="filter-toggle"></div>				
					<h3 class="filter-title">Select Physician</h3>
				</header>
				<div class="content"><select></select></div>		
			</div>			
            
			<button class="btn green icon left fa-plus" data-name="add_patient"> Add patient </button>
			
        </div>
        
    </section>
    
    <?php echo $forms->html; ?>

</div>

<?php require_once("footer.php"); ?>
