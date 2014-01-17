$(document).ready(function(){
	$('#file_contents').ckeditor();
});
	

function HCPage() {
}

CKEDITOR.config.contentsCss = ['/style.css','http://fonts.googleapis.com/css?family=Noto+Sans|Open+Sans+Condensed:300'];

HCPage.Load = function(){
	var file = $('#page').val();
	if(file == '--') {
		$('#thestuff').css({'display':'none'});
		return false;
	}
	easyj = new EasyJax('do.php/'+file,'GET',function(data){
		$('#file_contents').val(data.data);
		$('#thestuff').css({'display':'block'});
	});
	easyj.submit_data();
}

HCPage.Save = function(){
	var file = $('#page').val();
	if(file == '--'){
		alert("You have not selected a valid file and therefore cannot perform a save right now.");
		return false;
	}
	easyj = new EasyJax('do.php/'+file,'PUT',function(){
		alert("Page Contents Saved Successfully!");
	},{'data' : $('#file_contents').val()});
	easyj.submit_data();
}
	