<?php

namespace App\Services;

class QueryProxy {

    protected $data;

    protected $cache; // should the database be cached?

    public function __construct($data, $cache = true) {

        $this->data = $data;

        $this->cache = $cache;

    }

    public function query($query) {

        $this->buildDatabase();

        // run query against DB

        // return results

        return;
    }

    protected function buildDatabase()
    {
        if ($this->cache) {

        }
        else {
            $db = new PDO('sqlite::memory:');
        }
    }

}
