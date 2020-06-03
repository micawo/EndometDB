export default function cleanInput(str, limit) {

	str = str.trim();
	str = (limit > 0) ? str.substring(0, limit) : str;
	str = str.replace(/ *\{[^)]*\} */g, "");
	return str.replace(/(<([^>]+)>)/ig, "");
}
