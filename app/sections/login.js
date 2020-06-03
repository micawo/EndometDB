import $ from 'jquery';
import clickEvent from '../utils/clickevent';
import ajax from '../utils/ajax';

export default function login(self_) {

	var username = document.querySelector(".login input"),
		password = document.querySelector(".login input:last-of-type"),
		err 	 = document.querySelector(".login_error"),
		lgn 	 = document.querySelector(".btn"), loading = false;

	function onLogin(e) {

		e.preventDefault();

		if(!self_.dragging && !loading) {

			var data = { email: username.value, password: password.value };

			ajax({

				url: self_.baseDirectoryUrl + 'api/login',
				data: data,
				beforeSend: function() {

					loading = true;
					$(err).removeClass("show");
					$(lgn).addClass("loading");
					lgn.insertAdjacentHTML("afterbegin", '<div class="loader"><span></span></div>');
				},
				callback: function(res) {

					setTimeout(function() {

						res = JSON.parse(res);

						if(res.id > 0) {

							window.location.reload();

						} else {

							$(lgn).removeClass("loading");
							lgn.removeChild(lgn.querySelector(".loader"));
							$(err).addClass("show");
							err.innerHTML = res.error;
							loading = false;
						}

					}, 400);
				},
				error: function() {

					$(lgn).removeClass("loading");
					lgn.removeChild(lgn.querySelector(".loader"));
					$(err).addClass("show");
					err.innerHTML = 'Network error, please try again later';
					loading = false;
				}
			});
		}
	}

	lgn.addEventListener(clickEvent, onLogin, false);

	$(document).keypress(function(e) {

		if(e.which == 13) {

			onLogin(e);
		}
	});
}
