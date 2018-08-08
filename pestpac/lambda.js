function authHeader(context) {
    return Buffer.from(process.env.pestpac_clientid + ":" + process.env.pestpac_secret).toString('base64');
}
function getAccessToken(context) {
    let auth = authHeader(context);
    let headers = {
        'Authorization': 'Bearer ' + auth,
        'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
    };
    let post_data = "grant_type=password&username=" + process.env.pestpac_username +
        "&password=" + process.env.pestpac_password;
    return context.http_post("https://is.workwave.com/oauth2/token?scope=openid", post_data, headers);
}

function renewAcessToken(context) {
    let auth = authHeader(context);
    let headers = {
        'Authorization': 'Bearer ' + auth,
        'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
    };
    let post_data = "grant_type=refresh_token&refresh_token=" + process.env.refresh_token;
    return context.http_post("https://is.workwave.com/oauth2/token?scope=openid", post_data, headers);
}

function createPestPacCall(event, context) {
    /*
    curl -i -X GET --header 'Accept: application/json' \
                 --header 'Authorization: Bearer f057fea0-7e4c-3259-9bbf-5f8bd969eab4' \
                 --header 'Apikey: fXGpNGCKII3m8UhVxkRNt34k7z7P9sGt' \
                 --header 'tenant-id: 923082' 'https://api.workwave.com/pestpac/v1/lookups/Branches'
    */
    let headers = {
        'Authorization': 'Bearer ' + process.env.access_token,
        'Apikey':        process.env.pestpac_api,
        'tenant-id':     process.env.pestpac_tenant,
        'Accept':        'application/json',
        'Content-Type':  'application/json'
    };
    let activity = event.activity;
    let locationid  = '';
    if (typeof(activity.custom_fields.strings) == 'object') {
        locationid = activity.custom_fields.strings.filter(function(value) {
            return value.name == 'locationid';
        })[0].value;
    } else {
        locationid = activity.custom_fields.locationid;
    }
    let branch  = '';
    if (typeof(activity.custom_fields.strings) == 'object') {
        branch = activity.custom_fields.strings.filter(function(value) {
            return value.name == 'branch';
        })[0].value;
    } else {
        branch = activity.custom_fields.branch;
    }
    console.log("selected branch:", branch);
    let post_data = {
        LocationID: locationid,
        Branch: branch,
        ContactName: activity.name || "Not Set",
        Address: activity.street,
        City: activity.city,
        State: activity.state,
        Zip: activity.postal_code,
        Phone: activity.caller_number.replace(/^\+1/,''),
        PhoneExtension: "",
        AlternatePhone: "",
        AlternatePhoneExtension: "",
        Fax: "",
        FaxExtension: "",
        EMail: activity.email,
        CalledForUser: "",
        Description: "",
        Status: "New",
        Type: "Customer",
        DateOpened: activity.called_at,
        Source: activity.tracking_label,
        TargetPest: "",
        MailTo: "",
        FoundIn: "Current",
        UserDefinedFields: [{
            Caption: "CallID",
            Value: activity.id
        }]
    };
    //console.log(headers);
    console.log(post_data);
    return context.http_post("https://api.workwave.com/pestpac/v1/Calls", JSON.stringify(post_data), headers);
    /*
    {
    "CallID": 0,
    "Company": "string",
    "ContactName": "string",
    "Address": "string",
    "Address2": "string",
    "City": "string",
    "State": "string",
    "Zip": "string",
    "Country": "string",
    "Phone": "string",
    "PhoneExtension": "string",
    "AlternatePhone": "string",
    "AlternatePhoneExtension": "string",
    "Fax": "string",
    "FaxExtension": "string",
    "EMail": "string",
    "CalledForUser": "string",
    "Description": "string",
    "DateOpened": "2017-12-05T04:16:08.552Z",
    "DateClosed": "2017-12-05T04:16:08.552Z",
    "Status": "New",
    "Type": "Customer",
    "Source": "string",
    "TargetPest": "string",
    "UserDefinedFields": [
      {
        "Caption": "string",
        "Value": "string"
      }
    ],
    "FoundIn": "Current"
  }*/
}
function createNote(pestCall, event, context) {
    /*
    curl -i -X GET --header 'Accept: application/json' \
                 --header 'Authorization: Bearer f057fea0-7e4c-3259-9bbf-5f8bd969eab4' \
                 --header 'Apikey: fXGpNGCKII3m8UhVxkRNt34k7z7P9sGt' \
                 --header 'tenant-id: 923082' 'https://api.workwave.com/pestpac/v1/lookups/Branches'
    */
    let headers = {
        'Authorization': 'Bearer ' + process.env.access_token,
        'Apikey':        process.env.pestpac_api,
        'tenant-id':     process.env.pestpac_tenant,
        'Accept':        'application/json',
        'Content-Type':  'application/json'
    };
    let activity = event.activity;
    let post_data = {
        NoteDate: activity.called_at,
        Note: "https://app.calltrackingmetrics.com/api/v1/accounts/4313/calls/" + activity.sid + "/recording",
        NoteCode: activity.id
    };
    //console.log(headers);
    console.log(post_data);
    return context.http_post("https://api.workwave.com/pestpac/v1/Calls/" + pestCall.CallID + "/notes", JSON.stringify(post_data), headers);

}
function createNotetwo(pestCall, event, context) {
    /*
    curl -i -X GET --header 'Accept: application/json' \
                 --header 'Authorization: Bearer f057fea0-7e4c-3259-9bbf-5f8bd969eab4' \
                 --header 'Apikey: fXGpNGCKII3m8UhVxkRNt34k7z7P9sGt' \
                 --header 'tenant-id: 923082' 'https://api.workwave.com/pestpac/v1/lookups/Branches'
    */
    let headers = {
        'Authorization': 'Bearer ' + process.env.access_token,
        'Apikey':        process.env.pestpac_api,
        'tenant-id':     process.env.pestpac_tenant,
        'Accept':        'application/json',
        'Content-Type':  'application/json'
    };
    let activity = event.activity;
    let post_data = {
        NoteDate: activity.called_at,
        Note: "CallID:" + activity.id + '\n' + '\n' + "Source:" + '\n' + '\n' + activity.tracking_label+ '\n' + '\n' +"Call Direction: " + activity.direction+ '\n' + '\n' +"Date: " + activity.called_at,
        NoteCode: activity.id
    };
    //console.log(headers);
    console.log(post_data);
    return context.http_post("https://api.workwave.com/pestpac/v1/Calls/" + pestCall.CallID + "/notes", JSON.stringify(post_data), headers);

}
function UpdateCall(pestCall, context) {
    context.ctm.update({custom_fields: { value: pestCall.CallID }}).then(function(r) {
        console.log(pestCall.CallID);
        console.log("success");
        context.done();
    }).catch(function() {
        console.log("fail");
        context.done();
    });
}

