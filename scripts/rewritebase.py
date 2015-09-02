#!/usr/bin/env python

import sys, re
from os.path import expanduser

args = sys.argv
home = expanduser("~")
sites = home + "/Sites/"
site = args[1];

if site == None or site == 'help':
    print '''Please use a valid site name'''
    sys.exit(2)
else:
    htaccess = open(sites + site + "/.htaccess", 'r+')
    txt = htaccess.read()
    htaccess.close()
    
    pattern = '[\s]\#[\s]RewriteBase /\n'
    
    if re.search(pattern, txt):
        newtxt = re.sub(pattern, " RewriteBase /\n", txt)
        htaccess = open(sites + site + "/.htaccess", 'w')
        htaccess.write(newtxt)
        htaccess.close()
        print '''rewrite base done'''