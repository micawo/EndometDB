import $ from 'jquery';
import clickEvent from '../utils/clickevent';
import ajax from '../utils/ajax';
import testMail from '../utils/testmail';

export default function admin(args, self_) {
	
	var sbmt = document.querySelector(".btn[data-name='save-user']"),
		rmv  = document.querySelector(".btn[data-name='remove-user']"),
		err  = document.querySelector(".login_error"), loading = false;

	var arg  = (args[0]) ? args[0].split("/") : [];

	if(arg.length > 1) {
		
		args[0] = arg[0];
		args[1] = arg[1];
	}
	
	var id = (args[1] === null) ? 0 : args[1];
	
	function toggleCheckbox() {
		
		$(this).toggleClass("checked");
	}	
	
	function validateData() {
		
		var name 	 = document.querySelector("input[name='name']").value,
			username = document.querySelector("input[name='email']").value,
			passwd 	 = document.querySelector("input[name='passwd']").value,
			passwd2  = document.querySelector("input[name='passwd2']").value,
			admin    = document.querySelector(".checkbox[data-name='admin']"),
			active   = document.querySelector(".checkbox[data-name='active']"), data = {};
		
		data.err = [];
		data.fields = {
			
			id: id,
			name: name,
			username: username,
			password: passwd,
			password2: passwd2,
			admin: ($(admin).hasClass("checked") ? 1 : 0),
			active: ($(active).hasClass("checked") ? 1 : 0)
		};
		
		if(passwd != "" || passwd2 != "") {
			
			if(passwd != passwd2) {
				
				data.err.push("passwords don't match");
			}
		}
		
		if(id == 0 && passwd == "") {
			
			data.err.push("give password");
		}
		
		if(!testMail(username) || username == "") {
			
			data.err.push("give valid email")
		}
		
		if(name == "") { data.err.push("give name"); }
		
		return data;
	}

	function onSubmit(e) {
		
		e.preventDefault();
		
		if(!self_.dragging && !loading) {
			
			var data = validateData();
			
			if(data.err.length == 0) {

				ajax({
					
					url: self_.baseDirectoryUrl + 'api/save_user',
					data: data.fields,
					beforeSend: function() {
						
						loading = true;
						$(err).removeClass("show");	
						$(sbmt).addClass("loading");
						sbmt.insertAdjacentHTML("afterbegin", '<div class="loader"><span></span></div>');						
					},
					callback: function(res) {
		
						setTimeout(function() {
							
							res = JSON.parse(res);

							if(res.status ==  1) {
								
								setTimeout(function() {
									
									window.location.href = self_.baseDirectoryUrl + 'admin/';
									
								}, 400);

							} else {
	
								$(sbmt).removeClass("loading");
								sbmt.removeChild(sbmt.querySelector(".loader"));	
								$(err).addClass("show");	
								err.innerHTML = res.error;
								loading = false;
							}
							
						}, 400);			
					},
					error: function() {
						
						$(sbmt).removeClass("loading");
						sbmt.removeChild(sbmt.querySelector(".loader"));	
						$(err).addClass("show");							
						err.innerHTML = 'Network error, please try again later';					
						loading = false;
					}
				});			
					
			} else {

				$(err).addClass("show");	
				var derr = data.err.join(", ") + ".";	
				derr =  derr.charAt(0).toUpperCase() + derr.slice(1);									
				err.innerHTML = derr;	
			}
		}
	}
	
	function removeUser() {

		if(!self_.dragging && !loading) {
	
			var r = confirm("Are you sure you want to remove this user?");
			
			if(r) {
				
				ajax({
					
					url: self_.baseDirectoryUrl + 'api/remove_user',
					data: { id: id },
					beforeSend: function() {
						
						loading = true;
						$(err).removeClass("show");	
						$(rmv).addClass("loading");
						rmv.insertAdjacentHTML("afterbegin", '<div class="loader"><span></span></div>');						
					},
					callback: function(res) {
		
						setTimeout(function() {
							
							res = JSON.parse(res);
	
							if(res.status ==  1) {
								
								setTimeout(function() {
									
									window.location.href = self_.baseDirectoryUrl + 'admin/';
									
								}, 400);
	
							} else {
	
								$(rmv).removeClass("loading");
								rmv.removeChild(sbmt.querySelector(".loader"));	
								$(err).addClass("show");							
								err.innerHTML = 'Network error, please try again later';					
								loading = false;
							}
							
						}, 400);			
					},
					error: function() {
						
						$(sbmt).removeClass("loading");
						sbmt.removeChild(sbmt.querySelector(".loader"));	
						$(err).addClass("show");							
						err.innerHTML = 'Network error, please try again later';					
						loading = false;
					}
				});			
			}
		}			
	}
	
	if(sbmt !== null) {

		sbmt.addEventListener(clickEvent, onSubmit, false);
	} 
	
	if(rmv !== null) {
		
		rmv.addEventListener(clickEvent, removeUser, false);
	}

	$(".checkbox").on(clickEvent, toggleCheckbox);
}
