<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Adapter\V8\Wrapper\Connection;

use Fusio\Adapter\V8\Wrapper\Connection\MongoDB\DeleteResult;
use Fusio\Adapter\V8\Wrapper\Connection\MongoDB\InsertManyResult;
use Fusio\Adapter\V8\Wrapper\Connection\MongoDB\InsertOneResult;
use Fusio\Adapter\V8\Wrapper\Connection\MongoDB\UpdateResult;
use MongoDB\Database;
use PSX\V8\ObjectInterface;

/**
 * MongoDB
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MongoDB implements ObjectInterface
{
    /**
     * @var \MongoDB\Database
     */
    protected $database;

    /**
     * @param \MongoDB\Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param string $collection
     * @param array $pipeline
     * @param array $options
     * @return \Traversable
     */
    public function aggregate($collection, $pipeline = [], $options = [])
    {
        return $this->database->selectCollection($collection)->aggregate((array) $pipeline, (array) $options);
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array $options
     * @return \MongoDB\Driver\Cursor
     */
    public function find($collection, $filter = [], $options = [])
    {
        return $this->database->selectCollection($collection)->find($filter, (array) $options);
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array $options
     * @return array|null|object
     */
    public function findOne($collection, $filter = [], $options = [])
    {
        return $this->database->selectCollection($collection)->findOne($filter, (array) $options);
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array $options
     * @return array|null|object
     */
    public function findOneAndDelete($collection, $filter, $options = [])
    {
        return $this->database->selectCollection($collection)->findOneAndDelete($filter, (array) $options);
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array|object $update
     * @param array $options
     * @return array|null|object
     */
    public function findOneAndUpdate($collection, $filter, $update, $options = [])
    {
        return $this->database->selectCollection($collection)->findOneAndUpdate($filter, $update, (array) $options);
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array|object $replacement
     * @param array $options
     * @return array|null|object
     */
    public function findOneAndReplace($collection, $filter, $replacement, $options = [])
    {
        return $this->database->selectCollection($collection)->findOneAndReplace($filter, $replacement, (array) $options);
    }

    /**
     * @param string $collection
     * @param array|object $document
     * @param array $options
     * @return \Fusio\Adapter\V8\Wrapper\Connection\MongoDB\InsertOneResult
     */
    public function insertOne($collection, $document, $options = [])
    {
        return new InsertOneResult($this->database->selectCollection($collection)->insertOne($document, (array) $options));
    }

    /**
     * @param string $collection
     * @param array $documents
     * @param array $options
     * @return \Fusio\Adapter\V8\Wrapper\Connection\MongoDB\InsertManyResult
     */
    public function insertMany($collection, $documents, $options = [])
    {
        return new InsertManyResult($this->database->selectCollection($collection)->insertMany($documents, (array) $options));
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array|object $update
     * @param array $options
     * @return \Fusio\Adapter\V8\Wrapper\Connection\MongoDB\UpdateResult
     */
    public function updateOne($collection, $filter, $update, $options = [])
    {
        return new UpdateResult($this->database->selectCollection($collection)->updateOne($filter, $update, (array) $options));
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array|object $update
     * @param array $options
     * @return \Fusio\Adapter\V8\Wrapper\Connection\MongoDB\UpdateResult
     */
    public function updateMany($collection, $filter, $update, $options = [])
    {
        return new UpdateResult($this->database->selectCollection($collection)->updateMany($filter, $update, (array) $options));
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array $options
     * @return \Fusio\Adapter\V8\Wrapper\Connection\MongoDB\DeleteResult
     */
    public function deleteOne($collection, $filter, $options = [])
    {
        return new DeleteResult($this->database->selectCollection($collection)->deleteOne($filter, (array) $options));
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array $options
     * @return \Fusio\Adapter\V8\Wrapper\Connection\MongoDB\DeleteResult
     */
    public function deleteMany($collection, $filter, $options = [])
    {
        return new DeleteResult($this->database->selectCollection($collection)->deleteMany($filter, (array) $options));
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array|object $replacement
     * @param array $options
     * @return \Fusio\Adapter\V8\Wrapper\Connection\MongoDB\UpdateResult
     */
    public function replaceOne($collection, $filter, $replacement, $options = [])
    {
        return new UpdateResult($this->database->selectCollection($collection)->replaceOne($filter, $replacement, (array) $options));
    }

    /**
     * @param string $collection
     * @param array|object $filter
     * @param array $options
     * @return int
     */
    public function count($collection, $filter, $options = [])
    {
        return $this->database->selectCollection($collection)->count($filter, (array) $options);
    }

    public function getProperties()
    {
        return [
            'aggregate' => [$this, 'aggregate'],
            'find' => [$this, 'find'],
            'findOne' => [$this, 'findOne'],
            'findOneAndDelete' => [$this, 'findOneAndDelete'],
            'findOneAndUpdate' => [$this, 'findOneAndUpdate'],
            'findOneAndReplace' => [$this, 'findOneAndReplace'],
            'insertOne' => [$this, 'insertOne'],
            'insertMany' => [$this, 'insertMany'],
            'updateOne' => [$this, 'updateOne'],
            'updateMany' => [$this, 'updateMany'],
            'deleteOne' => [$this, 'deleteOne'],
            'deleteMany' => [$this, 'deleteMany'],
            'replaceOne' => [$this, 'replaceOne'],
            'count' => [$this, 'count'],
        ];
    }
}
