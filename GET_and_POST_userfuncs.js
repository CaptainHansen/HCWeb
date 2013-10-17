//this function is responsible for acquiring the data from the webpage to submit to the server and is the only one of these that needs to be editted.  Use setPostBody to create the pdata element.
function getData(){
	pdata='';
	pdata += setPostBody('photos',data);
	Url='path_to_script...';
	process=ProcessRequest; //this function is called while the request is working.
	type='POST'; //this Variable should be set to either GET or POST
	submit_data(Url,type,process,pdata);
}

//after the request has been sent from submit_data, this function is responsible for getting the data returned from the server (whether it be an error message, etc.).
//depending on the number of different operations you are need to do, you may need to make duplicates of this function
function ProcessRequest() {
	if ( xmlHttp.readyState == 4 && xmlHttp.status == 200 ) {
		alert(xmlHttp.responseText); //this is just an example and should be changed.
	}
}
