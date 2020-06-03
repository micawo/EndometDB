<?php

	class EndometDBSQL {
		
		function __construct() {

			$this->open();
		}

		private function open() {

			$this->mysqli = new mysqli(SQL_HOST, SQL_USER, SQL_PASSWORD, SQL_DATABASE);			
			$this->mysqli->set_charset('utf8');						
		}
		
		private function pqopen() {
			
      $this->pq =pg_connect(
        'host=' . ENDOMETDB_HOST . 
        ' dbname=' . ENDOMETDB_DBNAME .
        ' user=' . ENDOMETDB_USER .
        ' password=' . ENDOMETDB_PASSWORD); 
		}		

		protected function get($q) {
			
			if(!isset($this->mysqli)) { $this->open(); }

			$result = $this->mysqli->query($q) or die($this->mysqli->error);			
			return $result;						
		}
		
		protected function esc($str) {
			
			if(!isset($this->mysqli)) { $this->open(); }

			$str = strip_tags($str);						
			$str = (!$this->isUTF8($str)) ? utf8_encode($str) : $str;			
			return mysqli_real_escape_string($this->mysqli, $str);
		}
		
		protected function pquery($str) {
			
			if(!isset($this->pq)) { $this->pqopen(); }
			
			$result = pg_query($this->pq, $str);

			if (!$result) { 

               echo "Problem with query " . $query . "<br/>"; 
               echo pg_last_error(); 
               exit(); 
           } 
						
			return $result; 
		}
		
		protected function pqesc($str) {
			
			if(!isset($this->pq)) { $this->pqopen(); }
			$str = strip_tags($str);						
			$str = (!$this->isUTF8($str)) ? utf8_encode($str) : $str;			
			return pg_escape_string($this->pq, $str);			
			// pg_escape_string
		}
		
		protected function getStatusCount() {
			
			$c = pg_fetch_object($this->pquery("SELECT COUNT(id) AS lkm FROM form_feed_incident"));		
			return $c;
		}
		
		protected function isUTF8($string) {
	
			return preg_match('%^(?:
				  [\x09\x0A\x0D\x20-\x7E]            # ASCII
				| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
				|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
				| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
				|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
				|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
				| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
				|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
			)*$%xs', $string);
					
		}  		
		
		protected function getId($q) {
			
			if(!isset($this->mysqli)) { $this->open(); }

			$this->mysqli->query($q) or die( $this->mysqli->error );				
			return $this->mysqli->insert_id;						
		}

		public function verifyLogin() {
			
			if(!isset($this->mysqli)) { $this->avaa(); }

			$data = new StdClass;
			$data->id = 0;
			$data->admin = 0;
			$data->name = "";

			if(isset($_SESSION["id"]) && ($_SESSION["bus"])) {
				
				if(sha1($_SERVER['HTTP_USER_AGENT'].AGENT_SALT) == $_SESSION["bus"]) {
					
					$user_id = intval($_SESSION["id"]);						
					$users 	 = $this->get("SELECT * FROM users WHERE id = '".$user_id."' AND active = 1");		
									
					while($u = $users->fetch_object()) {
	
						$data = $u;	
					}
				}
			}
			
			return $data;
		}
		
		protected function login() {
			
			$json = new StdClass();
			$json->id = 0;
			$json->error = "";
			
			if(isset($_POST["email"]) && isset($_POST["password"])) {		

				$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
				
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					
					$json->error = "Invalid email.";
					echo json_encode($json);
					exit;
				}
				
				$login = $this->get("SELECT * FROM users WHERE username = '".$this->esc($email)."'");
				
				if(mysqli_num_rows($login) == 1) {
					
					while($l = $login->fetch_object()) {
						
						if(password_verify($_POST["password"], $l->password)) {
													
							$this->createSession($l->id);
							$json->id = $l->id;
							echo json_encode($json);
							exit;
							
						} else {
							
							$json->error = "Unregistered email or wrong password.";
							echo json_encode($json);
							exit;										
						}						
					}
	
				} else {
	
					$json->error = "Unregistered email.";
					echo json_encode($json);
					exit;				
				}
									
			} else {
	
				$json->error = "Type in your email and password.";
				echo json_encode($json);
				exit;				
			}
		}

		protected function sanitize($str) {
			
			return filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_LOW);	
		}

		public function createSession($id) {
	
			session_regenerate_id();
			$_SESSION["id"]  = $id;
			$_SESSION["bus"] = sha1($_SERVER['HTTP_USER_AGENT'].AGENT_SALT);
		}	

		public function logout() {
			
			session_regenerate_id();
			unset($_SESSION["id"]);
			unset($_SESSION["bus"]);		
		}
		
		protected function saveUser() {
			
			if(LOGGED_IN && IS_ADMIN) {
			
				$json = new StdClass();
				$json->status = 0;
				$json->err = array();
				
				if(isset($_POST["id"]) && is_numeric($_POST["id"])) {
					
					$id = intval($_POST["id"]);
					$pw_sql = '';
					$pw = '';
					
					if($_POST["password"] != "" || $_POST["password2"] != "") {
						
						if($_POST["password"] != $_POST["password2"]) {

							$json->err = "Passwords don't match";
							echo json_encode($json);
							exit;							
						
						} else {
							
							$pw = (password_hash($_POST["password"], PASSWORD_BCRYPT));
							$pw_sql = ", password = '".$pw."'";
						}
					}
					
					$name = $this->sanitize($_POST["name"]);					
					$email = filter_var($_POST["username"], FILTER_SANITIZE_EMAIL);
					
					if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
						
						$json->error = "Invalid email.";
						echo json_encode($json);
						exit;
					}
					
					$admin  = (intval($_POST["admin"] == 1) ? 1 : 0);
					$active = (intval($_POST["active"] == 1) ? 1 : 0);
					
					if($id > 0) {

						$u = $this->get("UPDATE users SET name = '".$this->esc($name)."', username = '".$this->esc($email)."', admin = ".$admin.", active = ".$active.$pw_sql." WHERE id = ".$id);

					} else {
						
						if($_POST["password"] == "") {
							
							$json->error = "Give password to new user.";
							echo json_encode($json);
							exit;							
						}
						
						$u = $this->get("INSERT INTO users (id, name, username, password, admin, active) VALUES (NULL, '".$this->esc($name)."', '".$this->esc($email)."', '".$pw."', ".$admin.", ".$active.")");
					}
					
					$json->status = ($u) ? 1 : 0;
					echo json_encode($json);
					exit;					
					
				}  else {
					
					$json->err = 'Network error';
					echo json_encode($json);
					exit;
				}
			
			} else {

				http_response_code(403);
				exit;					
			}
		}	
		
		protected function removeUser() {
			
			$json = new StdClass();
			$json->status = 0;
			
			if(LOGGED_IN && IS_ADMIN) {
			
				if(isset($_POST["id"]) && is_numeric($_POST["id"])) {
					
					$id = intval($_POST["id"]);
					
					if($id > 0) {
						
						$u = $this->get("DELETE FROM users WHERE id = '".$id."'");
					}
					
					$json->status = ($u) ? 1 : 0;
				}
			}
			
			echo json_encode($json);
			exit;
		}										
	}

?>
