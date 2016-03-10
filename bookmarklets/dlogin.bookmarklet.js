javascript:(function () {
    if (typeof Drupal != 'undefined' && typeof Drupal.settings != 'undefined') {
        var u = false;
        if (typeof jQuery != 'undefined' && jQuery('body.logged-in').length) {
            if (!confirm("It looks like you're already logged in. Continue?")) {
                return;
            }
        }
        var l = window.location;
        var h = l.hash || '';
        var s = l.search || '';
        var b = Drupal.settings.basePath;
        var p = l.pathname.slice(b.length) || '<front>';
        window.location.href = l.protocol+ "//"+ l.host+b+ "index.php?q=user&destination="+ encodeURI(p+s+h);
    } else {
        alert("This doesn't appear to be a Drupal site.");
    }
})();