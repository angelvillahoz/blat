<?php
namespace CCR\BLAT\Datasource\Query;

// BLAT libraries with namespaces
use CCR\BLAT\Datasource\Query\GetAlignmentList;
use CCR\BLAT\Datasource\Service\AlignmentMatcher;
use CCR\BLAT\Service\Message\QueryResult;
class GetAlignmentListHandler
{
    private $alignmentMatcher;

    public function __construct(AlignmentMatcher $alignmentMatcher) {
        $this->alignmentMatcher = $alignmentMatcher;
    }

    public function __invoke(GetAlignmentList $getAlignmentList): QueryResult
    {
        if ( ($getAlignmentList->getSpeciesShortName() !== "") &&
            ($getAlignmentList->getGenomeAssemblyReleaseVersion() !== "") &&
            ($getAlignmentList->getMinimumIdentityPercentage() !== "") &&
            ($getAlignmentList->getSequence() !== "") &&
            ($getAlignmentList->getOutputFormat() !== "") ) {
            $alignmentList = $this->alignmentMatcher->get(
                $getAlignmentList->getSpeciesShortName(),
                $getAlignmentList->getGenomeAssemblyReleaseVersion(),
                $getAlignmentList->getMinimumIdentityPercentage(),
                $getAlignmentList->getSequence(),
                $getAlignmentList->getOutputFormat()
            );
            $coordinates = preg_replace(
                "/[\r\n]+/",
                "<br />",
                preg_replace(
                    "/[\t]+/",
                    "&nbsp;&nbsp;&nbsp;&nbsp;",
                    $alignmentList
                )
            );
        }

        return QueryResult::fromArray($coordinates);
    }
}
