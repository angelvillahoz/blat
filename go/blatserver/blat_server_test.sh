docker-compose exec blat_server /usr/local/bin/blat -q=dna -minIdentity=100 -out=blast9 /assets/dm6.2bit ./input.fa output.blast9
docker-compose exec blat_server cat ./output.blast9