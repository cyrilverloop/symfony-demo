FROM httpd:alpine
RUN apk update \
    && apk add --no-cache tzdata
COPY ./conf/ /usr/local/apache2/conf/

# Adding user :
ARG uid
ARG user
RUN adduser -g "" -D -h "/var/www/" -u "$uid" "$user"
