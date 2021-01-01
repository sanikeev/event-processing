FROM ubuntu:18.04
RUN apt-get update && \
    apt-get install -y supervisor && \
    apt-get clean

RUN mkdir -p /var/log/supervisor
COPY supervisord.conf /etc/supervisor/supervisord.conf

EXPOSE 8089

CMD ["/usr/bin/supervisord"]
