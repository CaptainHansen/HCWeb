//This function is responsible for submitting data to the receiving server.  if type is set to GET, then boundary and pdata can be omitted from the function call.
//DO NOT EDIT
function submit_data(Url,type,p_func,pdata){
	if(window.XMLHttpRequest) {
		xmlHttp = new XMLHttpRequest(); 
	} else {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange = p_func;
	if(type == 'GET'){
		xmlHttp.open( "GET", Url, true );
		xmlHttp.send( null );
	}
	if(type == 'POST'){
		xmlHttp.open( "POST", Url, true );
		xmlHttp.setRequestHeader("Content-Type","multipart/form-data; boundary=\""+boundary+"\"; charset=ISO-8859-1");
		xmlHttp.send( pdata );
	}
}

boundary = '--__generic_boundary--';

//this function should be called from the user-edited getData function to create the data string to be submitted (pdata)
//DO NOT EDIT
function setPostBody(name,value){
	var body='--'+boundary+'\r\n';
	body += "Content-Disposition: form-data; name=\"" + name + "\"" + "\r\n\r\n"
	body += value + "\r\n";
	return body;
}
