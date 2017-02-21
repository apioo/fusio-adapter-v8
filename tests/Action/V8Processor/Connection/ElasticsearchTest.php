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

use Fusio\Adapter\Elasticsearch\Connection\Elasticsearch;
use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;
use Fusio\Engine\Model\Connection;

/**
 * ElasticsearchTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ElasticsearchTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $connection = new Connection();
        $connection->setId(1);
        $connection->setName('elasticsearch');
        $connection->setClass(Elasticsearch::class);
        $connection->setConfig([
            'host' => ['127.0.0.1'],
        ]);

        $this->getConnectionRepository()->add($connection);

        // add elasticsearch test data
        $this->setUpFixture($connection->getConfig());
    }

    public function providerHandler()
    {
        return [
            [$this->getIndexCode(), 200, [], $this->getIndexBody()],
            [$this->getGetCode(), 200, [], $this->getGetBody()],
            [$this->getUpdateCode(), 200, [], $this->getUpdateBody()],
            [$this->getDeleteCode(), 200, [], $this->getDeleteBody()],
            [$this->getSearchCode(), 200, [], $this->getSearchBody()],
            [$this->getCountCode(), 200, [], $this->getCountBody()],
            [$this->getExistsCode(), 200, [], $this->getExistsBody()],
            [$this->getCreateCode(), 200, [], $this->getCreateBody()],
        ];
    }

    protected function getIndexCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.index({
    index: "my_foo",
    type: "my_bar",
    id: "my_id",
    body: {
        testField: "abc"
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getIndexBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "_index": "my_foo",
        "_type": "my_bar",
        "_id": "my_id",
        "_version": 1,
        "result": "created",
        "_shards": {
            "total": 2,
            "successful": 1,
            "failed": 0
        },
        "created":true
    }
}
JSON;
    }

    protected function getGetCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.get({
    index: "my_index",
    type: "my_type",
    id: "1"
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getGetBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "_index": "my_index",
        "_type": "my_type",
        "_id": "1",
        "_version": 2,
        "found": true,
        "_source": {
            "id": "1",
            "title": "foo",
            "content": "bar",
            "date": "2015-02-27 19:59:15"
        }
    }
}
JSON;
    }

    protected function getUpdateCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.update({
    index: "my_index",
    type: "my_type",
    id: "1",
    body: {
        doc: {
            title: "foobar"
        }
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getUpdateBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "_index": "my_index",
        "_type": "my_type",
        "_id": "1",
        "_version": 4,
        "result": "updated",
        "_shards": {
            "total": 2,
            "successful": 1,
            "failed": 0
        }
    }
}
JSON;
    }

    protected function getDeleteCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.delete({
    index: "my_index",
    type: "my_type",
    id: "1"
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getDeleteBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "found": true,
        "_index": "my_index",
        "_type": "my_type",
        "_id": "1",
        "_version": 6,
        "result": "deleted",
        "_shards": {
            "total": 2,
            "successful": 1,
            "failed": 0
        }
    }
}
JSON;
    }

    protected function getSearchCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.search({
    index: "my_index",
    type: "my_type",
    body: {
        query: {
            match: {
                title: "bar"
            }
        }
    }
});

delete result.took;

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getSearchBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "timed_out": false,
        "_shards": {
            "total": 5,
            "successful": 5,
            "failed": 0
        },
        "hits": {
            "total": 0,
            "max_score": null,
            "hits": [{
                "_index": "my_index",
                "_type": "my_type",
                "_id": "2",
                "_score": 0.13353139,
                "_source": {
                    "id": "2",
                    "title": "bar",
                    "content": "foo",
                    "date": "2015-02-27 19:59:15"
                }
            }]
        }
    }
}
JSON;
    }

    protected function getCountCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.count({
    index: "my_index",
    type: "my_type",
    body: {
        query: {
            match: {
                title: "foo"
            }
        }
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getCountBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "count": 1,
        "_shards": {
            "total": 5,
            "successful": 5,
            "failed": 0
        }
    }
}
JSON;
    }

    protected function getExistsCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.exists({
    index: "my_index",
    type: "my_type",
    id: "1"
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getExistsBody()
    {
        return <<<JSON
{
    "success": true,
    "result": true
}
JSON;
    }

    protected function getCreateCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.create({
    index: "my_index",
    type: "my_type",
    id: "3",
    body: {
        title: "foofoo"
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getCreateBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "_index": "my_index",
        "_type": "my_type",
        "_id": "3",
        "_version": 1,
        "result": "created",
        "_shards": {
            "total": 2,
            "successful": 1,
            "failed": 0
        },
        "created": true
    }
}
JSON;
    }

    protected function setUpFixture(array $config)
    {
        $factory    = $this->getConnectionFactory()->factory(Elasticsearch::class);
        /** @var $connection \Elasticsearch\Client */
        $connection = $factory->getConnection($this->getParameters($config));

        $result = $this->getFixtures();
        foreach ($result as $row) {
            $connection->index([
                'index' => 'my_index',
                'type' => 'my_type',
                'id' => $row['id'],
                'body' => $row,
            ]);
        }
    }

    protected function getFixtures()
    {
        $result = [];
        $result[] = [
            'id' => '1',
            'title' => 'foo',
            'content' => 'bar',
            'date' => '2015-02-27 19:59:15',
        ];
        $result[] = [
            'id' => '2',
            'title' => 'bar',
            'content' => 'foo',
            'date' => '2015-02-27 19:59:15',
        ];

        return $result;
    }
}
