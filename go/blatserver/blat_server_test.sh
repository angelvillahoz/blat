docker-compose exec blat_server /usr/local/bin/blat -minIdentity=95 -out=pslx -q=dna /assets/aaeg5.2bit ./input.fa output.pslx
docker-compose exec blat_server cat ./output.pslx