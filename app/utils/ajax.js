import $ from 'jquery';

export default function ajax(data) {

	var ajax = {};

	if(typeof data.beforeSend === "function") {

		ajax.beforeSend = data.beforeSend;
	}

	if(typeof data.uploadProgress === "function") {

		ajax.uploadProgress = data.uploadProgress;
	}

	if(typeof data.callback === "function") {

		ajax.success = function(res) { data.callback(res); }
	}

	if(typeof data.error === "function") {

		ajax.error = data.error;
	}

	ajax.url = (typeof data.url !== "undefined") ? data.url : "";
	ajax.type = (typeof data.type !== "undefined") ? data.type : "POST";
	ajax.dataType = (typeof data.dataType !== "undefined") ? data.dataType : "html";
	ajax.data = (typeof data.data !== "undefined") ? ((data.dataType === "json") ? JSON.stringify(data.data) : data.data) : "";
	$.ajax(ajax);
}
