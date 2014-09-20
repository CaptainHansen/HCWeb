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
	var ej = new EasyJax('do.php','PHOTO_CH');
	ej.on('success',function (data) {
		window.location.href = data.URL;
	}).push('ids',photos);
	ej.send();
}

HCPhotos.Change.Click = function(id){
	var ej = new EasyJax('do.php','PHOTO_CH');
	ej.on('success',function(data){
		window.location.href = data.URL;
	}).push("id",id);
	ej.send();
}