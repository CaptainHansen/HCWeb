HCPhotos.Change = function(){
}

HCPhotos.Change.Select = function(){
	var photos = HCPhotos.GetSel('HCPhotos-main');
	if(photos.length != 1 && $('#HCPhotos-change-select-multi').val() != '1'){
		alert("You must select one and ONLY one photo.");
		return false;
	} else if(photos.length == 0) {
		alert("You must select at least one photo.");
		return false;
	}
	easyj = new EasyJax('do.php','PHOTO_CH',function(data){
		window.location.href = data.URL;
	},{'ids':photos});
	easyj.submit_data();
}

HCPhotos.Change.Click = function(id){
	easyj = new EasyJax('do.php','PHOTO_CH',function(data){
		window.location.href = data.URL;
	},{"id":id});
	easyj.submit_data();
}