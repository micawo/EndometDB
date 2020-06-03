<?php

class EndometDBAnalysis extends EndometDBSQL {

	function __construct() {}

	public function render() {

		if(LOGGED_IN) {

			require_once(VIEWS."analysis.php");

		} else {

			header('Location: '.URL.'login/');
		}
	}

	public function getFilters($filter = NULL) {

		$fl = ($filter === NULL) ? 'filters.json' : 'filters_home.json';

		$filters = json_decode(file_get_contents(JSON.$fl));
		$html = '';

		foreach($filters as $fl) {

			$html .= $this->generateFilter($fl);
		}

		return $html;
	}

	private function generateFilter($data, $sub = false) {

		$open = ($data->open) ? (($data->hidden) ? ' open hidden' : ' open') : (($data->hidden) ? ' hidden' : '');
		$html = '';

		foreach($data->content as $c) {

			switch($c->type) {

				case "checkbox": case "radio":
					$requires = "";
					if(isset($c->requires)) {
						$req = implode(",", $c->requires);
						$requires = ' data-requires="'.$req.'"';
					}

					$html .= '<div '.$requires.' class="fl '.(($c->disabled) ? "disabled" : "")." ".(($c->type == "radio") ? "checkbox radio".$hidden : "checkbox".$hidden).(($c->checked) ? " checked" : "").'" data-name="'.$c->name.'"><figure><i class="fa fa-check"></i></figure>'.$c->title.'</div>';
				break;
				case "select":

					$opts = '';
					$val = array(0, "");

					foreach($c->options as $opt) {

						if($opt->selected) {

							$val[0] = $opt->value;
							$val[1] = $opt->title;
						}

						$opts .= '<div class="option" data-value="'.$opt->value.'">'.$opt->title.'</div>';
					}

					$html .= '<div class="fl select full" data-value="'.$val[0].'" data-name="'.$c->name.'">
								<span>'.$val[1].'</span>
								<div class="select-content no-padding">'.$opts.'</div>
							  </div>';
				break;
				case "multiselect":
					$clear = ($c->clear) ? '<a data-name="'.$c->name.'_reset">Clear</a><div class="clear"></div>' : '';

					$html .= '<div class="selectize" data-name="'.$c->name.'">
								<input type="text" value="" />
								<div class="options">'.$opts.'</div>
							</div>'.$clear;
				break;
				case "sub_filter":

					$html .= '<div class="filter-options no_margin">'.$this->generateFilter($c, true).'</div>';
				break;
			}
		}

		if(!$sub) {

			$st = (isset($data->toggle)) ? ' data-toggle="'.$data->toggle.'"' : '';

			return '<div class="filter'.$open.'" data-name="'.$data->name.'" data-type="'.$data->type.'"'.$st.'>
						<header class="filter-header">
							<div class="filter-toggle"></div>
							<h3 class="filter-title">'.$data->title.'</h3>
						</header>
						<div class="content">'.$html.'</div>
					</div>';

		} else {

			return '<div class="trace '.$open.'" data-name="'.$data->name.'" data-type="'.$data->sub_type.'" data-toggle="'.$data->toggle.'">
						<header class="trace-header">
							<div class="trace-toggle"></div>
							<h2 class="trace-title">'.$data->title.'</h2>
						</header>
						<div class="content padding">'.$html.'</div>
					</div>';
		}

	}

	/* TRACES */

