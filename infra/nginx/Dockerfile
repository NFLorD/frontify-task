FROM nginx:stable-alpine

COPY ./default.conf /etc/nginx/conf.d/default.conf
COPY ./nginx-selfsigned.crt /etc/ssl/certs/nginx-selfsigned.crt
COPY ./nginx-selfsigned.key /etc/ssl/private/nginx-selfsigned.key

RUN chmod 644 /etc/ssl/certs/nginx-selfsigned.crt && \
    chmod 600 /etc/ssl/private/nginx-selfsigned.key