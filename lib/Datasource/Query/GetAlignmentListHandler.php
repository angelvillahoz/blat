<?php
namespace CCR\BLAT\Datasource\Query;

// BLAT libraries with namespaces
use CCR\BLAT\Datasource\Query\GetAlignmentList;
use CCR\BLAT\Datasource\Service\AlignmentMatcher;
use CCR\BLAT\Service\Message\QueryResult;
class GetAlignmentListHandler
{
    private $alignmentMatcher;
    public function __construct(
        AlignmentMatcher $alignmentMatcher
    ) {
        $this->alignmentMatcher = $alignmentMatcher;
    }
    public function __invoke(GetAlignmentList $getAlignmentList): QueryResult
    {
        if ( ($getAlignmentList->getSpeciesShortName() !== "") &&
            ($getAlignmentList->getSequence() !== "") ) {
            $alignmentList = $this->alignmentMatcher->get(
                $getAlignmentList->getSpeciesShortName(),
                $getAlignmentList->getSequence()
            );
            $coordinates = array();
            foreach ( $alignmentList as $rawAlignment ) {
                $alignment = array(
                    "chromosome"    => $rawAlignment->chromosomeName,
                    "end"           => $rawAlignment->endCoordinate,
                    "start"         => $rawAlignment->startCoordinate
                );
                $coordinates[] = $alignment;
            }
        }

        return QueryResult::fromArray($coordinates);
    }
}
