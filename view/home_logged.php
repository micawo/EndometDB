<?php if(!defined('LOGGED_IN')) { exit; } ?>
<?php require_once("header.php"); ?>

<div id="content">
	
	<div class="inner">
		
		<header class="no_padding">
			<h1> Turku Endometriosis Database </h1>							
		</header>

		<article class="content main">
            
			<div class="spinParticleContainer">
				<div class="particle red"></div>
				<div class="particle grey other-particle"></div>
				<div class="particle blue other-other-particle"></div>
			</div>
				
			<div id="statistics" style="display: none;">
			
	            <div class="block_area">
	                
	                <div class="block">
	                                    
	                    <div class="block_inner">
	                    
	                        <h1>Patients / controls</h1>
	                        
	                        <div class="donut-chart" data-name="patient">  
	                            <div class="info hide"></div>
	                            <div class="donuts"></div>                   
	                        </div>
	                        
	                        <div class="bullets_wrapper">
	                            <ul class="bullets"></ul>	                        
	                        </div>
	                        
	                        <div class="clear"></div><p class="havainnot_total" style="margin: 20px 0 0 0;">Total Patients: <b>2247</b>, Total Samples: <b>19847</b></p>
	                        
	                    </div>
	                    
	                </div>
	                
	                <!-- -->

	                <div class="block">
	                                    
	                    <div class="block_inner">
	                    
	                        <h1>Samples by tissue type</h1>
	                        
	                        <div class="donut-chart" data-name="tissue">
								<div class="info hide"></div>
	                            <div class="donuts"></div>           
	                        </div>
	                        
	                        <div class="bullets_wrapper">
	                        	<ul class="bullets"></ul
	                        </div>
	                        
	                    </div>
	                    
	                </div>
	                
	            </div>
			
			</div>
			
			

		</article>
		
	</div>
		
</div>

<?php require_once("home_footer.php"); ?>
