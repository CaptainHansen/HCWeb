$(document).ready(function(){
	if(typeof CryptoJS === 'undefined') $.getScript('/js/encryption.js');
});

HCCrypt = function(){
}

HCCrypt.Base64 = {
	map:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
	padchar:"="
}

HCCrypt.Base16to64 = function(h){
	var i;
	var c;
	var ret = "";
	for(i = 0; i+3 <= h.length; i+=3) {
		c = parseInt(h.substring(i,i+3),16);
		ret += this.Base64.map.charAt(c >> 6) + this.Base64.map.charAt(c & 63);
	}
	if(i+1 == h.length) {
		c = parseInt(h.substring(i,i+1),16);
		ret += this.Base64.map.charAt(c << 2);
	} else if(i+2 == h.length) {
		c = parseInt(h.substring(i,i+2),16);
		ret += this.Base64.map.charAt(c >> 2) + this.Base64.map.charAt((c & 3) << 4);
	}
	while((ret.length & 3) > 0) ret += this.Base64.padchar;
	return ret;
}

HCCrypt.Base64to16 = function(s) {
	var ret = ""
	var i;
	var k = 0; // b64 state, 0-3
	var slop;
	for(i = 0; i < s.length; ++i) {
		if(s.charAt(i) == this.Base64.padchar) break;
		v = this.Base64.map.indexOf(s.charAt(i));
		if(v < 0) continue;
		if(k == 0) {
			ret += int2char(v >> 2);
			slop = v & 3;
			k = 1;
		} else if(k == 1) {
			ret += int2char((slop << 2) | (v >> 4));
			slop = v & 0xf;
			k = 2;
		} else if(k == 2) {
			ret += int2char(slop);
			ret += int2char(v >> 2);
			slop = v & 3;
			k = 3;
		} else {
			ret += int2char((slop << 2) | (v >> 4));
			ret += int2char(v & 0xf);
			k = 0;
		}
	}
	if(k == 1) ret += int2char(slop << 2);
	return ret;
}


HCCrypt.RSA = function(){
	this.rsa = new RSAKey();
	this.setPublic = function(modulus,exponent){
		return this.rsa.setPublic(modulus,exponent);
	};
	
	this.encrypt = function(data){
		return HCCrypt.Base16to64(this.rsa.encrypt(data));
	}
}

HCCrypt.AES = function(){
	this.key = '0102030405060708090a0b0c0d0e0f';
	this.iv = '000000000000000000000000000000';

	this.genKey = function(size){
		if(size == undefined) size=64;
		charmap = '0123456789abcdef';
		var i;
		this.key = '';
		this.iv = '';
		var ind;
		for(i=0; i < size; i++){
			ind = parseInt(Math.random() * (charmap.length));
			if(charmap[ind] == undefined){
				alert(ind+" DOES NOT EXIST!");
			}
			this.key += charmap[ind];
		}
		for(i=0; i < 32; i++){
			ind = parseInt(Math.random() * (charmap.length));
			this.iv += charmap[ind];
		}
	}
		
	this.setKey = function(key){
		this.key = CryptoJS.enc.Hex.parse(key);
	}
	
	this.setIv = function(iv){
		this.iv = CryptoJS.enc.Hex.parse(iv);
	}
	
	this.encrypt = function(ptext){
		var enc = CryptoJS.AES.encrypt(ptext,CryptoJS.enc.Hex.parse(this.key),{iv: CryptoJS.enc.Hex.parse(this.iv)});
		return enc.toString();
	}
	
	this.decrypt = function(ciphertext){
		var dec = CryptoJS.AES.decrypt(ciphertext,CryptoJS.enc.Hex.parse(this.key),{iv: CryptoJS.enc.Hex.parse(this.iv)});
		return dec.toString(CryptoJS.enc.Utf8);
	}
	
	this.getKeyIv = function(){
		return {'key':HCCrypt.Base16to64(this.key),'iv':HCCrypt.Base16to64(this.iv)};
	}
}