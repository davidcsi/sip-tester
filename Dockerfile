#+++++++++++++++++++++++++++++++++++++++
# Dockerfile for webdevops/php-nginx:centos-7
#    -- automatically generated  --
#+++++++++++++++++++++++++++++++++++++++

# Based on webdevops/php-nginx:centos-7

FROM webdevops/php:centos-7

MAINTAINER david.villasmil.work@gmail.com
LABEL vendor=WebDevOps.io
LABEL io.webdevops.layout=7
LABEL io.webdevops.version=0.50.0

ENV WEB_DOCUMENT_ROOT  /app
ENV WEB_DOCUMENT_INDEX index.php
ENV WEB_ALIAS_DOMAIN   *.vm

COPY conf/ /opt/docker/
COPY app/ /app/

# Install tools
RUN /usr/local/bin/yum-install \
        nginx \
    && /opt/docker/bin/provision run --tag bootstrap --role webdevops-nginx --role webdevops-php-nginx \
    && /opt/docker/bin/bootstrap.sh

#### David's stuff for the tester

RUN yum install epel-release && yum install -y gcc make bison flex libcurl libcurl-devel libunistring-devel \
                   openssl openssl-devel pcre-devel zlib-devel lua-5.1.4-14.el7 \
                   lua-devel-5.1.4-14.el7 mysql-community-devel-5.6.26-2.el7 \
                   libxml2-devel perl-ExtUtils-Embed net-snmp-devel memcached \
                   cyrus-sasl-devel && \
    yum groupinstall "Development Tools" -y && \
    yum install -y ngrep.x86_64 perl-Data-UUID.x86_64 perl-CGI.noarch perl-NetAddr-IP.x86_64 \
      		         perl-TimeDate.noarch perl-DBI.x86_64 openssl-devel.x86_64 libpcap-devel.x86_64 \
		               ncurses-devel.x86_64 nano.x86_64 wget.x86_64 cpan sipp.x86_64 ngrep.x86_64 tcpdump.x86_64 wireshark.x86_64 && \
    yum clean all

# Perl modules that need forcing
ENV PERL_MM_USE_DEFAULT 1
ENV PERL_EXTUTILS_AUTOINSTALL "--defaultdeps"

RUN wget http://search.cpan.org/CPAN/authors/id/H/HA/HAARG/local-lib-2.000018.tar.gz && \
    tar -xvzf local-lib-2.000018.tar.gz && \
    cd local-lib-2.000018 && \
    perl Makefile.PL && \
    make install
RUN perl -MCPAN -e "CPAN::Shell->force(qw(install Devel::Trace));"
RUN perl -MCPAN -e "CPAN::Shell->force(qw(install Net::WebSocket::Server));"
RUN cpan -i UUID::Generator::PurePerl
RUN cpan -i Net::Address::IP::Local
RUN cpan -i Protocol::WebSocket::Handshake::Server

#RUN wget http://vorboss.dl.sourceforge.net/project/sipp/sipp/3.4/sipp-3.3.990.tar.gz && \
#    tar -xvzf sipp-3.3.990.tar.gz && \
#    cd sipp-3.3.990/ && \
#    ./configure --with-openssl --with-pcap && \
#    make && make install



ENV TERM xterm
EXPOSE 80 65000
