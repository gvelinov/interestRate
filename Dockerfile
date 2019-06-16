FROM centos:latest

RUN yum -y install httpd \
 && yum clean all; systemctl enable httpd.service \
 && yum install epel-release yum-utils -y \
 && yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm \
 && yum-config-manager --enable remi-php72 \
 && yum -y install php72 php72-php php72-php-pdo php72-php-gd php72-php-json php72-php-mbstring php72-php-mysqlnd php72-php-xml php72-php-phalcon3

COPY config/httpd/app.conf /etc/httpd/conf.d/
COPY src/* /var/www/html/

EXPOSE 80

CMD ["/usr/sbin/init"]