exports.handler = function(event, context, callback) {
    // first time the function runs we'll have no expires_time set
    //console.log(process.env.expires_time);
    //console.log(event.activity);
    if(typeof event.activity.custom_fields === 'undefined') {
        event.activity.custom_fields ={
            strings: ''
        }
    }
    console.log("create pestpac call: ", event.activity.custom_fields.strings);

    //context.done();
    //return;
    if (!process.env.expires_time) { // get a new access token, and refresh token
        getAccessToken(context).then(function(response) {
            var token = JSON.parse(response.responseBody);
            var ts = new Date().getTime();
            var offset = (parseInt(token.expires_in) * 1000);
            var expiresTime = new Date(ts + offset);
            context.ctm.setKeys([{key: 'access_token', val: token.access_token},
                {key: 'refresh_token', val: token.refresh_token}]).then(function() {
                console.log(token);
                console.log(new Date(ts));
                console.log(expiresTime);
                process.env.access_token = token.access_token;
                createPestPacCall(event, context).then(function(res) {
                    console.log(res.response.statusCode, res.responseBody);
                    var pestCall = JSON.parse(res.responseBody);
                    createNote(pestCall, event, context);
                    createNotetwo(pestCall, event, context);
                    UpdateCall(pestCall, context);
                });
            });
        });
    } else if (process.env.expires_time < (new Date().getTime())) {
        console.log("renew token using refresh token");
        renewAcessToken(context).then(function(response) {
            var token = JSON.parse(response.responseBody);
            var ts = new Date().getTime();
            var offset = (parseInt(token.expires_in) * 1000);
            var expiresTime = new Date(ts + offset);
            context.ctm.setKeys([{key: 'access_token', val: token.access_token},
                {key: 'refresh_token', val: token.refresh_token},
                {key: 'expires_time', val: expiresTime.getTime()}]).then(function() {
                console.log(token);
                console.log(new Date(ts));
                console.log(expiresTime);
                process.env.access_token = token.access_token;
                createPestPacCall(event, context).then(function(res) {
                    console.log(res.response.statusCode, res.responseBody);
                    var pestCall = JSON.parse(res.responseBody);
                    createNote(pestCall, event, context);
                    createNotetwo(pestCall, event, context);
                    UpdateCall(pestCall, context);
                });
            });
        });
    } else {
        callback({'hello':'world'});
        console.log("skip");
        createPestPacCall(event, context).then(function(res) {
            console.log(res.response.statusCode, res.responseBody);
            var pestCall = JSON.parse(res.responseBody);
            createNote(pestCall, event, context);
            createNotetwo(pestCall, event, context);
            UpdateCall(pestCall, context);
        });
    }
};