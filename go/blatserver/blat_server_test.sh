docker-compose exec blat_server /usr/local/bin/blat -minIdentity=95 -out=psl -q=dna /assets/dm6.2bit ./input.fa output
docker-compose exec blat_server cat ./output
