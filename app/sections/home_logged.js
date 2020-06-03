import $ from 'jquery';
import ajax from '../utils/ajax';
import clickEvent from '../utils/clickevent';
import getElementOffset from '../utils/offset';

export default function homeLogged(self_) {
    
    var donuts = {
        
        patient: document.querySelector(".donut-chart[data-name='patient']"),
        tissue: document.querySelector(".donut-chart[data-name='tissue']")
    }
    
    function generateDonutChart(elem, data) {

		var html = '<svg viewBox="0 0 238 238">', bullets = "", i, last_rot = 0, tot = 0;

		for(i = 0; i < data.length; i += 1) {

			tot += data[i].percent;

			var pcnt  = ((i + 1) == data.length && tot < 100 && tot > 0) ? 100 - tot + data[i].percent : data[i].percent,
				deg   = (3.61 * pcnt > 360) ? 359.99 : 3.61 * pcnt,
				rayon = (deg * Math.PI / 180),
				x     = Math.sin(rayon) * 119,
				y     = Math.cos(rayon) * -119,
				mid   = (deg > 180) ? 1 : 0,
				anim  = 'M 0 0 v -119 A 119 119 1 ' + mid + ' 1 ' + x + ' ' + y + ' z',
				c 	  = (data[i].color == "pink1" || data[i].color == "pink2" || data[i].color == "pink3") ? "pink" : data[i].color;

			html 	 += '<path data-info="' + data[i].info + '" class="que ' + data[i].color + '" d="' + anim + '" transform="translate(119, 119) rotate(' + last_rot + ')"></path>';
			bullets  += '<li class="' + c + '">' + data[i].text + '</li>';
			last_rot += deg;
		}

		html += '</svg>';
		elem.querySelector(".donuts").innerHTML = html;
		elem.parentNode.querySelector(".bullets").innerHTML = bullets;
	} 

    function generateDonutArea() {
        
        return `<div class="block"> 
                   <div class="block_inner">
                        <h1></h1>
                        <div class="donut-chart">  
                            <div class="info hide"></div>
                            <div class="donuts"></div>                   
                        </div>
                        <div class="bullets_wrapper">
                            <ul class="bullets"></ul>	                        
                        </div>
                    </div>
	            </div>`;            
    }
    
    function generateDonutChartInfo(e) {

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

			var info = e.target.parentNode.parentNode.parentNode.querySelector(".info");
			info.classList.remove("hide");
			info.style.top = (y + 20) + "px";
			info.style.left = (x + 20) + "px";
			info.innerHTML = e.target.getAttribute("data-info");
		}
	}

	function mouseLeaveChartInfo(e) {

		this.querySelector(".info").classList.add("hide");
	}     

    var donutStatistics = document.querySelector("#statistics .block_area");
    
    ajax({

        url: self_.baseDirectoryUrl + 'api/getstatistics',
        beforeSend: function() {

        },
        callback: function(res) {
            
            setTimeout(function() {

                res = JSON.parse(res);                        
                res = res.splice(4);

                donutStatistics.innerHTML = '';
                                    
                for(let i = 0; i < res.length; i += 1) {
                    
                    if(res[i].data.length == 0) {
                        
                        donutStatistics.insertAdjacentHTML("beforeend", '<h2' + ((i > 0) ? ' class="hide_block"' : '') + '>' + res[i].title + '</h2>');                            

                    } else {
                    
                        donutStatistics.insertAdjacentHTML("beforeend", generateDonutArea());

                        if(i > 3) {
                            
                            $(donutStatistics.querySelector(".block:last-of-type")).addClass("hide_block");
                        }
    
                        var helem = donutStatistics.querySelector(".block:last-of-type h1"),
                            elem  = donutStatistics.querySelector(".block:last-of-type .donut-chart");

                        helem.textContent = res[i].title;                            
                        generateDonutChart(elem, res[i].data);                                                    

                        if(clickEvent == "click") {
                
                            elem.addEventListener("mousemove", mouseMoveChartInfo, false);
                            elem.addEventListener("mouseout", mouseLeaveChartInfo, false);
                
                        } else {
                
                            elem.addEventListener(clickEvent, generateDonutChartInfo, false);
                        }
                    }
                }
                
                donutStatistics.insertAdjacentHTML("beforeend", '<button class="btn black icon left fa-area-chart" data-name="show_more_statistics"> Show more </button>');  
                $(".spinParticleContainer").remove();        
                $("#statistics").css("display", "block");
                
                /*console.log(res);
                generateDonutChart(donuts.patient, res.patient);
                generateDonutChart(donuts.tissue, res.tissue);
                document.querySelector(".havainnot_total").innerHTML = 'Total Patients: <b>' + res.total.patients + '</b>, Total Samples: <b>' + res.total.samples + '</b>'
                $(".spinParticleContainer").remove();
                $("#statistics").css("display", "block");*/
                
            }, 500);                        
        },
        error: function() {}
    });    
    
    $("#statistics .block_area").on(clickEvent, ".btn", function() {
        
        $(this).remove();
        $("#statistics .block_area .hide_block").removeClass("hide_block");    
    });
    
    
    /*if(clickEvent == "click") {

		donuts.patient.addEventListener("mousemove", mouseMoveChartInfo, false);
		donuts.patient.addEventListener("mouseout", mouseLeaveChartInfo, false);
        donuts.tissue.addEventListener("mousemove", mouseMoveChartInfo, false);
		donuts.tissue.addEventListener("mouseout", mouseLeaveChartInfo, false);

	} else {

		donuts.patient.addEventListener(clickEvent, generateDonutChartInfo, false);
        donuts.tissue.addEventListener(clickEvent, generateDonutChartInfo, false);
	}*/
}
