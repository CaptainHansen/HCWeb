$(document).ready(function(){
	$('div.blackout').click(function(e){
		if($(e.target).hasClass('blackout')){
			$(e.target).fadeOut(200,function(){
				$('html,body').removeClass('blackout-on');
			});
		}
	});
	
	easyj = new EasyJax('do.php','GET',function(data){
		HCUser.Users = data.data;
		for(id in HCUser.Users) {
			$('#HCUser-table').append(HCUser.Format(id));
			HCUser.Activate(id);
		}
	});
	easyj.submit_data();
});

function HCUser() {
}

HCUser.Users = {};

HCUser.Format = function(id){
	var d = this.Users[id];
	var cls = 'disabled';
	if(d.enabled == 1) cls = 'enabled';
	
	var admin = 'Unpriveleged';
	if(d.admin) admin = "Administrator";
	
	var html = "";
	html += "<tr id=\""+d.ID+"\" class=\"HCUser "+cls+"\"><td id=\"user\">"+d.user+"</td><td id=\"admin\">"+admin+"</td><td>"+getElapsed(d.lastact)+"</td><td id=\"name\">"+d.fname+" "+d.lname+"</td><td>"+d.ip_addr+"</td></tr>";
	return html;
}

HCUser.Activate = function(id){
	$('#'+id).click(function(e){
		HCUser.Edit($(e.target).attr('id'));
	});
}

HCUser.Edit = function(id){
	$('.blackout').fadeIn(200,function(){
		$('html,body').addClass('blackout-on');
	});
}

HCUser.Post = function(){
	var newu = $('#user-new');
	var pass = newu.find('#pass').val();
	var verify = newu.find('#verify').val();
	if(newu.find('#user').val() == ""){
		alert("You must enter a username.");
		return false;
	}
	if(pass != verify) {
		alert("The passwords you entered do not match.");
		return false;
	}
	if(pass.length == 0){
		alert("The password cannot be blank.");
		return false;
	}
	easyj = new EasyJax('do.php','POST',function(data,pobj){
		$('#users-table').append("<tr id=\"user-"+data.id+"\"><td id=\"user\">"+pobj.user+"</td><td><input type=\"password\" id=\"pass\"></td><td><input type=\"password\" id=\"verify\"></td><td><button onclick='HCUser.ResetPass("+data.id+")'>Reset Password</button></td><td><button id=\"adminb\" onclick='HCUser.CHAdmin("+data.id+")'>Authorize</button</td><td><button onclick='HCUser.Delete("+data.id+")'>Delete User</button></td></tr>");
	},{'user':newu.find('#user').val(),'pass':pass});
	easyj.submit_data();
}

HCUser.CHAdmin = function(id){
	var user = $('#user-'+id);
	var admin = 0;
	if(user.find('#adminb').html() == 'Authorize'){
		admin = 1;
	}
	
	easyj = new EasyJax('do.php/'+id,'PUT',function(data,pobj){
		var nval = '';
		if(pobj.admin == 1){
			nval = "De-Authorize";
		} else {
			nval = 'Authorize';
		}
		$('#user-'+id).find('#adminb').html(nval);
	},{'admin':admin});
	easyj.submit_data();
}

HCUser.ResetPass = function(id){
	var user = $('#user-'+id);
	var pass = user.find('#pass').val();
	var verify = user.find('#verify').val();
	if(pass != verify) {
		alert("The new passwords you entered do not match.");
		return false;
	}
	if(pass.length == 0){
		alert("The new password cannot be blank.");
		return false;
	}
	
	easyj = new EasyJax('do.php/'+id,'PUT',function(){
		alert("Password reset successfully.");
	},{'pass':pass});
	easyj.submit_data();
}

HCUser.Delete = function(id){
	if(!confirm("Are you sure you want to delete this user?  This CANNOT be undone!")){
		return false;
	}
	
	easyj = new EasyJax('do.php/'+id,'DELETE',function(){
		$('#user-'+id).fadeOut();
	});
	easyj.submit_data();
}