curl -v \
     -F speciesShortName=dmel \
     -F genomeAssemblyReleaseVersion=dm6 \
     -F minimumIdentityPercentage=95 \
     -F input=@./input.fa \
     -F outputFormat=psl \
     http://127.0.0.1:8080
