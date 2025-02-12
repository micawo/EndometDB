export function detectTransitionEnd() {

	var t, el = document.createElement('fakeelement'),
		transitions = {
			'transition':'transitionend',
			'OTransition':'oTransitionEnd',
			'MozTransition':'transitionend',
			'WebkitTransition':'webkitTransitionEnd'
	};

	for(t in transitions) {

		if(el.style[t] !== "undefined") {

			return transitions[t];
		}
	}
}
