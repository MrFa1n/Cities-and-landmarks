FROM library/postgres
COPY /docker/data/init.sql /docker-entrypoint-initdb.d/