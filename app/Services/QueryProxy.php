<?php

namespace App\Services;

class QueryProxy {

    protected $db;
    protected $cacheKey;

    public function __construct(Array $data = [], $cacheKey = null) {
        $this->cacheKey;

        if (!empty($data)) {
            $this->createTable($this->fieldNames($data));

            $this->insert($data);
        }
    }

    public function insert(Array $data)
    {
        $fieldNames = $this->fieldNames($data);

        $db = $this->connection();

        foreach ($data as $line) {
            $db->query('INSERT INTO dataset (' . implode(',', $fieldNames) . ') '
                . 'VALUES ("' . implode('", "', (array) $line) . '")');
        }
    }

    public function query($query)
    {
        $response = $this->db->query($query);

        if (!$response) {
            throw new \InvalidArgumentException(array_last($this->db->errorInfo()));
        }
        return $response->fetchAll(\PDO::FETCH_CLASS);
    }

    protected function createTable($fieldNames)
    {
        $fields = array_map(function ($f) { return $f . ' text default null'; }, $fieldNames);

        $db = $this->connection();

        $db->query('CREATE TABLE dataset (' . implode(',', $fields) . ')');
    }

    protected function fieldNames($data)
    {
        return empty($data) ? null : array_keys((array) $data[0]);
    }

    protected function connection()
    {
        return $this->db ?: $this->db = new \PDO('sqlite::memory:');
    }

}
