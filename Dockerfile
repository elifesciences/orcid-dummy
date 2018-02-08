FROM elifesciences/php_cli

USER elife
RUN mkdir /srv/orcid-dummy
WORKDIR /srv/orcid-dummy
COPY --chown=elife:elife composer.json composer.lock /srv/orcid-dummy/
RUN composer-install-prod
COPY --chown=elife:elife . /srv/orcid-dummy/
RUN composer-autoload-prod

USER www-data
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "web/"]
