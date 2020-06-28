import $ from 'jquery';
window.jQuery = $;
import slick from 'slick-carousel';
import 'slick-carousel/slick/slick.css';
import '@fancyapps/fancybox/dist/jquery.fancybox.css';
import ajax from '../utils/ajax';
import clickEvent from '../utils/clickevent';
import 'slick-carousel/slick/slick-theme.css';
import analytics from './analytics';
import getElementOffset from '../utils/offset';

require("@fancyapps/fancybox");

export default function home(self_) {

    // Carousel

    if($(".carousel").length > 0) {

        $(".carousel").on('init', function(event, slick) {

            $(this).removeClass("loading");
        });

        $(".carousel").slick({

            fade: true,
            autoplay: true,
            speed: 1200,
            dots: true,
            autoplaySpeed: 6000
        });
    }

    // Nav click

    if(clickEvent == "touchend") {

        $("#content .inner ul li.sub-menu a").on(clickEvent, function(e) {

            e.preventDefault();

            if($(this).parent().hasClass("sub-menu")) {

                $(this).parent().toggleClass("clicked");

            } else {

                window.location.href = $(this).attr("href");
            }
        });
    }

    // Mobile menu

    $(".menu").on(clickEvent, function(e) {

        $(this).toggleClass("open");
    });

    // Fixed nav

    var h = $("ul.nav").offset().top + $("ul.nav").height();

    $(window).bind('scroll', function () {

        if ($(window).scrollTop() > h) {

            $('#content .inner ul.nav').addClass('fixed');
        } else {

            $('#content .inner ul.nav').removeClass('fixed');
        }
    });

    // search

    $("#search-button").on(clickEvent, function(e) {

        var nav = $("#content .inner ul.nav");
        nav.toggleClass("on_search");
    });

    $(document).on(clickEvent, function(e) {

        var nav = $("#content .inner ul.nav");

        if(!$(e.target).hasClass("fa-search")) {

            nav.removeClass("on_search");
        }
    });

    function generatePieChart(pie, width, height) {

        width = (typeof width !== "undefined") ? parseInt(width) : 300;
        height = (typeof height !== "undefined") ? parseInt(height) : 300;

        const data = pie.data ? pie.data : [];
        const diameter = width * 0.8;
        const scheme = pie.scheme ? pie.scheme : null;

        // Lasketaan ensin svg:n leveys ja korkeus perustuen labeleihin

        const test_svg = document.querySelector("#test_svg");

        let last_rot = 0;
        let tot = 0

        const measures = data.map((dp, i) => {

            tot += dp.percent;

            const pcnt = ((i + 1) == data.length && tot < 100 && tot > 0) ? 100 - tot + dp.percent : dp.percent;
            const deg = (3.61 * pcnt > 360) ? 359.99 : 3.61 * pcnt;
            const middle_angle = ((deg + last_rot > 360) ? 360 : deg + last_rot) - deg / 2;
            const rayon = (middle_angle * Math.PI / 180);
            const x = Math.sin(rayon) * (diameter/2);
            const y = Math.cos(rayon) * -(diameter/2);
            const anchor = (middle_angle == 0 || middle_angle == 180) ? 'middle' : (middle_angle > 180) ? 'end' : 'start';

            const measure_svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            measure_svg.setAttribute("width", 400);
            measure_svg.setAttribute("height", 400);

            const measure_text = document.createElementNS("http://www.w3.org/2000/svg", "text");
            const measure_text_node = document.createTextNode(dp.info + ": " + dp.percent);

            measure_text.appendChild(measure_text_node);
            measure_text.setAttributeNS(null, "x", 0);
            measure_text.setAttributeNS(null, "y", 0);
            measure_text.setAttribute(null, "style", "font-weight: bold");
            measure_text.setAttributeNS(null, "class", "que_label");

            measure_svg.appendChild(measure_text);
            test_svg.appendChild(measure_svg);

            const res = test_svg.querySelector("text").getBBox();
            res.anchor = anchor;
            test_svg.innerHTML = 0;

            last_rot += deg;
            return res;

        }).reduce((tot, sum) => {

            tot = (!tot) ? { left: 0, right: 0, middle: 0} : tot;
            tot.left = (sum.anchor == "start") ? ((tot.left < sum.width) ? sum.width : tot.left) : tot.left;
            tot.right = (sum.anchor == "end") ? ((tot.right < sum.width) ? sum.width : tot.right) : tot.right;
            tot.middle = (sum.anchor == "middle") ? ((tot.middle < sum.width) ? sum.width : tot.middle) : tot.middle;

            return tot;

        }, null);

        // Tehdään SVG ja piirretään graafit

        width += measures.left + measures.right + 60;
        height += 0;
        last_rot = 0;
        tot = 0;

        let bullets = '';

        const html = [`<svg viewBox="0 0 ${width} ${height}">`, ...data.map((dp, i) => {

            tot += data[i].percent;

            const pcnt = ((i + 1) == data.length && tot < 100 && tot > 0) ? 100 - tot + dp.percent : dp.percent;
            const deg = (3.61 * pcnt > 360) ? 359.99 : 3.61 * pcnt;

            const rayon = (deg * Math.PI / 180);
            const x = Math.sin(rayon) * (diameter/2);
            const y = Math.cos(rayon) * -(diameter/2);
            const mid = (deg > 180) ? 1 : 0;
            const anim = `M 0 0 v -${diameter/2} A ${diameter/2} ${diameter/2} 1 ${mid} 1 ${x} ${y} z`;

            const middle_angle = ((deg + last_rot > 360) ? 360 : deg + last_rot) - deg / 2;
            const anchor = (middle_angle == 0 || middle_angle == 180) ? 'middle' : (middle_angle > 180) ? 'end' : 'start';

            const rayon_text = (middle_angle * Math.PI / 180);
            const text_x = Math.sin(rayon_text) * (diameter/2) + ((anchor == "middle") ? 0 : (anchor == "end") ? -10 : 10);
            const text_y = Math.cos(rayon_text) * -(diameter/2) + (((middle_angle > 180 && middle_angle < 270) || (middle_angle > 90 && middle_angle < 180)) ? 10 : -10);

            bullets += '<li class="' + dp.color + '">' + dp.text + '</li>';

            const pie_classes = (scheme) ? ['que que_hover', 'que'] : ["que que_hover " + dp.color, "que " + dp.color];

            const res = (`
                <path data-ind="${i+1}" data-info="${dp.info}" class="${pie_classes[0]}" d="${anim}" transform="translate(${width/2}, ${height/2}) scale(1.2) rotate(${last_rot})"></path>
                <path data-ind="${i+1}" data-info="${dp.info}" class="${pie_classes[1]}" d="${anim}" transform="translate(${width/2}, ${height/2}) rotate(${last_rot})"></path>
                <text x="${(width / 2) + text_x}" y="${(height / 2) + text_y}" class="que_label" text-anchor="${anchor}">
                    <tspan style="font-weight: bold">${dp.info}</tspan><tspan>: ${dp.percent} %</tspan>
                </text>`);

            last_rot += deg;
            return res;

        }), '</svg>'].join("");

        return [html, bullets];
	}

    function generateDonutArea(pie) {

        return `<div class="${pie.scheme ? 'block ' + pie.scheme : 'block'}">
                   <div class="block_inner">
                        <h1></h1>
                        <div class="donut-chart full">
                            <div class="info hide"></div>
                            <div class="donuts"></div>
                        </div>
                        <div class="bullets_wrapper">
                            <ul class="bullets"></ul>
                        </div>
                    </div>
	            </div>`;
    }

    function generatePieChartInfo(e) {

		var offset = getElementOffset(e.target);

		var x = e.clientX - offset.x,
			y = e.clientY - offset.y;

		if(e.target.classList.contains("que")) {

			var info = this.querySelector(".info");

			if(!info.classList.contains("hide")) {

				info.classList.add("hide");

			} else {

				info.classList.remove("hide");
				info.style.top = y + "px";
				info.style.left = x + "px";
				info.innerHTML = e.target.getAttribute("data-info");
			}

		} else {

			this.querySelector(".info").classList.add("hide");
		}
	}

	function mouseMoveChartInfo(e) {

		var offset = getElementOffset(e.target);

		var x = e.pageX - offset.x,
			y = e.pageY - offset.y;

		if(/(^|\s)que(\s|$)/.test($(e.target).attr("class"))) {

            var que_hover = this.parentNode.querySelector(".que_hover[data-ind='" + e.target.getAttribute("data-ind") + "']");
            console.log(que_hover);

            if(que_hover) {

                que_hover.classList.add("show");
            }

			var info = e.target.parentNode.parentNode.parentNode.querySelector(".info");
			info.classList.remove("hide");
			info.style.top = (y + 20) + "px";
			info.style.left = (x + 20) + "px";
			info.innerHTML = e.target.getAttribute("data-info");
		}
	}

	function mouseLeaveChartInfo(e) {

		this.querySelector(".info").classList.add("hide");
        [...this.querySelectorAll(".que_hover")].map(qh => qh.classList.remove("show"));
	}

    var div_id_counter = 0;
    var donutStatistics = document.querySelector("#statistics .block_area");

    if(donutStatistics !== null) {

        ajax({

            url: self_.baseDirectoryUrl + 'api/getstatistics',
            beforeSend: function() {

            },
            callback: function(res) {

                setTimeout(function() {

                    res = JSON.parse(res);
                    donutStatistics.innerHTML = '';

                    for(let i = 0; i < res.length; i += 1) {

                        if(res[i].data.length == 0) {

                            //donutStatistics.insertAdjacentHTML("beforeend", '<h2>' + res[i].title + '</h2>');

                        } else {

                            donutStatistics.insertAdjacentHTML("beforeend", generateDonutArea(res[i]));

                            var helem = donutStatistics.querySelector(".block:last-of-type h1"),
                                elem  = donutStatistics.querySelector(".block:last-of-type .donut-chart");

                            helem.textContent = res[i].title;

                            const graphs = generatePieChart(res[i], 300, 300);
                            elem.querySelector(".donuts").innerHTML = graphs[0];
                            elem.parentNode.querySelector(".bullets").innerHTML = graphs[1];

                            if(clickEvent == "click") {

                                elem.addEventListener("mousemove", mouseMoveChartInfo, false);
                                elem.addEventListener("mouseout", mouseLeaveChartInfo, false);

                            } else {

                                elem.addEventListener(clickEvent, generatePieChartInfo, false);
                            }
                        }
                    }

                    $(".spinParticleContainer[data-name='home_spinner']").remove();
                    $("#statistics").css("display", "block");

                }, 500);
            },
            error: function() {}
        });
    }

    // Publications

    const publ = document.querySelector("#publications");

    if(publ !== null) {

        ajax({

            url: 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json&retmax=1000&term=poutanen+endometriosis&sort=date',
            beforeSend: function() {},
            callback: function(res) {

                res = JSON.parse(res);

                if(res.esearchresult) {

                    const ids = (!res.esearchresult.idlist) ? '' : res.esearchresult.idlist.join(",");

                    ajax({

                        url: 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=json&id=' + ids,
                        beforeSend: function() {},
                        callback: function(sumres) {

                            sumres = JSON.parse(sumres);

                            const html = (!sumres.result) ? '' : Object.keys(sumres.result).map((id) => {

                                const elem = sumres.result[id];
                                const title = (!elem.title) ? null : elem.title;

                                if(!title) { return; }

                                const authors = (!elem.authors) ? [] : elem.authors.map(a => a.name);
                                const url = 'https://www.ncbi.nlm.nih.gov/pubmed/' + id;
                                const source = (!elem.source) ? null : elem.source;
                                const pubdate = (!elem.pubdate) ? null : elem.pubdate;
                                const articleids = (!elem.articleids) ? null : ['pii', 'doi'].map((ar) => {

                                    const fil = elem.articleids.filter(ai => ai.idtype == ar);
                                    return (fil.length == 1) ? ar + ": " + fil[0].value : null;

                                }).filter(af => af !== null).join(". ");

                                return `<p>${authors.join(", ")} <a href="${url}" target="_blank">${title}</a> ${source ? source + "." : ''} ${pubdate ? pubdate + "." : ''} ${articleids ? articleids + "." : ''}</p>`

                            }).reverse().join("");

                            publ.innerHTML = html;
                        },
                        error: function() {}
                    });
                }
            },
            error: function() {}
        });
    }

    // Analysis

    if(document.querySelector("#chart") !== null) {

        analytics(self_);
    }

    // Guide

    if($(".fnbx").length > 0 && $(".menu").css("display") == "none") {

        //$(".fnbx").find("img").addClass("fbox").unwrap();
        $(".fnbx").fancybox({
            'scrolling': 'yes'
        });
    }

}
