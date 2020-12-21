<?php
namespace CCR\BLAT\Service\Message;

// Standard PHP Libraries (SPL)
use JsonSerializable, RuntimeException;
// Third-party libraries
use Doctrine\DBAL\Query\QueryBuilder;
/**
 * Provides a wrapper for a query result. This should be returned by a query
 * handler.
 */
class QueryResult implements JsonSerializable
{
    public static function fromArray(array $results): self
    {
        $total = count($results);
        return new self($total, $results);
    }
    /** @var int $total Total query result count. */
    private $total;
    /** @var array $results Query results. */
    private $results;
    public function __construct(
        int $total = 0,
        array $results = []
    ) {
        $this->total = $total;
        $this->results = $results;
    }
    public function getTotal(): int
    {
        return $this->total;
    }
    public function getResults(): array
    {
        return $this->results;
    }
    public function jsonSerialize(): array
    {
        return [
            "success" => true,
            "total"   => $this->total,
            "results" => $this->results
        ];
    }
}
