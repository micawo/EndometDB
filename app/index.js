import $ from 'jquery';
//import WebFont from 'webfontloader';
import router from './utils/router';
import clickEvent from './utils/clickevent';
import analytics from './sections/analytics';
import patient from './sections/patient';
import login from './sections/login';
import admin from './sections/admin';
import home from './sections/home';
import homeLogged from './sections/home_logged';
import initNav from './utils/nav';
import 'jquery-ui/themes/base/core.css';
import 'jquery-ui/themes/base/theme.css';
import 'jquery-ui/themes/base/datepicker.css';
import './elements/css/reset.css';
import './elements/css/icons.css';
import './elements/css/styles.css';
import './elements/css/queries.css';

var endometDB = function() { this.init(); };

endometDB.prototype = {

	init: function() {

		if(clickEvent == "click") { $("body").addClass("hover"); }

		this.baseUrl = (!window.location.origin) ? window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '') : window.location.origin;

		if(this.baseUrl.indexOf("endometdb.utu.fi") !== -1) {

			this.root = "/";
			this.baseDirectoryUrl = "https://endometdb.utu.fi/";

		} else {

			var parts = window.location.pathname.split( '/' );
			this.root = "/" + parts[1] + "/";
			//this.baseDirectoryUrl = (this.baseUrl.slice(-1) != "/") ? this.baseUrl + "/" + parts[1] + "/" : this.baseUrl + parts[1] + "/";
			this.baseDirectoryUrl = base_url;
		}

		this.dragging = false;

		if(clickEvent == "touchend") {

			var self_ = this;

			$("body").on("touchmove", function() {  self_.dragging = true;  });
			$("body").on("touchstart", function() {  self_.dragging = false;  });
		}

		initNav();
		router(this.baseDirectoryUrl, this);
	},

	initHome: function(args) {

		(typeof logged === "undefined") ? home(this) : homeLogged(this);
	},

	initLoggedHome: function(args) {

		home(this);
	},

	initAnalytics: function() {

		analytics(this);
	},

	initPatient: function(args) {

		patient(this, args);
	},

	initLogin: function() {

		login(this);
	},

	initAdmin: function(args) {

		admin(args, this);
	}
};

document.addEventListener("DOMContentLoaded", (e) => {

	new endometDB();
});