	public function generateTracesOptions($json, $return = false) {

		if(!$return) {

			$json = json_decode($json);
		}

		$html = '';
		$globals = '';

		if(is_array($json->globals) && count($json->globals) > 0) {

			$globals = '<div class="trace open" data-index="global">
							<header class="trace-header">
								<div class="trace-toggle"></div>
								<h2 class="trace-title">Globals</h2>
							</header><div class="content">';

			foreach($json->globals as $opt) {

				$index = 'global';
				$target = (isset($opt->target)) ? $opt->target : NULL;

				switch($opt->type) {

					case "checkbox":
						$globals .= $this->_generateCheckboxFilter($opt, $target, $index);
					break;
					case "number":
						$globals .= $this->_generateNumberFilter($opt, $target, $index);
					break;
					case "select":
						$globals .= $this->_generateSelectFilter($opt, $target, $index);
					break;
					case "line":
						$globals .= $this->_generateLineSelection($target, $index);
					break;
					case "symbol":
						$globals .= $this->_generateSymbolSelection($target, $index);
					break;
					case "shape":
						$globals .= $this->_generateShapeSelection($target, $index);
					break;
					case "color":
						$globals .= $this->_generateColorFilter($opt, $target, $index);
					break;
				}
			}

			$globals .= '</div></div>';
		}

		foreach($json->traces as $k => $tra) {

			$index = $k;

			$html .= '<div class="trace" data-index="'.$index.'">
						<header class="trace-header">
							<div class="trace-toggle"></div>
							<h2 class="trace-title">#'.$index.'</h2>
						</header><div class="content">';

			foreach($tra->groups as $grp) {

				$html .= '<h3>'.$grp->name.'</h3>';

				foreach($grp->options as $opt) {

					$target = (isset($grp->target)) ? $grp->target : NULL;

					switch($opt->type) {

						case "checkbox":
							$html .= $this->_generateCheckboxFilter($opt, $target, $index);
						break;
						case "number":
							$html .= $this->_generateNumberFilter($opt, $target, $index);
						break;
						case "select":
							$html .= $this->_generateSelectFilter($opt, $target, $index);
						break;
						case "line":
							$html .= $this->_generateLineSelection($target, $index);
						break;
						case "symbol":
							$html .= $this->_generateSymbolSelection($target, $index);
						break;
						case "shape":
							$html .= $this->_generateShapeSelection($target, $index);
						break;
						case "color":
							$html .= $this->_generateColorFilter($opt, $target, $index);
						break;
						case "colorscale":
							$html .= $this->_generateColorpalette($target, $index);
						break;
					}
				}
			}

			$html .= '</div></div>';
		}

		if($return) {

			return $globals.$html;

		} else {

			echo $globals.$html;
			exit;
		}
	}

	private function _generateNumberFilter($data, $target, $index) {

		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';

		return '<div class="input" data-name="'.$data->name.'" data-index="'.$index.'" data-trace-filter'.$dt.'>
					<label>'.$data->caption.'</label>
					<input type="number" min="'.$data->min.'" max="'.$data->max.'" step="'.$data->step.'" value="'.$data->value.'">
				</div>';
	}

	private function _generateCheckboxFilter($data, $target, $index) {

		$checked = ($data->checked) ? ' checked' : '';
		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';

		return '<div class="checkbox'.$checked.'" data-index="'.$index.'" data-name="'.$data->name.'" data-trace-filter'.$dt.'>
					<figure><i class="fa fa-check"></i></figure> '.$data->caption.'
				</div>';
	}

	private function _generateSelectFilter($data, $target, $index) {

		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';
		$opts = '';
		$selected = array("", "");

		foreach($data->options as $opt) {

			if($opt->value == $data->value) {

				$selected = array($data->value, $opt->name);
			}

			$opts .= '<div class="option" data-value="'.$opt->value.'">'.$opt->name.'</div>';
		}

		return '<div class="input" data-name="'.$data->name.'" data-index="'.$index.'" data-trace-filter'.$dt.'>
					<label>'.$data->caption.'</label>
					<div class="select" data-value="'.$data->value.'" data-trace-filter>
						<span>'.$selected[1].'</span>
						<div class="select-content no-padding">'.$opts.'</div>
					</div>
				</div>';
	}

