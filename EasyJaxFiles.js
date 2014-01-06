/*
 * Copyright (c) 2013 Stephen Hansen (www.hansencomputers.com)
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:

 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE. 
 */

/** EasyJaxFiles Javascript class
 *
 * instantiate this class with the URL to post files to (PHP script using the EasyJaxFiles PHP class)
 * supply a callback function that will run after each successful upload.  If an error is returned,
 * it is announced using the alert() function
 */
 
 // make sure we have the sendAsBinary method on all browsers
XMLHttpRequest.prototype.mySendAsBinary = function(text){
	var data = new ArrayBuffer(text.length);
	var ui8a = new Uint8Array(data, 0);
	for (var i = 0; i < text.length; i++) ui8a[i] = (text.charCodeAt(i) & 0xff);

	if(typeof window.Blob != undefined) {
		var blob = new Blob([data]);
	} else {
		var bb = new (window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder)();
		bb.append(data);
		var blob = bb.getBlob();
	}

	this.send(blob);
}

function EasyJaxFiles (Url,req_type,files){
	this.Url = Url;
	
	this.start = function() {};
	this.progress = function(e) {};
	/*** like this: 
	function(e) {
				// get percentage of how much of the current file has been sent
				var position = e.position || e.loaded;
				var total = e.totalSize || e.total;
				var percentage = Math.round((position/total)*100);
		
				$('.file_progress').last().html("Uploaded "+percentage+"% of file "+file.name);

				// here you should write your own code how you wish to process this
			});
	*/
	this.badjson = function(txt) { alert("Unknown JSON response:<br>"+txt); return false; }
	this.success = function(data) {};
	this.error = function(data) { alert(data.error); return false; };
	
	this.files = files;
	this.files_index = 0;
	
	this.xmlHttp;
	this.req_type = req_type;
	
	this.fileReader;
	
	
	this.on = function(type,f){
		switch(type){
		case "start":
			this.start = f;
			break;
		case "progress":
			this.progress = f;
			break;
		case "badjson":
			this.badjson = f;
			break;
		case "success":
			this.success = f;
			break;
		case "error":
			this.error = f;
			break;
		default:
			break;
		}
		return this;
	}
	
	this.upload = function (){
		if(this.files_index >= files.length) return true;
		this.start();
		
		this.runUpload();
		this.files_index += 1;
	}
	
	this.runUpload = function(){
		// take the file from the input
		this.fileReader = new FileReader();
		this.fileReader.onloadend = this.createUploader();
		this.fileReader.readAsBinaryString(this.files[this.files_index]); // alternatively you can use readAsDataURL
	}
	
	this.createUploader = function(){
		var file = this.files[this.files_index];
		var ejf = this;
		this.xmlHttp = new XMLHttpRequest();
		
		var xhrCallback = this.createXHRCallback();

		var x = this.xmlHttp;
		return function(evt) {
			x.open(ejf.req_type, ejf.Url, true);
			x.setRequestHeader("Filename",file.name);

			// let's track upload progress
			var eventSource = x.upload || x;
			eventSource.addEventListener("progress", function(e){
				var s = {
					'position' : e.position || e.loaded,
					'total' : e.totalSize || e.total
				};
				s.percent = Math.round((s.position/s.total)*100);
				ejf.progress(s,file,e);
			});

			// state change observer - we need to know when and if the file was successfully uploaded
			x.onreadystatechange = xhrCallback;

			// start sending
			x.mySendAsBinary(evt.target.result);
		};
	}
	
	this.createXHRCallback = function(){
		var x = this.xmlHttp;
		var ejf = this;
		return function() {
			if(x.readyState == 4) {
				if(x.status == 200) {
					try {
						var data = JSON.parse(x.responseText);
						if(data.error == ''){
							ejf.success(data);
						} else {
							if(!ejf.error(data)) return false;
						}
					} catch (e) {
						if(!ejf.badjson(x.responseText)) return false;
					}
					ejf.upload();
				} else {
					console.log("Status code "+x.status+" not continuing...");
				}
			}
		};
	}
}