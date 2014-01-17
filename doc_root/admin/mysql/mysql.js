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

$(document).ready(function(){
	$('#query').keydown(function(event){
		if(event.keyCode == 13){
			run_query();
		}
	});
});

function run_query(){
	easyj = new EasyJax('do.php','QUERY',function(data,pdat){
		if(data.dberr != undefined){
			$('#results').prepend("<div style=\"color: red;\">"+pdat.query+"<br>"+data.dberr+"</div><hr>");
		} else {
			var html = "<div style=\"color: green;\">"+pdat.query+"</div>";
			if(data.cols != undefined){
				html += "<table style=\"border: solid 1px;\"><tr>";
				for(i in data.cols){
					html += "<th>"+data.cols[i]+"</th>";
				}
				html += "</tr>";
		
				for(i in data.rows){
					html += "<tr>";
					var row = data.rows[i];
					for(j in row){
						html += "<td>"+row[j]+"</td>";
					}
					html += "</tr>";
				}
				html += "</table>";
			}
			html += "<hr>";
			$('#results').prepend(html);
		}
	});
	easyj.set_send_data('query',$('#query').val());
	
	easyj.submit_data();
}
