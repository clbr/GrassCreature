function showlogin() {
	var div = document.getElementById("loginbox");
	div.innerHTML = "<form action=login.php method=post name=loginform>" +
			"<input type=text name=username length=20 " +
			"onkeypress=\"return noenter(event)\">" +
			" <input type=password name=password length=20 " +
			"onkeypress=\"return noenter(event)\">" +
			" <input type=button onclick=\"dologin()\" value=\"Log in\">" +
			"</form>";

	document.forms["loginform"].username.focus();
}

function dologin() {

	var form = document.getElementsByName("loginform")[0];
	var pass = "ideabank" + form.username.value + form.password.value;

	var final = md5(pass);

	form.password.value = final;

	form.submit();
}

// This prevents the enter key from submitting the login form.
function noenter(e) {
	var key;
	if (window.event)
		key = window.event.keyCode;
	else
		key = e.which;

	if (key == 13) {
		dologin();
		return false;
	}
	return true;
}

/*
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */
function g(e,a,b,c,d,f,l){return h(h(h(e,a&b|~a&c),h(d,l))<<f|h(h(e,a&b|~a&c),h(d,l))>>>32-f,a)}function i(e,a,b,c,d,f,l){return h(h(h(e,a&c|b&~c),h(d,l))<<f|h(h(e,a&c|b&~c),h(d,l))>>>32-f,a)}function j(e,a,b,c,d,f,l){return h(h(h(e,a^b^c),h(d,l))<<f|h(h(e,a^b^c),h(d,l))>>>32-f,a)}function k(e,a,b,c,d,f,l){return h(h(h(e,b^(a|~c)),h(d,l))<<f|h(h(e,b^(a|~c)),h(d,l))>>>32-f,a)}function h(e,a){var b=(e&65535)+(a&65535);return(e>>16)+(a>>16)+(b>>16)<<16|b&65535}
window.md5=function(e){var a;a="";for(var b=-1,c,d;++b<e.length;)c=e.charCodeAt(b),d=b+1<e.length?e.charCodeAt(b+1):0,55296<=c&&56319>=c&&56320<=d&&57343>=d&&(c=65536+((c&1023)<<10)+(d&1023),b++),127>=c?a+=String.fromCharCode(c):2047>=c?a+=String.fromCharCode(192|c>>>6&31,128|c&63):65535>=c?a+=String.fromCharCode(224|c>>>12&15,128|c>>>6&63,128|c&63):2097151>=c&&(a+=String.fromCharCode(240|c>>>18&7,128|c>>>12&63,128|c>>>6&63,128|c&63));e=Array(a.length>>2);for(b=0;b<e.length;b++)e[b]=0;for(b=0;b<8*
a.length;b+=8)e[b>>5]|=(a.charCodeAt(b/8)&255)<<b%32;a=8*a.length;e[a>>5]|=128<<a%32;e[(a+64>>>9<<4)+14]=a;a=1732584193;b=-271733879;c=-1732584194;d=271733878;for(var f=0;f<e.length;f+=16){var l=a,m=b,n=c,o=d;a=g(a,b,c,d,e[f+0],7,-680876936);d=g(d,a,b,c,e[f+1],12,-389564586);c=g(c,d,a,b,e[f+2],17,606105819);b=g(b,c,d,a,e[f+3],22,-1044525330);a=g(a,b,c,d,e[f+4],7,-176418897);d=g(d,a,b,c,e[f+5],12,1200080426);c=g(c,d,a,b,e[f+6],17,-1473231341);b=g(b,c,d,a,e[f+7],22,-45705983);a=g(a,b,c,d,e[f+8],7,1770035416);
d=g(d,a,b,c,e[f+9],12,-1958414417);c=g(c,d,a,b,e[f+10],17,-42063);b=g(b,c,d,a,e[f+11],22,-1990404162);a=g(a,b,c,d,e[f+12],7,1804603682);d=g(d,a,b,c,e[f+13],12,-40341101);c=g(c,d,a,b,e[f+14],17,-1502002290);b=g(b,c,d,a,e[f+15],22,1236535329);a=i(a,b,c,d,e[f+1],5,-165796510);d=i(d,a,b,c,e[f+6],9,-1069501632);c=i(c,d,a,b,e[f+11],14,643717713);b=i(b,c,d,a,e[f+0],20,-373897302);a=i(a,b,c,d,e[f+5],5,-701558691);d=i(d,a,b,c,e[f+10],9,38016083);c=i(c,d,a,b,e[f+15],14,-660478335);b=i(b,c,d,a,e[f+4],20,-405537848);
a=i(a,b,c,d,e[f+9],5,568446438);d=i(d,a,b,c,e[f+14],9,-1019803690);c=i(c,d,a,b,e[f+3],14,-187363961);b=i(b,c,d,a,e[f+8],20,1163531501);a=i(a,b,c,d,e[f+13],5,-1444681467);d=i(d,a,b,c,e[f+2],9,-51403784);c=i(c,d,a,b,e[f+7],14,1735328473);b=i(b,c,d,a,e[f+12],20,-1926607734);a=j(a,b,c,d,e[f+5],4,-378558);d=j(d,a,b,c,e[f+8],11,-2022574463);c=j(c,d,a,b,e[f+11],16,1839030562);b=j(b,c,d,a,e[f+14],23,-35309556);a=j(a,b,c,d,e[f+1],4,-1530992060);d=j(d,a,b,c,e[f+4],11,1272893353);c=j(c,d,a,b,e[f+7],16,-155497632);
b=j(b,c,d,a,e[f+10],23,-1094730640);a=j(a,b,c,d,e[f+13],4,681279174);d=j(d,a,b,c,e[f+0],11,-358537222);c=j(c,d,a,b,e[f+3],16,-722521979);b=j(b,c,d,a,e[f+6],23,76029189);a=j(a,b,c,d,e[f+9],4,-640364487);d=j(d,a,b,c,e[f+12],11,-421815835);c=j(c,d,a,b,e[f+15],16,530742520);b=j(b,c,d,a,e[f+2],23,-995338651);a=k(a,b,c,d,e[f+0],6,-198630844);d=k(d,a,b,c,e[f+7],10,1126891415);c=k(c,d,a,b,e[f+14],15,-1416354905);b=k(b,c,d,a,e[f+5],21,-57434055);a=k(a,b,c,d,e[f+12],6,1700485571);d=k(d,a,b,c,e[f+3],10,-1894986606);
c=k(c,d,a,b,e[f+10],15,-1051523);b=k(b,c,d,a,e[f+1],21,-2054922799);a=k(a,b,c,d,e[f+8],6,1873313359);d=k(d,a,b,c,e[f+15],10,-30611744);c=k(c,d,a,b,e[f+6],15,-1560198380);b=k(b,c,d,a,e[f+13],21,1309151649);a=k(a,b,c,d,e[f+4],6,-145523070);d=k(d,a,b,c,e[f+11],10,-1120210379);c=k(c,d,a,b,e[f+2],15,718787259);b=k(b,c,d,a,e[f+9],21,-343485551);a=h(a,l);b=h(b,m);c=h(c,n);d=h(d,o)}e=[a,b,c,d];a="";for(b=0;b<32*e.length;b+=8)a+=String.fromCharCode(e[b>>5]>>>b%32&255);e=a;a="";for(c=0;c<e.length;c++)b=e.charCodeAt(c),
a+="0123456789abcdef".charAt(b>>>4&15)+"0123456789abcdef".charAt(b&15);return a};
