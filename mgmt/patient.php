<?php 

class EndometDBPatient extends EndometDBSQL {
	
	function __construct() {}
    
    public function render() {
        
        if(LOGGED_IN) {
            
            require_once(VIEWS."patient.php");
        }
    }
    
    public function drawForms() {
        
        $form = json_decode(file_get_contents(JSON."patient.json"));	
		$html = '';
        $tabs = '';
        $res = new StdClass();
        
		$new_patient = ($_GET["osio"]) ? ' loading' : ' run_again';
		$np_tab = ($_GET["osio"] == "new") ? ' disabled' : '';
		
        foreach($form as $k => $f) {
            
            $tabs .= '<div class="tab'.(($k == 0) ? ' selected' : $np_tab).'" data-target="'.$f->type.'"><i class="fa '.$f->icon.'"></i><span>'.$f->title.'</span></div>';
            
            $html .= '<section class="patient_section'.(($k > 0) ? ' hide run_again' : $new_patient).'" data-type="'.$f->type.'">
						<div class="spinParticleContainer">
							<div class="particle red"></div>
							<div class="particle grey other-particle"></div>
							<div class="particle blue other-other-particle"></div>
						</div>	
						
						<div class="stillSpinParticleContainer">
							<div class="particle red"></div>
							<div class="particle grey"></div>
							<div class="particle blue"></div>
						</div>		
                        <div class="patient_inner">
                        <div class="input-area one patient_id">
                            <div class="input">
                                <label> Patient ID </label>
                                <input type="text" class="patient_id_input" value="0" disabled />
                            </div>
                        </div>';            
            
            if($f->cloner) {
                
                $html .= '<button class="btn green add_clone" data-type="'.$f->type.'">'.$f->cloner_button.'</button>';
                $html .= '<div class="clone"><button class="btn icon fa-times remove_clone"></button>';
            }
                        
            foreach($f->form as $fm) {
                
                $html .= '<div class="input-area '.$fm->class.'">';
                
                foreach($fm->inputs as $fmi) {
                    
                    if($fmi->type == "button") {
                        
                        $html .= '<button class="btn green" data-name="'.$fmi->name.'">'.$fmi->title.'</button>';
                        
                    } else if($fmi->type == "select") {
                    	
						$opt = '';
						
						foreach($fmi->options as $op) {
							
							$opt .= '<option value="'.$op->value.'">'.$op->title.'</option>';
						}
						
                        $html .= '<div class="input">
                                    <label>'.$fmi->title.'</label>
                                    <select name="'.$fmi->name.'">'.$opt.'</select>
                                  </div>';
								  
                    } else if($fmi->type == "date") {
					
						$html .= '<div class="input">
                                    <label>'.$fmi->title.'</label>
                                    <input type="text" class="datepicker" name="'.$fmi->name.'" />
                                  </div>'; 					
						
					} else if($fmi->type == "title") {
					
						$html .= '<div class="input">
                                    <label class="title">'.$fmi->title.'</label>
                                  </div>'; 					
						
					} else if($fmi->type == "checkbox") {

						$html .= '<div class="input">
                                    <label>'.$fmi->title.'</label>
									<div class="fl  checkbox" data-name="'.$fmi->name.'">
										<figure><i class="fa fa-check"></i></figure>
									</div>
                                  </div>'; 		

						
					} else {

                        $html .= '<div class="input">
                                    <label>'.$fmi->title.'</label>
                                    <input type="'.$fmi->type.'" name="'.$fmi->name.'" />
                                  </div>';                        
                    }
                }
                
                $html .= '</div>';            
            }
            
            if($f->cloner) {

                $html .= '<button class="btn green icon left fa-floppy-o save_clone disabled">Save</button></div>';
            }                
        
            $html .= '</div></section>';
        }
        
        $res->html = $html;
        $res->tabs = $tabs;
        
        return $res;
    }
    
