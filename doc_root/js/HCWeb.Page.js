if(typeof HCWeb === "undefined"){
	HCWeb = function(){
	}
}

HCWeb.Page = function(){
};

HCWeb.Page.Edit = function(o){
	var obj = $(o).parent().parent();
	$(o).parent().css({'display':'none'});
	var html = obj.find('.HCWeb-Page-contents').css({'display': 'none'}).html();
	obj.append('<textarea id="CK">'+html+'</textarea><div class="center" id="HCWeb-Page-otherbuttons"><button onclick="HCWeb.Page.Save(this)">Save</button><button onclick="HCWeb.Page.Cancel(this)">Cancel</button></div>');
	obj.find('textarea').ckeditor();
}

HCWeb.Page.Save = function(o){
	var obj = $(o).parent().parent();
	var ms = obj.attr('id').match(/HCWeb-Page-(\d+)/);
	var id = ms[1];
	var html = obj.find('#CK').val();
	easyj = new EasyJax('/admin/pages/do.php/'+id,'PUT',function(){
		obj.find('#CK, #cke_CK, #HCWeb-Page-otherbuttons').remove();
		obj.find('.HCWeb-Page-contents').html(html).css({'display':'block'});
	},{'html':html});
	easyj.submit_data();
}

HCWeb.Page.Cancel = function(o){
	var obj = $(o).parent().parent();
	obj.find('#CK, #cke_CK, #HCWeb-Page-otherbuttons').remove();
	obj.find('.HCWeb-Page-contents').css({'display':'block'});
}

$(document).ready(function(){
	$('.HCWeb-Page').mouseenter(function(){
		if($(this).find('#cke_CK').length == 0) $(this).find('.HCWeb-Page-buttons').css({'display':'block'});
	}).mouseleave(function(){
		$(this).find('.HCWeb-Page-buttons').css({'display':'none'});
	});
});