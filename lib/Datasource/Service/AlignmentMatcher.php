<?php
namespace CCR\BLAT\Datasource\Service;

// BLAT libraries with namespaces
use CCR\BLAT\Service\External\BlatDataSource;
class AlignmentMatcher
{
    private $blatDataSource;
    public function __construct(BlatDataSource $blatDataSource)
    {
        $this->blatDataSource = $blatDataSource;
    }
    /**
     * Returns an array of string objects from the results 
     * obtained from the BLAT data source depending on the 
     * output format.
     */
    public function get(
        string $speciesShortName,
        string $genomeAssemblyReleaseVersion,
        string $minimumIdentityPercentage,
        string $sequence,
        string $outputFormat
    ): Array {
        $customizedPsl = ( $outputFormat === "psl - REDfly" );
        if ( $customizedPsl ) {
            $outputFormat = "psl";
        }
        $objects = $this->blatDataSource->query(
            $speciesShortName,
            $genomeAssemblyReleaseVersion,
            $minimumIdentityPercentage,
            $sequence,
            $outputFormat
        );
        $objectResults = [];
        foreach ( $objects as $objectResult ) {
            $objectResults[] = $objectResult;
        }        
        if ( $customizedPsl ) {
            $rows = explode(PHP_EOL, $objectResults[0]);
            $customizedObjectResult = $rows[0] . PHP_EOL . 
                $rows[1] . PHP_EOL .
                $this->reformatLine($rows[2]) . PHP_EOL .
                $this->reformatLine($rows[3]) . PHP_EOL .
                $rows[4] . PHP_EOL;
            for ( $rowIndex = 5; $rowIndex < (count($rows) - 2); $rowIndex++ ) {
                $customizedObjectResult .= $this->reformatLine($rows[$rowIndex]) . PHP_EOL;
            }
            $objectResults[0] = $customizedObjectResult . $this->reformatLine($rows[count($rows) - 2]);
        }

        return $objectResults;
    }

    /**
     * More information about the psl format in 
     * http://genome.ucsc.edu/FAQ/FAQformat.html#format2
     */    
    private function reformatLine($inputLine): string {
        $columns = explode("\t", $inputLine);
        $outputLine = $columns[13] . "\t" .
            $columns[15] . "\t" .
            $columns[16] . "\t" .
            $columns[0] . "\t";
        for ( $columnIndex = 1; $columnIndex < 13; $columnIndex++ ) {
            $outputLine .= $columns[$columnIndex] ."\t";

        }
        $outputLine .= $columns[14] . "\t"; 
        for ( $columnIndex = 17; $columnIndex < count($columns); $columnIndex++ ) {
            $outputLine .= $columns[$columnIndex] ."\t";
        }
        
        return $outputLine;
    }
}
