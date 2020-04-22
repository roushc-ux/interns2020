# interns2020

Steps to install Apache as a service to run this repo:

* open a Git Bash shell window as administrator
* execute the command: `/c/dev/tools/apache/bin/httpd.exe -f /c/dev/code/interns2020/conf/httpd.conf -k install -n "Apache2.4 (interns2020)"`
* open up the Services window
* select NGINX in the services list
  * Stop the service
  * right click and select "properties"
  * set the Startup Type to "manual"
  * click OK
* select Apache2.4 with no qualifiers in the services list
  * Stop the service
  * Set the Startup Type to "manual" as you did NGINX
* select Apache2.4 (interns2020), just below the previous entry
  * Set the Startup Type to "automatic"
  * Start the service
