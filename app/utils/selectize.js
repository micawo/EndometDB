import $ from 'jquery';
import ajax from './ajax';
import clickEvent from './clickevent';

export default function initSelectize(elem, ajaxdata, callback, min_length, single) {

	var inp  	= elem.querySelector("input"),
		opts 	= elem.querySelector(".options"),
		add_div = document.createElement("div"),
		mes_div = document.createElement("div"),
		elem_name = (elem.getAttribute("data-name")) ? elem.getAttribute("data-name") : null,
		tags_cont = elem.querySelector(".tags"),
		mes, add;

	if(!tags_cont) {

		elem.insertAdjacentHTML("afterbegin", '<div class="tags"></div>');
		tags_cont = elem.querySelector(".tags");
	}

	single = (typeof single !== "undefined");

	var real_value = null;

	min_length = (typeof min_length === "undefined") ? 0 : min_length;

	// Measurement Div
	mes_div.className = "input_mes";
	elem.appendChild(mes_div);
	mes = elem.querySelector(".input_mes");

	// Add Tag Button
	add_div.className = "add_tag";
	opts.insertBefore(add_div, opts.querySelector(".option:first-child"));
	add = elem.querySelector(".add_tag");
	if(elem.classList.contains("only_opts")) { add.style.display = "none"; }

	function filterOptions() {

		var val  = (real_value !== null) ? real_value : (inp.value).trim().toLowerCase(),
			tags = elem.querySelectorAll(".tag"), lst = [], i, h = 0, optVal;

		opts.innerHTML = '';

		if(Array.isArray(ajaxdata) && inp.value.length >= min_length) {

			for(i = 0; i < tags.length; i += 1) {

				lst.push((tags[i].innerHTML).trim().toLowerCase());
			}

			for(i = 0; i < ajaxdata.length; i += 1) {

				optVal = (ajaxdata[i]).trim().toLowerCase();

				if(optVal.indexOf(val) > -1 && lst.indexOf(optVal) === -1) {

					opts.insertAdjacentHTML("beforeend", '<div class="option show" data-value="' + ajaxdata[i] + '">' + ajaxdata[i] + '</div>');

				} else {

					h += 1;
				}
			}

		} else if(typeof ajaxdata === 'object' && inp.value.length >= min_length) {

			for(i = 0; i < tags.length; i += 1) {

				lst.push((tags[i].innerHTML).trim().toLowerCase());
			}

			for(var key in ajaxdata) {

				var added = false;

				for(i = 0; i < ajaxdata[key].length; i += 1) {

					optVal = (ajaxdata[key][i]).trim().toUpperCase();

					if(optVal.indexOf(val) > -1 && lst.indexOf(optVal) === -1) {

						if(!added) {
							added = true;
							var k = key.replace(/_/g, ' ');
							k.charAt(0).toUpperCase() + k.slice(1);
							opts.insertAdjacentHTML("beforeend", '<label>' + k + '</label>');
						}

						opts.insertAdjacentHTML("beforeend", '<div class="option show" data-value="' + ajaxdata[key][i] + '">' + ajaxdata[key][i] + '</div>');

					} else {

						h += 1;
					}
				}
			}
		}
	}

	function sortTags(s) {

		Array.prototype.slice.call(document.body.querySelectorAll(s)).sort(function sort (ea, eb) {

			var a = ea.textContent.trim(),
				b = eb.textContent.trim();

			if (a < b) { return -1; }
			if (a > b) { return 1; }

			return 0;

		}).forEach(function(div) {

			//elem.insertBefore(div, elem.querySelector("input"));
			//tags.insertBefore(div, elem.querySelector("input"));
			tags_cont.appendChild(div);
		});
	}

	function addTag(e, plain) {

		plain = (typeof plain !== "undefined") ? plain : false;

		var val	   = (plain) ? e : (e.tagName.toLowerCase() == "input") ? e.value : e.innerHTML,
			newTag = (val).trim(),
			tags   = elem.querySelectorAll(".tag"),
			found  = false, i;

		for(i = 0; i < tags.length; i += 1) {

			if((tags[i].innerHTML).trim() == newTag) {

				found = true;
			}
		}

		if(!found && newTag != "") {

			var tagDiv = document.createElement("div");
			tagDiv.className = "tag";

			if(!plain) {

				if(e.hasAttribute("data-id")) {

					tagDiv.setAttribute("data-id", e.getAttribute("data-id"));
				}
			}

			tagDiv.innerHTML = newTag;
			//elem.insertBefore(tagDiv, elem.querySelector("input"));
			//tags.insertBefore(tagDiv, elem.querySelector("input"));

			tags_cont.appendChild(tagDiv);
		}

		real_value = (plain) ? "" : (inp.value !== "") ? inp.value.trim().toLowerCase() : real_value;

		inp.focus();

		if(!elem.classList.contains("only_opts") && elem_name) {
			sortTags('.selectize[data-name="' + elem_name + '"] .tag');
		}

		filterOptions();
	}

	function onInput() {

		mes.innerHTML = this.value;
		this.style.width = mes.offsetWidth + "px";
		real_value = null;
		filterOptions();
	}

	function onBlur() {

		opts.classList.remove("show");
	}

	function onElemClick(e) {

		e.preventDefault(); // the important thing I think
		e.stopPropagation();

		if(e.target.classList.contains("selectize") || e.target.tagName == "INPUT") {

			filterOptions();
			inp.focus();
			opts.classList.add("show");

		} else if(e.target.classList.contains("tag")) {

			e.target.parentNode.removeChild(e.target);
			inp.value = "";
			if(typeof callback === "function") { callback(); }
		}
	}

	function onOptClick(e) {

		console.log("JOJOJOO");

		if(e.target.classList.contains("option")) {

			addTag(e.target);

		} else if(e.target.classList.contains("add_tag")) {

			addTag(inp);
		}

		if(typeof callback === "function") { callback(); }
	}

	// Enter
	if(!elem.classList.contains("only_opts")) {

		$(inp).keyup(function (e) {

			var ok_email = true;

			if (e.keyCode == 13 && ok_email) {

				addTag(inp);
				if(typeof callback === "function") { callback(); }
			}
		});
	}

	// On paste
	function onPaste(e) {

		e.stopPropagation();
		e.preventDefault();

		var clipboardData = e.clipboardData || window.clipboardData,
			pastedData = clipboardData.getData('Text').trim(),
			p_opts = pastedData.split(/\W+/),
			ins_bef = elem.querySelector("input");

		for(var i = 0; i < p_opts.length; i += 1) {

			var found = false,
				opt = p_opts[i].trim().toLowerCase(),
				f_value = '';

			for(var j = 0; j < ajaxdata.length; j += 1) {

				var optVal = (ajaxdata[j]).trim().toLowerCase();

				if(opt == optVal) {

					found = true;
					f_value = ajaxdata[j].trim();
				}
			}

			if(found && f_value != "") {

				var tagDiv = document.createElement("div");
				tagDiv.className = "tag";
				tagDiv.innerHTML = f_value;
				//elem.insertBefore(tagDiv, ins_bef);
				//tags.insertBefore(tagDiv, ins_bef);
				tags_cont.appendChild(tagDiv);
			}
		}

		inp.value = "";
		inp.focus();

		if(!elem.classList.contains("only_opts") && elem_name) {

			sortTags('.selectize[data-name="' + elem_name + '"] .tag');
		}

		filterOptions();
		if(typeof callback === "function") {

			callback();
		}
	}

	inp.addEventListener("input", onInput, false);
	opts.addEventListener(clickEvent, onOptClick, false);
	elem.addEventListener(clickEvent, onElemClick, false);

	if(!elem.classList.contains("only_opts") && Array.isArray(ajaxdata)) {

		inp.addEventListener("paste", onPaste, false);
	}

	$(document).click(function(e) {

		if(e.target != opts && e.target != elem) {

			inp.value = "";
			onBlur();
		}
	});
}
