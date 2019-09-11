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
        window.location.href = l.protocol+ "//"+ l.host+ b+ "user?destination="+ encodeURI(p + s + h);
    } else if (typeof drupalSettings != 'undefined') {
        var u = false;
        if (typeof jQuery != 'undefined' && jQuery('body.user-logged-in').length) {
            if (!confirm("It looks like you're already logged in. Continue?")) {
                return;
            }
        }
        var l = window.location;
        var h = l.hash || '';
        var s = l.search || '';
        var b = drupalSettings.path.baseUrl || drupalSettings.baseUrl;
        var p = l.pathname.slice(b.length) || '<front>';
        window.location.href = l.protocol + "//" + l.host + b + "user/login?destination=" + encodeURI(p + s + h);
    } else if (typeof _int != 'undefined') {
        var u = false;
        if (typeof jQuery != 'undefined' && jQuery('body.logged-in').length) {
            if (!confirm("It looks like you're already logged in. Continue?")) {
                return;
            }
        }
        var l = window.location;
        var h = l.hash || '';
        var s = l.search || '';
        var b = "/";
        var p = l.pathname.slice(b.length) || '<front>';
        window.location.href = l.protocol + "//" + l.host + b + "admin?destination=" + encodeURI(p + s + h);
    } else {
        alert('This doesn\'t appear to be a Drupal site.');
    }