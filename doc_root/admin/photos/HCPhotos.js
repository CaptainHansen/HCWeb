offsetTop = 0;

end = false;
total = 0;
loading = 0;
more = 50;

$(document).ready(function(){
	HCPhotos.FetchMore(0,more);
	offsetTop = $('.HCPhotos-buttons').offset().top;
});

$(window).scroll(function(){
	var stop = $(window).scrollTop();
	if(offsetTop < stop){
		if(!$('.HCPhotos-buttons').hasClass('HCPhotos-buttons-scroll')){
			$('.HCPhotos-buttons-place').css({'height':$('.HCPhotos-buttons').outerHeight()+'px'});
			$('.HCPhotos-buttons,.HCPhotos-buttons-place').addClass('HCPhotos-buttons-scroll');
		}
	} else {
		if($('.HCPhotos-buttons').hasClass('HCPhotos-buttons-scroll')){
			$('.HCPhotos-buttons,.HCPhotos-buttons-place').removeClass('HCPhotos-buttons-scroll');
		}
	}

	var position = $(window).scrollTop();
	var end_pos = $(document).height() - $(window).height();
	
	if((end_pos - position < 500) & (loading != total) & !end) {
		loading = total;
		console.log('fired!');
		HCPhotos.FetchMore(total,more);
	}
});

function HCPhotos(){
}

HCPhotos.ResetPageSelect = true;

HCPhotos.Reload = function(){
	$('#HCPhotos-main').html('');
	end=false;
	total = 0;
	loading = 0;
	HCPhotos.FetchMore($('#HCPhotos-page-select').val(),more);
}

HCPhotos.Filter = function(){
	$('#HCPhotos-main').html('');
	end = false;
	total = 0;
	loading = 0;
	HCPhotos.ResetPageSelect = true;
	HCPhotos.FetchMore(0,more,$('#filter').val());
}

HCPhotos.Activate = function(el){
	if(el === undefined){
		el = $('HCPhotos-main').find('.HCPhoto');
	}
	el.click(function(ev){
		if($(ev.target).is('img')) $(this).toggleClass('HCPhoto-sel');
		if($(ev.target).is('input[type=checkbox]')) $(this).addClass('HCPhoto-catchanged');
	})
	.mouseenter(function(){
		$(this).find('.HCPhoto-catmenu').css({'display':'block'}).addClass('HCPhoto-catactive');
	})
	.mouseleave(function(){
		if(HCPhotos.Cats !== undefined){
			if($(this).hasClass('HCPhoto-catchanged')){
				HCPhotos.Cats.Send(this);
			}
		}
		$(this).find('.HCPhoto-catmenu').css({'display':'none'}).removeClass('HCPhoto-catactive');
	});
}

HCPhotos.FetchMore = function(start,more,filter){
	var pobj = {'start':start,'more':more};
	if(filter != undefined){
		pobj.filter = filter;
	}
	easyj = new EasyJax('do.php','RANGE',function(data,pobj){
		if(HCPhotos.ResetPageSelect){
			$('#HCPhotos-page-select').html('');
			var pages = parseInt((parseInt(data.count)+(more-1))/more);
			for(var i = 0; i < pages; i++){
				$('#HCPhotos-page-select').append('<option value="'+(i*more)+'">Page '+(i+1)+'</option>');
			}
			HCPhotos.ResetPageSelect = false;
		}
		if(HCPhotos.Cats !== undefined) HCPhotos.Cats.All = data.cats;
		for(i in data.photos){
			$('#HCPhotos-main').append(HCPhotos.Format(data.photos[i]));
			HCPhotos.Activate($('#HCPhotos-main').find('.HCPhoto').last());
		}
		total = parseInt(pobj.start)+data.photos.length;
		if(data.photos.length == 0) end=true;
	},pobj);
	easyj.submit_data();
}

HCPhotos.GetSel = function(getfrom){
	var ids = [];
	$('#'+getfrom).find('.HCPhoto-sel').each(function(){
		ids.push($(this).attr('id'));
	});
	return ids;
}

HCPhotos.ClearSel = function(from){
	$('#'+from).find('.HCPhoto-sel').each(function(){
		$(this).removeClass('HCPhoto-sel');
	});
}

HCPhotos.Delete = function(){
	var ids = HCPhotos.GetSel('HCPhotos-main');
	if(ids.length > 0){
		if(confirm("Are you sure you want to delete the "+ids.length+" photos you selected PERMANENTLY?")){
		
			var imgs = $('#HCPhotos-main').find('.HCPhoto');
			//Need to send the ID of the last image displayed on the page.  Must guard against the possibility that the last image on the page is one of the images getting deleted.
			for(i=imgs.length-1;i>=0;i--){
				if(!$.inArray($(imgs[i]).attr('id'),ids)) {
					var last = $(imgs[i]);
					break;
				}
			}
			easyj = new EasyJax('do.php','DELETE',HCPhotos.ViewUpdate,{'ids':ids});
			easyj.set_send_data('start',parseInt($('#total').val()) - ids.length - 1);
			easyj.submit_data();
		}
	} else {
		alert('Nothing to delete');
	}
}

//NEEDS UPDATING!!!
HCPhotos.ViewUpdate = function(data,pobj){
	var add_to_view = 0;
	var i,j;
	for(i in pobj.ids){
		add_to_view += $('#HCPhotos-main').find('#'+pobj.ids[i]).length;
		setTimeout(
			(function(id){
				return function(){
					$('#'+id).hide(600);
				};
			}(pobj.ids[i])),
		i*300);
	}
	HCPhotos.ClearSel('HCPhotos-main');
	
	//THEN - add the next images that were on the next page to this view.
	if(data.photos.length > 0){
		var last = $('#HCPhotos-main').find('.HCPhoto').last().attr('id');
		for(i in data.photos){
			if(data.photos[i].ID == last) break;
		}
		
		i = parseInt(i);
		for(j=i+1;j<=add_to_view+i;j++){
			$('#HCPhotos-main').append(HCPhotos.Format(data.photos[j],'hidden'));
			HCPhotos.Activate($('#HCPhotos-main').find('.HCPhoto').last());
			setTimeout(
				(function(id){
					return function(){
						$('#'+id).show(600);
					};
				}(data.photos[j].ID)),
			j*300+600);
		}
	}
}


HCPhotos.Format = function(data,hidden){
	var html;
	if(hidden == 'hidden'){ //we want to hide this initially
		html = '<div class="HCPhoto" id="'+data.ID+'" style="display: none;">';
	} else {
		html = '<div class="HCPhoto" id="'+data.ID+'">';
	}
	html += '<img src="img.php/'+data.ID+'">';
	if(HCPhotos.Cats !== undefined){
		html += HCPhotos.Cats.Format(JSON.parse(data.cats));
	}
	html += '</div>';
	return html;
}