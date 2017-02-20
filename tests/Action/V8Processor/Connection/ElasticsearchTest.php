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
    }

    public function providerHandler()
    {
        return [
            [$this->getIndexCode(), 200, [], $this->getIndexBody()],
            [$this->getGetCode(), 200, [], $this->getGetBody()],
        ];
    }

    protected function getIndexCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("elasticsearch");

var result = connection.index({
    index: "my_index",
    type: "my_type",
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
    "result": {}
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
    id: "my_id"
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
    "result": {}
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
    id: "my_id",
    body: {
        doc: {
            new_field: "abc"
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
    "result": {}
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
    id: "my_id"
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
    "result": {}
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
                testField: "abc"
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

    protected function getSearchBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {}
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
                testField: "abc"
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
    "result": {}
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
    id: "my_id"
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
    "result": {}
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

    protected function getCreateBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {}
}
JSON;
    }

}
