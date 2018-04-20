<?php

namespace App\Services;

class QueryProxy {

    protected $db;

    protected $cache; // should the database be cached?

    public function __construct(Array $data) {

        $this->buildDatabase($data);

    }

    public function query($query) {

        return $this->db->query($query)->fetchAll(\PDO::FETCH_CLASS);

    }

    protected function buildDatabase($data)
    {
        $fieldNames = array_keys((array) $data[0]);

        $fields = array_map(function ($item) {
            return $item . ' text default null';
        }, $fieldNames);

        $db = new \PDO('sqlite::memory:');

        $db->exec('DROP TABLE IF EXISTS dataset');

        $db->exec('CREATE TABLE dataset (' . implode(',', $fields) . ')');
        
        foreach ($data as $line) {
            $db->query('INSERT INTO dataset (' . implode(',', $fieldNames) . ') '
                . 'VALUES ("' . implode('", "', (array) $line) . '")');
        }

        $this->db = $db;
    }

}
