#!/usr/bin/env bash
url="https://raw.githubusercontent.com/wmcmillian-coalmarch/wills-repo/master/scripts/dlogin.js";
echo "javascript:(function(){s=document.createElement('script');s.type='text/javascript';s.src='${url}?v='+parseInt(Math.random()*99999999);document.body.appendChild(s);})();"