<?php

session_start();

require_once("mgmt/sql.php");
require_once("include/constants.php");

class EndometDB extends EndometDBSQL {

	function __construct() {

		if(isset($_GET["sivu"])) {

			switch($_GET["sivu"]) {

				case "api":
					require_once(MGMT."api.php");
					new EndometDBAPI();
				break;
				case "analytics":
					require_once(MGMT."analysis.php");
					$analysis = new EndometDBAnalysis();
					$analysis->render();
				break;
				case "patient":
					require_once(MGMT."patient.php");
					$patient = new EndometDBPatient();
					$patient->render();
				break;
				case "admin":
					require_once(MGMT."admin.php");
					$admin = new EndometDBAdmin();
				break;
				case "status":
					require_once(MGMT."status.php");
					$status = new EndometDBStatus();
					$status->render();
				break;
				case "logout":
					$this->logout();
					header('Location: '.URL);
				break;
				case "login":
					require_once(VIEWS."login.php");
				break;
				case "home":
					require_once(VIEWS."home.php");
				break;
				default:
					(LOGGED_IN) ? require_once(VIEWS."home_logged.php") : require_once(VIEWS."home.php");
			}

		} else {

			(LOGGED_IN) ? require_once(VIEWS."home_logged.php") : require_once(VIEWS."home.php");
		}
	}
}

new EndometDB();

?>
