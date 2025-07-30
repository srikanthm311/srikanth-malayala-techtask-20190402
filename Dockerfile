FROM docker-registry.cloud.caixabank.com/catalog/docker-init-setup:3.4.0 as ca-certs
RUN /usr/local/build.sh && tar xzvf /opt/ca-certs/ca-sources.tar.gz -C /etc/pki/ca-trust/source/anchors

FROM docker-registry.cloud.caixabank.com/catalog/paas/itnow-build-composer-php-apache:php82-1.0.0 as php-composer-builder

FROM docker-registry.cloud.caixabank.com/containers/itncdi/batchphp:2.0.0

COPY --from=ca-certs /etc/pki/ca-trust/source/anchors /etc/pki/ca-trust/source/anchors
USER root
RUN update-ca-trust

COPY --from=php-composer-builder /usr/local/bin/composer /usr/local/bin/composer

ENV SRC_ROOT='/apps/eingdp'

WORKDIR $SRC_ROOT
USER root
RUN mkdir -p "$SRC_ROOT"
RUN chown -R 1022:1022 "$SRC_ROOT"
USER 1022
COPY --chown=1022:1022 src "$SRC_ROOT"
WORKDIR $SRC_ROOT
USER 1022

ARG USER_ARTIFACTORY_ARG="${USER_ARTIFACTORY_ARG}"
ARG PASS_ARTIFACTORY_ARG="${PASS_ARTIFACTORY_ARG}"
RUN composer config --global http-basic.artifacts.cloud.caixabank.com "${USER_ARTIFACTORY_ARG}" "${PASS_ARTIFACTORY_ARG}"

RUN /usr/local/bin/composer update
RUN /usr/local/bin/composer install --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction --no-dev --ignore-platform-reqs &&\
      composer dump-autoload --classmap-authoritative

RUN chown -R 1022:1022 "$SRC_ROOT"
RUN chmod +x -R "$SRC_ROOT"

WORKDIR $SRC_ROOT
RUN ln -s /tmp/properties ${SRC_ROOT}/properties
