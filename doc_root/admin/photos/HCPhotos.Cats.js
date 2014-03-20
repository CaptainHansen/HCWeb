HCPhotos.Cats = function(){
}

HCPhotos.Cats.All = {};

$(document).keydown(function(e){
	if(e.keyCode >= 49 && e.keyCode <= 57) HCPhotos.Cats.KeyToggle(e.keyCode);
});

HCPhotos.Cats.KeyToggle = function(kc){
	var menu = $('.HCPhoto-catactive');
	if(menu.length != 1){
		return false;
	} else {
		menu.parent().addClass('HCPhoto-catchanged');
		cat_els = menu.find('input[type=checkbox]');
		i = kc-49; //49-48 = 1 (keycode for the "1" key is 49)
		if(cat_els[i] != null){
			cat_els[i].checked = (!cat_els[i].checked);
		}
	}
}

HCPhotos.Cats.Send = function(el){
	var newcats = [];
	$(el).find('.HCPhoto-catmenu').find('input[type=checkbox]:checked').each(function(){
		newcats.push($(this).val());
	});

	easyj = new EasyJax('do.php/'+$(el).attr('id'),'CH_CATS',function(data){
		$(el).removeClass('HCPhoto-catchanged');
	},{'cats':newcats});
	easyj.submit_data();
}

HCPhotos.Cats.Format = function(data){
	var html = '<div class="HCPhoto-catmenu"><div style="text-align: center; font-size: 14pt;">Categories</div>';
	var checked = '';
	for(i in HCPhotos.Cats.All){
		if($.inArray(HCPhotos.Cats.All[i].ID,data) > -1) { //make it checked
			checked = 'checked ';
		} else {
			checked = '';
		}
		html += '<div><input type="checkbox" '+checked+'value="'+HCPhotos.Cats.All[i].ID+'"> '+HCPhotos.Cats.All[i].name+'</div>';
	}
	return html;
}