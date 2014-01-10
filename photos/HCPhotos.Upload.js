$(document).ready(function(){
	$('#HCPhotos-upload').change(HCPhotos.Upload.Run);
});

HCPhotos.Upload = function(){
}

HCPhotos.Upload.Close = function(){
	$('#HCPhotos-upload-blackout').fadeOut(200);
}

HCPhotos.Upload.Run = function(){
	var files = $('#HCPhotos-upload')[0].files;
	ejf = new EasyJaxFiles("upload.php","POST",files);
	ejf.on('progress',function(s,file){
		if(s.percent == 100){
			$('#HCPhotos-overall-status').html("Compiling \""+file.name+"\". "+(s.num_files-s.current_file)+" more files remaining...");
		} else {
			$('#HCPhotos-overall-status').html("Uploading "+s.current_file+" of "+s.num_files+" photos...");
		}
		$('#HCPhotos-overall-status').val(s.overallPercent);
	}).on('success',function(data){
		$('#HCPhotos-main').prepend(HCPhotos.Format(data.photo,'hidden'));
		HCPhotos.Activate($('#HCPhotos-main').find('.HCPhoto').first());
		setTimeout(function(){$('#'+data.photo.ID).show(600);},i*300+600);
//		$('#HCPhotos-upload-status').append('<div class=\"HCPhotos-upload-success\">Compilation completed successfully for "'+data.orig_name+'"</div>');
	}).on('error',function(data){
		$('#HCPhotos-upload-status').append('<div class=\"HCPhotos-upload-error\">Compilation/Upload FAILED for "'+data.orig_name+'" - '+data.error+'</div>');
		return true;	//continue
	}).on('start',function(data){
		$('#HCPhotos-upload-blackout').fadeIn(200).html('<div id="HCPhotos-upload-status"><h1>Upload Progress</h1><div id="HCPhotos-overall-status" class="HCPhotos-progress-main" value="0">Progress</div></div>');
		$('#HCPhotos-overall-status').HCProgress();
	}).on('finish',function(){
		$('#HCPhotos-overall-status').html('Process Complete!');
		$('#HCPhotos-upload-status').append('<div class=\"center\"><button onclick=\"HCPhotos.Upload.Close();\">Done</button></div>');
	});
	ejf.upload();
}

function progressTest(){
	$('#HCPhotos-upload-blackout').fadeIn(200).html('<div id="HCPhotos-upload-status"><h1>Upload Progress</h1><div id="HCPhotos-overall-status"></div></div>');
	$('#HCPhotos-upload-status').append('<div id="ffff" value="20">Testing!</div>');
	$('#ffff').HCProgress();
}