export default function getElementOffset(elem) {

	if(elem.tagName.toLowerCase() == "path") {

		elem = elem.parentNode.parentNode;

	} else if(elem.tagName.toLowerCase() == "svg") {

		elem = elem.parentNode;
	}

	var x = 0, y = 0;

	do {

		x += elem.offsetLeft;
		y += elem.offsetTop;

	} while( elem = elem.offsetParent);
	
	return {x: x, y: y };
}