	private function _generateLineSelection($target, $index) {

		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';

		return '<div class="input" data-name="dash" data-index="'.$index.'" data-trace-filter'.$dt.'>
					<label> Type </label>
					<div class="select" data-value="1" data-trace-filter>
						<span><svg width="100" height="4"><g><path d="M5,0h100" style="fill: none; stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 4px;"></path></g></svg></span>
						<div class="select-content no-padding">

							<div class="option" data-value="1">
								<svg width="100" height="4"><g><path d="M5,0h100" style="fill: none; stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 4px;"></path></g></svg>
							</div>

							<div class="option" data-value="2">
								<svg width="100" height="4"><g><path d="M5,0h100" style="fill: none; stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 4px; stroke-dasharray: 3px, 3px;"></path></g></svg>
							</div>

							<div class="option" data-value="3">
								<svg width="100" height="4"><g><path d="M5,0h100" style="fill: none; stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 4px; stroke-dasharray: 9px, 9px;"></path></g></svg>
							</div>

							<div class="option" data-value="4">
								<svg width="100" height="4"><g><path d="M5,0h100" style="fill: none; stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 4px; stroke-dasharray: 15px, 15px;"></path></g></svg>
							</div>

						</div>
					</div>
				</div>';
	}

	private function _generateShapeSelection($target, $index) {

		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';

		return '<div class="input" data-name="shape" data-index="'.$index.'" data-trace-filter'.$dt.'>
					<label> Shape </label>
					<div class="select" data-value="1" data-trace-filter>
						<span>╱</span>
						<div class="select-content no-padding">
							<div class="option" data-value="linear">╱</div>
							<div class="option" data-value="spline">╭╯</div>
							<div class="option" data-value="hvh">┗┓</div>
							<div class="option" data-value="vhv">┏┛</div>
						</div>
					</div>
				</div>';
	}

	private function _generateColorFilter($data, $target, $index) {

		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';

		return '<div class="input" data-name="'.$data->name.'" data-index="'.$index.'" data-trace-filter'.$dt.'>
					<label>'.$data->caption.'</label>
					<input type="color">
				</div>';
	}

