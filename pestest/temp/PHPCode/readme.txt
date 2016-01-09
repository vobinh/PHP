PHP Samples

Note: you will need curl installed on your server to run this sample code. Curl is a free download available from http://curl.haxx.se.

Unzip files and insert contents into web directory.

Zip file contains four files

cc_purchase.htm      Html form for Credit Card purchase
check_purchase.htm   Html form for Check purchase

results_approved.php PHP approval page
results_error.php     PHP error page

php_curl.php with the help of curl sends the transaction to the paymentsgateway.net. Replace pg_merchant_id=xxxx and pg_password=xxxxx with the information provided by ACHDirect.


This code sample is provided as an example on how to use Curl and PHP together to post securely to PaymentsGateway.net.
(Note this example is provided for developers wanting only a string returned, not a url.)

Helpful Links

PHP Website
http://www.php.net

Reference to Curl on PHP's Website
http://www.php.net/manual/en/ref.curl.php

Curl Website
http://curl.haxx.se
