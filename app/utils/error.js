import $ from 'jquery';
import clickEvent from './clickevent';
import scrollTo from './scrollto';

export function insertError(target, message, classes, style) {

	classes = (typeof classes !== "undefined") ? " " + classes : "";

	var msg   	= (typeof message !== "undefined") ? message : "Virhe, yritä uudelleen ole hyvä!",
		styles	= (typeof style !== "undefined") ? ' style="' + style + '"' : '',
		error 	= $('<div class="errormsg ' + classes + '"' + styles + '>' + msg + '</div>');

	if(target.find(".errormsg").length > 0) {

		target.find(".errormsg").each(function(index, element) {

			$(this).unbind(clickEvent);
			$(this).remove();
		});
	}

	error.bind(clickEvent, function() {
	
		$(this).unbind(clickEvent);
		$(this).removeClass("show").promise().done(function() {

			var __ = $(this);
			setTimeout(function() { __.remove(); }, 150);
		});
	});

	target.prepend(error);

	setTimeout(function() {

		error.addClass("show");
		//scrollTo((target.offset().top - 22 < 0) ? 0 : (target.offset().top - 22));

	}, 20);
}

export function clearErrors(target) {

	target.find(".errormsg").each(function(index, element) {

		$(this).unbind(clickEvent);
		$(this).remove();
	});
}
