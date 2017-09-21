FROM php:apache

ENV DEV_MODE false

# Set the working directory to /app
WORKDIR /var/www

# SOFTWARE REQS
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install git -y

# PHP EXTENSIONS
RUN pecl install redis-3.1.3 && \
    docker-php-ext-enable redis && \
    docker-php-ext-install pdo pdo_mysql

# APACHE MODULES
RUN a2enmod rewrite

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

# REMOVE default directory
RUN rm -rf /var/www/html && \
    ln -s /var/www/web /var/www/html

# RUN COMPOSER to generate parameters.yml file
RUN /usr/local/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN /usr/local/bin/php -r "copy('https://composer.github.io/installer.sig', 'composer-installer.sig');"
RUN /usr/local/bin/php -r "if (hash_file('SHA384', 'composer-setup.php') === trim(file_get_contents('composer-installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN /usr/local/bin/php composer-setup.php
RUN /usr/local/bin/php -r "unlink('composer-setup.php');"
RUN /usr/local/bin/php -r "unlink('composer-installer.sig');"
RUN /usr/local/bin/php composer.phar install -n

# SET UP DEPLOYMENT KEY TO ALLOW GIT PULL
RUN  mkdir -p ~/.ssh && \
    echo "c3NoLXJzYSBBQUFBQjNOemFDMXljMkVBQUFBREFRQUJBQUFCQVFESXFQWWYxSlhxNlVuSGNlTmpER3F6VTVDUlZxN2plWmZpdUFRU3JqRTd4Zk9TZkNUdzgzTGM5Zk1ITXBQQjFQR3g5K1NKc3NYdkNPbmI5ZzM0UWY2UXc2S1l1KzllMmx2a3FDbVhxNzZuZFQzcnhIN1FuT2ppdUs3S3pMem1pWkpHQ016WXF0MmYyVGRUM3Y5aGNYMnBQa0p5QllkY2ZxdUNId0wrbHFrZWh2d1BXK2VNbFlWQWJIN3RGOXRGSkVOVFJpRk0vL2NDRHZUVmtnOGgwa1NSYlFtZk12ZlI1QjNGdGQ5eFBiZ05BcUNiaTV6TENiS2FQRWtPaUxFaHEvVXQ5QlR2eDVmSHVPMGxQeFptZ01LSGYzVUhUTmNOUEFId1BUWlhDUUxyRDQ2WTBNTzk0NTg3N3lBdWY4OXBsYTJvN3M0bXlHWGgrTXRLN0VBQVRXVkIgZGVwbG95dXNlckBhcGkubWFya3NtaXRoLnNpdGUNCg==" | base64 --decode > ~/.ssh/id_rsa.pub && \
    echo "LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQ0KTUlJRW93SUJBQUtDQVFFQXlLajJIOVNWNnVsSngzSGpZd3hxczFPUWtWYXU0M21YNHJnRUVxNHhPOFh6a253aw0KOFBOeTNQWHpCektUd2RUeHNmZmtpYkxGN3dqcDIvWU4rRUgra01PaW1MdnZYdHBiNUtncGw2dStwM1U5NjhSKw0KMEp6bzRyaXV5c3k4NW9tU1Jnak0yS3JkbjlrM1U5Ny9ZWEY5cVQ1Q2NnV0hYSDZyZ2g4Qy9wYXBIb2I4RDF2bg0KakpXRlFHeCs3UmZiUlNSRFUwWWhUUC8zQWc3MDFaSVBJZEpFa1cwSm56TDMwZVFkeGJYZmNUMjREUUtnbTR1Yw0KeXdteW1qeEpEb2l4SWF2MUxmUVU3OGVYeDdqdEpUOFdab0RDaDM5MUIwelhEVHdCOEQwMlZ3a0M2dytPbU5ERA0KdmVPZk8rOGdMbi9QYVpXdHFPN09Kc2hsNGZqTFN1eEFBRTFsUVFJREFRQUJBb0lCQUNnRWVrRlMxaXNwSjB1ZA0KVE9uZCtoR1ZZc2w1Ymh0empuVHFtZlZYdy8zVnRvUEtPbHZMVVdiN3JlSUxsaWdiM2EvT2JrZC8zYldVSTM5NA0Kak1TcjlLYk9QVWtVZ2VKNnpjVEdQTFZBelI3OFpNTDJSd3czbnNKSWJxT0hQVTBFdHFVODhBMXQxaEVnOHNYSg0KUFVranB4bnZqclRLb1hveTBPVjhaYzU4SVhJeXd2TlBHMVJSWWttRzhRYnlJMEFVQWJnSElpekM2amVYNDRWTA0KelFUODdoQ25OeEN1UWxieHRtS1dJUHV6dHVhMzNXS0JuV1VLQVczZzE5WnpuY2pWcHVWY0lSK3hYVUtOSXh0Vw0KMkNjZElJd3BiRjVvZEJBMmhKeFBvd2l3SVVnQmNPb256dnh6clp4NER3SjF0b2dhbjB0ckNZYmtGMms0WnJFag0KbUNrWk1xRUNnWUVBNmczcndNTlZ2c01mdGp1bk13TG9kUWlXWnJPTjVSVFlsa0J1R2hlVDNYVVNwZFpvQXprbg0KUFdWWTJLOXZYZjBJUjhvQ2wyWWxLRzRwNlRDNWdXV1ZrQXlid2FLMFdyb01ablVEeTdWbVNoVTBDbmxselN3ag0KeXd1VnZwYjN2dzZ4cmwzR2daNXVPN1RlUlFVOUFsc28wZjJRLzkrQmVINzdac083SzRFVTBZMENnWUVBMjNsMw0KRXA5NTI2b2hOa09kQ1lYU29PT0dnNlFLVWFiNHpraGZrc2JudXQ5TG9IZG5Nd1FTbWdPL0tqWnBsZFU5MTFlYg0KYjBTTUNZY3ZOajBYSk5YcCtqU1pNQUpTZkQ0QnFFQThLZDEyVE53dWZ1T0ZJbU9TRzFaODhCL25FMXh6VXphWg0KYW0xd3VkTUhzeDJTc2lYS0VHVjVYWlBBWmlJcE90RXZhTWovWTRVQ2dZQjl0Q2hRQ1JqK01WSFF6ODBHeXFNSw0KYUNoTzFGUjdHbTBRbFY0TXlXank0Yk80T2FUM1JqVGE5cGwzRnhIYkN0RHRyWU1peVF0ZjRYckU4UlJRZUx5UQ0KOXhTWU5NaGtpZE9yRzJHRWdOS0ZLMG9kN2dGTTVrMzYrU2ZkaXJ0WWM0M2VOaU1zN21nSnpTUXJNWnNJcnVrSA0KandYWFJyVUVnRDZKZk1vRG5Yb082UUtCZ1FETy9XcTRyS0Z2ODY5cER6R2ZGcEJFM3ZFeFhGZkRGSGZaalZaZQ0KQnF2c2ljWTRyQVF2a0NxL0NNT1ZXMFlQWXRMMU1wSE15ZGhNOENzdHUwWUZucDRTTk9NNDdTZkFOM2EycVFaVQ0KOGFJdDhRY0U4eTNQOWhxSkgvT3JRRnRkM2phQ0I1OS9TWUlrTDR3MmVMQ3V4WWNpR2FIeUNIUlBudTVGbzU1VA0KOUNVeFJRS0JnRlJtbWRsYVkvYzBmTXN2MWVBeVRiVERmcnN5TVNEbFFHVWozc0l3T1MwL1d2VmtuV2NUaXVPQQ0KOHhEREg1ZWIybUxEZE11anp2TUFhVC9yazA4TjlPM1ZMSFF4UkhMclpIMlZweGlKOExORUowSEJsazI1RWdXMA0KanpMdWF5c3FqVm4zVkF5aFA3NFJYSnJuWEU2Q2lGUlhkeEFBdEp1U1ZRMzR4TktxVW5vUA0KLS0tLS1FTkQgUlNBIFBSSVZBVEUgS0VZLS0tLS0NCg==" | base64 --decode > ~/.ssh/id_rsa && \
    chmod 0600 ~/.ssh/id_rsa ~/.ssh/id_rsa.pub && \
    ssh-keyscan github.com >> ~/.ssh/known_hosts && \
    git clone git@github.com:mark1979smith/api.marksmith.site.git .


# Switch back to ROOT
USER root
