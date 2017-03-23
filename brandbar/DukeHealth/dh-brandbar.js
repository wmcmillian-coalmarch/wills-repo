// DOM ready function from [dustindiaz](http://dustindiaz.com/smallest-domready-ever)
function r(f){/in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}

var $ = document;
var cssID = 'dh-brandbar-style';

r(function() {

    // add the stylesheet
    if(!$.getElementById(cssID)) {
        var head = $.getElementsByTagName('head')[0];
        var link = $.createElement('link');
        link.id = cssID;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = 'https://pdc.dukemedicine.org/sites/pdc.dukemedicine.org/files/brandbar/style.css';
        link.media = 'all';
        head.appendChild(link);
    }

    // determine which tagline should be used
    var tagline = $.getElementById('dh-brandbar').getAttribute('data-tagline');
    var arrow = $.getElementById('dh-brandbar').getAttribute('data-arrow');

    var taglineCopy = 'A Duke Private Diagnostic Clinic';

    if (tagline == 1) {
        taglineCopy = 'Caring for Our Patients, Their Loved Ones, and Each Other';
    } else if (tagline == 2) {
        taglineCopy = 'A Duke Private Diagnostic Clinic';
    }
    var arrowClass = "onRight";
    if(arrow == 'onLeft') {
        arrowClass = "onLeft";
    }

    // plop in the HTML
    $.getElementById('dh-brandbar').innerHTML = '<div class="dh-wrap ' + arrowClass + '"><a class="dh-logo" href="https://www.dukehealth.org">Duke Health</a><span class="dh-tagline">'+ taglineCopy +'</span><span class="dh-links"><a href="https://www.dukehealth.org" class="duke-health">Duke Health</a><a href="https://www.dukemychart.org/home" class="my-chart">My Chart</a></span></div>';

});