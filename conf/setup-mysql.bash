# This script must be run from a bash shell started in Administrator mode

mkdir -p /c/dev/interns2020_database/data
mkdir -p /c/dev/interns2020_database/tmp
mkdir -p /c/dev/interns2020_database/log

/c/dev/tools/mysql/bin/mysqld.exe --defaults-file=/c/dev/code/interns2020/conf/my.ini --datadir=/c/dev/interns2020_database/data --initialize-insecure --console
/c/dev/tools/mysql/bin/mysqld.exe --install "MySQL (interns2020)" --defaults-file="C:/dev/code/interns2020/conf/my.ini"
