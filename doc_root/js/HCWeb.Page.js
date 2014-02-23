if(typeof HCWeb === "undefined"){
	HCWeb = function(){
	}
}

HCWeb.Page = function(){
};

HCWeb.Page.Edit = function(id){
	var html = $('#HCWeb-Page-'+id).find('.HCWeb-Page-contents').css({'display': 'none'}).html();
	$('#HCWeb-Page-'+id).append('<textarea id="CK">'+html+'</textarea><div class="center" id="HCWeb-Page-otherbuttons"><button onclick="HCWeb.Page.Save('+id+')">Save</button><button onclick="HCWeb.Page.Cancel('+id+')">Cancel</button></div>');
	$('#HCWeb-Page-'+id).find('textarea').ckeditor();
}

HCWeb.Page.Save = function(id){
	var html = $('#HCWeb-Page-'+id).find('#CK').val();
	easyj = new EasyJax('/admin/pages/do.php/'+id,'PUT',function(){
		$('#HCWeb-Page-'+id).find('#cke_CK, #HCWeb-Page-otherbuttons').remove();
		$('#HCWeb-Page-'+id).find('.HCWeb-Page-contents').html(html).css({'display':'block'});
	},{'html':html});
	easyj.submit_data();
}

HCWeb.Page.Cancel = function(id){
	$('#HCWeb-Page-'+id).find('#cke_CK, #HCWeb-Page-otherbuttons').remove();
	$('#HCWeb-Page-'+id).find('.HCWeb-Page-contents').css({'display':'block'});
}

$(document).ready(function(){
	$('.HCWeb-Page').mouseenter(function(){
		$(this).find('.HCWeb-Page-buttons').css({'display':'block'});
	}).mouseleave(function(){
		$(this).find('.HCWeb-Page-buttons').css({'display':'none'});
	});
});