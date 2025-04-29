<?php

declare(strict_types=1);

namespace App\Task;

use Hyperf\Task\Annotation\Task;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\WriteConcern;

class MongoTask
{
    private ?Manager $manager;

    public function __construct()
    {
        $uri = 'mongodb://root:secret@mongo:27017';
        $this->manager = new Manager($uri); 
    }

    #[Task]
    public function save(string $namespace, array $document): bool
    {
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $bulk = new BulkWrite();
        $bulk->insert($document);

        $result = $this->manager()->executeBulkWrite($namespace, $bulk, $writeConcern);
        return $result->getInsertedCount() === 1;
    }

    #[Task]
    public function find(string $namespace, array $filter = [], array $options = []): array
    {
        $query = new Query($filter, $options);
        return $this->manager()->executeQuery($namespace, $query)->toArray();
    }

    protected function manager(): ?Manager
    {
        if ($this->manager instanceof Manager) {
            return $this->manager;
        }
        $uri = 'mongodb://root:secret@mongo:27017';
        return $this->manager = new Manager($uri, []);
    }
}
