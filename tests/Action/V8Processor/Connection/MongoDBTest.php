<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace Fusio\Adapter\V8\Tests\Action\V8Processor\Connection;

use Fusio\Adapter\Mongodb\Connection\MongoDB;
use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;
use Fusio\Engine\Model\Connection;

/**
 * MongoDBTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MongoDBTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $connection = new Connection();
        $connection->setId(1);
        $connection->setName('mongodb');
        $connection->setClass(MongoDB::class);
        $connection->setConfig([
            'url'      => 'mongodb://127.0.0.1',
            'options'  => '',
            'database' => 'test',
        ]);

        $this->getConnectionRepository()->add($connection);

        // add mongodb test data
        $this->setUpFixture($connection->getConfig());
    }

    public function providerHandler()
    {
        return [
            [$this->getAggregateCode(), 200, [], $this->getAggregateBody()],
        ];
    }

    protected function getAggregateCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.aggregate("app_news", [{
    $project: {
        _id: 0,
        title: 1
    }
}]);

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getAggregateBody()
    {
        return <<<JSON
{
    "success": true,
    "result": [{
        "title": "foo"
    },{
        "title" :"bar"
    }]
}
JSON;
    }

    protected function setUpFixture(array $config)
    {
        $factory    = $this->getConnectionFactory()->factory(MongoDB::class);
        /** @var $connection \MongoDB\Database */
        $connection = $factory->getConnection($this->getParameters($config));

        try {
            $connection->createCollection('app_news');
        } catch (\MongoDB\Driver\Exception\RuntimeException $e) {
            // collection already exists
            $connection->dropCollection('app_news');
            $connection->createCollection('app_news');
        }

        $collection = $connection->selectCollection('app_news');
        $collection->insertMany($this->getFixtures());
    }

    protected function getFixtures()
    {
        $result = [];
        $result[] = (object) [
            'title' => 'foo',
            'content' => 'bar',
            'user' => (object) [
                'name' => 'foo',
                'uri' => 'http://google.com'
            ],
            'date' => '2015-02-27 19:59:15',
        ];
        $result[] = (object) [
            'title' => 'bar',
            'content' => 'foo',
            'user' => (object) [
                'name' => 'bar',
                'uri' => 'http://google.com'
            ],
            'date' => '2015-02-27 19:59:15',
        ];

        return $result;
    }
}
