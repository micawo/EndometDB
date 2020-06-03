export default function testMail(mail) {

	var t1 = /\s/.test(mail),
		t2 = /^([\w-+\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(mail);

	return (!t1 && t2);
}
