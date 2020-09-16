FROM nginx:stable
COPY uuids.conf /etc/nginx/conf.d/
# RUN rm /etc/nginx/conf.d/default.conf
RUN nginx -s reload