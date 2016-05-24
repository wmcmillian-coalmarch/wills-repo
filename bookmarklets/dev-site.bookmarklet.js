javascript:(function(){
	var loc = window.location;
	var host = loc.protocol +"//"+ loc.hostname;

	var reg = /dev-(.*).(gotpantheon.com|pantheon.io|pantheonsite.io)/;

	var onPantheon = reg.test(host);

	if(onPantheon) {
		var parts = reg.exec(host);
		var newUrl = loc.href.replace(host, "http://"+parts[1]+".dev");
		window.location = newUrl;
	}
	else {
		var locreg = /http:\/\/(.*).dev/;

		var onLocal = locreg.test(host);

		if(onLocal) {
			var parts = locreg.exec(host);
			var newUrl = loc.href.replace(host, "http://dev-"+parts[1]+".pantheonsite.io");
			window.location = newUrl;
		}
	}
})();