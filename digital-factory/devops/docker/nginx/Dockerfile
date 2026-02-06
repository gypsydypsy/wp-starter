# nginx

ARG version

FROM nginx:${version}

COPY ./template-vhost/client-projet.nginx /etc/nginx/sites-available/client-projet.nginx

EXPOSE 80 443