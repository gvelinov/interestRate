FROM centos:latest

RUN yum -y install httpd \
 && yum clean all; systemctl enable httpd.service \
 && yum install epel-release yum-utils -y \
 && yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm \
 && yum-config-manager --enable remi-php72 \
 && yum -y install zip unzip php72 php72-php php72-php-pdo php72-php-gd php72-php-json php72-php-mbstring php72-php-mysqlnd php72-php-xml php72-php-phalcon3 php72-php-pecl-zip php72-php-soap php72-php-pecl-xdebug git

COPY config/httpd/app.conf /etc/httpd/conf.d/
COPY src/* /var/www/html/
COPY composer.json /var/www/

WORKDIR /var/www/
RUN php72 -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php72 -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php72 composer-setup.php \
    && php72 -r "unlink('composer-setup.php');" \
    && ln -sf /opt/remi/php72/root/bin/php /usr/bin/php \
    && php72 composer.phar install

EXPOSE 80

CMD ["/usr/sbin/init"]