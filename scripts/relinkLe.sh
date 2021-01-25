#!/bin/bash
website="$1"

if [ -z $website ] || [ ! -d  /etc/letsencrypt/archive/$website ]; then
      echo "$website doesn't exist";
      exit 1;
fi

lecert=$(find /etc/letsencrypt/archive/$website/cert* -type f -printf '%p\n' | sort -h | tail -n1)

lechain=$(find /etc/letsencrypt/archive/$website/chain* -type f -printf '%p\n' | sort -h | tail -n1)

lefullchain=$(find /etc/letsencrypt/archive/$website/fullchain* -type f -printf '%p\n' | sort -h | tail -n1)

leprivkey=$(find /etc/letsencrypt/archive/$website/privkey* -type f -printf '%p\n' | sort -h | tail -n1)


if [ ! -f $lecert ]; then
        echo "Cannot find $lecert";
        exit 1;
fi


rm -f /etc/letsencrypt/live/$website/*
ln -s $lecert /etc/letsencrypt/live/$website/cert.pem
ln -s $lechain /etc/letsencrypt/live/$website/chain.pem
ln -s $lefullchain /etc/letsencrypt/live/$website/fullchain.pem
ln -s $leprivkey /etc/letsencrypt/live/$website/privkey.pem

echo ""
echo "###########################################################################################################################"
echo "linked $lecert => /etc/letsencrypt/live/$website/cert.pem"
echo "linked $lechain => /etc/letsencrypt/live/$website/chain.pem"
echo "linked $lefullchain => /etc/letsencrypt/live/$website/fullchain.pem"
echo "linked $leprivkey => /etc/letsencrypt/live/$website/privkey.pem"
echo "###########################################################################################################################"
echo ""
