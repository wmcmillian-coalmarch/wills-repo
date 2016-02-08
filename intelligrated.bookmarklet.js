javascript:(function(){
    if(_int != undefined && _int.nid != undefined) {
        var loc = window.location;
        var host = loc.protocol +"//"+ loc.hostname;
        var url = host + '/node/' + _int.nid + '/edit';
        window.location = url;
    }
})();