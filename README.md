# PHPQuick

PHP scripts that do things usefull quickly (read messy). Most of these assume you're using PHP+MySQL. 

## PHPQuickAnalytics

Quickly add some private analytics to your project. 

## PHPQuickMailer

A script to pass json to a remote mailer system (PHPQuickMailer-Server)

This is usefull if you have a number of devices/servers that are hidden away and they need to email updates from time to time. 

The PHPQuickMailer-Client file shows what the sending device does, basically builds an array then turns this to json before passing it to the remote mailer script. The mailer script parses this and sends the email. 

## PHPQuickTableView

This script quickly shows you a list of tables in your mysql database on the connection you pass it, which then links to a view of the data in each table using the link prepend also passed. 



## OLD - PHPQuickTable
PHP function that quickly generates a table using a simpler interface to SQL. a good understanding is still needed but this can make rapid prototyping quicker. 
