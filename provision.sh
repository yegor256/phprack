#!/bin/bash

export DEBIAN_FRONTEND=noninteractive
hostname vagrant && echo -e "\n127.0.0.1 vagrant\n" >> /etc/hosts

apt-get -y update \
    && apt-get -y install zip unzip git make openjdk-7-jdk jscoverage python-software-properties sendmail \
    && apt-get -y upgrade
update-java-alternatives --set java-1.7.0-openjdk-amd64
/usr/bin/add-apt-repository ppa:ondrej/php5-oldstable \
    && apt-get -y update \
    && apt-get -y install php-pear php5 php5-dev php5-curl php5-xsl php5-sqlite php5-gd php5-mysql php5-mcrypt php5-memcached

pear channel-discover pear.phing.info \
    && pear channel-discover pear.symfony-project.com \
    && pear channel-discover pear.pdepend.org \
    && pear channel-discover pear.phpdoc.org
pear upgrade
pear install --force --alldeps PHP_CodeSniffer \
    PhpDocumentor \
    phing/phing \
    Net_FTP Net_SMTP Mail

if [ ! -e /etc/php5/cli/conf.d/xdebug.ini ]; then
    pecl install --force --alldeps xdebug;
    echo "zend_extension=/usr/lib/php5/20100525/xdebug.so" > /etc/php5/cli/conf.d/xdebug.ini;
fi

if [ ! -e /usr/local/bin/jsl ]; then
    wget --quiet http://www.javascriptlint.com/download/jsl-0.3.0-src.tar.gz;
    tar xzf jsl-0.3.0-src.tar.gz;
    rm -rf jsl-0.3.0-src.tar.gz;
    cd jsl-0.3.0/src;
    make -f Makefile.ref;
    sudo mv Linux_All_DBG.OBJ/jsl /usr/local/bin/jsl;
    cd ../..;
    rm -rf jsl-0.3.0;
fi

if [ ! -e /usr/local/bin/phantomjs ]; then
    wget --quiet https://phantomjs.googlecode.com/files/phantomjs-1.9.1-linux-x86_64.tar.bz2;
    bunzip2 phantomjs-1.9.1-linux-x86_64.tar.bz2;
    tar xf phantomjs-1.9.1-linux-x86_64.tar;
    rm -rf phantomjs-1.9.1-linux-x86_64.tar;
    mv phantomjs-1.9.1-linux-x86_64/bin/phantomjs /usr/local/bin/phantomjs;
    rm -rf phantomjs-1.9.1-linux-x86_64;
fi

if [ ! -e /usr/local/bin/phpmd.phar ];  then
    wget -c http://static.phpmd.org/php/latest/phpmd.phar
    mv phpmd.phar /usr/local/bin/phpmd.phar
fi

if [ ! -e /usr/local/bin/phpunit ]; then
    wget https://phar.phpunit.de/phpunit.phar
    chmod +x phpunit.phar
    sudo mv phpunit.phar /usr/local/bin/phpunit
fi
