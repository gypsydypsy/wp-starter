#apache

ARG version

FROM httpd:${version}

COPY ["./template-vhost/client-projet-fo.apache2", "/usr/local/apache2/client-projet-fo.apache2"]

COPY ["./template-wpconfig/wp-config.php", "/usr/local/wp-config.php"]

EXPOSE 7000