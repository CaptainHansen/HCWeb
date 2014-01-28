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

function date(fstr,d){
	var mthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
	var fullMonthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	var dayNames = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
	var fullDayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
	
	var suffix = function(number){
		switch(number){
			case 1:
				return 'st';
				break;
			case 2:
				return 'nd';
				break;
			case 3:
				return 'rd';
				break;
			default:
				return 'th';
		}
	}
	
	var zeroPad = function(number) {
		return ("0"+number).substr(-2,2);
	}
	
	var ampm = function(hrs){
		return (hrs < 12) ? 'am' : 'pm';
	}
	
	var twelveHour = function(hrs){
		var nval = (hrs < 12) ? hrs : hrs - 12;
		return (nval == 0) ? 12 : nval;
	}

	var dateMarkers = {
//Day
		d:['getDate',zeroPad ],
		D:['getDay',function(v) { return dayNames[v]; }],
		j:['getDate'],
		l:['getDay',function(v) { return fullDayNames[v]; }],
		N:['getDay',function(v) { if(v==0) return 7; return v; }],
		S:['getDate',suffix],
		w:['getDay'],
		//z

//Week
		//W
		
//Month
		F:['getMonth',function(v) { return fullMonthNames[v]; }],
		m:['getMonth',zeroPad],
		M:['getMonth',function(v) { return mthNames[v]; }],
		n:['getMonth'],
		//t:
		
//Year
		//L:
		//o:
		Y:['getFullYear'],
		y:['getFullYear',function(v) { return v.toString().substr(2); }],

//Time
		a:['getHours',ampm],
		A:['getHours',function(v) { return ampm(v).toUpperCase(); }],
		//B
		g:['getHours',twelveHour],
		G:['getHours'],
		
		h:['getHours',function(v) { return zeroPad(twelveHour(v)); }],
		H:['getHours',zeroPad],
		i:['getMinutes',zeroPad],
		s:['getSeconds',zeroPad],
		u:['getMilliseconds',function(v) { return v.toString()+"000"; }],

//Timezone
		//e:

//Full Date/Time
		U:['getTime',function(v) { return parseInt(v/1000); }]
	};
	
	if(d === undefined){
		d = new Date();
	} else if (!(d instanceof Date)){
		d = new Date(d*1000);
	}
	
	var dateTxt = fstr.replace(/(.){1}/g, function(m, p) {
		if(dateMarkers[p] == undefined) return p;
		var rv = d[ (dateMarkers[p])[0] ] ();
		if ( dateMarkers[p][1] != null ) rv = dateMarkers[p][1](rv);
		return rv;
	});

	return dateTxt;
}

function getElapsed(time){
	var since = date('U') - parseInt(time);
	if(time == 0) return 'never';
	if(since < 10) return "moments ago";
	if(since < 60) return since+" seconds ago";
	if(since < 3600) return parseInt(since/60)+" minutes ago";
	if(since < 86400) return parseInt(since / 3600)+" hours ago";
	return parseInt(since / 86400)+" days ago";
}