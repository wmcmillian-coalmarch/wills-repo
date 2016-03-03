#!/bin/env python2

import os
import subprocess as c
import sys
import json
from git import Repo

def main():
    tlogin();
    
    tsites = json.loads(command("terminus sites list --format"));
    
    sites = {};
    
    for i,site in tsites.iteritems():
        sites[site.name] = tsites[i];
    
    for i,site in sites.iteritems():
        print i + "\n";
    
    
    
    
    
    
    print '''All good!'''
    
def command(command):
    try:
        return c.check_output(command, stderr=c.STDOUT, shell=True);
    except c.CalledProcessError as e:
        return e.output

def tlogin():
    tlogin =  command('which tlogin');
    if "no tlogin in" in tlogin:
        print '''tlogin function not found.
exiting...'''
        sys.exit(2);
    
    command('tlogin');
    



if __name__ == '__main__':
    main()