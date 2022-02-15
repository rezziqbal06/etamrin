echo "Pull Request (PR) Helper"
set prbranch=main
set /p prbranch=Please input PR branch (default - %prbranch%)?:
git fetch origin %prbranch:%prbranch
git checkout %prbranch
mysql -u root s3demo_sbpkr < sql/s3demo_sbpkr.sql
echo
echo "Thankyou, open the app through your browser http://localhost/sbp/karir/"
