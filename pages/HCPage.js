function HCPage() {
}

CKEDITOR.config.contentsCss = ['/style.css','http://fonts.googleapis.com/css?family=Noto+Sans|Open+Sans+Condensed:300'];

HCPage.Load = function(){
	var file = $('#page').val();
	easyj = new EasyJax('do.php/'+file,'GET',function(data){
		$('#thestuff').html('<textarea id="file_contents">'+data.data+'</textarea><div class="center"><button onclick="HCPage.Save()">Save Changes</button></div>');
		$('#file_contents').ckeditor();
	});
	easyj.submit_data();
}

HCPage.Save = function(){
	var file = $('#page').val();
	easyj = new EasyJax('do.php/'+file,'PUT',function(){
		alert("Page Contents Saved Successfully!");
	},{'data' : $('#file_contents').val()});
	easyj.submit_data();
}
	