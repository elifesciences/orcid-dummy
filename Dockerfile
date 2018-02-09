FROM elifesciences/php_cli

USER elife
RUN mkdir /srv/orcid-dummy
WORKDIR /srv/orcid-dummy
COPY --chown=elife:elife composer.json composer.lock /srv/orcid-dummy/
RUN composer-install
COPY --chown=elife:elife src/ /srv/orcid-dummy/src
COPY --chown=elife:elife web/ /srv/orcid-dummy/web
RUN composer-post

USER www-data
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "web/"]
