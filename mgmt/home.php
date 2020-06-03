<?php 

	class EndometDBHome {
		
		function __construct() {
			
			if(isset($_GET["osio"])) {
				
				

			} else {
				
				require_once(VIEWS."home.php");
			}
		}
	}

?>
