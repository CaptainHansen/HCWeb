$(document).ready(function(){
	$('#page_html').ckeditor();
});
	

function HCPage() {
}

//CKEDITOR.config.contentsCss = ['/style.css'];

HCPage.Load = function(){
	var file = $('#page').val();
	if(file == '--') {
		$('#thestuff').css({'display':'none'});
		return false;
	}
	var ej = new EasyJax('do.php/'+file,'GET');
	ej.on('success',function(data){
		$('#page_html').val(data.data.html);
		$('#thestuff').css({'display':'block'});
	});
	ej.send();
}

HCPage.Save = function(){
	var id = $('#page').val();
	if(id == '--'){
		alert("You have not selected a valid file and therefore cannot perform a save right now.");
		return false;
	}
	var ej = new EasyJax('do.php/'+id,'PUT');
	ej.on('success',function(){
		alert("Page Contents Saved Successfully!");
	}).push('html',$('#page_html').val()).send();
}
	