	private function _generateSymbolSelection($target, $index) {

		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';

		return '<div class="input" data-name="symbol" data-index="'.$index.'" data-trace-filter'.$dt.'">
					<label> Type </label>
					<div class="select" data-value="1" data-trace-filter>
						<span>
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,0A3,3 0 1,1 0,-3A3,3 0 0,1 3,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						</span>
						<div class="select-content no-padding row">

						  <div class="option" data-value="circle">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,0A3,3 0 1,1 0,-3A3,3 0 0,1 3,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="square">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,3H-3V-3H3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="diamond">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3.9,0L0,3.9L-3.9,0L0,-3.9Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="cross">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3.6,1.2H1.2V3.6H-1.2V1.2H-3.6V-1.2H-1.2V-3.6H1.2V-1.2H3.6Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="x">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,1.7l1.7,1.7l1.7,-1.7l-1.7,-1.7l1.7,-1.7l-1.7,-1.7l-1.7,1.7l-1.7,-1.7l-1.7,1.7l1.7,1.7l-1.7,1.7l1.7,1.7Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="circle-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,0A3,3 0 1,1 0,-3A3,3 0 0,1 3,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="square-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,3H-3V-3H3ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="diamond-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3.9,0L0,3.9L-3.9,0L0,-3.9ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-up">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.46,1.5H3.46L0,-3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-down">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.46,-1.5H3.46L0,3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-left">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M1.5,-3.46V3.46L-3,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-right">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.5,-3.46V3.46L3,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-ne">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.6,-1.8H1.8V3.6Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-se">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M1.8,-3.6V1.8H-3.6Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-sw">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3.6,1.8H-1.8V-3.6Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-nw">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.8,3.6V-1.8H3.6Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="pentagon">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M2.85,-0.93L1.76,2.43H-1.76L-2.85,-0.93L0,-3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hexagon">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M2.6,-1.5V1.5L0,3L-2.6,1.5V-1.5L0,-3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hexagon2">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.5,2.6H1.5L3,0L1.5,-2.6H-1.5L-3,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="octagon">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.15,-2.77H1.15L2.77,-1.15V1.15L1.15,2.77H-1.15L-2.77,1.15V-1.15Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0.94,-1.3H3.99L1.52,0.5L2.47,3.4L0,1.6L-2.47,3.4L-1.52,0.5L-3.99,-1.3H-0.94L0,-4.2Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hexagram">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-2.28,0l-1.14,-1.98h2.28l1.14,-1.98l1.14,1.98h2.28l-1.14,1.98l1.14,1.98h-2.28l-1.14,1.98l-1.14,-1.98h-2.28Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-triangle-up">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-4.16,2.4A 12,12 0 0 1 4.16,2.4A 12,12 0 0 1 0,-4.8A 12,12 0 0 1 -4.16,2.4Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-triangle-down">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M4.16,-2.4A 12,12 0 0 1 -4.16,-2.4A 12,12 0 0 1 0,4.8A 12,12 0 0 1 4.16,-2.4Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-square">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.3,-3.3A 6,6 0 0 1 -3.3,3.3A 6,6 0 0 1 3.3,3.3A 6,6 0 0 1 3.3,-3.3A 6,6 0 0 1 -3.3,-3.3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-diamond">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-4.2,0A 5.7,5.7 0 0 1 0,4.2A 5.7,5.7 0 0 1 4.2,0A 5.7,5.7 0 0 1 0,-4.2A 5.7,5.7 0 0 1 -4.2,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="diamond-tall">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,4.2L2.1,0L0,-4.2L-2.1,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="diamond-wide">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,2.1L4.2,0L0,-2.1L-4.2,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hourglass">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,3H-3L3,-3H-3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="bowtie">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,3V-3L-3,3V-3Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="cross-thin-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,4.2V-4.2M4.2,0H-4.2" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="x-thin-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,3L-3,-3M3,-3L-3,3" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="asterisk-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,3.6V-3.6M3.6,0H-3.6M2.55,2.55L-2.55,-2.55M2.55,-2.55L-2.55,2.55" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hash-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M1.5,3V-3m-3,0V3M3,1.5H-3m0,-3H3" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="y-up-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.6,2.4L0,0M3.6,2.4L0,0M0,-4.8L0,0" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="y-down-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.6,-2.4L0,0M3.6,-2.4L0,0M0,4.8L0,0" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="y-left-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M2.4,3.6L0,0M2.4,-3.6L0,0M-4.8,0L0,0" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="y-right-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-2.4,3.6L0,0M-2.4,-3.6L0,0M4.8,0L0,0" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="line-ew-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M4.2,0H-4.2" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="line-ns-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,4.2V-4.2" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="line-ne-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,-3L-3,3" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="line-nw-open">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,3L-3,-3" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: rgb(31, 119, 180);">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="circle-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,0A3,3 0 1,1 0,-3A3,3 0 0,1 3,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="square-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3,3H-3V-3H3ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="diamond-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3.9,0L0,3.9L-3.9,0L0,-3.9ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="cross-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3.6,1.2H1.2V3.6H-1.2V1.2H-3.6V-1.2H-1.2V-3.6H1.2V-1.2H3.6ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="x-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,1.7l1.7,1.7l1.7,-1.7l-1.7,-1.7l1.7,-1.7l-1.7,-1.7l-1.7,1.7l-1.7,-1.7l-1.7,1.7l1.7,1.7l-1.7,1.7l1.7,1.7ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-up-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.46,1.5H3.46L0,-3ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-down-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.46,-1.5H3.46L0,3ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-left-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M1.5,-3.46V3.46L-3,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-right-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.5,-3.46V3.46L3,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-ne-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.6,-1.8H1.8V3.6ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-se-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M1.8,-3.6V1.8H-3.6ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-sw-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M3.6,1.8H-1.8V-3.6ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="triangle-nw-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.8,3.6V-1.8H3.6ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="pentagon-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M2.85,-0.93L1.76,2.43H-1.76L-2.85,-0.93L0,-3ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hexagon-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M2.6,-1.5V1.5L0,3L-2.6,1.5V-1.5L0,-3ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hexagon2-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.5,2.6H1.5L3,0L1.5,-2.6H-1.5L-3,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="octagon-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-1.15,-2.77H1.15L2.77,-1.15V1.15L1.15,2.77H-1.15L-2.77,1.15V-1.15ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0.94,-1.3H3.99L1.52,0.5L2.47,3.4L0,1.6L-2.47,3.4L-1.52,0.5L-3.99,-1.3H-0.94L0,-4.2ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hexagram-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-2.28,0l-1.14,-1.98h2.28l1.14,-1.98l1.14,1.98h2.28l-1.14,1.98l1.14,1.98h-2.28l-1.14,1.98l-1.14,-1.98h-2.28ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-triangle-up-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-4.16,2.4A 12,12 0 0 1 4.16,2.4A 12,12 0 0 1 0,-4.8A 12,12 0 0 1 -4.16,2.4ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-triangle-down-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M4.16,-2.4A 12,12 0 0 1 -4.16,-2.4A 12,12 0 0 1 0,4.8A 12,12 0 0 1 4.16,-2.4ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-square-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-3.3,-3.3A 6,6 0 0 1 -3.3,3.3A 6,6 0 0 1 3.3,3.3A 6,6 0 0 1 3.3,-3.3A 6,6 0 0 1 -3.3,-3.3ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="star-diamond-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M-4.2,0A 5.7,5.7 0 0 1 0,4.2A 5.7,5.7 0 0 1 4.2,0A 5.7,5.7 0 0 1 0,-4.2A 5.7,5.7 0 0 1 -4.2,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="diamond-tall-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,4.2L2.1,0L0,-4.2L-2.1,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="diamond-wide-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M0,2.1L4.2,0L0,-2.1L-4.2,0ZM0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						  <div class="option" data-value="hash-open-dot">
							<svg width="20" height="20">
							  <g transform="translate(10,10)">
								<path d="M1.5,3V-3m-3,0V3M3,1.5H-3m0,-3H3M0,0.5L0.5,0L0,-0.5L-0.5,0Z" style="stroke: rgb(31, 119, 180); stroke-opacity: 1; stroke-width: 1px; fill: none;">
								</path>
							  </g>
							</svg>
						  </div>
						</div>
					</div>
				</div>';
	}

