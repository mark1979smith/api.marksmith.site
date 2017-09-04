FROM php:apache

ENV DEV_MODE false

# Set the working directory to /app
WORKDIR /var/www

COPY . /var/www

# SOFTWARE REQS
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install git -y

# PHP EXTENSIONS
RUN pecl install redis-3.1.3 && \
    docker-php-ext-enable redis

# APACHE MODULES
RUN a2enmod rewrite

# REMOVE default directory
RUN rm -rf /var/www/html && \
    ln -s /var/www/web /var/www/html

# Create Deployment User and group
# Change Apache User from www-data to deployuser
RUN groupadd deploygroup && \
    adduser --disabled-password --gecos ""  --ingroup deploygroup deployuser && \
    chgrp deploygroup /var/www -R && \
    chown deployuser /var/www -R && \
    sed -i 's/${APACHE_RUN_USER:=www-data}/${APACHE_RUN_USER:=deployuser}/g' /etc/apache2/envvars && \
    sed -i 's/${APACHE_RUN_GROUP:=www-data}/${APACHE_RUN_GROUP:=deploygroup}/g' /etc/apache2/envvars

# VHOSTS SETUP - to set AllowOverride
#RUN echo "IDxWaXJ0dWFsSG9zdCAqOjgwPg0KDQogICAgICAgICMgVGhlIFNlcnZlck5hbWUgZGlyZWN0aXZlIHNldHMgdGhlIHJlcXVlc3Qgc2NoZW1lLCBob3N0bmFtZSBhbmQgcG9ydCB0aGF0DQogICAgICAgICMgdGhlIHNlcnZlciB1c2VzIHRvIGlkZW50aWZ5IGl0c2VsZi4gVGhpcyBpcyB1c2VkIHdoZW4gY3JlYXRpbmcNCiAgICAgICAgIyByZWRpcmVjdGlvbiBVUkxzLiBJbiB0aGUgY29udGV4dCBvZiB2aXJ0dWFsIGhvc3RzLCB0aGUgU2VydmVyTmFtZQ0KICAgICAgICAjIHNwZWNpZmllcyB3aGF0IGhvc3RuYW1lIG11c3QgYXBwZWFyIGluIHRoZSByZXF1ZXN0J3MgSG9zdDogaGVhZGVyIHRvDQogICAgICAgICMgbWF0Y2ggdGhpcyB2aXJ0dWFsIGhvc3QuIEZvciB0aGUgZGVmYXVsdCB2aXJ0dWFsIGhvc3QgKHRoaXMgZmlsZSkgdGhpcw0KICAgICAgICAjIHZhbHVlIGlzIG5vdCBkZWNpc2l2ZSBhcyBpdCBpcyB1c2VkIGFzIGEgbGFzdCByZXNvcnQgaG9zdCByZWdhcmRsZXNzLg0KICAgICAgICAjIEhvd2V2ZXIsIHlvdSBtdXN0IHNldCBpdCBmb3IgYW55IGZ1cnRoZXIgdmlydHVhbCBob3N0IGV4cGxpY2l0bHkuDQoNCiAgICAgICAgU2VydmVyTmFtZSBtZS5tYXJrc21pdGguc2l0ZQ0KDQoNClNlcnZlckFkbWluIHdlYm1hc3RlckBsb2NhbGhvc3QNCiBEb2N1bWVudFJvb3QgL3Zhci93d3cvaHRtbA0KDQogRXJyb3JMb2cgJHtBUEFDSEVfTE9HX0RJUn0vZXJyb3IubG9nDQogQ3VzdG9tTG9nICR7QVBBQ0hFX0xPR19ESVJ9L2FjY2Vzcy5sb2cgY29tYmluZWQNCjxEaXJlY3RvcnkgL3Zhci93d3cvaHRtbD4NCiBPcHRpb25zIEluZGV4ZXMgRm9sbG93U3ltTGlua3MNCiBBbGxvd092ZXJyaWRlIEFsbA0KIFJlcXVpcmUgYWxsIGdyYW50ZWQNCiA8L0RpcmVjdG9yeT4NCjwvVmlydHVhbEhvc3Q+DQo="  | base64 --decode > /usr/local/zend/etc/sites.d/vhost_me.marksmith.site.conf

# Change owner to avoid running as root
USER deployuser

# RUN COMPOSER to generate parameters.yml file
RUN /usr/local/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    /usr/local/bin/php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    /usr/local/bin/php composer-setup.php && \
    /usr/local/bin/php -r "unlink('composer-setup.php');" && \
    /usr/local/bin/php composer.phar install -n -q

# SET UP DEPLOYMENT KEY TO ALLOW GIT PULL
RUN  mkdir -p ~/.ssh && echo "c3NoLXJzYSBBQUFBQjNOemFDMXljMkVBQUFBREFRQUJBQUFDQVFEa0g5VUh0ZDVxNFZ3dm5NL3J5eTJmYVloSDRWMWluZjRRTXRqT204cDlkdThxV05CTnhRdlBpYWtidkliN0EvcythWllQb1VJS2M5d1NMd0xwZFFQbTlXd1FVZEtHSkNxNnZCUGZtMHBmYVdqL2VwME53ZVVrN2JDQmE2ZUVPZVovYzlTNitoaHd5cndLTS85bE9jc0xjeWc0NVZhSFdaa1JndkJtQzkwbnArK1FvbjJVdHd3VFNlNWhoZFFiUDBwTWdVZnlxaEJMRmcrcW1CVURHYldaUFJVdTRCSFRaZHRnRUd0eHVrcnVSYy9BUFE3VSs2ZENUM2hIL1V1c000a2tmMGJ2djJ5eERIbDFuZ2NnSk5RalhJbnFEOEtwTnNjUk92QXV0b2YrdzBQVFJtR21XNkEvQTRKbDVrMzlQcDdHcU1GMmxLRVJINzFvSnpzcnVoeU1oWlpVTTBoK1BTTEIzNWNkVSt3dmRYMlUzUllRR1pydW0vTmFtQmFBK213SFFzd1habzMxU1BQOVdrUWw2RVFYeW02elRtbmkrakhxbnVJekFrcTRIalh4aWdUVzBXMzRqbEhMVlRaOS9PRENZUjBUczl5empuaUl5NGhzTUpJcGhuYndQNTEyK2lIcHpJSVBYTjV1MkltbGZ3S0Y1MS9FallwdlRxTnVjdWlhc3dNdzNGRXZxNmJHWktUU1M4YkU4cy9xMlZJQk9ieUxHUXh0QzhUSEhoV0hyanRDZGYwbVo2Rkx6MXdJWFJKdEQ2cGhFQ1h0S1VzMmp5a1VTT1BrRUVwWFIveDkvTi9BRFlLWTM1azJ2TkRybFJDbisxMys2Witzb2owRDI3L0xlY3BvVGEvVklMc3UyTzdxOE10RTRMcFZkemN3SXBCTUY1RjdjOEtvQlE9PSBtYXJrMTk3OXNtaXRoQGdvb2dsZS5jb20NCg==" | base64 --decode > ~/.ssh/id_rsa.pub

# Switch back to ROOT
USER root
