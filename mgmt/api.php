<?php

	require(INCLUDE_DIR."rserve-php-2.0/src/autoload.php");
	use Sentiweb\Rserve\Connection;
	use Sentiweb\Rserve\Parser\NativeArray;

	class EndometDBAPI extends EndometDBSQL {

		function __construct() {

			if($_GET["sivu"] == "testr") {

				$this->getRGraph();

			} else {

				if(isset($_GET["osio"])) {

					switch($_GET["osio"]) {

						case "options":
							if(IS_LOGGED) {

								require_once(MGMT.'analysis.php');
								$analysis = new EndometDBAnalysis();
								$analysis->generateTracesOptions($_POST["data"]);
							}
						break;
						case "getgraph":
							if(IS_LOGGED) {
								$this->getRGraph();
							}
						break;
						case "getpdfgraph":
							if(IS_LOGGED) {
								$this->getPDFGraph();
							}
						break;
						case "pdf":
							$this->getPDF();
						break;
						case "getsymbols":
							$this->getsymbols();
						break;

						case "getRGraph":
							$this->getRGraph();
						break;
						case "getstatistics":
							$this->getStatistics();
						break;
						case "save_user":
							$this->saveUser();
						break;
						case "remove_user":
							$this->removeUser();
						break;
						case "login":
							$this->login();
						break;

						/* Patient */

						case "getPatientData":
							require_once(MGMT."patient.php");
							$patient = new EndometDBPatient();
							$patient->getPatientData();
						break;
						case "getPatient":
							require_once(MGMT."patient.php");
							$patient = new EndometDBPatient();
							$patient->getPatient();
						break;
						case "savePatient":
							require_once(MGMT."patient.php");
							$patient = new EndometDBPatient();
							$patient->savePatient();
						break;

						/* No match */

						default:
							http_response_code(403);
							exit;
					}

				} else {

					http_response_code(403);
					exit;
				}
			}
		}

		/* Main page statistics */

		private function getStatistics() {

			$colorarr = array('green', 'red', 'orange', 'blue', 'yellow', 'brown', 'pink', 'gray', 'cyan', 'purple');

			/*$stats = new StdClass();
			$stats->patient = array();
			$stats->tissue = array();
			$stats->total = new StdClass();
			$stats->total->samples = 40;

			$samples = pg_fetch_object($this->pquery("SELECT COUNT(*) AS samples FROM public.patient_sample"));
			$patients = pg_fetch_object($this->pquery("SELECT COUNT(*) AS patients FROM public.patient"));

			$stats->total->samples = $samples->samples;
			$stats->total->patients = $patients->patients;

			$patients_p = pg_fetch_object($this->pquery("SELECT COUNT(*) AS c FROM patient WHERE patient.code_patient_category_id = 266"));
			$patients_c = pg_fetch_object($this->pquery("SELECT COUNT(*) AS c FROM patient WHERE patient.code_patient_category_id = 267"));
			$patients_t = intval($patients_p->c) + intval($patients_c->c);

			$t = new StdClass();
			$t->percent = round(($patients_p->c / $patients_t) * 100);
			$t->color = $colorarr[2];
			$t->info = "Patients (".$patients_p->c.")";
			$t->text = '<b>'.$patients_p->c.'</b> Patients';
			$stats->patient[]= $t;

			$t = new StdClass();
			$t->percent = round(($patients_c->c / $patients_t) * 100);
			$t->color = $colorarr[0];
			$t->info = "Controls (".$patients_c->c.")";
			$t->text = '<b>'.$patients_c->c.'</b> Controls';
			$stats->patient[]= $t;

			$tissues = pg_fetch_object($this->pquery("SELECT COUNT(*) as total,
			SUM(CASE WHEN patient_sample.code_tissue_type_id = 366 THEN 1 ELSE 0 END) AS blood_serum,
			SUM(CASE WHEN patient_sample.code_tissue_type_id = 367 THEN 1 ELSE 0 END) AS blood_edta,
			SUM(CASE WHEN patient_sample.code_tissue_type_id = 368 THEN 1 ELSE 0 END) AS endometrium_tissue,
			SUM(CASE WHEN patient_sample.code_tissue_type_id = 369 THEN 1 ELSE 0 END) AS peritoneal_flush,
			SUM(CASE WHEN patient_sample.code_tissue_type_id = 370 THEN 1 ELSE 0 END) AS peritoneum_tissue,
			SUM(CASE WHEN patient_sample.code_tissue_type_id IN (371, 372, 373) THEN 1 ELSE 0 END) AS red_peritoneal,
			SUM(CASE WHEN patient_sample.code_tissue_type_id IN (374, 375, 376) THEN 1 ELSE 0 END) AS black_peritoneal,
			SUM(CASE WHEN patient_sample.code_tissue_type_id IN (377, 378, 379) THEN 1 ELSE 0 END) AS white_peritoneal,
			SUM(CASE WHEN patient_sample.code_tissue_type_id IN (380, 381, 382) THEN 1 ELSE 0 END) AS ovarian,
			SUM(CASE WHEN patient_sample.code_tissue_type_id IN (383, 384, 385, 386, 387, 388, 389, 390, 391) THEN 1 ELSE 0 END) AS deep FROM patient_sample"));

			$t_title = [
				"blood_serum" => 'Blood Serum',
				"blood_edta" => 'Blood EDTA',
				"endometrium_tissue" => 'Endometrium',
				"peritoneal_flush" => 'Peritoneal Flush',
				"peritoneum_tissue" => 'Peritoneum',
				"red_peritoneal" => 'Red Peritoneal Endometriosis',
				"black_peritoneal" => 'Black Peritoneal Endometriosis',
				"white_peritoneal" => 'White Peritoneal Endometriosis',
				"ovarian" => 'Ovarian Endometrioma',
				"deep" => 'Deep Endometriosis',
			];

			$i = 0;

			foreach($t_title as $k => $v) {

				if(!isset($colorarr[$i])) { $i = 0; }
				$t = new StdClass();
				$t->percent = round(($tissues->{$k} / $tissues->total) * 100);;
				$t->color = $colorarr[$i];
				$t->info = $v." (".$tissues->{$k}.")";
				$t->text = '<b>'.$tissues->{$k}.'</b> '.$v;
				$stats->tissue[] = $t;
				$i += 1;
			}

			echo json_encode($stats);
			exit;*/

			$statistics = json_decode(file_get_contents(JSON."statistics.json"));
			$data = array();

			foreach($statistics as $st) {

				$tot = 0;
				$obj = new StdClass();
				$obj->title = $st->title;
				$obj->data = array();
				$i = 0;

				if(isset($st->data)) {

					foreach($st->data as $d) {

						$tot += $d->value;
					}

					foreach($st->data as $d) {

						if(!isset($colorarr[$i])) { $i = 0; }
						$t = new StdClass();
						$t->percent = round(($d->value / $tot) * 100);
						$t->color = $colorarr[$i];
						$t->info = $d->title." (".$d->value.")";
						$t->text = '<b>'.$d->value.'</b> '.$d->title;
						$obj->data[] = $t;
						$i++;
					}
				}

				$data[] = $obj;
			}

			echo json_encode($data);
			exit;
		}

		/* Get symbols for analysis */

		private function getsymbols() {

			if(!file_exists(ROOT.'mgmt/genes.json')) {

				$gene_data = $this->pquery("SELECT DISTINCT symbol FROM normalized_microarray_data");

				$opts = array();
				$data = new StdClass();
				$data->genes = array();

				while ($gd = pg_fetch_object($gene_data)) {

					$opts[] = $gd->symbol;
				}

				$myfile = fopen(ROOT."mgmt/genes.json", "w");
				fwrite($myfile, json_encode($opts));
				fclose($myfile);

			} else {

				$opts = file_get_contents(ROOT."mgmt/genes.json");
				$opts = json_decode($opts);
			}

			$data->genes = $opts;
			$data->cytokines = explode(", ", 'IL-1b, IL-2, IL-1ra, IL-4, IL-5, EGF, IL-6, IL-7, TGFa, Fractalkine, IL-8, IL-10, IL-12p70, IL-13, IL-15, IL-17, IL-1a, IFNg, G-CSF, GM-CSF, TNFa, Eotaxin, MCP-1, sCD40L, IL-12p40, MIP-1a, MIP-1b, IP-10, VEGF');
			$data->hormone_concentration = explode(", ", 'Estrone, 17-OH-pregnenolone, DHEA, 17-OH-progesterone, Androstenedione, Testosterone, Androstenedione, DHT, Pregnenolone, Progesterone, Estradiol, LH, FSH, Cortisol, SHBG');
			$data->biomarkers = explode(", ", 'CA125, HE4, MYH11, CNN1, PDLIM3, ASRGL1, CAPS, C20orf103, SH3BGRL, BGN, CSRP1, EMILIN1, FLNA, PRELP, MMP11, C20orf85, DLX5, TRH, ACTN1, MDK, SFRP2, TGFB1');
			$data->metabolomics = new StdClass();
			$data->metabolomics->acylcarnitines = explode(", ", 'C0, C10, C10:1, C10:2, C12, C12-DC, C12:1, C14, C14:1, C14:1-OH, C14:2, C14:2-OH, C16, C16-OH, C16:1, C16:1-OH, C16:2, C16:2-OH, C18, C18:1, C18:1-OH, C18:2, C2, C3, C3-DC (C4-OH), C3-OH, C3:1, C4, C4:1, C5, C5-DC (C6-OH), C5-M-DC, C5-OH (C3-DC-M), C5:1, C5:1-DC, C6 (C4:1-DC), C6:1, C7-DC, C8, C8:1, C9');
			$data->metabolomics->amino_acids = explode(", ", 'Arg, Gln, Gly, His, Met, Orn, Phe, Pro, Ser, Thr, Trp, Tyr, Val, xLeu');
			$data->metabolomics->glycerophospholipids = explode(", ", 'lysoPC a C14:0, lysoPC a C16:0, lysoPC a C16:1, lysoPC a C17:0, lysoPC a C18:0, lysoPC a C18:1, lysoPC a C18:2, lysoPC a C20:3, lysoPC a C20:4, lysoPC a C24:0, lysoPC a C26:0, lysoPC a C26:1, lysoPC a C28:0, lysoPC a C28:1, lysoPC a C6:0, PC aa C24:0, PC aa C26:0, PC aa C28:1, PC aa C30:0, PC aa C30:2, PC aa C32:0, PC aa C32:1, PC aa C32:2, PC aa C32:3, PC aa C34:1, PC aa C34:2, PC aa C34:3, PC aa C34:4, PC aa C36:0, PC aa C36:1, PC aa C36:2, PC aa C36:3, PC aa C36:4, PC aa C36:5, PC aa C36:6, PC aa C38:0, PC aa C38:1, PC aa C38:3, PC aa C38:4, PC aa C38:5, PC aa C38:6, PC aa C40:1, PC aa C40:2, PC aa C40:3, PC aa C40:4, PC aa C40:5, PC aa C40:6, PC aa C42:0, PC aa C42:1, PC aa C42:2, PC aa C42:4, PC aa C42:5, PC aa C42:6, PC ae C30:0, PC ae C30:1, PC ae C30:2, PC ae C32:1, PC ae C32:2, PC ae C34:0, PC ae C34:1, PC ae C34:2, PC ae C34:3, PC ae C36:0, PC ae C36:1, PC ae C36:2, PC ae C36:3, PC ae C36:4, PC ae C36:5, PC ae C38:0, PC ae C38:1, PC ae C38:2, PC ae C38:3, PC ae C38:4, PC ae C38:5, PC ae C38:6, PC ae C40:0, PC ae C40:1, PC ae C40:2, PC ae C40:3, PC ae C40:4, PC ae C40:5, PC ae C40:6, PC ae C42:0, PC ae C42:1, PC ae C42:2, PC ae C42:3, PC ae C42:4, PC ae C42:5, PC ae C44:3, PC ae C44:4, PC ae C44:5, PC ae C44:6');
			$data->metabolomics->sphingolipids = explode(", ", 'SM (OH) C14:1, SM (OH) C16:1, SM (OH) C22:1, SM (OH) C22:2, SM (OH) C24:1, SM C16:0, SM C16:1, SM C18:0, SM C18:1, SM C20:2, SM C22:3, SM C24:0, SM C24:1, SM C26:0, SM C26:1');
			echo json_encode($data);
			exit;
		}

		private function buildBoolean($n) {

			return ($n) ? "TRUE" : "FALSE";
		}

		private function getRGraph() {

			//ini_set('display_errors', 0);

			$data = json_decode($_POST["data"]);
			$rparams = "process(".$this->buildRObject($data).")";

			$myfile = fopen(ROOT."mgmt/testfile3.txt", "w");
			fwrite($myfile, print_r($rparams, true));
			fclose($myfile);

			//$rparams = $rparams;
			//require_once(INCLUDE_DIR.'rserve-php/Connection.php');
			//$cnx = new Rserve_Connection(RSERVE_HOST);

			$cnx = new Connection(RSERVE_HOST, RSERVE_PORT);
			$result = $cnx->evalString($rparams);

			$myfile = fopen(ROOT."mgmt/testfile2.txt", "w");
			fwrite($myfile, print_r($result, true));
			fclose($myfile);

			if(is_array($result)) { // Jos monta plotsia

				$res = array();

				foreach($result as $k => $r) {

					if(is_array($r)) { // UUtta plotsit tabeihin 7.3.2018

						$nres = new StdClass();
						$nres->nimi = $k;
						$nres->plots = array();
						$nres->asd = array();

						foreach($r as $p) {

							$ext = pathinfo($p, PATHINFO_EXTENSION);

							if($ext == "html") {

								$doc = new DOMDocument();
								$doc->loadHTML(file_get_contents($p));
								$scripts = $doc->getElementsByTagName('script');

								$myfile = fopen(ROOT."mgmt/testfile3.txt", "w");
								fwrite($myfile, file_get_contents($p));
								fclose($myfile);

								foreach ($scripts as $k => $sc) {

									if(substr($sc->nodeValue, 0, 3) == '{"x') {

										$n = json_decode($sc->nodeValue);
										$dres = new StdClass();
										$dres->data = $n->x->data;
										$dres->layout = $n->x->layout;
										$nres->plots[] = $dres;
									}
								}
							}
						}

						$res[] = $nres;

					} else if(is_string($r)) { // Vanha tyyli, luupataan html tiedostot läpi

						// eio_npending
						$ext = pathinfo($r, PATHINFO_EXTENSION);

						if($ext == "html") {

							$doc = new DOMDocument();
							$doc->loadHTML(file_get_contents($r));
							$scripts = $doc->getElementsByTagName('script');

							foreach ($scripts as $k => $sc) {

								if(substr($sc->nodeValue, 0, 3) == '{"x') {

									$n = json_decode($sc->nodeValue);
									$dres = new StdClass();
									$dres->data = $n->x->data;
									$dres->layout = $n->x->layout;
									$res[] = $dres;
								}
							}

						} else if($ext == "png") {

							$pres = new StdClass();
							$pres->type = "png";
							$png = file_get_contents($r);
							$pres->img = base64_encode($png);
							$res[] = $pres;
						}

					} else { // Vanhin tyyli, palautetaan suora plotly data, ei enää käytössä

						$res[] = $this->parseResults($r);
					}
				}

			} else if(is_string($result)) { // Jos vain yksi plotly

				$ext = pathinfo($result, PATHINFO_EXTENSION);

				if($ext == "html") { // Vanha tyyli, luupataan html tiedostot läpi

					$doc = new DOMDocument();
					$doc->loadHTML(file_get_contents($result));
					$scripts = $doc->getElementsByTagName('script');

					foreach ($scripts as $k => $sc) {

						if(substr($sc->nodeValue, 0, 3) == '{"x') {

							$n = json_decode($sc->nodeValue);
							$res = new StdClass();
							$res->data = $n->x->data;
							$res->layout = $n->x->layout;
							$res->type = "json";
						}
					}

				} else if($ext == "png") {

					$res = new StdClass();
					$res->type = "png";
					$png = file_get_contents($result);
					$res->img = base64_encode($png);
				}

			} else { // Vanhin tyyli, palautetaan suora plotly data, ei enää käytössä

				$res = $this->parseResults(array_values($result)[0]);
				$res->type = "json";
			}

			$myfile = fopen(ROOT."mgmt/testfile.txt", "w");
			fwrite($myfile, print_r($res, true));
			fclose($myfile);

			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($res);
			exit;
		}

		private function getPDFGraph() {

			$data = json_decode($_POST["data"]);
			$rparams = "process(".$this->buildRObject($data).")";

			$cnx = new Connection(RSERVE_HOST, RSERVE_PORT);
			$result = $cnx->evalString($rparams);

			$myfile = fopen(ROOT."mgmt/testfile.txt", "w");
			fwrite($myfile, $result);
			fclose($myfile);

			$name = basename($result);
			$gname = basename($result, ".pdf");
			@copy($result, ROOT.'tmp/'.$name);

			$res = new StdClass();
			$res->id = $gname;

			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($res);
			exit;
		}

		private function getPDF() {

			if(isset($_GET["id"])) {

				$id = preg_replace('~[^a-zA-Z0-9]+~', '', $_GET["id"]);
				$file = ROOT.'tmp/'.$id.".pdf";

				if(file_exists($file)) {

					header('Content-type: application/pdf');
					header('Content-Disposition: attachment; filename="'.$id.'.pdf"');
					header('Content-Transfer-Encoding: binary');
					header('Content-Length: ' . filesize($file));
					@readfile($file);
				}

				unlink($file);
				exit;
			}
		}

		private function parseResults($result) {

			for($i = 0; $i < count($result["x"]["data"]); $i++) {

				unset($result["x"]["data"][$i]["frame"]);
			}

			if(isset($result["x"]["layoutAttrs"])) {

				$layoutAttrs = array_values($result["x"]["layoutAttrs"])[0];
				$mergedLayout = $this->formatData($result["x"]["layout"], $layoutAttrs);
				$result["x"]["layout"] = $mergedLayout;
				unset($result["x"]["layoutAttrs"]);
			}

			$result = $this->formatData($result, $result);

			$res = new StdClass();
			$res->data 	 = $result["x"]["data"];
			$res->layout = $result["x"]["layout"];
			//$res->raw	 = $result;

			return $res;
		}

		private function formatData($Arr1, $Arr2) {

			foreach($Arr2 as $key => $Value) {

				if(array_key_exists($key, $Arr1) && is_array($Value)) {

					$Arr1[$key] = $this->formatData($Arr1[$key], $Arr2[$key]);

				} else {

					$Value = iconv(mb_detect_encoding($Value, mb_detect_order(), true), "UTF-8", $Value);

					if(!$this->isUTF8($Value)) {

						$Arr1[$key] = NULL;
						if(!isset($Arr1["unset"])) {
							$Arr1["unset"] = array();
							$Arr1["unset"][] = $key;
						}

					} else {

						if(is_numeric($Value)) {

							$Arr1[$key] = ($Value == (int) $Value) ? (int) $Value : (float) $Value;

						} else if ($Value == "NAN") {

							$Arr1[$key] = NULL;

						} else {

							$Arr1[$key] = $Value;
						}
					}
				}
			}

			return $Arr1;
		}

		private function buildRObject($data) {

			$str = '';

			foreach($data as $k => $v) {

				switch(gettype($v)) {

					case "object":
						$str .= '`'.$k."`=list(";
						$str .= $this->buildRObject($v);
						$str .= ')';
						$str .= ", ";
					break;
					case "boolean":
						$str .= '`'.$k."`=".(($v) ? "TRUE" : "FALSE");
						$str .= ", ";
					break;
					case "string":
						$str .= '`'.$k."`=".'"'.$v.'"';
						$str .= ", ";
					break;
					case "number": case "int": case "double":
						$str .= '`'.$k."`=".$v;
						$str .= ", ";
					break;
					default:
						break;

				}
			}

			return substr($str, 0, -2);
		}
	}
?>
