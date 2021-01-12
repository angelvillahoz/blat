docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
docker rmi blat_blat_server
docker rmi blat_php_server
docker rmi mariadb:10.3
docker rmi $(docker images -f "dangling=true" -q)
docker volume prune -f
