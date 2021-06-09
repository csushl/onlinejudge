# Online Judge使用说明

系统环境: **Ubuntu Linux 18.04**

## 部署说明

安装好ubuntu 18.04之后，执行下面脚本即可。

```c
#!/bin/bash
sed -i 's/tencentyun/aliyun/g' /etc/apt/sources.list

apt-get update
apt-get install -y subversion
/usr/sbin/useradd -m -u 1536 judge
cd /home/judge/


git clone https://github.com/csushl/onlinejudge.git
mv /home/judge/onlinejudge/src /home/judge/src
#svn up src

for pkg in "net-tools make flex g++ clang libmysqlclient-dev libmysql++-dev php-fpm nginx mysql-server php-mysql  php-common php-gd php-zip fp-compiler openjdk-11-jdk mono-devel php-mbstring php-xml php-curl php-intl php-xmlrpc php-soap"
do
    while ! apt-get install -y $pkg 
    do
        echo "Network fail, retry... you might want to change another apt source for install"
    done
done

USER=`cat /etc/mysql/debian.cnf |grep user|head -1|awk  '{print $3}'`
PASSWORD=`cat /etc/mysql/debian.cnf |grep password|head -1|awk  '{print $3}'`
CPU=`grep "cpu cores" /proc/cpuinfo |head -1|awk '{print $4}'`

mkdir etc data log backup

cp src/install/java0.policy  /home/judge/etc
cp src/install/judge.conf  /home/judge/etc
chmod +x src/install/ans2out

if grep "OJ_SHM_RUN=0" etc/judge.conf ; then
    mkdir run0 run1 run2 run3
    chown judge run0 run1 run2 run3
fi

sed -i "s/OJ_USER_NAME=root/OJ_USER_NAME=$USER/g" etc/judge.conf
sed -i "s/OJ_PASSWORD=root/OJ_PASSWORD=$PASSWORD/g" etc/judge.conf
sed -i "s/OJ_COMPILE_CHROOT=1/OJ_COMPILE_CHROOT=0/g" etc/judge.conf
sed -i "s/OJ_RUNNING=1/OJ_RUNNING=$CPU/g" etc/judge.conf

chmod 700 backup
chmod 700 etc/judge.conf

sed -i "s/DB_USER[[:space:]]*=[[:space:]]*\"root\"/DB_USER=\"$USER\"/g" src/web/include/db_info.inc.php
sed -i "s/DB_PASS[[:space:]]*=[[:space:]]*\"root\"/DB_PASS=\"$PASSWORD\"/g" src/web/include/db_info.inc.php

chmod 700 src/web/include/db_info.inc.php
chown -R www-data src/web/

chown -R root:root src/web/.svn
chmod 750 -R src/web/.svn

chown www-data src/web/upload data
if grep "client_max_body_size" /etc/nginx/nginx.conf ; then 
    echo "client_max_body_size already added" ;
else
    sed -i "s:include /etc/nginx/mime.types;:client_max_body_size    80m;\n\tinclude /etc/nginx/mime.types;:g" /etc/nginx/nginx.conf
fi

mysql -h localhost -u$USER -p$PASSWORD < src/install/db.sql
echo "insert into jol.privilege values('admin','administrator','true','N');"|mysql -h localhost -u$USER -p$PASSWORD 

if grep "added by hustoj" /etc/nginx/sites-enabled/default ; then
    echo "default site modified!"
else
    echo "modify the default site"
    sed -i "s#root /var/www/html;#root /home/judge/src/web;#g" /etc/nginx/sites-enabled/default
    sed -i "s:index index.html:index index.php:g" /etc/nginx/sites-enabled/default
    sed -i "s:#location ~ \\\.php\\$:location ~ \\\.php\\$:g" /etc/nginx/sites-enabled/default
    sed -i "s:#\tinclude snippets:\tinclude snippets:g" /etc/nginx/sites-enabled/default
    sed -i "s|#\tfastcgi_pass unix|\tfastcgi_pass unix|g" /etc/nginx/sites-enabled/default
    sed -i "s:}#added by hustoj::g" /etc/nginx/sites-enabled/default
    sed -i "s:php7.0:php7.2:g" /etc/nginx/sites-enabled/default
    sed -i "s|# deny access to .htaccess files|}#added by hustoj\n\n\n\t# deny access to .htaccess files|g" /etc/nginx/sites-enabled/default
fi
/etc/init.d/nginx restart
sed -i "s/post_max_size = 8M/post_max_size = 80M/g" /etc/php/7.2/fpm/php.ini
sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 80M/g" /etc/php/7.2/fpm/php.ini
sed -i 's/;request_terminate_timeout = 0/request_terminate_timeout = 128/g' `find /etc/php -name www.conf`
sed -i 's/pm.max_children = 5/pm.max_children = 200/g' `find /etc/php -name www.conf`
 
COMPENSATION=`grep 'mips' /proc/cpuinfo|head -1|awk -F: '{printf("%.2f",$2/5000)}'`
sed -i "s/OJ_CPU_COMPENSATION=1.0/OJ_CPU_COMPENSATION=$COMPENSATION/g" etc/judge.conf

/etc/init.d/php7.2-fpm restart
service php7.2-fpm restart

cd src/core
chmod +x ./make.sh
./make.sh
if grep "/usr/bin/judged" /etc/rc.local ; then
    echo "auto start judged added!"
else
    sed -i "s/exit 0//g" /etc/rc.local
    echo "/usr/bin/judged" >> /etc/rc.local
    echo "exit 0" >> /etc/rc.local
fi
if grep "bak.sh" /var/spool/cron/crontabs/root ; then
    echo "auto backup added!"
else
    crontab -l > conf && echo "1 0 * * * /home/judge/src/install/bak.sh" >> conf && crontab conf && rm -f conf
fi
ln -s /usr/bin/mcs /usr/bin/gmcs

/usr/bin/judged
cp /home/judge/src/install/hustoj /etc/init.d/hustoj
update-rc.d hustoj defaults
systemctl enable hustoj
systemctl enable nginx
systemctl enable mysql
systemctl enable php7.2-fpm

mkdir /var/log/hustoj/
chown www-data -R /var/log/hustoj/

cls
reset

echo "Remember your database account for HUST Online Judge:"
echo "username:$USER"
echo "password:$PASSWORD"
```

