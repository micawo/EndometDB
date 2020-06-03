import $ from 'jquery';
import datepicker from 'jquery-ui/ui/widgets/datepicker';
import clickEvent from '../utils/clickevent';
import ajax from '../utils/ajax';
import initSelectize from '../utils/selectize';
import { detectTransitionEnd } from '../utils/transitions';

export default function patient(self_, args) {

    var filters       = document.querySelector("#filters"),
        patient_sel   = filters.querySelector(".filter[data-name='patient'] select"),
        physician_sel = filters.querySelector(".filter[data-name='physician'] select"),
        sections      = document.querySelectorAll(".patient_section"),
        tabs          = document.querySelectorAll(".filter_tabs .tab"),
        cloners       = {},
        physicians    = null,
        loading       = true;

    /* New patient / old patient */

    var new_patient  = false,
        patient_id   = 0,
        patient_code = '';

    var real_patient_id = 0;

    if(args[0] !== null) {

        var params = args[0].split("/");

        if(params[0] == "new") {

            new_patient = true;
            $("button[data-name='save_patient_data']").text("Save new patient");
            $("button[data-name='add_patient']").addClass("disabled");

            if(params.length == 2) {

                patient_code = (params[1] != "") ? params[1] : "";
                patient_code = patient_code.replace(/[^\w\s!?]/g,'');
            }

        } else {

            patient_id = params[0];
            patient_id = patient_id.replace(/[^\w\s!?]/g,'');
        }
    }

    // Init date picker

    $(".patient_section[data-type='patient_data'] .datepicker").datepicker({ dateFormat: 'yy-mm-dd' });

    // init cloners

    function onAddClone() {

        var key = this.getAttribute("data-type");
        this.insertAdjacentHTML("afterend", cloners[key].outerHTML);
    }

    function onRemoveClone() {

        var cln = $(this).parent(),
            cln_id = cln.attr("data-id"),
            tpe = cln.parent().parent().attr("data-type");

        if(!isNaN(cln_id)) {

            var r = confirm("Confirm to remove data");

            if(r) {

                ajax({

                    url: self_.baseDirectoryUrl + 'api/removePatientData',
                    data: {
                        id: cln_id,
                        type: tpe
                    },
                    beforeSend: function() {

                        cln.find(".datepicker").each(function() {

                            $(this).datepicker("destroy");
                        });
                        cln.remove();
                    },
                    callback: function(res) {
                    },
                    onError: function() {}
                });
            }

        } else {

            cln.find(".datepicker").each(function() {

                $(this).datepicker("destroy");
            });

            cln.remove();
        }
    }

    function onCloneChange() {

        $(this).parent().parent().parent().find(".save_clone").removeClass("disabled");
    }

    function initCloners() {

        var keys = Object.keys(cloners);

        for(let i = 0; i < keys.length; i += 1) {

            var btn = document.querySelector(".patient_section[data-type='" + keys[i] + "'] .add_clone");
            btn.addEventListener(clickEvent, onAddClone, false);
        }

        $(".patient_section").on(clickEvent, '.remove_clone', onRemoveClone);
        $(".patient_section").on("change", ".clone input, .clone select", onCloneChange);
    }

    // Get physicians

    ajax({

		url: self_.baseDirectoryUrl + 'api/getPatientData',
		data: {},
		beforeSend: function() {},
		callback: function(res) {

            res = JSON.parse(res);
			physicians = res.physicians;

            console.log(res);

            var pyhtml = '<option value="0">Select</option>',
                pahtml = '<option value="0">Select</option>';

            var pa_arr = {};

            for(let i = 0; i < physicians.length; i += 1) {

                for(let j = 0; j < physicians[i].patients.length; j += 1) {

                    var html = '<option data-physician="' + physicians[i].id  + '" value="' + physicians[i].patients[j] + '">' + physicians[i].patients[j] + '</option>';
                    pa_arr[physicians[i].patients[j]] = html;
                }

                pyhtml += '<option value="' + physicians[i].id + '">' + physicians[i].forename + " " + physicians[i].surname  +'</option>';
            }

            Object.keys(pa_arr).sort().forEach(function(key) {

                pahtml += pa_arr[key];
            });

            patient_sel.innerHTML = pahtml;
            physician_sel.innerHTML = pyhtml;

            // Menstruation

            var m_cycle = '<option value="0">Select</option>';

            for(let i = 0; i < res.menstrual_cycle_length.length; i += 1) {

                m_cycle += '<option value="' + res.menstrual_cycle_length[i].id + '">' + res.menstrual_cycle_length[i].title_en + '</option>';
            }

            sections[0].querySelector("select[name='cycle_length']").innerHTML = m_cycle;

            // Init samples

            ['sample', 'time_until_frozen', 'medium', 'vials'].forEach((k) => {

                let sel = '<option value="0">Select</option>';

                for(let j = 0; j < res.samples[k].length; j += 1) {

                    if(typeof res.samples[k][j] === "object") {

                        sel += '<option value="' + res.samples[k][j].id + '">' + res.samples[k][j].title_en  +'</option>';

                    } else {

                        sel += '<option value="' + res.samples[k][j] + '">' + res.samples[k][j] +'</option>';
                    }
                }

                sections[1].querySelector("select[name='" + k + "']").innerHTML = sel;

                if(k == "sample") {

                    sections[2].querySelector("select[name='sample']").innerHTML = sel;
                    sections[3].querySelector("select[name='sample']").innerHTML = sel;
                }
            });

            /* Histology */

            ['class', 'phase', 'subclass'].forEach((k) => {

                let sel = '<option value="0">Select</option>';

                for(let j = 0; j < res.histology[k].length; j += 1) {

                    sel += '<option value="' + res.histology[k][j].id + '">' + res.histology[k][j].title_en  +'</option>';
                }

                sections[2].querySelector("select[name='" + k + "']").innerHTML = sel;
            });

            // Init cloners

            for(let i = 0; i < sections.length; i += 1) {

                var cln = sections[i].querySelector(".clone");

                if(cln !== null) {

                    var nm = sections[i].getAttribute("data-type");

                    cloners[nm] = cln;
                }
            }

            initCloners();

            // Init rest

            document.querySelector("select[name='attending_physician']").innerHTML = pyhtml;
            sections[2].querySelector("select[name='person']").innerHTML = pyhtml;
            $(filters).removeClass("loading");
            loading = false;

            /* New patient */

            if(new_patient) {

                $(sections[0]).removeClass("loading");
                $(".patient_id_input").val(patient_code).prop("disabled", false);
            }

            /* Old patient */

            var opt = $("select[data-name='patient'] option[value='" + patient_id +"']").val();

            if(typeof opt !== "undefined") {

                $(physician_sel).val($("select[data-name='patient'] option[value='" + patient_id +"']").attr("data-physician")).change();
                $(patient_sel).val(opt).change();

            } else {

                $(sections[0]).removeClass("loading").addClass("run_again");
            }
		},
		error: function() {}
	});

    // Select Physician

    function onPhysicianSelectChange() {

        if(!loading) {

            var opts = patient_sel.querySelectorAll("option");

            for(let i = 0; i < opts.length; i += 1) {

                opts[i].style.display = (opts[i].getAttribute("data-physician") == this.value || this.value == 0 || opts[i].value == 0) ? 'block' : 'none';
            }

            patient_sel.value = 0;
        }
    }

    $(physician_sel).on("change", onPhysicianSelectChange);

    // Init Tabs

    // Format patient data

    function onTabClick() {

        if(!loading && !new_patient) {

            var target = $(this).attr("data-target");

            $(tabs).each(function() {

                $(this).removeClass("selected");
            });

            $(sections).each(function() {

                ($(this).attr("data-type") == target) ? $(this).removeClass("hide") : $(this).addClass("hide");
            })

            $(this).addClass("selected");
        }
    }

    $(tabs).each(function(i, e) {

        e.addEventListener(clickEvent, onTabClick, false);
    });

    function calculateBMI(w, h) {

        return Math.round((w / (Math.pow((h / 100), 2))) * 100) / 100;
    }

    function formatPatientData(data) {

        console.log(data);

        var pids = document.querySelectorAll(".patient_id_input");

        $(pids).each(function() {

            $(this).val(data.patient_code);
        });

        // Patient data;

        sections[0].querySelector("input[name='age']").value = data.survey.age;
        sections[0].querySelector("input[name='height']").value = data.survey.height;
        sections[0].querySelector("input[name='weight']").value = data.survey.weight;
        sections[0].querySelector("input[name='bmi']").value = calculateBMI(data.survey.weight, data.survey.height);
        sections[0].querySelector("select[name='attending_physician']").value = data.physician;
        sections[0].querySelector("select[name='category']").value = (data.code_patient_category_id === null) ? 0 : data.code_patient_category_id;
        sections[0].querySelector("select[name='confirmed_disease']").value = data.code_confirmed_disease_status_id;
        sections[0].querySelector("select[name='cycle_length']").value = data.survey.code_menstruation_cycle_length_id;

        // Samples

        $(sections[1]).find(".clone").remove();

        for(let i = 0; i < data.samples.length; i += 1) {

            let elem = $(cloners.samples).clone(true);

            elem.find("select[name='sample']").val(data.samples[i].code_tissue_type_id);
            elem.find("input[name='sample_date']").val(data.samples[i].date).datepicker({ dateFormat: 'yy-mm-dd' });
            elem.find("select[name='time_until_frozen']").val(data.samples[i].code_time_until_frozen_id);
            elem.find("select[name='storage_medium']").val(data.samples[i].medium);
            elem.find("select[name='vials']").val(data.samples[i].vials);
            elem.find("input[name='notes']").val(data.samples[i].notes);
            elem.attr("data-id", data.samples[i].sample_id);
            elem.insertAfter(".patient_section[data-type='samples'] .add_clone");
        }

        // Histology

        $(sections[2]).find(".clone").remove();

        for(let i = 0; i < data.histology.length; i += 1) {

            let elem = $(cloners.histology).clone(true);

            elem.find("select[name='sample']").val(data.histology[i].code_tissue_type_id);
            elem.find("input[name='date']").val(data.histology[i].date).datepicker({ dateFormat: 'yy-mm-dd' });
            elem.find("select[name='person']").val(data.histology[i].person);
            elem.find("select[name='class']").val(data.histology[i].code_histology_class_id);
            elem.find("select[name='phase']").val(data.histology[i].code_histology_phase_id);
            elem.find("select[name='subclass']").val(data.histology[i].code_histology_subclass_id);
            elem.attr("data-id", data.histology[i].id);
            elem.insertAfter(".patient_section[data-type='histology'] .add_clone");
        }

        $(sections[3]).find(".clone").remove();
        $(sections[3]).find("button.add_clone").css("display", "none");

        for(let i = 0; i < data.biomarkers.length; i += 1) {

            let elem = $(cloners.biomarker).clone(true);

            elem.find("select[name='sample']").val(data.biomarkers[i].code_tissue_type_id);
            elem.find("input[name='analyte_name']").val(data.biomarkers[i].name)
            elem.find("input[name='mean_value']").val(parseFloat(data.biomarkers[i].value));
            elem.find("input[name='unit']").val(data.biomarkers[i].unit);
            elem.attr("data-id", data.biomarkers[i].id);

            elem.find("button").css("display", "none");

            elem.insertAfter(".patient_section[data-type='biomarker'] .add_clone");
        }
    }

    // Select patient

    function loadNewPatient(patient_id, phy) {

        ajax({

            url: self_.baseDirectoryUrl + 'api/getPatient',
            data: { patient_id: patient_id },
            beforeSend: function() {

                $(filters).addClass("loading");
                loading = true;
                new_patient = false;
                patient_code = '';

                $(".patient_arrows").addClass("disabled");
                $("button[data-name='save_patient_data']").text("Save patient data");
                $("button[data-name='add_patient']").removeClass("disabled");
                $(".patient_id_input").val(patient_code).prop("disabled", true);

                for(let i = 0; i < sections.length; i += 1) {

                    $(tabs[i]).removeClass("disabled");
                    (i == 0) ? $(sections[i]).addClass("loading").removeClass("hide").removeClass("run_again") : $(sections[i]).addClass("hide").removeClass("run_again");
                    (i == 0) ? $(tabs[i]).addClass("selected") : $(tabs[i]).removeClass("selected");
                }
            },
            callback: function(res) {

                setTimeout(() => {

                    res = JSON.parse(res);
                    real_patient_id = res.id;
                    res.physician = phy;
                    formatPatientData(res);
                    loading = false;
                    $(".patient_arrows").removeClass("disabled");
                    $(sections[0]).removeClass("loading");
                    $(filters).removeClass("loading");

                }, 400);
            },
            error: function() {}
        });
    }

    function onPatientSelectChange() {

        if(!loading && this.value != 0) {

            var patient_id = this.value,
                phy = $(this).find(":selected").attr("data-physician");

            loadNewPatient(patient_id, phy);
        }
    }

    $(patient_sel).on("change", onPatientSelectChange);

    /* Next / Previous */

    $(".patient_arrows .arrow").on(clickEvent, (e) => {

        var pcode = $(".patient_id_input").val();

        if(!loading && !new_patient) {

            var elem = $("select[data-name='patient'] option:selected"),
                trg  = ($(e.target).hasClass("right")) ? elem.next() : elem.prev(),
                phy  = trg.attr("data-physician"),
                code = trg.val();

            if(typeof code !== "undefined") {

                $(physician_sel).val(phy).change();
                $(patient_sel).val(code).change();
            }
        }
    });

    $(sections[4]).on(clickEvent, '.checkbox', function(e, k) {

        $(e.currentTarget).toggleClass("checked");
    });

    /* Save */

    // Patient data

    $("button[data-name='save_patient_data']").on(clickEvent, function() {

        var data = {};

        if(!loading && !self_.dragging) {

            $(".patient_section[data-type='patient_data'] input, .patient_section[data-type='patient_data'] select").each(function(k, e) {

                let n = $(e).attr("name"),
                    v = $(e).val();

                data[n] = v;
            });
        }

        data.patient_id = real_patient_id;
        data.new_patient = new_patient;
        data.type = "patient";

        ajax({

            url: self_.baseDirectoryUrl + 'api/savePatient',
            data: data,
            beforeSend: function() {

                $(filters).addClass("loading");
                $(".patient_section[data-type='patient_data']").addClass("loading");
            },
            callback: function(res) {

                console.log(res);

                setTimeout(() => {

                    $(filters).removeClass("loading");
                    $(".patient_section[data-type='patient_data']").removeClass("loading");

                }, 400);
            },
            error: function() {}
        });
    });

    // samples

    $(".patient_section[data-type='samples']").on(clickEvent, '.save_clone', function() {

        if(!$(this).hasClass("disabled") && !loading && !self_.dragging) {

            let elem = $(this).parent(),
                sample_id  = elem.attr("data-id"),
                new_sample = !(sample_id),
                data = {};

            elem.find("input, select").each((k, e) => {

                let n = $(e).attr("name"),
                    v = $(e).val();

                data[n] = v;
            });

            data.patient_id = real_patient_id;
            data.sample_id = (typeof sample_id !== "undefined") ? sample_id : 0;
            data.new_sample = new_sample;
            data.type = "sample";

            ajax({

                url: self_.baseDirectoryUrl + 'api/savePatient',
                data: data,
                beforeSend: function() {

                    $(filters).addClass("loading");
                    elem.addClass("loading");
                    elem.append(`<div class="spinParticleContainer">
        							<div class="particle red"></div>
        							<div class="particle grey other-particle"></div>
        							<div class="particle blue other-other-particle"></div>
        						</div>`);
                },
                callback: function(res) {

                    console.log(res);

                    setTimeout(() => {

                        $(filters).removeClass("loading");
                        elem.removeClass("loading");
                        //elem.find(".spinParticleContainer").remove();

                    }, 400);
                },
                error: function() {}
            });
        }
    });


}
