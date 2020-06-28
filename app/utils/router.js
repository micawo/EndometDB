function routeToRegExp(route) {

	var escapeRegExp  = /[\-{}\[\]+?.,\\\^$|#\s]/g,
		namedParam 	  = /(\(\?)?:\w+/g,
		optionalParam = /\((.*?)\)/g,
		splatParam 	  = /\*\w+/g;

	route = route.replace(escapeRegExp, '\\$&').replace(optionalParam, '(?:$1)?').replace(namedParam, function(match, optional) { return optional ? match : '([^/?]+)'; }).replace(splatParam, '([^?]*?)');

	return new RegExp('^' + route + '(?:\\?([\\s\\S]*))?$');
}

function getFragment(fragment) {

	var routeStripper = /^[#\/]|\s+$/g,
		trailingSlash = /\/$/;

	return fragment.replace(routeStripper, '').replace(trailingSlash, '');
}

function extractParameters(route, fragment) {

	var params = route.exec(fragment).slice(1);

	return params.map(function(param) {

		return param ? decodeURIComponent(param) : null;
	});
}

function route(routes, self_) {

	var fragment = getFragment(window.location.pathname),
		keys  	 = Object.keys(routes),
		found 	 = false, route;

	while (typeof (route = keys.pop()) !== 'undefined' && !found) {

		var routeReg = routeToRegExp(route),
			callback = routes[route];

		if(routeReg.test(fragment)) {

			if(typeof self_[callback] === "function") {

				var args = extractParameters(routeReg, fragment);
				found = true;
				self_[callback](args);
			}
		}
	}
}

export default function router(baseUrl, self_) {

	var routes = {
		"": "initHome",
		"collaboration(/)*b": "initHome",
		"research(/)*b": "initHome",
		"people": "initHome",
		"contact": "initHome",
		"home(/)*b": "initLoggedHome",
		"gene_analysis": "initHome",
		"analysis(/)*b": "initHome",
		"login": "initLogin",
		"analytics": "initAnalytics",
		"patient(/)*b": "initPatient",
		"admin(/)*b": "initAdmin"
	};

	if(baseUrl.indexOf("endometdb.utu.fi") !== -1) {

		return route(routes, self_);

	} else {

		var parts = window.location.pathname.split('/'),
			path  = parts[1],
			r 	  = {};

		for(var k in routes) {

			var name = (k === "") ? path : path + "/" + k;
			r[name] = routes[k];
		}

		route(r, self_);
	}
}
