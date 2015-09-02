#!/usr/bin/env python

import sys,subprocess;
from os.path import expanduser;

args = sys.argv
home = expanduser("~")
sites = home + "/Sites/"
try:
    site = args[1];
except:
    args[1] = 'help';

try: 
    env = args[2];
except:
    env = 'dev';

if site == None or site == 'help':
    print '''Please use a valid site name'''
    sys.exit(2)
else:
    output = subprocess.check_output(['drush','@pantheon.'+site+'.'+env,'sql-connect']);
    
    listvar = output.split("mysql");
    creds = listvar.pop().rstrip().replace("--database=","");
    print creds;
