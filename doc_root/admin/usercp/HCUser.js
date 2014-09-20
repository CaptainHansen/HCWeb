$(document).ready(function(){
	$('div.blackout').click(function(e){
		if($(e.target).hasClass('blackout')){
			$(e.target).fadeOut(200,function(){
				$('html,body').removeClass('blackout-on');
			});
		}
	});
	
	var ej = new EasyJax('do.php','GET');
	ej.on('success', function(data){
		HCUser.Users = data.data;
		for(id in HCUser.Users) {
			$('#HCUser-table').append(HCUser.Format(id));
			HCUser.Activate(id);
		}
	});
	ej.send();
});

function HCUser() {
}

HCUser.Users = {};

HCUser.Format = function(id){
	var d = this.Users[id];
	var cls = 'disabled';
	if(d.enabled == 1) cls = 'enabled';
	
	var admin = 'Unpriveleged';
	if(d.admin == 1) admin = "Administrator";
	
	var html = "";
	html += "<tr id=\""+d.ID+"\" class=\"HCUser "+cls+"\"><td>"+d.user+"</td><td>"+admin+"</td><td>"+getElapsed(d.lastact)+"</td><td>"+d.fname+" "+d.lname+"</td><td>"+d.ip_addr+"</td></tr>";
	return html;
}

HCUser.Activate = function(id){
	$('#'+id).click(
	(function(id){
		return function(){ HCUser.Edit(id); }
	})(id));
}

HCUser.Attributes = [
	["Username","user",'text'],
	["First Name","fname",'text'],
	["Last Name","lname",'text'],
	["Administrator","admin",'checkbox'],
	["Enabled","enabled","checkbox"],
	["Password","pass",'password'],
	["Verify Password","verify",'password']
];

HCUser.New = function(){
	$('html,body').addClass('blackout-on');
	
	var html = "<table id=\"HCUser-new\">";
	var i;
	var attr;
	for(i in this.Attributes){
		attr = this.Attributes[i];
		html += "<tr><td>"+attr[0]+"</td><td><input type=\""+attr[2]+"\" id=\""+attr[1]+"\"></td></tr>";
	}
	
	html += "</table>";
	html += "<div class=\"center\"><button onclick=\"HCUser.Post()\">Create New User</button>";
	
	$('.blackout').find('.HCUser-dialog').html(html);
	$('.blackout').fadeIn(200);
}

HCUser.Edit = function(id){
	$('html,body').addClass('blackout-on');
	
	var html = "<table id=\"HCUser-edit\">";
	var i;
	var attr;
	var user = this.Users[id];
	var chked;
	
	for(i in this.Attributes){
		attr = this.Attributes[i];
		switch(attr[2]){
		case 'password':
			html += "<tr><td>"+attr[0]+"</td><td><input type=\""+attr[2]+"\" id=\""+attr[1]+"\"></td></tr>";
			break;
		case 'checkbox':
			chked = '';
			if(user[attr[1]] == 1) chked = ' checked'
			html += "<tr><td>"+attr[0]+"</td><td><input"+chked+" type=\""+attr[2]+"\" id=\""+attr[1]+"\"></td></tr>";
			break;
		default:
			html += "<tr><td>"+attr[0]+"</td><td><input type=\""+attr[2]+"\" id=\""+attr[1]+"\" value=\""+user[attr[1]]+"\"></td></tr>";
		}
	}
	
	html += "</table>";
	html += "<div class=\"center\"><button onclick=\"HCUser.Put("+id+")\">Save</button><button onclick=\"HCUser.Delete("+id+")\">Delete</button><button onclick=\"$('#HCUser-blackout').trigger('click');\">Close</button></div>";
	
	$('.blackout').find('.HCUser-dialog').html(html);
	$('.blackout').fadeIn(200);
}

HCUser.Put = function(id){
	var edit = $('#HCUser-edit');
	var pass = edit.find('#pass').val();
	var verify = edit.find('#verify').val();
	if(edit.find('#user').val() == ""){
		alert("You must enter a username.");
		return false;
	}

	var ej = new EasyJax('do.php/'+id,'PUT');

	if(pass.length != 0){
		if(pass != verify) {
			alert("The passwords you entered do not match.");
			return false;
		}
		ej.push('pass',pass);
	}
	var attr;
	for(i in this.Attributes){
		attr = this.Attributes[i];
		if(attr[2] == 'password') continue;
		if(attr[2] == 'checkbox'){
			ej.push(attr[1], edit.find('#'+attr[1]).prop('checked'));
		} else {
			ej.push(attr[1], edit.find('#'+attr[1]).val());
		}
	}
	
	ej.on('success',function(data,pobj){
		for(i in pobj){
			HCUser.Users[id][i] = pobj[i];
		}
		var newtr = $(HCUser.Format(id));
		$('#HCUser-table').find('#'+id).html(newtr.html()).attr('class',newtr.attr('class'));
		$('.blackout').fadeOut(200,function(){
			$('html,body').removeClass('blackout-on');
		});
	});
	ej.send();
}
	

HCUser.Post = function(){
	var newu = $('#HCUser-new');
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
	var pobj = {};
	var attr;
	for(i in this.Attributes){
		attr = this.Attributes[i];
		if(attr[1] == 'verify') continue;
		if(attr[2] == 'checkbox'){
			pobj[attr[1]] = newu.find('#'+attr[1]).prop('checked');
		} else {
			pobj[attr[1]] = newu.find('#'+attr[1]).val();
		}
	}
		
	easyj = new EasyJax('do.php','POST',function(data,pobj){
		pobj.ID = data.id;
		HCUser.Users[data.id] = pobj;
		$('#HCUser-table').append(HCUser.Format(data.id));
		HCUser.Activate(data.id);
		$('#HCUser-blackout').fadeOut(200,function(){
			$('html,body').removeClass('blackout-on');
		});
	},pobj);
	easyj.submit_data();
}

HCUser.Delete = function(id){
	if(!confirm("Are you sure you want to delete this user?  This CANNOT be undone!")){
		return false;
	}
	
	var ej = new EasyJax('do.php/'+id,'DELETE');
	ej.on('success',function(){
		$('#'+id).remove();
		$('#HCUser-blackout').fadeOut(200,function(){
			$('html,body').removeClass('blackout-on');
		});
	});
	easyj.send();
}	