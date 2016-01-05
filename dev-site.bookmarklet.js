javascript:(function(){
	var loc = window.location;
	var host = loc.protocol +"//"+ loc.hostname;

	var reg = /dev-(.*).(gotpantheon.com|pantheon.io)/;

	var test = reg.test(host);

	if(test) {
		var parts = reg.exec(host);
		var newUrl = loc.href.replace(host, "http://"+parts[1]+".dev");
		window.location = newUrl;
	}
})();