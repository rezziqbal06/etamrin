#!/bin/bash
echo "Pull Request (PR) Helper"
read -p 'Please input your branch PR: ' prbranch
read -p 'Please input your remote PR (default: origin): ' prremote
git branch -D $prbranch
git fetch $prremote $prbranch:$prbranch
git checkout $prbranch
mysql -u root s3demo_sbpkr < sql/s3demo_sbpkr.sql
echo
echo Thankyou. Please wait while we open the app
open -a /Applications/Google\ Chrome.app http://localhost/sbp/karir/
