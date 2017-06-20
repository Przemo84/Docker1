FROM php:7.0-cli

MAINTAINER docker@ekito.fr

RUN apt-get update && apt-get -y install cron unzip libxslt-dev libpq-dev nano

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql xsl

RUN export EDITOR=nano

# Add crontab file in the cron directory
ADD crontab /etc/cron.d/hello-cron

RUN crontab /etc/cron.d/hello-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/hello-cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Run the command on container startup
CMD cron && tail -f /var/log/cron.log
