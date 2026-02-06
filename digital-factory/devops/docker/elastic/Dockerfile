#elastic

ARG version

FROM docker.elastic.co/elasticsearch/elasticsearch:${version}

COPY ./config/jvm.options /usr/share/elasticsearch/config/jvm.options

RUN elasticsearch-plugin remove x-pack

CMD ["elasticsearch", "-E", "discovery.type=single-node"]

ENV LANG=C.UTF-8