#!/bin/bash
git checkout main
git pull github main
git push origin main
ssh -t demo@s6.thecloudalert.com "./sbpkr.sh"
date