## 系统配置

配置文件：/home/judge/etc/judge.conf

| 配置                              | 注释                                                         |
| --------------------------------- | ------------------------------------------------------------ |
| OJ_HOST_NAME=127.0.0.1            | 如果用 mysql 连接读取数据库，数据库的主机地址。              |
| OJ_USER_NAME=root                 | 数据库帐号。                                                 |
| OJ_PASSWORD=root                  | 数据库密码。                                                 |
| OJ_DB_NAME=jol                    | 数据库名称。                                                 |
| OJ_PORT_NUMBER=3306               | 数据库端口。                                                 |
| OJ_RUNNING=4                      | judged 会启动 judge_client 判题，这里规定最多同时运行几个 judge_client。 |
| OJ_SLEEP_TIME=5                   | judged通过轮询数据库发现新任务，轮询间隔的休息时间，单位为秒。 |
| OJ_TOTAL=1                        | 老式并发处理中总的 judged数量。                              |
| OJ_MOD=0                          | 老式并发处理中，本 judged负责处理 solution_id 按照 TOTAL 取模后余数为几的任务。 |
| OJ_JAVA_TIME_BONUS=2              | Java 等虚拟机语言获得的额外运行时间。                        |
| OJ_JAVA_MEMORY_BONUS=512          | Java 等虚拟机语言获得的额外内存。                            |
| OJ_SIM_ENABLE=0                   | 是否使用sim 进行代码相似度的检测。                           |
| OJ_HTTP_JUDGE=0                   | 是否使用 HTTP 方式连接数据库，如果启用，则前面的 HOST_NAME 等设置忽略。 |
| OJ_HTTP_BASEURL=http://127.0.0.1/ | 使用 HTTP 方式连接数据库的基础地址，就是 O 的首页地址。      |
| OJ_HTTP_USERNAME=admin            | 使用 HTTP 方式所用的用户帐号（HTTP_JUDGE 权限），该帐号登录时不能启用 VCODE 图形验证码，但可以登录成功后启用。 |
| OJ_HTTP_PASSWORD=admin            | 使用 HTTP 方式所用的用户帐号的密码                           |
| OJ_OI_MODE=0                      | 是否启用 OI（信息学奥林匹克竞赛） 模式，即无论是否出错都继续判剩余的数据，在 ACM 比赛中一旦出错就停止运行。 |
| OJ_SHM_RUN=0                      | 是否使用 /dev/shm 的共享内存虚拟磁盘来运行答案，如果启用能提高判题速度，但需要较多内存。 |
| OJ_USE_MAX_TIME=1                 | 是否使用所有测试数据中最大的运行时间作为最后运行时间，如果不启用则以所有测试数据的总时间作为超时判断依据。 |
| OJ_LANG_SET=0,1,2,3,4             | 判哪些语言的题目                                             |
| OJ_COMPILE_CHROOT=1               | 是否使用chroot构建编译环境，避免编译器攻击 (#include</dev/random>之类) |
| OJ_TURBO_MODE=0                   | 是否跳过中间状态的更新来加速判题（对增加判题机数量有帮助）   |
| OJ_CPU_COMPENSATION=1.0           | CPU处理能力系数，如果CPU太快，应该TLE的程序能AC，则增大这个值；如果CPU太慢对应该AC的程序报TLE减小这个值。一般设定MIPS指数4000的CPU设为1.0。 |
| OJ_UDP_ENABLE=1                   | 是否监听UDP端口已收取Web端发送的新提交通知，减少平均等待周期。 |
| OJ_UDP_SERVER=127.0.0.1           | 监听的IP地址                                                 |
| OJ_UDP_PORT=1536                  | 监听的端口                                                   |
| OJ_PYTHON_FREE=0                  | 是否允许Python程序调用非核心的三方库                         |
| OJ_COPY_DATA=0                    | 是否将测试数据复制到runX工作目录中供被测程序使用             |



配置文件：/home/judge/src/web/include/db_info.inc.php。

| 配置                                 | 注释                                                         |
| :----------------------------------- | ------------------------------------------------------------ |
| static $DB_HOST="localhost";         | 数据库的服务器地址。                                         |
| static $DB_NAME="jol";               | 数据库名。                                                   |
| static $DB_USER="root";              | 数据库用户名。                                               |
| static $DB_PASS="root";              | 数据库密码。                                                 |
| static $OJ_NAME="Online Judge";      | OJ 的名字，将取代页面标题等位置 Online Judge 字样。          |
| static $OJ_HOME="./";                | OJ 的首页地址。                                              |
| static $OJ_ADMIN="root@localhost";   | 管理员email。                                                |
| `static $OJ_DATA="/home/judge/data"; | 测试数据所在目录，实际位置。                                 |
| static $OJ_BBS="discuss";            | 论坛的形式，discuss3为自带的简单论坛,bbs 为外挂论坛，参考 bbs.php代码。 |
| static $OJ_ONLINE=false;             | 是否使用在线监控，需要消耗一定的内存和计算，因此如果并发大建议关闭。 |
| static $OJ_LANG="en";                | 默认的语言，中文为 cn 。                                     |
| static $OJ_SIM=true;                 | 是否显示相似度检测的结果。                                   |
| static $OJ_DICT=true;                | 是否启用在线英字典。                                         |
| static $OJ_LANGMASK=1008;            | `1mC` `2mCPP` `4mPascal` `8mJava` `16mRuby` `32mBash` 用掩码表示的OJ 接受的提交语言，可以被比赛设定覆盖。1008 为只使用 `C` `CPP` `Pascal` `Java`。 |
| static $OJ_EDITE_AREA=true;          | 是否启用高亮语法显示的提交界面，可以在线编程，无须IDE。      |
| static $OJ_AUTO_SHARE=false;         | true: 自动分享代码，启用的话，做出一道题就可以在该题的Status 中看其他人的答案。 |
| static $OJ_CSS="hoj.css";            | 默认的css,可以选择 dark.css 和 gcode.css , 具有有限的界面制定效果。 |
| static $OJ_SAE=false;                | 是否是在新浪的云平台运行web 部分                             |
| static $OJ_VCODE=true;               | 是否启用图形登录、注册验证码。                               |
| static $OJ_APPENDCODE=false;         | 是否启用自动添加代码，启用的话，提交时会参考$OJ_DATA 对应目录里是否有 append.c 一类的文件，有的话会把其中代码附加到对应语言的答案之后，巧妙使用可以指定 main 函数而要求学生编写main 部分调用的函数。 |
| static $OJ_MEMCACHE=false;           | 是否使用 memcache 作为页面缓存，如果不启用则用 /cache 目录   |
| static $OJ_MEMSERVER="127.0.0.1";    | memcached 的服务器地址                                       |
| static $OJ_MEMPORT=11211;            | memcached 的端口                                             |
| static $OJ_RANK_LOCK_PERCENT=0;      | 比赛封榜时间的比率，如 5 小时比赛设为 0.2 则最后 1 小时封榜。 |
| static $OJ_SHOW_DIFF=false;          | 显示 WrongAnswer 时的对比                                    |