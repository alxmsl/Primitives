<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Set\Provider;
use alxmsl\Connection\Postgresql\Connection;
use alxmsl\Connection\Postgresql\Exception\DuplicateEntryException;
use alxmsl\Connection\Postgresql\Exception\UndefinedTableException;
use alxmsl\Primitives\Set\Iterator\PostgresIterator;
use RuntimeException;

/**
 * Postgres implementation for set
 * @author alxmsl
 * @date 4/6/13
 */
final class PostgresProvider extends AbstractProvider {
    /**
     * Create set table query
     */
    const QUERY_CREATE = '
        CREATE TABLE {{ tbl(name) }} (
            id SERIAL,
            key CHAR(32) NOT NULL DEFAULT NULL PRIMARY KEY,
            data TEXT NOT NULL DEFAULT NULL
        )
    ';

    /**
     * Add item to set query
     */
    const QUERY_ADD = '
        INSERT INTO {{ tbl(name) }} (key, data)
        VALUES ({{ str(key) }}, {{ str(data) }})
    ';

    /**
     * Check existence item in the set query
     */
    const QUERY_EXISTS = '
        SELECT COUNT(*) FROM {{ tbl(name) }}
        WHERE key = {{ str(key) }}
    ';

    /**
     * Get one item from the set
     */
    const QUERY_GET = '
        SELECT * FROM {{ tbl(name) }}
        {{ IF id }}
            WHERE id >= {{ str(id) }}
        {{ END }}
        ORDER BY id
        LIMIT 1
    ';

    /**
     * @var Connection postgres connection instance
     */
    private $Postgres = null;

    /**
     * @var null|PostgresIterator postgres iterator instance
     */
    private $Iterator = null;

    /**
     * Getter for the postgres iterator
     * @return PostgresIterator postgres iterator instance
     */
    public function getIterator() {
        if (is_null($this->Iterator)) {
            $this->Iterator = new PostgresIterator();
            $this->Iterator->setProvider($this);
        }
        return $this->Iterator;
    }

    /**
     * Postgres connection setter
     * @param Connection $Postgres postgres connection instance
     * @return PostgresProvider self
     */
    public function setPostgres(Connection $Postgres) {
        $this->Postgres = $Postgres;
        return $this;
    }

    /**
     * Postgres connection getter
     * @return Connection postgres connection instance
     * @throws RuntimeException when connection is not set
     */
    private function getPostgres() {
        if (is_null($this->Postgres)) {
            throw new RuntimeException('postgres connection was not define');
        }
        return $this->Postgres;
    }

    /**
     * Add item to set
     * @param mixed $Item adding item
     * @return bool result of adding item
     */
    public function add($Item) {
        try {
            $this->getPostgres()->query(self::QUERY_ADD, array(
                'name'  => $this->getName(),
                'key'   => $this->buildKey($Item),
                'data'  => $Item,
            ));
        } catch (UndefinedTableException $Ex) {
            $this->getPostgres()->query(self::QUERY_CREATE, array(
                'name' => $this->getName(),
            ));
            $this->getPostgres()->query(self::QUERY_ADD, array(
                'name'  => $this->getName(),
                'key'   => $this->buildKey($Item),
                'data'  => $Item,
            ));
        } catch (DuplicateEntryException $Ex) {
            return false;
        }
        return true;
    }

    /**
     * Check item in set
     * @param mixed $Item checking item
     * @return bool result of check existence
     */
    public function exists($Item) {
        try {
            $Result = $this->getPostgres()->query(self::QUERY_EXISTS, array(
                'name'  => $this->getName(),
                'key'   => $this->buildKey($Item),
            ));
            $data = $Result->getResult();
            return (bool) $data[0]['count'];
        } catch (UndefinedTableException $Ex) {
            return false;
        }
    }

    /**
     * Set item getter
     * @param mixed $offset set items offset
     * @return mixed set item
     */
    public function get($offset = null) {
        $parameters = array(
            'name' => $this->getName(),
        );
        if (!is_null($offset)) {
            $parameters['id'] = $offset;
        }
        $Result = $this->getPostgres()->query(self::QUERY_GET, $parameters);
        return $Result->getResult();
    }

    /**
     * Build key for set element
     * @param mixed $Data item data
     * @return string key string for data
     */
    private function buildKey($Data) {
        return md5($Data);
    }
}
