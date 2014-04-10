$(document).ready(function(){
	Social.Activate()
});

function Social() {
}

Social.networks = {
	'yelp' : "Yelp",
	'facebook' : "Facebook",
	'linkedin' : "LinkedIn",
	'googleplus' : "Google+",
	'twitter' : "Twitter",
	'instagram' : "Instagram",
	'flickr' : "Flickr",
	'youtube' : "YouTube",
	'pinterest' : "Pinterest",
	'github' : "Github",
	'tumblr' : "Tumblr"
};

Social.Activate = function(sel) {
	if(sel == undefined) sel = '#social-links .social a';
	$(sel).click(function(e){
		e.preventDefault();
		Social.Edit(this);
	})
	
	.attr('draggable',true)
	.bind('dragstart',function(ev){
		ev.originalEvent.dataTransfer.setData('id',ev.target.id);
		$(ev.target).addClass('dragging').css({"opacity":"0.5"});
	})
	.bind('dragend',function(ev){
		$('.drag-over').removeClass('drag-over');
		$('.dragging').css({"opacity":"1"}).removeClass('dragging');
	})
	.bind('dragover',function(ev) { ev.preventDefault(); })
	.bind('dragenter',function(ev) { $(ev.target).parent().addClass('drag-over'); })
	.bind('dragleave',function(ev) { $(ev.target).parent().removeClass('drag-over'); })
	.bind('drop',function(ev) {
		var fromid = $('.dragging').attr('id');
		ms = fromid.match(/site-(\d+)/);
		from = ms[1];
		var toid = $(ev.target).parent()[0].id;
		ms = toid.match(/site-(\d+)/);
		to = ms[1];
		
		if(from == to) return false;

		easyj = new EasyJax(from,'SEQ',function(data,pobj){
			var fromid = 'site-'+from;
			var toid = 'site-'+pobj.toid;
			$('div.social').each(function(){
				var items = $(this).children();
				for(i in items){
					if(items[i].id == fromid) {
						$(this).find('#'+fromid).insertAfter($(this).find('#'+toid));
						return true;
					}
					if(items[i].id == toid) {
						$(this).find('#'+fromid).insertBefore($(this).find('#'+toid));
						return true;
					}
				}
			});
		},{'toid':to});
		
		easyj.submit_data();
	});
}

Social.FormatEditor = function(id,site,url){
	if(id == undefined) id = 0;
	if(site == undefined) site = '--';
	if(url == undefined) url = '';
	
	var html = "<input type=\"hidden\" id=\"ID\" value=\""+id+"\">";
	
	var sadmin = '';
	
	html += "<div><span>Network</span><select id=\"site\"><option value=\"--\">-- choose one --</option>";
	for(i in Social.networks){
		html += "<option value=\""+i+"\"";
		if(i == site) {
			html += " selected";
			sadmin = "<a class=\""+site+"\"><div></div></a>";
		}
		html += ">"+Social.networks[i]+"</option>";
	}
	html += "</select></div>";
	
	var cls = 'hide';
	if(site != '--') cls = '';
	
	html += "<div class=\"social admin\">"+sadmin+"</div>";
	
	html += "<div id=\"url\" class=\""+cls+"\"><span>URL</span><input type=\"text\" value=\""+url+"\"></div>";
	
	$('#SocialEdit').html(html);
	$('#site').change(function(){
		var html = '';
		if($(this).val() != '--') {
			html = "<a class=\""+$(this).val()+"\"><div></div></a>";
			$('div#url').removeClass('hide');
		} else {
			$('div#url').addClass('hide');
		}
		$('#SocialEdit').find('div.social').html(html);
	});
}
	

Social.Add = function(){
	Social.FormatEditor();

	$('#SocialEdit').prepend("<h2>Add New Account</h2>");
	$('#SocialEdit').append("<div class=\"center\"><button onclick=\"Social.Post();\">Save</button><button onclick=\"HCUI.BlkOff();\">Cancel</button></div>");
	HCUI.BlkOn();
}

Social.Edit = function(sel){
	var ms = $(sel).attr('id').match(/site-(\d+)/);
	Social.FormatEditor(ms[1],$(sel).attr('class'),$(sel).attr('href'));
	$('#SocialEdit').prepend('<h2>Edit Account</h2>');
	$('#SocialEdit').append("<div class=\"center\"><button onclick=\"Social.Put();\">Save</button><button onclick=\"HCUI.BlkOff();\">Cancel</button><button onclick=\"Social.Delete();\">Delete</button></div>");
	HCUI.BlkOn();
}

Social.Post = function(){
	var site = $('#site').val();
	if(site == '--'){
		alert("You must choose a network first.");
		return false;
	}
	if(!$('#url input').val().match(/^https?:\/\//)){
		alert("The URL parameter must begin with either \"http://\" or \"https://\"");
		return false;
	}
	
	easyj = new EasyJax('0','POST',function(data,pobj){
		$('#social-links').find('div.social').append("<a id=\"site-"+data.id+"\" class=\""+pobj.site+"\" href=\""+pobj.url+"\" target=\"_blank\"><div></div></a>");
		Social.Activate($('#social-links').find('div.social').children().last());
		HCUI.BlkOff();
	},{'site':site, 'url':$('#url input').val(), 'visible': 1});
	easyj.submit_data();
}

Social.Put = function(){
	var site = $('#site').val();
	if(site == '--'){
		alert("You must choose a network first.");
		return false;
	}
	if(!$('#url input').val().match(/^https?:\/\//)){
		alert("The URL parameter must begin with either \"http://\" or \"https://\"");
		return false;
	}
	var ID = $('#SocialEdit').find('#ID').val();
	
	easyj = new EasyJax(ID,'PUT',function(data,pobj){
		$('#social-links').find("div.social").find("#site-"+ID).attr('class',pobj.site).attr('href',pobj.url);
		HCUI.BlkOff();
	},{'site':site, 'url':$('#url input').val()});
	easyj.submit_data();
}

Social.Delete = function() {
	if(!confirm("Are you sure you want to delete this social media link?")){
		return false;
	}
	
	var ID = $('#SocialEdit').find('#ID').val();
	easyj = new EasyJax(ID,'DELETE',function(){
		$('#social-links').find('#site-'+ID).remove();
		HCUI.BlkOff();
	});
	easyj.submit_data();
}