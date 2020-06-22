#!/usr/bin/env bash

sites="antipesto
hawxpestcontrol
trugreenmidsouth
lawnconnection
ecolawn
mesaturfmasters
triuspest
naturalstate
btpestcontrol
sextonlandscapes
lawntech2017
aggielandgreen
davenportenvironmental
gotbugs
mothernaturesinc
lando-sprowt-test
regionalpestservices
raleighpestcompany
proficienthealth
morseclinics
kbjacks
facacademy
soundpest
suburbanpest
villagedeli
nanas-of-durham
mjrosehomes
cip
briancenterofdurham
apexpestcompany
britegums
quickbooks
dashboards-sprowt
patriotpestmanagement
html_title
lawndoctordenver
valleyradiologync
seagullseafarer
zashleytestsite1
ncpestmanagement
dukehealth-brandbar"

for site in $sites; do
    rm -rf ~/Sites/$site;
done