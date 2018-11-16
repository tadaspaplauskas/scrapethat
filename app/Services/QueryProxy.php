<?php

namespace App\Services;

class QueryProxy {

    protected $db;
    protected $cacheKey;

    public function __construct($fields, $cacheKey = null) {
        $this->cacheKey;

        $this->createTable($fields);
    }

    public function insert($data)
    {
        $db = $this->connection();

        $query = 'INSERT INTO dataset VALUES ("' . implode('", "', (array) $data) . '")';

        return $db->query($query);
    }

    public function query($query)
    {
        $response = $this->db->query($query);

        if (!$response) {
            throw new \InvalidArgumentException(array_last($this->db->errorInfo()));
        }
        return $response->fetchAll(\PDO::FETCH_CLASS);
    }

    protected function createTable(array $fields)
    {
        $fieldDefinitions = [];

        foreach ($fields as $field => $type) {
            $fieldDefinitions[] = $field . ' ' . $type . ' default null';
        }

        $db = $this->connection();

        $query = 'CREATE TABLE dataset (' . implode(',', $fieldDefinitions) . ')';

        $db->query($query);
    }

    protected function connection()
    {
        return $this->db ?: $this->db = new \PDO('sqlite::memory:');
    }

}
