import $ from 'jquery';
import clickEvent from '../utils/clickevent';
import ajax from '../utils/ajax';
import scatterOptions from '../filters/scatter';
import areaOptions from '../filters/area';
import barOptions from '../filters/bar';
import barGlobals from '../filters/bar_globals';
import histogramOptions from '../filters/histogram';
import histogramGlobals from '../filters/histogram_globals';
import boxOptions from '../filters/box';
import boxGlobals from '../filters/box_globals';
import heatmapOptions from '../filters/heatmap';
import contourOptions from '../filters/contour';
import initSelectize from '../utils/selectize';
import { detectTransitionEnd } from '../utils/transitions';

export default function analytics(self_) {

	var sbmt 	  = document.querySelector(".btn[data-name='run_analysis']"),
		filters   = document.querySelectorAll("#filters .filter:not(:first-of-type)"),
		chart 	  = document.querySelector("#chart"),
		loading   = false,
		chartData = [];

	/*var d3  = Plotly.d3,
		gd3 = d3.select('#chart .chart-area').append('div').style({ width: 100 + '%', height: 80 + 'vh' }),
		gd  = gd3.node();*/
	
	var plotly_arrs = [];
		
	plotly_arrs[0] = { d3: Plotly.d3 }
	plotly_arrs[0].gd3 = plotly_arrs[0].d3.select('#chart .chart-area').append('div').style({ width: 100 + '%', height: 80 + 'vh' }).attr("data-index", 1);
	plotly_arrs[0].gd  = plotly_arrs[0].gd3.node();	
		
	/** INIT CHART **/

	function minifyFilters() {

		if(!$("#filters").hasClass("hide")) {

			$(".filter_tabs .minify i").removeClass("fa-angle-double-left");
			$(".filter_tabs .minify i").addClass("fa-angle-double-right");
			$(".filter_tabs .minify span").text("Show filters");

		} else {

			$(".filter_tabs .minify i").removeClass("fa-angle-double-right");
			$(".filter_tabs .minify i").addClass("fa-angle-double-left");
			$(".filter_tabs .minify span").text("Hide filters");
		}

		$("#filters").toggleClass("hide");
		$(".filter_tabs .pre").toggleClass("hide");
		$("#chart").addClass("loading");
	}

	function onFilterTransitionEnd(e) {

		if(/transform/i.test(e.propertyName)) {

			for(var i = 0; i < plotly_arrs.length; i += 1) {
				
				Plotly.Plots.resize(plotly_arrs[i].gd);	
			}

			//Plotly.Plots.resize(gd);
		}
	}

	function onPlotlyResize() {
		
		for(var i = 0; i < plotly_arrs.length; i += 1) {
			
			Plotly.Plots.resize(plotly_arrs[i].gd);	
		}
		
		//Plotly.Plots.resize(gd);
	}

	/** DATA FETCH **/

	function pushErrorFilters(d) {

		var g = [];

		if(d.error_y) {

			g.push({

				name: "Errorbar-y",
				target: "error_y",
				options: [
					{
						type: "checkbox",
						checked: true,
						name: "visible",
						caption: "Show errorbar"
					}
				]
			});

		} else if(d.error_x) {

			g.push({

				name: "Errorbar-x",
				target: "error_x",
				options: [
					{
						type: "checkbox",
						checked: true,
						name: "visible",
						caption: "Show errorbar"
					}
				]
			});
		}

		return g;
	}

	function initTraceFilters() {

		/*var data = { globals: [], traces: [] },
			bar_globals = false,
			histogram_globals = false,
			box_globals = false;

		chartData.data.forEach(function(d) {

			var f;

			if(d.type == "scatter") {

				f = (typeof d.fill !== "undefined") ? areaOptions : scatterOptions;
				f.groups = f.groups.concat(pushErrorFilters(d));
				data.traces.push(f);

			} else if(d.type == "bar") {

				if(!bar_globals) {

					data.globals = data.globals.concat(barGlobals);
					bar_globals = true;
				}

				data.traces.push(barOptions);

			} else if(d.type == "histogram") {

				if(!histogram_globals) {

					data.globals = data.globals.concat(histogramGlobals);
					histogram_globals = true;
				}

				f = histogramOptions;
				f.groups = f.groups.concat(pushErrorFilters(d));
				data.traces.push(histogramOptions);

			} else if(d.type == "box") {

				if(!box_globals) {

					data.globals = data.globals.concat(boxGlobals);
					box_globals = true;
				}

				f = boxOptions;
				f.groups = f.groups.concat(pushErrorFilters(d));
				data.traces.push(f);

			} else if(d.type == "heatmap") {

				f = heatmapOptions;
				f.groups = f.groups.concat(pushErrorFilters(d));
				data.traces.push(f);

			} else if(d.type == "contour") {

				f = contourOptions;
				f.groups = f.groups.concat(pushErrorFilters(d));
				data.traces.push(f);
			}
		});

		ajax({

			url: self_.baseDirectoryUrl + 'api/options',
			data: { data: JSON.stringify(data), chart: JSON.stringify(chartData) },
			callback: function(res) {

				setTimeout(function() {					
					
					if(Array.isArray(chartData)) {

						for(var i = 0; i < chartData.length; i += 1) {
							
							if (typeof plotly_arrs[i] === "undefined") {

								plotly_arrs[i] = { d3: Plotly.d3 }
								plotly_arrs[i].gd3 = plotly_arrs[i].d3.select('#chart .chart-area').append('div').style({ width: 100 + '%', height: 80 + 'vh' }).attr("data-index", (i+1));
								plotly_arrs[i].gd  = plotly_arrs[i].gd3.node();			
														
							} else {
								
								plotly_arrs[i] = { d3: Plotly.d3 }
								plotly_arrs[i].gd3 = plotly_arrs[i].d3.select('#chart .chart-area div[data-index="' + (i+1) + '"]');
								plotly_arrs[i].gd  = plotly_arrs[i].gd3.node();									
							} 
							
							Plotly.plot(plotly_arrs[i].gd, chartData[i]);
						}
						
					} else {

						plotly_arrs[0] = { d3: Plotly.d3 }
						plotly_arrs[0].gd3 = plotly_arrs[0].d3.select('#chart .chart-area div[data-index="1"]');
						plotly_arrs[0].gd  = plotly_arrs[0].gd3.node();		
						Plotly.plot(plotly_arrs[0].gd, chartData[0]);						
					}
					
					//d3  = Plotly.d3;
					//gd3 = d3.select('#chart .chart-area div');
					//gd  = gd3.node();

					//Plotly.plot(gd, chartData);

					chart.removeEventListener(detectTransitionEnd(), onFilterTransitionEnd);
					chart.addEventListener(detectTransitionEnd(), onFilterTransitionEnd);
					window.removeEventListener("onresize", onPlotlyResize);
					window.addEventListener("resize", onPlotlyResize);
					
					plotly_arrs[(plotly_arrs.length - 1)].gd.on('plotly_afterplot', function() { $("#chart").removeClass("loading"); }); 
					
					//gd.on('plotly_afterplot', function() { $("#chart").removeClass("loading"); });

					//$("#chart_settings .filter-options").html(res);
					$("#filters").removeClass("loading");
					loading = false;

				}, 400);
			},
			error: function() {}
		}); */
		
		setTimeout(function() {					
			
			if(Array.isArray(chartData)) {

				for(var i = 0; i < chartData.length; i += 1) {			

					plotly_arrs[i] = { d3: Plotly.d3 }
					plotly_arrs[i].gd3 = plotly_arrs[i].d3.select('#chart .chart-area').append('div').style({ width: 100 + '%', height: 80 + 'vh' }).attr("data-index", (i+1));
					plotly_arrs[i].gd  = plotly_arrs[i].gd3.node();	
					Plotly.plot(plotly_arrs[i].gd, chartData[i]);
				}
				
			} else {

				plotly_arrs[0] = { d3: Plotly.d3 }
				plotly_arrs[0].gd3 = plotly_arrs[0].d3.select('#chart .chart-area').append('div').style({ width: 100 + '%', height: 80 + 'vh' }).attr("data-index", 1);
				plotly_arrs[0].gd  = plotly_arrs[0].gd3.node();		
				Plotly.plot(plotly_arrs[0].gd, chartData);					
			}
			
			/*d3  = Plotly.d3;
			gd3 = d3.select('#chart .chart-area div');
			gd  = gd3.node();
			Plotly.plot(gd, chartData);*/

			chart.removeEventListener(detectTransitionEnd(), onFilterTransitionEnd);
			chart.addEventListener(detectTransitionEnd(), onFilterTransitionEnd);
			window.removeEventListener("onresize", onPlotlyResize);
			window.addEventListener("resize", onPlotlyResize);
			
			plotly_arrs[(plotly_arrs.length - 1)].gd.on('plotly_afterplot', function() { $("#chart").removeClass("loading"); }); 
			
			//gd.on('plotly_afterplot', function() { $("#chart").removeClass("loading"); });

			//$("#chart_settings .filter-options").html(res);
			$("#filters").removeClass("loading");
			loading = false;
		}, 400);		
	}

	function getFilters() {

		var type = document.querySelector("#chart_settings .select"),
			symbols = document.querySelectorAll(".selectize[data-name='gene_symbols'] .tag"),
			data = {}, s = [];

		[...symbols].forEach(function(sym) {
			
			s.push(sym.innerHTML.trim());	
		});
		
		data.filter = {
			
			"category": {				
				"patient": $(".fl[data-name='category_patient']").hasClass("checked"),
				"control": $(".fl[data-name='category_control']").hasClass("checked")
			},
			"clinical": {
				"cycle_phase": $(".fl[data-name='cycle_phase']").hasClass("checked"),
				"disease_stage": $(".fl[data-name='disease_stage']").hasClass("checked"),
				"hormonal_medication_status": $(".fl[data-name='hormonal_medication_status']").hasClass("checked"),
				"hormonal_medication_type": $(".fl[data-name='hormonal_medication_type']").hasClass("checked")
			},
			"sample_type": {
				"blood": {
					"serum": ($(".fl[data-name='blood']").hasClass("checked") && $(".fl[data-name='serum']").hasClass("checked")),
					"plasma": ($(".fl[data-name='blood']").hasClass("checked") && $(".fl[data-name='plasma']").hasClass("checked"))
				},
				"peritoneal_fluid": ($(".fl[data-name='fluids']").hasClass("checked") && $(".fl[data-name='peritoneal_fluid']").hasClass("checked")),
				"urine": ($(".fl[data-name='fluids']").hasClass("checked") && $(".fl[data-name='urine']").hasClass("checked")),	
				"tissue": {
					"endometrium": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='endometrium']").hasClass("checked")),
					"peritoneum": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='peritoneum']").hasClass("checked")),
					"lesion": {
						"peritoneal_lesion": {
							"white": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='white']").hasClass("checked")),
							"black": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='black']").hasClass("checked")),
							"red": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='red']").hasClass("checked"))
						},
						"deep_endometriosis_lesion": {
							"SA": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SA']").hasClass("checked")),
							"SAO": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SAO']").hasClass("checked")),
							"SAV": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SAV']").hasClass("checked")),
							"RE": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='RE']").hasClass("checked")),
							"SI": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SI']").hasClass("checked")),
							"SU": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SU']").hasClass("checked")),
							"VI": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='VI']").hasClass("checked")),
							"IL": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='IL']").hasClass("checked")),
							"AP": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='AP']").hasClass("checked"))
						},
						"ovarian_lesion": {
							"O": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='O']").hasClass("checked")),
							"OO": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='OO']").hasClass("checked")),
							"OV": ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='OV']").hasClass("checked"))
						}
					}
				}									
			},
			"modules": {
				"ge": $(".fl[data-name='ge']").hasClass("checked"),
				"metabolomics": $(".fl[data-name='metabolomics']").hasClass("checked"),
				"proteomics": $(".fl[data-name='proteomics']").hasClass("checked"),
				"cytokines": $(".fl[data-name='cytokines']").hasClass("checked"),
				"hormone_concentration": $(".fl[data-name='hormone_concentration']").hasClass("checked")
			}
		};
		
		var chart = ($(".select[data-id='filter-type']").attr("data-type") == "box") ? "Boxplot (Expression profile per gene)" : "Heatmap (Clustering of two or more genes across two or more sample types)";

		data.params = {
			
			"function.name": {
				"Chart type": chart
			},
			"gene.symbol": s.join(","),
			"font.size": {
				"Select value": 1.5
			},
			"combine.lesions": $(".fl[data-name='combine_lesions']").hasClass("checked"),
			"stat.fun": {
				"Summarise by": $(".fl[data-name='summarise_by']").attr("data-value")
			},
			"cluster.heatmap": $(".fl[data-name='cluster_heatmap']").hasClass("checked"),
			"center.by": {
				"Center by": $(".fl[data-name='data_centering']").attr("data-value")
			},
			".scale": $(".fl[data-name='scale']").hasClass("checked")
		};

		return data;
	}

	function onSubmit() {

		if(!self_.dragging && !loading && !$(this).hasClass("disabled")) {

			var data = getFilters();

			ajax({

				url: self_.baseDirectoryUrl + 'api/getgraph',
				data: {
					data: JSON.stringify(data)
				},
				beforeSend: function() {

					$("#chart").addClass("loading");
					$("#chart").removeClass("run_again");
					$("#filters").addClass("loading");
					$("#filters .filter:first-of-type").removeClass("open");
					$('html, body').animate({ scrollTop: $("#chart").offset().top - 50 }, 400);
					loading = true;
				},
				callback: function(res) {
					
					console.log(res);					
					chartData = JSON.parse(res);
					console.log(chartData);

					for(var i = 0; i < plotly_arrs.length; i += 1) {
						
						Plotly.purge(plotly_arrs[i].gd);
					}
					
					plotly_arrs = [];
					$("#chart .chart-area div[data-index]").each(function(e) {
						$(this).remove();
					})
					
					//Plotly.purge(gd);
					initTraceFilters();
				},
				error: function() {}
			});
		}
	}
	
	function onDownloadPDF(e) {
		
		e.preventDefault();
		
		if(!self_.dragging && !loading && !$(this).hasClass("loading")) {
			
			var data  = getFilters(),
				pdfbtn = document.querySelector(".btn[data-name='download_as_pdf']");
				
			data.params.pdf = true;

			ajax({

				url: self_.baseDirectoryUrl + 'api/getpdfgraph',
				data: {
					data: JSON.stringify(data)
				},
				beforeSend: function() {
					
					$(pdfbtn).addClass("loading");
					pdfbtn.insertAdjacentHTML("afterbegin", '<div class="loader"><span></span></div>');						
					
					/*$("#chart").addClass("loading");
					$("#chart").removeClass("run_again");
					$("#filters").addClass("loading");
					$("#filters .filter:first-of-type").removeClass("open");
					$('html, body').animate({ scrollTop: $("#chart").offset().top - 50 }, 400);*/
					loading = true;
				},
				callback: function(res) {
					
					setTimeout(function() {
						
						$(pdfbtn).removeClass("loading");
						pdfbtn.removeChild(pdfbtn.querySelector(".loader"));							
						loading = false;
					}, 200);

				},
				error: function() {}
			});			
		}
	}

	function reStylePlot(data, index) {

		var update = {}

		data.forEach(function(d) {

			(d.target != "") ? update[d.target + "." + d.name] = d.value : update[d.name] = d.value;
		});

		(index == "global") ? Plotly.relayout(gd, update) : Plotly.restyle(gd, update, [index]);
	}

	function onTraceInput(e) {

		var data = [],
			cnt = $(e.target).parent(),
			nme = cnt.attr("data-name"),
			tgt = cnt.attr("data-target"),
			ind = cnt.attr("data-index");

		tgt = (tgt) ? tgt : "";
		data.push({ name: nme, target: tgt, value: e.target.value });
		reStylePlot(data, ind);
	}

	function toggleTraceFilterSelect() {

		$(this).parent().find(".select-content").toggleClass("open");
	}

	function selectTraceFilterOption() {

		var data = [],
			sel  = $(this).parent().parent().parent(),
			span = sel.find("span"),
			nme = sel.attr("data-name"),
			tgt = sel.attr("data-target"),
			ind = sel.attr("data-index");

		tgt = (tgt) ? tgt : "";

		data.push({ name: nme, target: tgt, value: $(this).attr("data-value") });
		span.html($(this).html());
		span.parent().attr("data-value", $(this).attr("data-value"));
		$(this).parent().parent().find(".select-content").toggleClass("open");
		reStylePlot(data, ind);
	}


	function toggleTraceFilterCheckbox() {

		$(this).toggleClass("checked");

		var data = [],
			val = $(this).hasClass("checked"),
			nme = $(this).attr("data-name"),
			tgt = $(this).attr("data-target"),
			ind = $(this).attr("data-index");

		tgt = (tgt) ? tgt : "";

		if(tgt == "error_y" || tgt == "error_x") {

			data.push({ name: "thickness", target: tgt, value: ((val) ? 1.5 : 0) });

		} else {

			data.push({ name: nme, target: tgt, value: val });
		}

		reStylePlot(data, ind);
	}

	function onTraceColorPalette() {

		var n = [0, 0.2, 0.4, 0.6, 0.8, 1],
			d = [],
			data = [],
			nme = $(this).parent().attr("data-name"),
			tgt = $(this).parent().attr("data-target"),
			ind = $(this).parent().attr("data-index");

		tgt = (tgt) ? tgt : "";

		$(this).find("div").each(function(k, v) {

			d.push([n[k], $(this).css("background-color")]);
		});

		data.push({ name: nme, target: tgt, value: [d] });

		reStylePlot(data, ind);
	}

	/** Listeners **/

	$(".minify").on(clickEvent, minifyFilters);
	$(sbmt).on(clickEvent, onSubmit);
	$(".btn[data-name='download_as_pdf']").on(clickEvent, onDownloadPDF);

	// Trace-filters

	/*$("#filters").on("input", "[data-trace-filter] input", onTraceInput);
	$("#filters").on(clickEvent, '.select[data-trace-filter] span', toggleTraceFilterSelect);
	$("#filters").on(clickEvent, '.select[data-trace-filter] .option', selectTraceFilterOption);
	$("#filters").on(clickEvent, ".checkbox[data-trace-filter]", toggleTraceFilterCheckbox);
	$("#filters").on(clickEvent, ".color-palette", onTraceColorPalette);*/
	
	/** FILTER BUTTONS **/

	function toggleFilters() {
		
		$(this).parent().parent().toggleClass("open");
	}

	function toggleFilterType() {
			
		if(!$("[data-id='filter-type'] .select-content").hasClass("open")) {

			var selected_type = $("[data-id='filter-type']").attr("data-type"),
				filters = $("[data-id='filter-type'] .filter-select");

			filters.each(function() {

				($(this).attr("data-type") == selected_type) ? $(this).addClass("selected") : $(this).removeClass("selected");
			});
		}

		$("[data-id='filter-type'] .select-content").toggleClass("open");
	}

	function selectFilterType() {
		
		if(!$(this).hasClass("disabled")) {
		
			$("[data-id='filter-type']").attr("data-type", $(this).attr("data-type"));
			$("[data-id='filter-type'] span").html('<i class="' + $(this).find("i").attr("class") + '"></i>' + $(this).text());
			$("[data-id='filter-type'] .select-content").toggleClass("open");
			$("#chart").addClass("run_again");
		}
	}

	function selectOption() {

		var span = $(this).parent().parent().find("span");
		span.html($(this).html());
		span.parent().attr("data-value", $(this).attr("data-value"));
		$(this).parent().parent().find(".select-content").toggleClass("open");
	}

	function toggleCheckbox() {
		
		if($(this).hasClass("radio")) {
			
			$(this).parent().find(".radio").each(function() {
				
				$(this).removeClass("checked");
			});
			
			$(this).addClass("checked");
			
		} else {
			
			$(this).toggleClass("checked");
		}
		
		if($(this).hasClass("checked")) {
			
			$(this).parent().parent().next(".filter:not(.hidden)").addClass("open");
			
			if($(this).parent().parent().next(".filter:not(.hidden)").attr("data-name") == "gene_symbol") {
				
				$("#chart_settings").addClass("open");
			}
			
			$("#filters .trace[data-toggle='" + $(this).attr("data-name") + "']").removeClass("hidden");
		
			if($(this).attr("data-name") == "tissue" && $(".checkbox[data-name='lesions']").hasClass("checked")) {
				
				$("#filters .trace[data-toggle='lesions']").removeClass("hidden").addClass("open");
			}			
			
		} else {
			
			$("#filters .trace[data-toggle='" + $(this).attr("data-name") + "']").addClass("hidden");
		
			if($(this).attr("data-name") == "tissue") {
				
				$("#filters .trace[data-toggle='lesions']").addClass("hidden");
			}
		}
	}

	function toggleFilterSelect() {

		$(this).parent().find(".select-content").toggleClass("open");
	}

	function toggleTrace() {

		$(this).parent().parent().toggleClass("open");
	}
	
	function toggleTypeRadio() {
		
		/*$(this).parent().find(".checkbox").each(function() {
			
			$(this).removeClass("checked");
		});*/
		
		$(this).toggleClass("checked");
		
		$("#chart_settings").removeClass("open");
		
		if($(".fl[data-name='clinical_data']").hasClass("checked")) {

			$("#filters .filter[data-type='clinical']").removeClass("open");
			$("#filters .filter[data-type='clinical']").first().addClass("open");
			$("#filters .filter[data-type='clinical']").removeClass("hidden");
			
		} else {

			$("#filters .filter[data-type='clinical']").addClass("hidden");			
		}

		if($(".fl[data-name='sample_control']").hasClass("checked")) {

			$("#filters .filter[data-type='sample']").removeClass("open");
			$("#filters .filter[data-type='sample']").first().addClass("open");
			$("#filters .filter[data-type='sample']").removeClass("hidden");
			
		} else {

			$("#filters .filter[data-type='sample']").each(function() {
				
				$(this).find(".checkbox").removeClass("checked");
				
				$(this).find(".trace[data-toggle]:not([data-toggle=''])").each(function() {
					
					$(this).find(".checkbox").addClass("checked");
					$(this).removeClass("open").addClass("hidden");	
				});
			});

			$("#filters .filter[data-type='sample']").addClass("hidden");					
		}		
		
		/*if($(this).attr("data-name") == 'clinical_data') {
			
			$("#filters .filter[data-type='sample']").each(function() {
				
				$(this).find(".checkbox").removeClass("checked");
				
				$(this).find(".trace[data-toggle]:not([data-toggle=''])").each(function() {
					
					$(this).find(".checkbox").addClass("checked");
					$(this).removeClass("open").addClass("hidden");	
				});
			});
			
			$("#filters .filter[data-type='sample']").addClass("hidden");
			$("#filters .filter[data-type='clinical']").removeClass("open");
			$("#filters .filter[data-type='clinical']").first().addClass("open");
			$("#filters .filter[data-type='clinical']").removeClass("hidden");
		
		} else {

			$("#filters .filter[data-type='clinical']").each(function() {
				
				$(this).find(".checkbox").removeClass("checked");
			});

			$("#filters .filter[data-type='sample']").removeClass("open");
			$("#filters .filter[data-type='sample']").first().addClass("open");
			$("#filters .filter[data-type='sample']").removeClass("hidden");
			$("#filters .filter[data-type='clinical']").addClass("hidden");			
		}	*/	
	}

	$("#filters .filter-toggle, #filters .filter-title").on(clickEvent, toggleFilters);
	$("#filters .select[data-id='filter-type'] span").on(clickEvent, toggleFilterType);
	$("#filters .select[data-id='filter-type'] .filter-select").on(clickEvent, selectFilterType);
	$("#filters").on(clickEvent, '.trace-toggle, .trace-title', toggleTrace);
	$("#filters").on(clickEvent, '.select:not([data-id="filter-type"]):not([data-trace-filter]) span', toggleFilterSelect);
	$("#filters").on(clickEvent, '.select:not([data-id="filter-type"]):not([data-trace-filter]) .option', selectOption);
	$("#filters").on(clickEvent, '.checkbox:not([data-trace-filter]):not([data-name="clinical_data"]):not([data-name="sample_control"])', toggleCheckbox);
	$("#filters").on(clickEvent, '.checkbox[data-name="clinical_data"], .checkbox[data-name="sample_control"]', toggleTypeRadio);
	
	[].forEach.call(document.querySelectorAll('.selectize'), function(e) { initSelectize(e, true, self_.baseDirectoryUrl); });
	

}
