<?php 

	class EndometDBTestAPI {
		
		function __construct() {
			
			switch($_POST["type"]) {
				
				case "scatter":
					$this->generateScatterChart();
				break;
				case "area":
					$this->generateAreaChart();
				break;
				case "bar":
					$this->generateBarChart();
				break;		
				case "pie":
					$this->generatePieChart();
				break;	
				case "histogram":
					$this->generateHistogramChart();
				break;		
				case "box":
					$this->generateBoxplotChart();
				break;	
				case "heatmap":
					$this->generateHeatmapChart();
				break;	
				case "contour":
					$this->generateContourChart();
				break;
				default:
					$this->generateBoxplotChart();
																	
			}
		}
			
		private function generateScatterChart() {
			
			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-scatter',
						'hoverinfo' => 'x+y+z+text',
						'text' => 'test_text',
						'x' => [1, 2, 3, 4],
						'y' => [10, 15, 13, 17],
						'mode' => 'lines+markers',
						'type' => 'scatter',
						'x-axis' => 'x',
						'y-axis' => 'y',
						'marker' => (object) [
							'symbol' => 'circle',
							'opacity' => 1,
							'size' => 6,
							'color' => '#1f77b4'
						],
						'line' => (object) [
							'color' => '#1f77b4',
							'width' => 2,
							'dash' => 'solid',
							'shape' => 'linear'
						],
						'error_y' => (object) [ // error_x
							type => 'percent',
							'value' => 2,
							'visible' => true
						]
					]
				],
				'layout' => (object) [
					'xaxis' => (object) [
						'title' => 'X-axis',
						'type' => 'linear',
						'showGrid' => false
					],
					'yaxis' => (object) [
						'title' => 'Y-axis',
						'type' => 'linear',
						'showgrid' => true,
						
					],
					'showlegend' => true,					
				]
			];
			
			echo json_encode($plot);
			exit;
		}
		
		private function generateAreaChart() {

			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-Area 1',
						'hoverinfo' => 'x+y+z+text',
						'text' => 'test_area',
						'x' => [1, 2, 3, 4],
						'y' => [0, 2, 3, 5],
						'mode' => 'lines+markers',
						'type' => 'scatter',
						'x-axis' => 'x',
						'y-axis' => 'y',
						'fill' => 'tozeroy',						
						'marker' => (object) [
							'symbol' => 'circle',
							'opacity' => 1,
							'size' => 6,
						],
						'line' => (object) [
							'width' => 2,
							'dash' => 'solid',
							'shape' => 'linear'
						],
						'error_x' => (object) [
							type => 'percent',
							'value' => 5,
							'visible' => true
						]
					],
					(object) [
						'name' => 'Test-Area 2',
						'hoverinfo' => 'x+y+z+text',
						'text' => 'test_area2',
						'x' => [1, 2, 3, 4],
						'y' => [3, 5, 1, 7],
						'mode' => 'lines+markers',
						'type' => 'scatter',
						'x-axis' => 'x',
						'y-axis' => 'y',
						'fill' => 'tonexty',
						'marker' => (object) [
							'symbol' => 'circle',
							'opacity' => 1,
							'size' => 6,
						],
						'line' => (object) [
							'width' => 2,
							'dash' => 'solid',
							'shape' => 'linear'
						]
					]					
				],
				'layout' => (object) [
					'xaxis' => (object) [
						'title' => 'X-axis',
						'type' => 'linear',
						'showGrid' => false
					],
					'yaxis' => (object) [
						'title' => 'Y-axis',
						'type' => 'linear',
						'showgrid' => true,
						
					],
					'showlegend' => true,					
				]
			];
			
			echo json_encode($plot);
			exit;			
		}
		
		private function generateBarChart() { 
			
			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-bar',
						'x' => ['giraffes', 'orangutans', 'monkeys'],
						'y' => [20, 14, 23],
						'type' => 'bar'
					],
					(object) [
						'name' => 'Test-bar2',
						'x' => ['giraffes', 'orangutans', 'monkeys'],
						'y' => [12, 18, 29],
						'type' => 'bar',
					]					
				],
				'layout' => (object) [
					'title' => "Test-bars",
					'barmode' => 'stack',
					'showlegend' => true,					
				]
			];
			
			echo json_encode($plot);
			exit;			
		}
		
		private function generatePieChart() { 
			
			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-bar',
						'values' => [19, 26, 55],
						'labels' => ['Residential', 'Non-Residential', 'Utility'],
						'type' => 'pie'
					],				
				],
				'layout' => (object) [
					'title' => "Test-pie",	
					'showlegend' => true,										
				]
			];
			
			echo json_encode($plot);
			exit;			
		}
		
		private function generateHistogramChart() {
			
			$x = [];
			
			for($i = 0; $i < 300; $i++) {
				
				$x[] = rand(0, 100);
			}

			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-Histogram',
						'x' => $x,
						'type' => 'histogram'
					],				
				],
				'layout' => (object) [
					'title' => "Test-histogram",
					'showlegend' => true,											
				]
			];	

			echo json_encode($plot);
			exit;						
		}
		
		private function generateBoxplotChart() {
			
			$y = [];
			$y0 = [];

			for($i = 0; $i < 300; $i++) {
				
				$y[] = rand(0, 100);
				$y0[] = rand(0, 100) + 1;
			}
			
			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-Boxplot1',
						'y' => $y,
						'type' => 'box'
					],	
					(object) [
						'name' => 'Test-Boxplot2',
						'y' => $y0,
						'type' => 'box'
					],									
				],
				'layout' => (object) [
					'title' => "Test-Boxplot",
					'showlegend' => true,											
				]
			];	

			echo json_encode($plot);
			exit;
		}
		
		private function generateHeatmapChart() {
						
			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-heatmap1',
						'z' => [[1, 20, 30], [20, 1, 60], [30, 60, 1]],
						'type' => 'heatmap'
					],								
				],
				'layout' => (object) [
					'title' => "Test-Heatmap"										
				]
			];	

			echo json_encode($plot);
			exit;			
		}	
		
		private function generateContourChart() {

			$plot = (object) [
				'data' => [
					(object) [
						'name' => 'Test-heatmap1',
						'z' => [[10, 10.625, 12.5, 15.625, 20],
							    [5.625, 6.25, 8.125, 11.25, 15.625],
							    [2.5, 3.125, 5.0, 8.125, 12.5],
							    [0.625, 1.25, 3.125, 6.25, 10.625],
							    [0, 0.625, 2.5, 5.625, 10]],
						'type' => 'contour'
					],								
				],
				'layout' => (object) [
					'title' => "Test-Contour"											
				]
			];	

			echo json_encode($plot);
			exit;					
		}	
	}

?>
