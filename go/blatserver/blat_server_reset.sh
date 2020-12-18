#cd cmd && go build -a ./main.go
docker stop $(docker ps -a | grep "blat_server" | cut -d" " -f1)
docker rm $(docker ps -a | grep "blat_server" | cut -d" " -f1)
docker rmi $(docker images | grep "blat_server" | cut -d" " -f1)
docker-compose up -d blat_server
docker image prune --force
docker volume prune --force
