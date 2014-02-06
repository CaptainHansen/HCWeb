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
	easyj = new EasyJax('do.php/'+file,'GET',function(data){
		$('#page_html').val(data.data.html);
		$('#thestuff').css({'display':'block'});
	});
	easyj.submit_data();
}

HCPage.Save = function(){
	var id = $('#page').val();
	if(id == '--'){
		alert("You have not selected a valid file and therefore cannot perform a save right now.");
		return false;
	}
	easyj = new EasyJax('do.php/'+id,'PUT',function(){
		alert("Page Contents Saved Successfully!");
	},{'html' : $('#page_html').val()});
	easyj.submit_data();
}
	