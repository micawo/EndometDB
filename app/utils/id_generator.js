export default function generateID(l) {

	const id_length = (typeof l === "undefined") ? 32 : (!isNaN(parseInt(l)) ? parseInt(l) : 32);
	const alph = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	let res = '';

	for(let i = 0; i < id_length; i += 1) {

		res += alph.charAt(Math.floor(Math.random() * alph.length));
	}

	return res;
}
