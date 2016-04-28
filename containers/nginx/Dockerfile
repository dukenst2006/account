FROM nginx:1.10-alpine

# add the application to the container
COPY ./public /var/www/html/public

# add vhost config
COPY ./containers/nginx/default.conf /etc/nginx/conf.d/default.conf

# give nginx permission to serve
RUN chown -R nginx:nginx /var/www/html/public

WORKDIR /var/www/html