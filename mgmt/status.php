<?php 

class EndometDBStatus extends EndometDBSQL {

	function __construct() {}

    public function render() {
        
        if(LOGGED_IN) {
            
            require_once(VIEWS."status.php");
        }
    }  
    
    private function getIncidents() {
        
        $inc = $this->pquery("SELECT source, patient_code, message, entry_date FROM form_feed_incident ORDER BY message, entry_date");
                
        $html = '<table id="status_table">
                    <thead>
                        <tr>
                            <th>type</th>
                            <th>date</th>
                            <th>source</th>
                            <th>message</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody> ';        
        
        $wbuttons = '';
        $wobuttons = '';
        $rest = '';
                
        while ($i = pg_fetch_object($inc)) {
            
            $status = "yellow";
        
            $message = trim(strtolower(preg_replace('/\s+/', ' ', $i->message)));
            $code = trim(preg_replace('/\s+/', ' ', $i->patient_code));
            $msg = $i->message;
            $button = '';
            
			$code_2 = preg_replace("/[^ \w]+/", "", $code);
			
            $add = true;
            $wb = false;
            $wo = false;
                        
            if($message == "unknown patient code") {

                $wo = true;

                if($code != "form has no patient code") {
                    
                    $p = $this->pquery("SELECT id FROM patient WHERE patient_code = '".$code."'");
                    
                    $msg .= '<br /><b>Patient code:</b> '.$code;
                    
                    if(pg_num_rows($p) == 0) {
                        
                        $button = '<a href="'.URL.'patient/new/'.$code_2.'/"><button class="btn black">Add '.$code.'</button></a>';
                        $wb = true;         
                        $wo = false;
                         
                    } else {

                        $add = false;    
                    }
                    
                } else {
                    
                    $status = "red";
                    $msg .= '<br /><b>Patient code:</b> '.$code;                    
                }               
            }
            
            $htm = '<tr class="'.$status.'">
                        <td>'.(($status == "yellow") ? 'warning' : 'error').'</td>
                        <td>'.$i->entry_date.'</td>
                        <td>'.$i->source.'</td>
                        <td>'.$msg.'</td>
                        <td>'.$button.'</td>
                    </tr>';
                
            if($add) {        

                if($wb) {

                    $wbuttons .= $htm;
                    
                } else if($wo) {
                    
                    $wobuttons .= $htm;
                    
                } else {
                    
                    $rest .= $htm;
                }
            }
        }
        
        return $html.$wbuttons.$wobuttons.$rest.'</tbody></table>';        
    }  
} 

?>
