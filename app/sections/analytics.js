import $ from 'jquery';
import clickEvent from '../utils/clickevent';
import ajax from '../utils/ajax';
import initSelectize from '../utils/selectize';
import { detectTransitionEnd } from '../utils/transitions';
import colorbrewer from 'colorbrewer';

$.fn.extend({

	'gradient': function(palette, direction) {

		if(!$.isArray(palette)) {

			switch (palette) {
				case "RwB": palette = ['#ff0000', '#f0f0f0', '#0000ff'];
				break;
				case "RbG": palette = ['#ff0000', '#000000', '#00ff00'];
				break;
				default:
					palette = colorbrewer[palette][7];
			}
		}

		var dir = '';

		if (direction !== undefined) {
			switch (direction) {
				case 'horiz': case 'horizontal':
					dir = 'to left,';
				break;
				case 'vert': case 'vertical':
					dir = 'to bottom,';
				break;
				default:
					console.log("warning: $.gradient: invalid direction")
				break;
			}
		}

		return this.css('background', 'linear-gradient(' + dir + palette.join(',') + ')');
	}
});

export default function analytics(self_) {

	var sbmt 	  	= document.querySelector(".btn[data-name='run_analysis']"),
        sbtm_nav    = document.querySelector(".btn[data-name='run_analysis_nav']"),
		filters   	= document.querySelectorAll("#filters .filter:not(:first-of-type)"),
		chart 	  	= document.querySelector("#chart"),
		loading   	= false,
        plotly_json = [],
		tab_index 	= 0;

	const reset_state = getFilters(true);
	reset_state.filter.type.clinical = false;
	reset_state.filter.type.sample = false;


    /* Keyboard shortcut for debugging purposes */
    document.onkeyup = function(e) {

        if (e.key === '.' && e.ctrlKey) {

            $('button[data-name="run_analysis"]').click();
        }
    }

	/** INIT CHART **/

	function minifyFilters() {

		if(!$("#filters").hasClass("hide")) {

			$(".filter_tabs .minify i").removeClass("fa-angle-double-left");
			$(".filter_tabs .minify i").addClass("fa-angle-double-right");
			$(".filter_tabs .minify span").text("Show filters");
			$(".filter_tabs .minify").parent().addClass("minified");

		} else {

			$(".filter_tabs .minify i").removeClass("fa-angle-double-right");
			$(".filter_tabs .minify i").addClass("fa-angle-double-left");
			$(".filter_tabs .minify span").text("Hide filters");
			$(".filter_tabs .minify").parent().removeClass("minified");
		}

		$("#filters").toggleClass("hide");
		$(".filter_tabs .pre").toggleClass("hide");

		if(!$("#chart").hasClass("run_again")) {

			$("#chart").addClass("loading");
		}
	}

	function resizePlots() {

		if(plotly_json[tab_index]) {

			if(plotly_json[tab_index].plots) {

				for(let i = 0; i < plotly_json[tab_index].plots.length; i += 1) {

					Plotly.Plots.resize(plotly_json[tab_index].plots[i].gd);
				}
			}
		}
	}

	function onFilterTransitionEnd(e) {

		if(/width/i.test(e.propertyName)) {

			resizePlots();
			$("#chart").removeClass("loading");
		}
	}

	chart.addEventListener(detectTransitionEnd(), onFilterTransitionEnd);
	window.addEventListener("onresize", resizePlots);

    function checkVisible(elm) {

        var rect = elm.getBoundingClientRect();
        var viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight);
        return !(rect.bottom < 0 || rect.top - viewHeight >= 0);
    }

    $(window).on("scroll", () => {

        let elem = document.querySelector(".chart_btns");
        let in_view = checkVisible(elem);

        if(in_view) {

            $("#float_nav").addClass("hide");

        } else {

            $("#float_nav").removeClass("hide");
        }
    });

	/** DATA FETCH **/

	function getFilters(force) {

		force = (typeof force === "undefined") ? false : force;

		var type = document.querySelector("#chart_settings .select"),
			symbols = {},
			symbols_arr = {
				ge: document.querySelectorAll(".selectize[data-name='gene_symbols'] .tag"),
				me: document.querySelectorAll(".selectize[data-name='metabolomics_symbols'] .tag"),
				bi: document.querySelectorAll(".selectize[data-name='biomarkers_symbols'] .tag"),
				cy: document.querySelectorAll(".selectize[data-name='cytokines_symbols'] .tag"),
				hc: document.querySelectorAll(".selectize[data-name='hormone_concentration_symbols'] .tag")
			},
			data = {}, s = [];

		var all_gene_symbols = false;

		for(var key in symbols_arr) {

			symbols[key] = [];

            [...symbols_arr[key]].forEach(function(sym) {

                symbols[key].push(sym.innerHTML.trim());
            });
		}

        const clinical_checked = (force) ? true : $(".fl[data-name='clinical_data']").hasClass("checked");
        const sample_checked = (force) ? true : $(".fl[data-name='sample_control']").hasClass("checked");
        const patient_or_control_checked = (force) ? true : $(".fl[data-name='category_patient']").hasClass("checked") || $(".fl[data-name='category_control']").hasClass("checked");

		if(force) {

			$(".fl[data-name='age']").addClass("checked");
			$(".fl[data-name='cycle_phase']").addClass("checked");
			$(".fl[data-name='disease_stage']").addClass("checked");
			$(".fl[data-name='pain']").addClass("checked");
			$(".fl[data-name='hormonal_medication_status']").addClass("checked");
			$(".fl[data-name='hormonal_medication_type']").addClass("checked");
		}

		data.filter = {
            "type": {
                "clinical": clinical_checked,
                "sample": sample_checked
            },
			"category": {
				"patient": sample_checked && $(".fl[data-name='category_patient']").hasClass("checked"),
				"control": sample_checked && $(".fl[data-name='category_control']").hasClass("checked")
			},
			"clinical": {
				"age": clinical_checked && $(".fl[data-name='age']").hasClass("checked"),
				"cycle_phase": clinical_checked && $(".fl[data-name='cycle_phase']").hasClass("checked"),
				"disease_stage": clinical_checked && $(".fl[data-name='disease_stage']").hasClass("checked"),
				"pain": clinical_checked && $(".fl[data-name='pain']").hasClass("checked"),
				"hormonal_medication_status": clinical_checked && $(".fl[data-name='hormonal_medication_status']").hasClass("checked"),
				"hormonal_medication_type": clinical_checked && $(".fl[data-name='hormonal_medication_type']").hasClass("checked"),
				"sub_age": {
					"20_yr": clinical_checked && $(".fl[data-name='age']").hasClass("checked") && $(".fl[data-name='20']").hasClass("checked"),
					"20_29_yr": clinical_checked && $(".fl[data-name='age']").hasClass("checked") && $(".fl[data-name='20_29']").hasClass("checked"),
					"30_39_yr": clinical_checked && $(".fl[data-name='age']").hasClass("checked") && $(".fl[data-name='30_39']").hasClass("checked"),
					"39_yr": clinical_checked && $(".fl[data-name='age']").hasClass("checked") && $(".fl[data-name='39']").hasClass("checked"),
					"unknown": clinical_checked && $(".fl[data-name='age']").hasClass("checked") && $(".fl[data-name='unknown']").hasClass("checked")
				},
				"sub_cycle_phase": {
                    "proliferative": clinical_checked && $(".fl[data-name='cycle_phase']").hasClass("checked") && $(".trace[data-name='sub_cycle_phase'] .fl[data-name='proliferative']").hasClass("checked"),
					"secretory": clinical_checked && $(".fl[data-name='cycle_phase']").hasClass("checked") && $(".trace[data-name='sub_cycle_phase'] .fl[data-name='secretory']").hasClass("checked"),
					"menstrual": clinical_checked && $(".fl[data-name='cycle_phase']").hasClass("checked") && $(".trace[data-name='sub_cycle_phase'] .fl[data-name='menstrual']").hasClass("checked"),
					"medication": clinical_checked && $(".fl[data-name='cycle_phase']").hasClass("checked") && $(".trace[data-name='sub_cycle_phase'] .fl[data-name='medication']").hasClass("checked")
				},
				"sub_disease_stage": {
					"stage_1": clinical_checked && $(".fl[data-name='disease_stage']").hasClass("checked") && $(".fl[data-name='stage_1']").hasClass("checked"),
					"stage_2": clinical_checked && $(".fl[data-name='disease_stage']").hasClass("checked") && $(".fl[data-name='stage_2']").hasClass("checked"),
					"stage_3": clinical_checked && $(".fl[data-name='disease_stage']").hasClass("checked") && $(".fl[data-name='stage_3']").hasClass("checked"),
					"stage_4": clinical_checked && $(".fl[data-name='disease_stage']").hasClass("checked") && $(".fl[data-name='stage_4']").hasClass("checked"),
					"none": clinical_checked && $(".fl[data-name='disease_stage']").hasClass("checked") && $(".fl[data-name='disease_none']").hasClass("checked"),
					"unknown": clinical_checked && $(".fl[data-name='disease_stage']").hasClass("checked") && $(".fl[data-name='disease_unknown']").hasClass("checked")
				},
				"sub_pain": {
					"abdominal": clinical_checked && $(".fl[data-name='pain']").hasClass("checked") && $(".trace[data-name='sub_pain'] .fl[data-name='abdominal']").hasClass("checked"),
					"menstrual": clinical_checked && $(".fl[data-name='pain']").hasClass("checked") && $(".trace[data-name='sub_pain'] .fl[data-name='pain_menstrual']").hasClass("checked"),
					"intercourse": clinical_checked && $(".fl[data-name='pain']").hasClass("checked") && $(".trace[data-name='sub_pain'] .fl[data-name='intercourse']").hasClass("checked"),
					"defecation": clinical_checked && $(".fl[data-name='pain']").hasClass("checked") && $(".trace[data-name='sub_pain'] .fl[data-name='defecation']").hasClass("checked"),
					"urination": clinical_checked && $(".fl[data-name='pain']").hasClass("checked") && $(".trace[data-name='sub_pain'] .fl[data-name='urination']").hasClass("checked")
				},
				"sub_hormonal_medication_status": {
					"yes": clinical_checked && $(".fl[data-name='hormonal_medication_status']").hasClass("checked") && $(".fl[data-name='yes']").hasClass("checked"),
					"no": clinical_checked && $(".fl[data-name='hormonal_medication_status']").hasClass("checked") && $(".fl[data-name='no']").hasClass("checked"),
					"unknown": clinical_checked && $(".fl[data-name='hormonal_medication_status']").hasClass("checked") && $(".fl[data-name='medication_status_unknown']").hasClass("checked")
				},
				"sub_hormonal_medication_type": {
					"progesterone": clinical_checked && $(".fl[data-name='hormonal_medication_type']").hasClass("checked") && $(".fl[data-name='progesterone']").hasClass("checked"),
					"combined": clinical_checked && $(".fl[data-name='hormonal_medication_type']").hasClass("checked") && $(".fl[data-name='combined']").hasClass("checked"),
					"gnrh_agonist": clinical_checked && $(".fl[data-name='hormonal_medication_type']").hasClass("checked") && $(".fl[data-name='gnrh_agonist']").hasClass("checked"),
					"iud": clinical_checked && $(".fl[data-name='hormonal_medication_type']").hasClass("checked") && $(".fl[data-name='iud']").hasClass("checked"),
					"none": clinical_checked && $(".fl[data-name='hormonal_medication_type']").hasClass("checked") && $(".fl[data-name='medication_type_none']").hasClass("checked"),
					"unknown": clinical_checked && $(".fl[data-name='hormonal_medication_type']").hasClass("checked") && $(".fl[data-name='medication_type_unknown']").hasClass("checked")
				}
			},
            "samples": {
                "tissue": sample_checked && patient_or_control_checked && $(".fl[data-name='tissue']").hasClass("checked"),
                "blood": sample_checked && patient_or_control_checked && $(".fl[data-name='blood']").hasClass("checked"),
                "fluids": sample_checked && patient_or_control_checked && $(".fl[data-name='fluids']").hasClass("checked")
            },
			"sample_type": {
				"blood": {
					"serum": sample_checked && patient_or_control_checked && ($(".fl[data-name='blood']").hasClass("checked") && $(".fl[data-name='serum']").hasClass("checked")),
					"plasma": sample_checked && patient_or_control_checked && ($(".fl[data-name='blood']").hasClass("checked") && $(".fl[data-name='plasma']").hasClass("checked"))
				},
				"peritoneal_fluid": sample_checked && patient_or_control_checked && ($(".fl[data-name='fluids']").hasClass("checked") && $(".fl[data-name='peritoneal_fluid']").hasClass("checked")),
				"urine": sample_checked && patient_or_control_checked && ($(".fl[data-name='fluids']").hasClass("checked") && $(".fl[data-name='urine']").hasClass("checked")),
				"tissue": {
                    "lesions": sample_checked && patient_or_control_checked && $(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked"),
					"endometrium": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='endometrium']").hasClass("checked")),
					"peritoneum": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='peritoneum']").hasClass("checked")),
					"lesion": {
						"peritoneal_lesion": {
							"white": sample_checked && patient_or_control_checked &&  ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='white']").hasClass("checked")),
							"black": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='black']").hasClass("checked")),
							"red": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='red']").hasClass("checked"))
						},
						"deep_endometriosis_lesion": {
							"SA": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SA']").hasClass("checked")),
							"SAO": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SAO']").hasClass("checked")),
							"SAV": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SAV']").hasClass("checked")),
							"RE": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='RE']").hasClass("checked")),
							"SI": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SI']").hasClass("checked")),
							"SU": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='SU']").hasClass("checked")),
							"VI": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='VI']").hasClass("checked")),
							"IL": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='IL']").hasClass("checked")),
							"AP": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='AP']").hasClass("checked"))
						},
						"ovarian_lesion": {
							"O": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='O']").hasClass("checked")),
							"OO": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='OO']").hasClass("checked")),
							"OV": sample_checked && patient_or_control_checked && ($(".fl[data-name='tissue']").hasClass("checked") && $(".fl[data-name='lesions']").hasClass("checked") && $(".fl[data-name='OV']").hasClass("checked"))
						}
					}
				}
			},
			"modules": {
				"ge": $(".fl[data-name='ge']").hasClass("checked"),
				"metabolomics": $(".fl[data-name='metabolomics']").hasClass("checked"),
				"biomarkers": $(".fl[data-name='biomarkers']").hasClass("checked"),
				"cytokines": $(".fl[data-name='cytokines']").hasClass("checked"),
				"hormone_concentration": $(".fl[data-name='hormone_concentration']").hasClass("checked")
			}
		};

		if(force) {

			$(".fl[data-name='age']").removeClass("checked");
			$(".fl[data-name='cycle_phase']").removeClass("checked");
			$(".fl[data-name='disease_stage']").removeClass("checked");
			$(".fl[data-name='pain']").removeClass("checked");
			$(".fl[data-name='hormonal_medication_status']").removeClass("checked");
			$(".fl[data-name='hormonal_medication_type']").removeClass("checked");
		}

        function filter_shortcut(filters, key) {
            filters[key] = true;
            const sub_filters = filters['sub_'+key];

            // none -> choose all
            if (!Object.values(sub_filters).some((x)=>x)) {
                for (let sub_key in sub_filters) {
                    sub_filters[sub_key] = true;
                }
            }
        }

		const chart_type = $(".select[data-id='filter-type']").attr("data-type");
		var chart = chart_type;

		switch (chart_type) {
			case "box": chart = "Boxplot"; break;
			case "heatmap": chart = "Heatmap"; break;
			case "correlation": chart = "Corrplot"; break;
			case "pca": chart = "PCA"; break;
			case "mds": chart = "MDS"; break;
			case "lfda": chart = "LFDA"; break;
		}

        // shortcut filters with PCA's color.by as it needs some data from them
        if (chart_type === "pca" || chart_type === "mds" || chart_type === "lfda") {

            const color_by = $(".fl[data-name='color_by']").attr("data-value");

            switch (color_by) {
                case "Disease Stage": filter_shortcut(data.filter.clinical, 'disease_stage'); break;
                case "Cycle Phase": filter_shortcut(data.filter.clinical, 'cycle_phase'); break;
                case "Age": filter_shortcut(data.filter.clinical, 'age'); break;
                case "Hormonal Medication Status": filter_shortcut(data.filter.clinical, 'hormonal_medication_status'); break;
            }
        }

        $(".fl.gradients[data-name='spectrum'] figure").each(function(e) {

            var attr = $(this).attr("data-value");
            $(this).gradient(attr);
        });

        var color_spectrum = '';

        $(".fl.gradients[data-name='spectrum'] figure").each(function(e) {

            if($(this).hasClass("selected")) {
                color_spectrum = $(this).attr("data-value");
            }
        });

        data.local = {
            chart: chart,
            gene: symbols.ge,
            metabolomics: symbols.me,
			biomarkers: symbols.bi,
			cytokines: symbols.cy,
			hormone_concentration: symbols.hc,
            combine_lesions: $(".fl[data-name='combine_lesions']").hasClass("checked"),
            "log2": $(".fl[data-name='log2']").hasClass("checked"),
            legend: $(".fl[data-name='legend']").hasClass("checked"),
            expand_legend_acronyms: $(".fl[data-name='expand-legend-acronyms']").hasClass("checked"),
            plot_counts: $(".fl[data-name='plot_counts']").hasClass("checked"),
            plot_scree: $(".fl[data-name='plot_scree']").hasClass("checked"),
            summarise_by: $(".fl[data-name='summarise_by']").attr("data-value"),
            data_centering: $(".fl[data-name='data_centering']").attr("data-value"),
            cluster_heatmap: $(".fl[data-name='cluster_heatmap']").hasClass("checked"),
            distance_metric: $(".fl[data-name='distance_metric']").attr("data-value"),
            clustering_method: $(".fl[data-name='clustering_method']").attr("data-value"),
            color_spectrum: color_spectrum,
            lfda_metric: $(".fl[data-name='lfda_metric']").attr("data-value"),
            correlation_method: $(".fl[data-name='correlation_method']").attr("data-value")
        };

		data.params = {

			"function.name": {
				"Chart type": chart
			},
			"gene.symbol": (all_gene_symbols) ? "__all__" : symbols.ge.join(","),
			"metabolomics.symbol": symbols.me.join(","),
			"biomarkers.symbol": symbols.bi.join(","),
			"cytokines.symbol": symbols.cy.join(","),
			"hormone_concentration.symbol": symbols.hc.join(","),
			"font.size": {
				"Select value": 1.5
			},
			"combine.lesions": $(".fl[data-name='combine_lesions']").hasClass("checked"),
			"log2": $(".fl[data-name='log2']").hasClass("checked"),
			"legend": $(".fl[data-name='legend']").hasClass("checked"),
			"expand_legend_acronyms": $(".fl[data-name='expand-legend-acronyms']").hasClass("checked"),
			"show.ellipses": $(".fl[data-name='show_ellipses']").hasClass("checked"),
			"label.ellipses": $(".fl[data-name='label_ellipses']").hasClass("checked"),
			"plot.counts": $(".fl[data-name='plot_counts']").hasClass("checked"),
			"plot.scree": $(".fl[data-name='plot_scree']").hasClass("checked"),
			"stat.fun": {
				"Summarise by": $(".fl[data-name='summarise_by']").attr("data-value")
			},
			"cluster.heatmap": $(".fl[data-name='cluster_heatmap']").hasClass("checked"),
			"center.by": {
				"Center by": $(".fl[data-name='data_centering']").attr("data-value")
			},
            "distance.metric": $(".fl[data-name='distance_metric']").attr("data-value"),
            "corr.method": $(".fl[data-name='correlation_method']").attr("data-value"),
            "color.by": $(".fl[data-name='color_by']").attr("data-value"),
            "clustering.method": $(".fl[data-name='clustering_method']").attr("data-value"),
            "color.spectrum": color_spectrum,
			"do.pdf": false,
			".scale": false,
            "lfda.metric": $(".fl[data-name='lfda_metric']").attr("data-value")
		};

		return data;
	}

    /* uutta 14.5.2019 Piirrä filtterit asetus-jsonista */

    function setCheckboxState(elem, state) {

        state = (typeof state !== "undefined") ? state : false;
        (state) ? $(elem).addClass("checked") : $(elem).removeClass("checked");
    }

    function setSelectState(el, val) {

        let elem = $(el);
        val = (typeof val !== "undefined") ? val : null;

        elem.find(".option").each(function() {

            if($(this).attr("data-value") == val) {

                elem.find("span").text($(this).text());
                elem.attr("data-value", val);
            }
        });
    }

    function initJSONFilters(json, force) {

        var filters = (json.filters) ? json.filters : {};
        var local = (json.local) ? json.local : {};
		force = (typeof force === "undefined") ? false : force;

        $("#filters .fl.checkbox").removeClass("checked");

        filters.type = (filters.type) ? filters.type : {};

        setCheckboxState(".fl[data-name='clinical_data']", filters.type.clinical);
        setCheckboxState(".fl[data-name='sample_control']", filters.type.sample);

        filters.category = (filters.category) ? filters.category : {};
        setCheckboxState(".fl[data-name='category_patient']", filters.category.patient);
        setCheckboxState(".fl[data-name='category_control']", filters.category.control);

        filters.clinical = (filters.clinical) ? filters.clinical : {};
        setCheckboxState(".fl[data-name='age']", filters.clinical.age);
        setCheckboxState(".fl[data-name='cycle_phase']", filters.clinical.cycle_phase);
        setCheckboxState(".fl[data-name='disease_stage']", filters.clinical.disease_stage);
        setCheckboxState(".fl[data-name='pain']", filters.clinical.pain);
        setCheckboxState(".fl[data-name='hormonal_medication_status']", filters.clinical.hormonal_medication_status);
        setCheckboxState(".fl[data-name='hormonal_medication_type']", filters.clinical.hormonal_medication_type);

        if(filters.clinical.age || force) {

            filters.clinical.sub_age = (filters.clinical.sub_age) ? filters.clinical.sub_age : {};
          /*
            setCheckboxState(".fl[data-name='22']", filters.clinical.sub_age['22_yr']);
            setCheckboxState(".fl[data-name='22_26']", filters.clinical.sub_age['22_26_yr']);
            setCheckboxState(".fl[data-name='27_30']", filters.clinical.sub_age['27_30_yr']);
            setCheckboxState(".fl[data-name='31_34']", filters.clinical.sub_age['31_34_yr']);
            setCheckboxState(".fl[data-name='35_38']", filters.clinical.sub_age['35_38_yr']);
            setCheckboxState(".fl[data-name='39_42']", filters.clinical.sub_age['39_42_yr']);
            setCheckboxState(".fl[data-name='42']", filters.clinical.sub_age['42_yr']);
            */
            setCheckboxState(".fl[data-name='20']", filters.clinical.sub_age['20_yr']);
            setCheckboxState(".fl[data-name='20_29']", filters.clinical.sub_age['20_29_yr']);
            setCheckboxState(".fl[data-name='30_39']", filters.clinical.sub_age['30_39_yr']);
            setCheckboxState(".fl[data-name='39']", filters.clinical.sub_age['39_yr']);
            setCheckboxState(".fl[data-name='unknown']", filters.clinical.sub_age.unknown);
        }

        if(filters.clinical.cycle_phase || force) {

            filters.clinical.sub_cycle_phase = (filters.clinical.sub_cycle_phase) ? filters.clinical.sub_cycle_phase : {};
            setCheckboxState(".fl[data-name='proliferative']", filters.clinical.sub_cycle_phase.proliferative);
            setCheckboxState(".fl[data-name='secretory']", filters.clinical.sub_cycle_phase.secretory);
            setCheckboxState(".fl[data-name='menstrual']", filters.clinical.sub_cycle_phase.menstrual);
            setCheckboxState(".fl[data-name='medication']", filters.clinical.sub_cycle_phase.medication);
        }

        if(filters.clinical.disease_stage || force) {

            filters.clinical.sub_disease_stage = (filters.clinical.sub_disease_stage) ? filters.clinical.sub_disease_stage : {};
            setCheckboxState(".fl[data-name='stage_1']", filters.clinical.sub_disease_stage['stage_1']);
            setCheckboxState(".fl[data-name='stage_2']", filters.clinical.sub_disease_stage['stage_2']);
            setCheckboxState(".fl[data-name='stage_3']", filters.clinical.sub_disease_stage['stage_3']);
            setCheckboxState(".fl[data-name='stage_4']", filters.clinical.sub_disease_stage['stage_4']);
            setCheckboxState(".fl[data-name='disease_none']", filters.clinical.sub_disease_stage.none);
            setCheckboxState(".fl[data-name='disease_unknown']", filters.clinical.sub_disease_stage.unknown);
        }

        if(filters.clinical.patient || force) {

            filters.clinical.sub_pain = (filters.clinical.sub_pain) ? filters.clinical.sub_pain : {};
            setCheckboxState(".fl[data-name='abdominal']", filters.clinical.sub_pain.abdominal);
            setCheckboxState(".fl[data-name='pain_menstrual']", filters.clinical.sub_pain.menstrual);
            setCheckboxState(".fl[data-name='intercourse']", filters.clinical.sub_pain.intercourse);
            setCheckboxState(".fl[data-name='defecation']", filters.clinical.sub_pain.defecation);
            setCheckboxState(".fl[data-name='urination']", filters.clinical.sub_pain.urination);
        }

        if(filters.clinical.hormonal_medication_status || force) {

            filters.clinical.sub_hormonal_medication_status = (filters.clinical.sub_hormonal_medication_status) ? filters.clinical.sub_hormonal_medication_status : {};
            setCheckboxState(".fl[data-name='yes']", filters.clinical.sub_hormonal_medication_status.yes);
            setCheckboxState(".fl[data-name='no']", filters.clinical.sub_hormonal_medication_status.no);
            setCheckboxState(".fl[data-name='medication_status_unknown']", filters.clinical.sub_hormonal_medication_status.unknown);
        }

        if(filters.clinical.hormonal_medication_type || force) {

            filters.clinical.sub_hormonal_medication_type = (filters.clinical.sub_hormonal_medication_type) ? filters.clinical.sub_hormonal_medication_type : {};
            setCheckboxState(".fl[data-name='progesterone']", filters.clinical.sub_hormonal_medication_type.progesterone);

            setCheckboxState(".fl[data-name='progesterone']", filters.clinical.sub_hormonal_medication_type.progesterone);
            setCheckboxState(".fl[data-name='combined']", filters.clinical.sub_hormonal_medication_type.combined);
            setCheckboxState(".fl[data-name='gnrh_agonist']", filters.clinical.sub_hormonal_medication_type.gnrh_agonist);
            setCheckboxState(".fl[data-name='iud']", filters.clinical.sub_hormonal_medication_type.iud);
            setCheckboxState(".fl[data-name='medication_type_none']", filters.clinical.sub_hormonal_medication_type.none);
            setCheckboxState(".fl[data-name='medication_type_unknown']", filters.clinical.sub_hormonal_medication_type.unknown);
        }

        filters.samples = (filters.samples) ? filters.samples : {};

        setCheckboxState(".fl[data-name='tissue']", filters.samples.tissue);
        setCheckboxState(".fl[data-name='blood']", filters.samples.blood);
        setCheckboxState(".fl[data-name='fluids']", filters.samples.fluids);

        filters.sample_type = (filters.sample_type) ? filters.sample_type : {};

        if(filters.samples.blood || force) {

            filters.sample_type.blood = (filters.sample_type.blood) ? filters.sample_type.blood : {};
            setCheckboxState(".fl[data-name='serum']", filters.sample_type.blood.serum);
            setCheckboxState(".fl[data-name='plasma']", filters.sample_type.blood.plasma);
        }

        if(filters.samples.fluids || force) {

            setCheckboxState(".fl[data-name='peritoneal_fluid']", filters.sample_type.peritoneal_fluid);
            setCheckboxState(".fl[data-name='urine']", filters.sample_type.urine);
        }

        if(filters.samples.tissue || force) {

            filters.sample_type.tissue = (filters.sample_type.tissue) ? filters.sample_type.tissue : {};
            setCheckboxState(".fl[data-name='endometrium']", filters.sample_type.tissue.endometrium);
            setCheckboxState(".fl[data-name='peritoneum']", filters.sample_type.tissue.peritoneum);
            setCheckboxState(".fl[data-name='lesions']", filters.sample_type.tissue.lesions);

            if(filters.sample_type.tissue.lesions || force) {

                filters.sample_type.tissue.lesion = (filters.sample_type.tissue.lesion) ? filters.sample_type.tissue.lesion : {};
                filters.sample_type.tissue.lesion.peritoneal_lesion = (filters.sample_type.tissue.lesion.peritoneal_lesion) ? filters.sample_type.tissue.lesion.peritoneal_lesion : {};
                filters.sample_type.tissue.lesion.deep_endometriosis_lesion = (filters.sample_type.tissue.lesion.deep_endometriosis_lesion) ? filters.sample_type.tissue.lesion.deep_endometriosis_lesion : {};
                filters.sample_type.tissue.lesion.ovarian_lesion = (filters.sample_type.tissue.lesion.ovarian_lesion) ? filters.sample_type.tissue.lesion.ovarian_lesion : {};

                setCheckboxState(".fl[data-name='white']", filters.sample_type.tissue.lesion.peritoneal_lesion.white);
                setCheckboxState(".fl[data-name='black']", filters.sample_type.tissue.lesion.peritoneal_lesion.black);
                setCheckboxState(".fl[data-name='red']", filters.sample_type.tissue.lesion.peritoneal_lesion.red);

                setCheckboxState(".fl[data-name='SA']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['SA']);
                setCheckboxState(".fl[data-name='SA']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['SA']);
                setCheckboxState(".fl[data-name='SAO']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['SAO']);
                setCheckboxState(".fl[data-name='SAV']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['SAV']);
                setCheckboxState(".fl[data-name='RE']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['RE']);
                setCheckboxState(".fl[data-name='SI']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['SI']);
                setCheckboxState(".fl[data-name='SU']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['SU']);
                setCheckboxState(".fl[data-name='VI']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['VI']);
                setCheckboxState(".fl[data-name='IL']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['IL']);
                setCheckboxState(".fl[data-name='AP']", filters.sample_type.tissue.lesion.deep_endometriosis_lesion['AP']);

                setCheckboxState(".fl[data-name='O']", filters.sample_type.tissue.lesion.ovarian_lesion['O']);
                setCheckboxState(".fl[data-name='OO']", filters.sample_type.tissue.lesion.ovarian_lesion['OO']);
                setCheckboxState(".fl[data-name='OV']", filters.sample_type.tissue.lesion.ovarian_lesion['OV']);
            }
        }

        filters.modules = (filters.modules) ? filters.modules : {};
        setCheckboxState(".fl[data-name='ge']", filters.modules.ge);
        setCheckboxState(".fl[data-name='metabolomics']", filters.modules.metabolomics);
        setCheckboxState(".fl[data-name='biomarkers']", filters.modules.biomarkers);
        setCheckboxState(".fl[data-name='cytokines']", filters.modules.cytokines);
        setCheckboxState(".fl[data-name='hormone_concentration']", filters.modules.hormone_concentration);

        let chart_elem = document.querySelector(".select[data-id='filter-type']");

        [...chart_elem.querySelectorAll(".filter-select")].map((fs) => {
            fs.classList.remove("selected");
        })

        let chart_type = '';

        switch(json.local.chart) {

            case "Boxplot":
                chart_elem.setAttribute("data-type", "box");
                chart_elem.querySelector("span").innerHTML = '<i class="icon-plot-box"></i> Box plot';
                chart_elem.querySelector(".filter-select[data-type='box']").classList.add("selected");
                chart_type = 'box';

            break;
            case "Heatmap":
                chart_elem.setAttribute("data-type", "heatmap");
                chart_elem.querySelector("span").innerHTML = '<i class="icon-plot-heatmap"></i> Heatmap';
                chart_elem.querySelector(".filter-select[data-type='heatmap']").classList.add("selected");
                chart_type = 'heatmap';
            break;
            case "Corrplot":
                chart_elem.setAttribute("data-type", "correlation");
                chart_elem.querySelector("span").innerHTML = '<i class="icon-plot-bar"></i> Correlation';
                chart_elem.querySelector(".filter-select[data-type='correlation']").classList.add("selected");
                chart_type = 'correlation';
            break;
            case "PCA":
                chart_elem.setAttribute("data-type", "pca");
                chart_elem.querySelector("span").innerHTML = '<i class="icon-plot-hist"></i> Principal Component Analysis (PCA)';
                chart_elem.querySelector(".filter-select[data-type='pca']").classList.add("selected");
                chart_type = 'pca';
            break;
            case "MDS":
                chart_elem.setAttribute("data-type", "mds");
                chart_elem.querySelector("span").innerHTML = '<i class="icon-contour"></i> Multidimensional Scaling (MDS)';
                chart_elem.querySelector(".filter-select[data-type='mds']").classList.add("selected");
                chart_type = 'mds';
            break;
            case "LFDA":
                chart_elem.setAttribute("data-type", "lfda");
                chart_elem.querySelector("span").innerHTML = '<i class="icon-contour"></i> Local Fisher Discriminant Analysis (LFDA)';
                chart_elem.querySelector(".filter-select[data-type='lfda']").classList.add("selected");
                chart_type = 'lfda';
            break;
            default:
                chart_type = 'box';
        }

        $("#chart_settings .chart_settings_filter").each(function() {

            var t = $(this).attr("data-type").split("|");
            (t.indexOf(chart_type) > -1) ? $(this).removeClass("hidden") : $(this).addClass("hidden");
        });

        filters.modules = (filters.modules) ? filters.modules : {};

        setCheckboxState(".fl[data-name='ge']", filters.modules.ge);
        setCheckboxState(".fl[data-name='metabolomics']", filters.modules.metabolomics);
        setCheckboxState(".fl[data-name='biomarkers']", filters.modules.biomarkers);
        setCheckboxState(".fl[data-name='cytokines']", filters.modules.cytokines);
        setCheckboxState(".fl[data-name='hormone_concentration']", filters.modules.hormone_concentration);

        ['gene', 'metabolomics', 'biomarkers', 'cytokines', 'hormone_concentration'].map((sym) => {

            let lsym = (sym == "gene") ? "ge" : sym;
            local[sym] = (local[sym]) ? local[sym] : [];

            let tag_elem = document.querySelector(".filter[data-name='" + sym + "_symbol']");

            if(tag_elem !== null) {

                let tags_wrapper = tag_elem.querySelector(".selectize .tags");
                $(".filter[data-name='" + sym + "_symbol'] .selectize .tag").remove();

                if(filters.modules[lsym]) {

                    for (var i = local[sym].length - 1; i >= 0; i--) {
                        tags_wrapper.insertAdjacentHTML("afterbegin", '<div class="tag">' + local[sym][i] + '</div>');
                    }
                }
            }
        });

        setCheckboxState(".fl[data-name='combine_lesions']", local.combine_lesions);
        setCheckboxState(".fl[data-name='log2']", local['log2']);
        setCheckboxState(".fl[data-name='legend']", local.legend);
        setCheckboxState(".fl[data-name='expand-legend-acronyms']", local.expand_legend_acronyms);
        setCheckboxState(".fl[data-name='plot_counts']", local.plot_counts);
        setCheckboxState(".fl[data-name='plot_scree']", local.plot_scree);
        setCheckboxState(".fl[data-name='cluster_heatmap']", local.cluster_heatmap);
        setCheckboxState(".fl[data-name='combine_lesions']", local.combine_lesions);

        setSelectState(".fl[data-name='summarise_by']", local.summarise_by);
        setSelectState(".fl[data-name='data_centering']", local.data_centering);
        setSelectState(".fl[data-name='distance_metric']", local.distance_metric);
        setSelectState(".fl[data-name='clustering_method']", local.clustering_method);
        setSelectState(".fl[data-name='lfda_metric']", local.lfda_metric);
        setSelectState(".fl[data-name='correlation_method']", local.correlation_method);

        $(".fl.gradients[data-name='spectrum'] figure").each(function(e) {

            $(this).removeClass("selected");

            if($(this).attr("data-value") == local.color_spectrum) {

                $(this).addClass("selected");
            }
        });

        toggleFilterVisibility();
    }

	var first_run = true;
	var run_id = 0;

	function onSubmit() {

		if(!self_.dragging && !loading && !$(this).hasClass("disabled")) {

			var data = getFilters();

            let senddata = {
                filter: data.filter,
                params: data.params
            };

			let run_tab_index = parseInt(tab_index);
			let plotly_ind = 0;
			let plot_run_id = 0;

			ajax({

				url: self_.baseDirectoryUrl + 'api/getgraph',
				data: {
					data: JSON.stringify(senddata)
				},
				beforeSend: function() {

					$("#chart").addClass("loading").removeClass("hide");
					$("#chart").removeClass("run_again").removeClass("error");
					$("#filters").addClass("loading");
					($("body").hasClass("logged")) ? $('html, body').animate({ scrollTop: $("#chart").offset().top - 50 }, 400) : $('html, body').animate({ scrollTop: $("#wrapper").offset().top - 50 }, 400);

                    $(sbmt).addClass("disabled");
                    $(sbtm_nav).addClass("disabled");
                    $(".btn[data-name='download_as_pdf_nav']").addClass("disabled");

					if(plotly_json[run_tab_index]) {

						if(plotly_json[run_tab_index].plots) {

							for(let i = 0; i < plotly_json[run_tab_index].plots.length; i += 1) {

								Plotly.purge(plotly_json[run_tab_index].plots[i].gd);
							}
						}

						if(plotly_json[run_tab_index].run_id) {

							plot_run_id = parseInt(plotly_json[run_tab_index].run_id);

						} else {

							run_id += 1;
							plot_run_id = parseInt(run_id);
							plotly_json[run_tab_index].run_id = plot_run_id;
						}

						plotly_json[run_tab_index].run_id = plot_run_id;
						plotly_json[run_tab_index].loading = true;
						plotly_json[run_tab_index].error = false;

					} else {

						run_id += 1;
						plot_run_id = parseInt(run_id);
						plotly_json[run_tab_index] = {

							run_id: plot_run_id,
							loading: true
						}
					}

					$("#chart .chart-area div[data-run-index]").each((i, e) => {

						let ti = parseInt($(e).attr("data-run-index"));

						if(ti == plot_run_id) {

							$(e).remove();
						}
					});

					loading = true;
					drawTabs();
				},
				callback: function(res) {

					const chartData = JSON.parse(res);

                    plotly_json[run_tab_index] = {
                        filters: Object.assign({}, data.filter),
                        local: Object.assign({}, data.local),
                        params: Object.assign({}, data.params),
						name: data.local.chart,
						run_id: plot_run_id,
						loading: false,
						plots: []
                    };

					if(Array.isArray(chartData)) {

						for(var i = 0; i < chartData.length; i += 1) {

							if(typeof chartData[i].nimi !== "undefined") {

								//let title_inserted = false;

								for(var j = 0; j < chartData[i].plots.length; j += 1) {

									plotly_json[run_tab_index].plots[plotly_ind] = {
										d3: Plotly.d3
									};

									plotly_json[run_tab_index].plots[plotly_ind].gd3 = plotly_json[run_tab_index].plots[plotly_ind].d3.select("#chart .chart-area").append('div').style({ width: 100 + '%', height: 80 + 'vh' }).attr("data-index", (plotly_ind+1)).attr("data-run-index", plot_run_id);
									plotly_json[run_tab_index].plots[plotly_ind].gd  = plotly_json[run_tab_index].plots[plotly_ind].gd3.node();
									Plotly.plot(plotly_json[run_tab_index].plots[plotly_ind].gd, chartData[i].plots[j]);

									/*if(!title_inserted) {

										var title_elem = document.querySelector("#chart .chart-area div[data-index='" + (plotly_ind + 1)  + "'][data-run-index='" + plot_run_id  + "']");
										console.log("TITLELELEEEM", title_elem);
										if(title_elem) {

											title_elem.insertAdjacentHTML("afterbegin", '<h2 class="clinical_title">' + chartData[i].nimi + '</h2>');
										}

										title_inserted = true;
									}*/

									plotly_ind += 1;

								}

							} else {

								plotly_json[run_tab_index].plots[plotly_ind] = {

									d3: Plotly.d3
								};

								plotly_json[run_tab_index].plots[plotly_ind].gd3 = plotly_json[run_tab_index].plots[plotly_ind].d3.select('#chart .chart-area').append('div').style({ width: 100 + '%', height: 80 + 'vh' }).attr("data-index", (plotly_ind+1)).attr("data-run-index", plot_run_id);
								plotly_json[run_tab_index].plots[plotly_ind].gd  = plotly_json[run_tab_index].plots[plotly_ind].gd3.node();
								Plotly.plot(plotly_json[run_tab_index].plots[plotly_ind].gd, chartData[i]);
								plotly_ind += 1;
							}
						}

					} else if(chartData.type == "json") { // Suora data

							plotly_json[run_tab_index].plots[0] = {

								d3: Plotly.d3
							};

							plotly_json[run_tab_index].plots[0].gd3 = plotly_json[run_tab_index].plots[0].d3.select('#chart .chart-area').append('div').style({ width: 100 + '%', height: 80 + 'vh' }).attr("data-index", 1).attr("data-run-index", plot_run_id);
							plotly_json[run_tab_index].plots[0].gd  = plotly_json[run_tab_index].plots[0].gd3.node();
							Plotly.plot(plotly_json[run_tab_index].plots[0].gd, chartData);
					}

					drawTabs();
					toggleLoading(plotly_json[tab_index].loading);
					togglePlotVisibility(plotly_json[tab_index].run_id);

                    setTimeout(() => {

                        $("#filters").removeClass("loading");
						$("#chart").removeClass("loading");
                        $(sbmt).removeClass("disabled");
                        $(sbtm_nav).removeClass("disabled");
                        $(".btn[data-name='download_as_pdf_nav']").removeClass("disabled");
                        loading = false;

						if(first_run) {

							$(".tab.new_tab").removeClass("hide");
							first_run = false;
						}

                    }, 200);
				},
				error: function() {

					plotly_json[run_tab_index].error = true;
					plotly_json[run_tab_index].loading = false;
					toggleError(true);
					toggleLoading(plotly_json[tab_index].loading);
					togglePlotVisibility(plotly_json[tab_index].run_id);
					drawTabs();
					loading = false;
				}
			});
		}
	}

	function onDownloadPDF(e) {

		e.preventDefault();

		if(!self_.dragging && !$(this).hasClass("disabled") && !$(this).hasClass("loading") && !plotly_json[tab_index].loading) {

            var pdfbtn = document.querySelector(".btn[data-name='download_as_pdf_nav']"),
				data = {
					filter: Object.assign({}, plotly_json[tab_index].filters),
					params: Object.assign({}, plotly_json[tab_index].params)
				};

			data.params["do.pdf"] = true;
			const pdf_run_tab = parseInt(tab_index);

			ajax({

				url: self_.baseDirectoryUrl + 'api/getpdfgraph',
				data: {
					data: JSON.stringify(data)
				},
				beforeSend: function() {

					$(pdfbtn).addClass("loading");
					pdfbtn.insertAdjacentHTML("afterbegin", '<div class="loader"><span></span></div>');
					loading = true;
				},
				callback: function(res) {

					setTimeout(function() {

						$(pdfbtn).removeClass("loading");
						pdfbtn.removeChild(pdfbtn.querySelector(".loader"));
						loading = false;
						res = JSON.parse(res);
						window.location.href = self_.baseDirectoryUrl + 'api/pdf/' + res.id;

					}, 200);
				},
				error: function() {

					$(pdfbtn).removeClass("loading");
					pdfbtn.removeChild(pdfbtn.querySelector(".loader"));
					loading = false;
				}
			});
		}
	}

	function toggleError(err) {

		err = (typeof err !== "undefined") ? err : false;

		if(err) {

			$("#chart").addClass("run_again").addClass("error");

		} else {

			$("#chart").removeClass("run_again").removeClass("error");
		}
	}

	function toggleLoading(ldng) {

		if(ldng) {

			$("#chart").addClass("loading").removeClass("hide");
			$("#filters").addClass("loading");
			$(sbmt).addClass("disabled");
			$(sbtm_nav).addClass("disabled");

		} else {

			$("#filters").removeClass("loading");
			$("#chart").removeClass("loading");
			$(sbmt).removeClass("disabled");
			$(sbtm_nav).removeClass("disabled");
		}
	}

	function togglePlotVisibility(rid) {

		$("#chart .chart-area div[data-run-index]").each((i, e) => {

			let ti = parseInt($(e).attr("data-run-index"));

			if(ti != rid) {

				$(e).css("display", "none");

			} else {

				$(e).css("display", "block");
			}
		});
	}

    function drawTabs() {

        let html = '';
        let c = 1;

        plotly_json.map((pj, i) => {
            let n = (pj.name) ? pj.name + " " + c + "." : 'Analysis ' + c + ".";
            let selected = (tab_index == i) ? ' selected' : '';
			let ldng = (typeof pj.loading !== "undefined") ? pj.loading : false;
			ldng = (ldng) ? ' tab_loading' : '';

            html += `<div class="${"tab analysis" + selected + ldng}" data-index="${i}"><i class="fa fa-bar-chart"></i><span>${n}</span></div>`;
            c += 1;
        });

        html += '<div class="tab new_tab"><i class="fa fa-plus"></i></div>';
        $(".analysis_tabs .filter_tabs:last-of-type").html(html);
    }

    function toggleTabs(e) {

        if(!self_.dragging) {

			let t = $(this);
            let cur_tab = parseInt(tab_index);
            let new_tab = null;
            let curdata = getFilters();
			let resetdata = JSON.parse(JSON.stringify(reset_state));
			let cur_run_id = (plotly_json[cur_tab]) ? parseInt(plotly_json[cur_tab].run_id) : 0;

			console.log(resetdata);

			const rkeys = {
				clinical: ['age', 'cycle_phase', 'disease_stage', 'hormonal_medication_status', 'hormonal_medication_type', 'pain'],
				samples: ['tissue', 'blood', 'fluids']
			};

			const skeys = {
				clinical: ['sub_age', 'sub_cycle_phase', 'sub_disease_stage', 'sub_hormonal_medication_status', 'sub_hormonal_medication_type', 'sub_pain'],
				samples: ['tissue', 'blood']
			};

			rkeys.clinical.map((rk, i) => {

				if(!curdata.filter.clinical[rk]) {

					console.log(rk, curdata.filter.clinical[skeys.clinical[i]], resetdata.filter.clinical[skeys.clinical[i]]);

					curdata.filter.clinical[skeys.clinical[i]] = Object.assign({}, resetdata.filter.clinical[skeys.clinical[i]]);
				}
			});

			rkeys.samples.map((rk, i) => {

				if(!curdata.filter.samples[rk]) {

					if(rk == "fluids") {

						curdata.filter.sample_type.peritoneal_fluid = true;
						curdata.filter.sample_type.urine = true;

					} else if(rk == "blood") {

						curdata.filter.sample_type.blood.plasma = true;
						curdata.filter.sample_type.blood.serum = true;

					} else {

						console.log(rk, curdata.filter.sample_type[skeys.samples[i]], resetdata.filter.sample_type[skeys.samples[i]]);

						curdata.filter.sample_type[skeys.samples[i]] = Object.assign({}, resetdata.filter.sample_type[skeys.samples[i]]);
					}
				}
			});

            if(t.hasClass("new_tab")) {

				if(plotly_json.length == 0) {

					new_tab = 1;
					run_id += 2;

					plotly_json[0] = {

						filters: Object.assign({}, curdata.filter),
						local: Object.assign({}, curdata.local),
						params: Object.assign({}, curdata.params),
						run_id: run_id
					};

					plotly_json[1] = {

						filters: Object.assign({}, curdata.filter),
						local: Object.assign({}, curdata.local),
						params: Object.assign({}, curdata.params),
						run_id: run_id
					};

				} else {

					new_tab = plotly_json.length;
					run_id += 1;

					plotly_json[new_tab] = {

						filters: Object.assign({}, curdata.filter),
						local: Object.assign({}, curdata.local),
						params: Object.assign({}, curdata.params),
						run_id: run_id
					};
				}

            } else {

                let tindx = (typeof t.attr("data-index") !== "undefined") ? parseInt(t.attr("data-index")) : null;

                if(tindx !== null && plotly_json[tindx]) {

					new_tab = tindx;
				}
            }


            if(new_tab != cur_tab && new_tab !== null && plotly_json[new_tab]) {

                tab_index = parseInt(new_tab);

				plotly_json[cur_tab].filters = Object.assign({}, curdata.filter);
				plotly_json[cur_tab].local = Object.assign({}, curdata.local);
				plotly_json[cur_tab].params = Object.assign({}, curdata.params);

                initJSONFilters(plotly_json[tab_index], true);

				let now_ldng = (typeof plotly_json[tab_index].loading !== "undefined") ? plotly_json[tab_index].loading : false;
				let now_err  = (typeof plotly_json[tab_index].error !== "undefined") ? plotly_json[tab_index].error : false;

				toggleLoading(now_ldng);
				toggleError(now_err);
				togglePlotVisibility(plotly_json[tab_index].run_id);
				toggleFilterVisibility();
                drawTabs();
				resizePlots();

                (tab_index == 0) ? $("button[data-name='close']").css("display", "none") : $("button[data-name='close']").removeAttr("style");
            }
        }
    }

	function removeUnusedPlots() {

	}

    function closeTab() {

		let ldng = (typeof plotly_json[tab_index].loading !== "undefined") ? plotly_json[tab_index].loading : false;

        if(tab_index > 0 && !ldng) {

            let next_tab = null;
            let f = false;

            for(let i = 0; i < plotly_json.length; i += 1) {

                if(i == tab_index) { f = true; }
                if(!f) { next_tab = i; }
            }

            next_tab = (next_tab === null) ? 0 : next_tab;

            let splice_ind = [];

			if(plotly_json[tab_index]) {

				if(plotly_json[tab_index].plots) {

					for(let i = 0; i < plotly_json[tab_index].plots.length; i += 1) {

						Plotly.purge(plotly_json[tab_index].plots[i].gd);
					}
				}
			}

			$("#chart .chart-area div[data-run-index]").each((i, e) => {

				let ti = parseInt($(e).attr("data-run-index"));

				if(ti == plotly_json[tab_index].run_id) {

					$(e).remove();
				}
			});

			plotly_json.splice(tab_index, 1);
            tab_index = next_tab;

			let cur_run_id = (plotly_json[tab_index]) ? parseInt(plotly_json[tab_index].run_id) : 0;
			let now_ldng = (typeof plotly_json[tab_index].loading !== "undefined") ? plotly_json[tab_index].loading : false;
			let now_err  = (typeof plotly_json[tab_index].error !== "undefined") ? plotly_json[tab_index].error : false;

			toggleLoading(now_ldng);
			toggleError(now_err);
			togglePlotVisibility(cur_run_id);
            initJSONFilters(plotly_json[tab_index]);
            drawTabs();
			resizePlots();

            (tab_index == 0) ? $("button[data-name='close']").css("display", "none") : $("button[data-name='close']").css("display", "inline-block");
        }
    }

    function resetSettings() {

		if(plotly_json[tab_index]) {

			plotly_json[tab_index].filters = Object.assign({}, reset_state.filter);
			plotly_json[tab_index].params = Object.assign({}, reset_state.params);
			plotly_json[tab_index].local = {};

		}

		const rstate = JSON.parse(JSON.stringify(reset_state));

        initJSONFilters({
			filters: Object.assign({}, rstate.filter),
			params: Object.assign({}, rstate.params),
			local: {}
		});

		$(".fl[data-name='age']").removeClass("checked");
		$(".fl[data-name='cycle_phase']").removeClass("checked");
		$(".fl[data-name='disease_stage']").removeClass("checked");
    }

	/** Listeners **/

	$(".tab.minify").on(clickEvent, minifyFilters);
	$(".analysis_tabs .filter_tabs:last-of-type").on(clickEvent, ".tab", toggleTabs);
    $("button[data-name='close']").on(clickEvent, closeTab);
    $("button[data-name='reset']").on(clickEvent, resetSettings);
	$(sbmt).on(clickEvent, onSubmit);
    $(sbtm_nav).on(clickEvent, onSubmit);
	$(".btn[data-name='download_as_pdf_nav']").on(clickEvent, onDownloadPDF);

	/** FILTER BUTTONS **/

	function toggleFilterVisibility() {

		var filter_type = document.querySelectorAll(".filter[data-type='main_type'] .fl.checkbox"),
			samples = $(".filter[data-type='sample']"),
			clinical = $(".filter[data-type='clinical']"),
			type = null;

		if(!$(filter_type[0]).hasClass("checked") && !$(filter_type[1]).hasClass("checked")) {

			samples.removeClass("open").addClass("hidden");
			clinical.removeClass("open").addClass("hidden");

		} else if($(filter_type[1]).hasClass("checked") && !$(filter_type[0]).hasClass("checked")) {

			type = ".filter[data-type='sample']";
			clinical.removeClass("open").addClass("hidden");

		} else if(!$(filter_type[1]).hasClass("checked") && $(filter_type[0]).hasClass("checked")) {

			type = ".filter[data-type='clinical']";
			samples.removeClass("open").addClass("hidden");

		} else {

			type = ".filter[data-type='clinical'], .filter[data-type='sample']";
		}

		if(type !== null) {

			var stop = false;

			$(type).each(function() {

				if(!stop) {

					$(this).removeClass("hidden").addClass("open");

					// Samples types

					if($(this).attr("data-name") == "sample_type") {

						$(this).find(".checkbox:not(.trace .checkbox)").each(function() {

							if($(this).hasClass("checked")) {

								$("#filters .trace[data-toggle='" + $(this).attr("data-name") + "']").removeClass("hidden");

								if($(this).attr("data-name") == "tissue" && $(".checkbox[data-name='lesions']").hasClass("checked")) {

									$("#filters .trace[data-toggle='lesions']").removeClass("hidden").addClass("open");

								} else if($(this).attr("data-name") == "tissue" && !$(".checkbox[data-name='lesions']").hasClass("checked"))  {

									$("#filters .trace[data-toggle='lesions']").addClass("hidden").removeClass("open");
								}

							} else {

								$("#filters .trace[data-toggle='" + $(this).attr("data-name") + "']").addClass("hidden");

								if($(this).attr("data-name") == "tissue") {

									$("#filters .trace[data-toggle='lesions']").addClass("hidden");
								}
							}
						});

					} else if($(this).attr("data-name") == "clinical") {

						$(this).find(".checkbox:not(.trace .checkbox)").each(function() {

							if($(this).hasClass("checked")) {

								$("#filters .trace[data-toggle='" + $(this).attr("data-name") + "']").removeClass("hidden");

							} else {

								$("#filters .trace[data-toggle='" + $(this).attr("data-name") + "']").addClass("hidden");
							}
						});
					}

					stop = !($(this).find(".checkbox.checked:not(.trace .checkbox.checked)").length > 0);

					if($(this).attr("data-name") == "clinical" && type == ".filter[data-type='clinical'], .filter[data-type='sample']") { stop = false; }

				} else {

					$(this).removeClass("open").addClass("hidden");
				}
			});
		}

		/* Check modules */

		$(".fl[data-requires]").each(function() {

			var req = $(this).data("requires").split(","), v = false;

			for(var i = 0; i < req.length; i += 1) {

				var p = $(".fl[data-name='" + req[i] + "']").parent().parent();

				if(p.hasClass("trace")) {

					if($(".fl[data-name='" + req[i] + "']").hasClass("checked") && $(".fl[data-name='" + p.data("toggle") + "']").hasClass("checked")) {

						v = true;
					}

				} else if($(".fl[data-name='" + req[i] + "']").hasClass("checked")) {

					v = true;
				}
			}

			if(v) {

				($(this).hasClass("select")) ? $(this).parent().removeClass("hidden") : $(this).removeClass("disabled");

			} else {

				($(this).hasClass("select")) ? $(this).parent().addClass("hidden") : $(this).removeClass("checked").addClass("disabled");
			}
		});

		/* Hides for heatmap */

		let cur_plot = $("[data-id='filter-type']").attr("data-type");

		if(cur_plot == "heatmap") {

			let cluster_checked = $(".fl[data-name='cluster_heatmap']").hasClass("checked");

			if(cluster_checked) {

				$(".fl[data-name='distance_metric']").parent().removeClass("hidden");
				$(".fl[data-name='clustering_method']").parent().removeClass("hidden");
				$(".fl[data-name='expand-legend-acronyms']").parent().removeClass("hidden");

			} else {

				$(".fl[data-name='distance_metric']").parent().addClass("hidden");
				$(".fl[data-name='clustering_method']").parent().addClass("hidden");
				$(".fl[data-name='expand-legend-acronyms']").parent().addClass("hidden");
			}
		}

    /* Hides for heatmap */
    else if (cur_plot == "pca") {
			let ellipses_checked = $(".fl[data-name='show_ellipses']").hasClass("checked");
			if(ellipses_checked) {
				$(".fl[data-name='label_ellipses']").parent().removeClass("hidden");
      } else {
				$(".fl[data-name='label_ellipses']").parent().addClass("hidden");
      }
      
    }

		/* Symbol visibilities */

		var char_visible = false;

		$(".filter[data-toggle]").each(function() {

			if($(".fl[data-name='" + $(this).data("toggle") + "']").hasClass("checked")) {

				$(this).addClass("open").removeClass("hidden");

				if($(this).find(".tag").length > 0) {

					char_visible = true;
				}

			} else {

				$(this).addClass("hidden").removeClass("open");
				$(this).find(".selectize .tag").each(function() {
					$(this).remove();
				});
			}
		});

		/* Chart setting visibilities */

		const plot_loading = (typeof plotly_json[tab_index] !== "undefined") ? plotly_json[tab_index].loading : false;

		if(char_visible && !plot_loading) {

			$("#chart_settings").addClass("open").removeClass("hidden");
			$(sbmt).removeClass("disabled");
            $(sbtm_nav).removeClass("disabled");
			$(".btn[data-name='download_as_pdf_nav']").removeClass("disabled");

		} else {

			$("#chart_settings").addClass("hidden").removeClass("open");
			$(sbmt).addClass("disabled");
            $(sbtm_nav).addClass("disabled");
			$(".btn[data-name='download_as_pdf_nav']").addClass("disabled");
		}
	}

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

			var dt = $(this).attr("data-type");

			$("[data-id='filter-type']").attr("data-type", dt);
			$("[data-id='filter-type'] span").html('<i class="' + $(this).find("i").attr("class") + '"></i>' + $(this).text());
			$("[data-id='filter-type'] .select-content").toggleClass("open");
			//$("#chart").addClass("run_again");

			$("#chart_settings .chart_settings_filter").each(function(e) {

				var t = $(this).attr("data-type").split("|");
				(t.indexOf(dt) > -1) ? $(this).removeClass("hidden") : $(this).addClass("hidden");
			});

			toggleFilterVisibility();
		}
	}

	function selectOption() {

		var span = $(this).parent().parent().find("span");
		span.html($(this).html());
		span.parent().attr("data-value", $(this).attr("data-value"));
		$(this).parent().parent().find(".select-content").toggleClass("open");
	}

	function toggleCheckbox() {

		if(!$(this).hasClass("disabled")) {

			if($(this).hasClass("radio")) {

				$(this).parent().find(".radio").each(function() {

					$(this).removeClass("checked");
				});

				$(this).addClass("checked");

			} else {

				$(this).toggleClass("checked");
			}

			toggleFilterVisibility();
		}
	}

	function toggleFilterSelect() {

		$(this).parent().find(".select-content").toggleClass("open");
	}

	function toggleTrace() {

		$(this).parent().parent().toggleClass("open");
	}

	function scrollToTop() {

		$('html, body').animate({ scrollTop: $("#chart").offset().top - 50 }, 400);
	}

    function onGradientClick(e) {

        $("#filters .gradients figure").removeClass("selected");
        $(this).addClass("selected");
    }

    $("#filters .gradients figure").on(clickEvent, onGradientClick);
	$("#filters .filter-toggle, #filters .filter-title").on(clickEvent, toggleFilters);
	$("#filters .select[data-id='filter-type'] span").on(clickEvent, toggleFilterType);
	$("#filters .select[data-id='filter-type'] .filter-select").on(clickEvent, selectFilterType);
	$("#filters").on(clickEvent, '.trace-toggle, .trace-title', toggleTrace);
	$("#filters").on(clickEvent, '.select:not([data-id="filter-type"]):not([data-trace-filter]) span', toggleFilterSelect);
	$("#filters").on(clickEvent, '.select:not([data-id="filter-type"]):not([data-trace-filter]) .option', selectOption);
	$("#filters").on(clickEvent, '.checkbox:not([data-trace-filter])', toggleCheckbox);
	$("#scroll_to_top").on(clickEvent, scrollToTop);

    $("[data-name='gene_symbols_reset']").on(clickEvent, function(e) {
        e.preventDefault();
		$(".filter[data-name='gene_symbol'] .selectize .tag").remove();
	});

    $(".fl.gradients[data-name='spectrum'] figure").each(function(e) {

        var attr = $(this).attr("data-value");
        $(this).gradient(attr);
    });

	// Init Selectizes

	ajax({

		url: self_.baseDirectoryUrl + 'api/getsymbols',
		data: {},
		beforeSend: function() {},
		callback: function(res) {

			res = JSON.parse(res);

			[].forEach.call(document.querySelectorAll('.selectize'), function(e) {

				switch(e.getAttribute("data-name")) {

					case "gene_symbols":
						initSelectize(e, res.genes, toggleFilterVisibility, 1);
					break;
					case "metabolomics_symbols":
						initSelectize(e, res.metabolomics, toggleFilterVisibility);
					break;
					case "biomarkers_symbols":
						initSelectize(e, res.biomarkers, toggleFilterVisibility);
					break;
					case "cytokines_symbols":
						initSelectize(e, res.cytokines, toggleFilterVisibility);
					break;
					case "hormone_concentration_symbols":
						initSelectize(e, res.hormone_concentration, toggleFilterVisibility);
					break;
					default:
						return;
				}
			});

		},
		error: function() {}
	});
}
