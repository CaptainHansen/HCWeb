function buttonClick(){
	$('#files').trigger('click');
}

$(document).ready(function(){
	$('#btn').click(buttonClick);
	$('#files').change(function(){
		ejf = new EasyJaxFiles('upload.php','POST',$('#files')[0].files);
		ejf.on('start',function(){
			$('#btn').off('click');
			$('#results').html('');
			$('#results').append('<div style="text-align: center;"><div id="allprogress">Process Initiated</div></div>');
			$('#allprogress').HCProgress();
		}).on('nextfile',function(){
			$('#results').append('<div class="file"><div id="file_progress"></div></div>');
			$('.file:last').find('#file_progress').HCProgress();
		}).on('progress',function(s,file){
			$('.file:last').find('#file_progress').val(s.percent).html('File "'+file.name+'" - '+s.percent.toFixed(0)+'% Complete');
			$('#allprogress').val(s.overallPercent).html(s.overallPercent.toFixed(0)+'% complete - '+s.current_file+' of '+s.num_files+' Uploaded');
		}).on('success',function(data){
			$('.file:last').find('#file_progress').html('File "'+data.name+'" Uploaded Successfully!');
			$('#allfiles').append("<div class='fname'>"+data.name+"</div>");
		}).on('error',function(data){
			$('.file:last').find('#file_progress').html('File "'+data.name+'" Not Uploaded! - '+data.error);
		}).on('finish',function(){
			$('#allprogress').html('Process Complete!');
			$('#btn').click(buttonClick);
		});
		ejf.upload();
	});
});