    public function getPatientData() {

		$res = new StdClass();
		$res->physicians = array();
		$res->samples = new StdClass();
		$res->histology = new StdClass();
        
		// Physicians & Patients
		
        $hospital_persons = $this->pquery("SELECT A.id, B.forename, B.surname FROM hospital_person A, person B WHERE B.id = A.person_id ORDER BY B.surname ASC");        		
		
        while ($hp = pg_fetch_object($hospital_persons)) {
            
            $hp->patients = array();
            $pt = $this->pquery("SELECT B.patient_code FROM patient_hospital_person A, patient B WHERE A.patient_id = B.id AND A.hospital_person_id = ".$hp->id." ORDER BY patient_code ASC");
            
            while ($p = pg_fetch_object($pt)) {
                
                $hp->patients[] = $p->patient_code;    
            }
            
            $res->physicians[] = $hp;
        }
		
			
		// Sample data
		
		$res->samples->sample = array();
		$res->samples->time_until_frozen = array();
		$res->menstrual_cycle_length = array();
		$res->samples->medium = explode(",", 'Formaldehyde,Bouin,Acetone,Methanol');
		$res->samples->vials = array(1,2,3,4,5,6,7,8,9);
		
		$samples = $this->pquery("SELECT id, title_en, code_type FROM code_value WHERE code_type IN ('tissue_type', 'time_until_frozen', 'menstrual_cycle_length')");
        
		while ($s = pg_fetch_object($samples)) {
			
			if($s->code_type == 'time_until_frozen') {
				
				$res->samples->time_until_frozen[] = $s;
				
			} else if($s->code_type == 'menstrual_cycle_length') {
				
				$res->menstrual_cycle_length[] = $s;
			
			} else {
				
				$res->samples->sample[] = $s;
			}
		}
		
		/* Histology data */
		
		$res->histology->subclass = array();
		$res->histology->phase = array();
		$res->histology->class = array();
		$res->histology->lesion = array();
		
		$histology = $this->pquery("SELECT id, title_en, code_type FROM code_value WHERE code_type IN ('histology_subclass', 'histology_phase', 'histology_class', 'lesion_histopathology')");

		while ($h = pg_fetch_object($histology)) {
			
			if($h->code_type == 'histology_subclass') {
				
				$res->histology->subclass[] = $h;
				
			} else if($h->code_type == 'histology_phase') {
				
				$res->histology->phase[] = $h;
			
			} else if($h->code_type == 'histology_class') {
				
				$res->histology->class[] = $h;
			
			}  else {
				
				$res->histology->lesion[] = $h;
			}
		}
		
        echo json_encode($res);
        exit;
    }
	
	public function getPatient() {
		
		$patient_id = preg_replace("/[^ \w]+/", "", $_POST["patient_id"]);
		
		if($patient_id != "") {
			
			$res = new StdClass();
			
			$patient = pg_fetch_object($this->pquery("SELECT * FROM patient WHERE patient_code = '".$this->pqesc($patient_id)."'")); 
			$survey = pg_fetch_object($this->pquery("SELECT * FROM patient_survey WHERE patient_id = '".$patient->id."'"));
			$samples = $this->pquery("SELECT A.*, B.title_en FROM patient_sample A, code_value B WHERE A.patient_id = ".$patient->id." AND A.code_tissue_type_id = B.id");
		
			$res = $patient;
			$res->samples = array();
			$res->histology = array();
			$res->biomarkers = array();			
			
			while ($s = pg_fetch_object($samples)) {
                
                $s->vials = ($s->vials === NULL) ? 0 : $s->vials;
				$res->samples[] = $s;
				
            }
			
			//unset($survey->patient_id);
			//unset($res->id);
			$res->survey = $survey;			
			
			/* Histology */
			
			$histology = $this->pquery("SELECT A.code_tissue_type_id, B.*, C.value FROM patient_sample A, pathology_report_endometrium B, code_value C 
										WHERE A.sample_id = B.patient_sample_id AND A.code_tissue_type_id = C.id AND A.patient_id = ".$patient->id);
							
			
			while ($h = pg_fetch_object($histology)) {
                
                $res->histology[] = $h;
            }			
			
			/* Biomarkers / Analytes */
			
			/*$bio = $this->pquery("SELECT A.*, B.code_tissue_type_id, C.sample_id FROM analyte A, patient_sample B, sample_analysis_result C
										WHERE B.sample_id = C.sample_id AND A.sample_analysis_result_id = C.sample_id AND B.patient_id = ".$patient->id);
							
			
			$ids = array();
			$res->asd = array();
			while ($b = pg_fetch_object($bio)) {
                
				if(!in_array($b->id, $ids)) {
					
					$ids[] = $b->id;					
                	$res->biomarkers[] = $b;						
				}
			}*/
			
			$bio_ids = $this->pquery("SELECT A.*, B.code_tissue_type_id FROM sample_analysis_result A, patient_sample B WHERE A.sample_id = B.sample_id AND B.patient_id = '".$patient->id."'");

			while ($b = pg_fetch_object($bio_ids)) {
				
				$analyte = pg_fetch_object($this->pquery("SELECT * FROM analyte WHERE sample_analysis_result_id = ".$b->id));
				$analyte->code_tissue_type_id = $b->code_tissue_type_id;
            	$res->biomarkers[] = $analyte;						

			}
						
			echo json_encode($res);
			exit;
		}
	}
	
	public function savePatient() {
		
		print_r($_POST);
		exit;
		
		$type = $_POST["type"];
		
		if($type == "patient") {
			
		} else if($type == "sample") {
			
		} else if($type == "histology") {
			
		} else if($type == "biomarker") {
			
		} else if($type == "visit") {
			
		} else {

			http_response_code(403);
			exit;			
		}
	}    
}

?>
