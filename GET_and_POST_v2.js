//This function is responsible for submitting data to the receiving server.  if type is set to GET, then boundary and pdata can be omitted from the function call.
//DO NOT EDIT
function submit_data(Url,type,pdata){
	if(window.XMLHttpRequest) {
		xmlHttp = new XMLHttpRequest(); 
	} else {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	//this was a version of ProcessRequest in v1 (input variable named "p_func").
	//Write function whose name is contained in data.runOnSuccess and place in a javascript for the client.
	//data is a JSON data package returned to the client from the server.
	xmlHttp.onreadystatechange = function (){
		if ( xmlHttp.readyState == 4 && xmlHttp.status == 200 ) {
			data = JSON.parse(xmlHttp.responseText);
			if(data.error != "" && data.error != undefined){
				alert(data.error);
			} else {
				eval(data.runOnSuccess)(data);
			}
		}
	}

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