	private function _generateColorpalette($target, $index) {

		$dt = ($target !== NULL) ? ' data-target="'.$target.'"' : '';

		return '<div class="color-palette-selector" data-name="colorscale" data-index="'.$index.'" data-trace-filter'.$dt.'>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(249, 216, 36);"></div>
						<div style="height: 15px; background-color: rgb(250, 186, 32);"></div>
						<div style="height: 15px; background-color: rgb(243, 134, 71);"></div>
						<div style="height: 15px; background-color: rgb(205, 73, 117);"></div>
						<div style="height: 15px; background-color: rgb(142, 12, 163);"></div>
						<div style="height: 15px; background-color: rgb(46, 4, 149);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(5, 10, 172);"></div>
						<div style="height: 15px; background-color: rgb(106, 137, 247);"></div>
						<div style="height: 15px; background-color: rgb(190, 190, 190);"></div>
						<div style="height: 15px; background-color: rgb(220, 170, 132);"></div>
						<div style="height: 15px; background-color: rgb(230, 145, 90);"></div>
						<div style="height: 15px; background-color: rgb(178, 10, 28);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(255, 255, 204);"></div>
						<div style="height: 15px; background-color: rgb(161, 218, 180);"></div>
						<div style="height: 15px; background-color: rgb(65, 182, 196);"></div>
						<div style="height: 15px; background-color: rgb(44, 127, 184);"></div>
						<div style="height: 15px; background-color: rgb(8, 104, 172);"></div>
						<div style="height: 15px; background-color: rgb(37, 52, 148);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(0, 0, 155);"></div>
						<div style="height: 15px; background-color: rgb(0, 108, 255);"></div>
						<div style="height: 15px; background-color: rgb(98, 255, 146);"></div>
						<div style="height: 15px; background-color: rgb(255, 147, 0);"></div>
						<div style="height: 15px; background-color: rgb(255, 47, 0);"></div>
						<div style="height: 15px; background-color: rgb(216, 0, 0);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(54, 50, 153);"></div>
						<div style="height: 15px; background-color: rgb(17, 123, 215);"></div>
						<div style="height: 15px; background-color: rgb(37, 180, 167);"></div>
						<div style="height: 15px; background-color: rgb(134, 191, 118);"></div>
						<div style="height: 15px; background-color: rgb(249, 210, 41);"></div>
						<div style="height: 15px; background-color: rgb(244, 236, 21);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(4, 4, 21);"></div>
						<div style="height: 15px; background-color: rgb(98, 24, 127);"></div>
						<div style="height: 15px; background-color: rgb(176, 52, 122);"></div>
						<div style="height: 15px; background-color: rgb(250, 129, 94);"></div>
						<div style="height: 15px; background-color: rgb(254, 185, 127);"></div>
						<div style="height: 15px; background-color: rgb(252, 234, 172);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(46, 4, 149);"></div>
						<div style="height: 15px; background-color: rgb(142, 12, 163);"></div>
						<div style="height: 15px; background-color: rgb(205, 73, 117);"></div>
						<div style="height: 15px; background-color: rgb(243, 134, 71);"></div>
						<div style="height: 15px; background-color: rgb(250, 186, 32);"></div>
						<div style="height: 15px; background-color: rgb(249, 216, 36);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(71, 17, 100);"></div>
						<div style="height: 15px; background-color: rgb(53, 92, 140);"></div>
						<div style="height: 15px; background-color: rgb(37, 130, 141);"></div>
						<div style="height: 15px; background-color: rgb(66, 189, 112);"></div>
						<div style="height: 15px; background-color: rgb(141, 214, 68);"></div>
						<div style="height: 15px; background-color: rgb(221, 226, 24);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(221, 42, 145);"></div>
						<div style="height: 15px; background-color: rgb(177, 77, 236);"></div>
						<div style="height: 15px; background-color: rgb(118, 117, 237);"></div>
						<div style="height: 15px; background-color: rgb(46, 142, 191);"></div>
						<div style="height: 15px; background-color: rgb(11, 152, 121);"></div>
						<div style="height: 15px; background-color: rgb(19, 152, 99);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(10, 10, 10);"></div>
						<div style="height: 15px; background-color: rgb(67, 67, 67);"></div>
						<div style="height: 15px; background-color: rgb(114, 114, 114);"></div>
						<div style="height: 15px; background-color: rgb(178, 178, 178);"></div>
						<div style="height: 15px; background-color: rgb(214, 214, 214);"></div>
						<div style="height: 15px; background-color: rgb(240, 240, 240);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(209, 190, 90);"></div>
						<div style="height: 15px; background-color: rgb(177, 173, 42);"></div>
						<div style="height: 15px; background-color: rgb(95, 144, 11);"></div>
						<div style="height: 15px; background-color: rgb(57, 129, 27);"></div>
						<div style="height: 15px; background-color: rgb(40, 122, 33);"></div>
						<div style="height: 15px; background-color: rgb(13, 86, 44);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(190, 207, 182);"></div>
						<div style="height: 15px; background-color: rgb(125, 178, 143);"></div>
						<div style="height: 15px; background-color: rgb(40, 144, 126);"></div>
						<div style="height: 15px; background-color: rgb(16, 125, 121);"></div>
						<div style="height: 15px; background-color: rgb(24, 97, 108);"></div>
						<div style="height: 15px; background-color: rgb(28, 71, 93);"></div>
					</div>
					<div class="color-palette">
						<div style="height: 15px; background-color: rgb(222, 183, 175);"></div>
						<div style="height: 15px; background-color: rgb(207, 131, 113);"></div>
						<div style="height: 15px; background-color: rgb(192, 88, 64);"></div>
						<div style="height: 15px; background-color: rgb(182, 59, 37);"></div>
						<div style="height: 15px; background-color: rgb(150, 19, 27);"></div>
						<div style="height: 15px; background-color: rgb(144, 19, 28);"></div>
					</div><div class="clear"></div>
				</div>';
	}
}

?>
