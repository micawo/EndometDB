<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=2.0, user-scalable=1" />
<title>EndometDB</title>
<style>
@font-face {
	font-family: "unified";
	src: url("fonts/unified.woff") format("woff"),
	url("fonts/unified.ttf") format("truetype");
	font-style: normal;
	font-weight: normal;
}
</style>
<link rel="stylesheet" href="css/font-awesome.min.css" />
<link rel="stylesheet" href="css/app.min.css" />
</head>
<body>
	
	<header id="nav">
		
		<div class="logo">
			<h1>End<figure></figure>omet</h1>
			<h2>Turku Endometriosis Database</h2>
		</div>
		
		<a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home </a>
		<a href="#" class="selected"><i class="fa fa-bar-chart" aria-hidden="true"></i> Analytics </a>
		<a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Contact </a>
		<a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout </a>
	
	</header>

	<div class="filter_tabs">

		<div class="tab minify"><i class="fa fa-angle-double-left"></i><span>Hide filters</span></div>
		<div class="tab pre"><i class="fa fa fa-area-chart"></i><span>Predefined analysis</span></div>

	</div>
	
	<div id="wrapper">

		<section id="filters">
						
			<div class="filter open">
				
				<header class="filter-header">
					<div class="filter-toggle"></div>				
					<h3 class="filter-title"> Chart type </h3>
				</header>
				
				<div class="content">
					
					<!-- Chart type select -->
					
					<div class="select" data-id="filter-type" data-type="line">
						
						<span><i class="icon-plot-line"></i>Line plot</span>
						
						<div class="select-content row-content">
							
							<div class="row">
								
								<h3> Basic </h3>
								

								<div class="filter-select" data-type="line">
									<i class="icon-plot-line"></i>
									Line plot 
								</div>
								
								<div class="filter-select" data-type="bar">
									<i class="icon-plot-bar"></i>
									Bar chart
								</div>
								
								<div class="filter-select" data-type="area">
									<i class="icon-plot-area"></i>
									Area chart 
								</div>																								

								<div class="filter-select" data-type="pie"> 
									<i class="icon-pie-chart"></i>
									Pie chart 
								</div>		
								
							</div>

							<div class="row">
								
								<h3> Statistical </h3>
								
								<div class="filter-select">
									<i class="icon-plot-hist"></i>
									Histogram 
								</div>

								<div class="filter-select">
									<i class="icon-plot-box"></i>
									Box plot 
								</div>
																														
							</div>

							<div class="row">
								
								<h3> Scientific </h3>
								
								<div class="filter-select">
									<i class="icon-error-bars"></i>
									Error bars
								</div>

								<div class="filter-select">
									<i class="icon-plot-heatmap"></i>
									Heatmap
								</div>
								
								<div class="filter-select">
									<i class="icon-contour"></i>
									Contour
								</div>
								
								<div class="filter-select">
									<i class="icon-plot-scatter"></i>
									Scatter plot 
								</div>																								
								
							</div>							
							
						</div>
						
					</div>
					
					<!-- / Chart type select -->
					
					<!-- / Chart options -->
					
					<div class="filter-options">
						
						<div class="option" data-type="line">

							<h3> Display </h3>

							<div class="checkbox checked" data-name="points">
								<figure><i class="fa fa-check"></i></figure> Points
							</div>
		
							<div class="checkbox checked" data-name="lines">
								<figure><i class="fa fa-check"></i></figure> Lines
							</div>
																	
							<h3> Lines </h3>
							
							<div class="input" data-name="line-opacity">
								<label> Opacity </label>
								<input type="number" min="0" max="1"  step="0.1" value="1" />
							</div>							
							
							<div class="input" data-name="thickness">
								<label> Thickness </label>
								<input type="number" min="0" max="2" step="0.5" value="1" />
							</div>

							<div class="input">
								<label> Type </label>
								<div class="select" data-name="type" data-value="1">								
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
							</div>		

							<div class="input">
								<label> Shape </label>
								<div class="select" data-name="shape" data-value="1">								
									<span>╱</span>	
									<div class="select-content no-padding">
										<div class="option" data-value="1">╱</div>			
										<div class="option" data-value="2">╭╯</div>
										<div class="option" data-value="3">┗┓</div>
										<div class="option" data-value="4">┏┛</div>		
									</div>	
								</div>		
							</div>														
							
							<h3> Points </h3>

							<div class="input" data-name="points-opacity">
								<label> Opacity </label>
								<input type="number" min="0" max="1"  step="0.1" value="1" />
							</div>

							<div class="input">
								<label> Diameter </label>
								<input type="number" min="0" max="99"  step="1" value="1" />
							</div>
							
							<div class="input">
								<label> Type </label>
								<div class="select" data-name="type" data-value="1">								
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
							</div>								
		

						</div>
						
					</div>
					
					<!-- / Chart options -->
					
				</div>

			</div>
			
			<div class="filter open">
				
				<header class="filter-header">
					<div class="filter-toggle"></div>				
					<h3 class="filter-title"> Category</h3>
				</header>
				
				<div class="content">
				
					<div class="checkbox">
						<figure><i class="fa fa-check" aria-hidden="true"></i></figure> Patient
					</div>

					<div class="checkbox">
						<figure><i class="fa fa-check" aria-hidden="true"></i></figure> Control
					</div>					
				
				</div>
	
			</div>			
			
		</section>
		
		<section id="chart" class="loading">
			
			<div class="chart-area"></div>
			
			<div class="spinParticleContainer">
				<div class="particle red"></div>
				<div class="particle grey other-particle"></div>
				<div class="particle blue other-other-particle"></div>
			</div>			

		</section>
		
	</div>
	
	<footer id="footer">
		
	</footer>


	<script src="js/plotly-latest.min.js"></script>
	<script src="js/app.min.js"></script>
	
</body>
</html>
