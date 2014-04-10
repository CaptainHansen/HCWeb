HCPhotos.Duplicates = function(){
}

HCPhotos.Duplicates.Show = function(){
	var selection = HCPhotos.GetSel('HCPhotos-main');
	if(selection.length == 0){
		alert("You must select a photo by clicking it first.");
	} else if(selection.length > 1){
		alert("Select a single photo to check the database for duplicate photos.");
	} else {
		var id = selection[0];
		var easyj = new EasyJax('do.php/'+id,'DUP_CHECK',function(data){
			var dd = $('#HCPhotos-dups');
			var txt = '<div style="max-height: '+($(window).height()-200)+'px;overflow: auto;">';
			for(i in data.dups){
				txt += '<div class="HCPhoto" id=\"'+data.dups[i]+'\" style="cursor: pointer;"><img src="/photos/small/'+data.dups[i]+'" alt="'+data.dups[i]+'"></div>';
			}
			txt += '</div>';
			
			dd.find('#HCPhotos-dups-imgs').html(txt);
			HCPhotos.Activate(dd.find('.HCPhoto'));
			HCUI.BlkOn('#HCPhotos-dups-blackout');
		});
		easyj.submit_data();
	}
}

HCPhotos.Duplicates.Merge = function(){
	var selection = HCPhotos.GetSel('HCPhotos-dups');
	if(selection.length < 2){
		alert("You must select at least 2 images.");
	} else {
		if(confirm("Are you sure you want to merge the selected "+selection.length+" images?\n\nThe most recently uploaded image (furthest to the right) will remain.  All other images will be deleted.")){
			
			var max = parseInt(selection[1]);
			for(i in selection){
				if(parseInt(selection[i]) > max) max = parseInt(selection[i]);
			}
			var easyj = new EasyJax('do.php/'+max,'MERGE',function(data,pobj){

				for(i in pobj.ids){
					if(pobj.ids[i] != max){
						$('#HCPhotos-dups').find('#'+pobj.ids[i]).hide(300);
					} else {
						delete pobj.ids[i];
					}
				}
				HCPhotos.ClearSel('HCPhotos-dups');
				HCPhotos.ViewUpdate(data,pobj);				
			},{'ids':selection,'start':parseInt($('#total').val())-selection.length-1});

			easyj.submit_data();
		}
	}
}
