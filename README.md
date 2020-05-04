# interns2020

### To where should you clone this repo?

* Use Git Bash to clone the repo to `/c/dev/code/interns2020`; you can do that with the command `git clone git.learninga-z.com:laz/interns2020 /c/dev/code/interns2020`

### Steps to install Apache as a service to run this repo:

* open a Git Bash shell window as administrator
  * execute the command: `/c/dev/tools/apache/bin/httpd.exe -f /c/dev/code/interns2020/conf/httpd.conf -k install -n "Apache2.4 (interns2020)"`; you can copy+paste to do it, just don't copy the back-ticks surrounding it if you're looking at the source view of the Markdown file
  * keep the window open for later use setting up MySQL
* open up the Services application (get to it from your Start Menu)
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

### Steps to install MySQL as a service:

* Stop the service mysqld_master and set its Startup Type to "manual", as you did with Apache and NGINX above
* Using the administrator shell you opened above, execute the command `/c/dev/tools/mysql/bin/mysqld.exe --install "MySQL (interns2020)" --defaults-file="C:/dev/code/interns2020/conf/my.ini"`; again, don't copy the back-ticks
  * You can now close this window
* Use the Action menu in the Services application to Refresh the list
* Open a new _non_-administrator shell window
  * execute the command `/c/dev/code/interns2020/conf/setup-mysql.bash`
* Start the MySQL (interns2020) service, just above the mysqld_master service you previously stopped
* Set a password for the root user by executing `winpty mysqladmin -u root -h 127.0.0.1 --skip-password password` and then following the prompts to type a new password twice.  Note that in the command, the word `password` is actually the word `password`; not a variable for you to replace.

* You can then access MySQL with the client by saying `winpty mysql -u root -h 127.0.0.1 -p`
