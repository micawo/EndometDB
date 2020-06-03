<?php 

	class EndometDBAdmin extends EndometDBSQL {
		
		function __construct() {
			
			if(IS_LOGGED && IS_ADMIN) {
				
				require_once(VIEWS."admin.php");
				
			} else {

				http_response_code(403);
				exit;				
			}
		}
		
		private function getUsers() {
				
			$html = '<table class="table">
						<thead>
							<tr>
								<th> Name </th>
								<th> Email </th>
								<th> Admin </th>
								<th> Active </th>
								<th></th>
							</tr><tbody>';	
		
			
			$users = $this->get("SELECT * FROM users");
		
			while($u = $users->fetch_object()) {
				
				$html .= '<tr>
							<td data-id="'.$u->id.'" data-admin="'.$u->admin.'" data-active="'.$u->active.'">'.$u->name.'</td>
							<td>'.$u->username.'</td>
							<td>'.(($u->admin == 1) ? 'yes' : 'no').'</td>
							<td>'.(($u->active == 1) ? 'yes' : 'no').'</td>
							<td style="text-align: right;"><a href="'.URL.'admin/user/'.$u->id.'/"><button class="btn green">Edit</button></a></td>
						  </tr>';
			}
			
			return $html.'</tbody></table>';
		}
		
		private function getUser() {
			
			$res = new StdClass();
			$res->id = 0;
			
			if(isset($_GET["id"])) {
				
				$id = intval($_GET["id"]);
				$user = $this->get("SELECT * FROM users WHERE id = '".$id."'");
				
				while($u = $user->fetch_object()) {
					
					$res = $u;
				}
			}
			
			return $res;
		}
	}

?>
