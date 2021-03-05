docker stop $(docker ps --filter "name=blat_blat_server_1" \
    --filter "name=blat_mariadb_server_1" \
    --filter "name=blat_php_server_1" \
    -q)
docker rm blat_blat_server_1 \
    blat_mariadb_server_1 \
    blat_php_server_1
docker rmi blat_blat_server \
    blat_php_server \
    $(docker images -f "dangling=true" -q)
docker volume prune -f
