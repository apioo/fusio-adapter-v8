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
            [$this->getFindCode(), 200, [], $this->getFindBody()],
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

    protected function getFindCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.find("app_news", {}, {
    projection: {
        _id: 0,
        title: 1
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFindBody()
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

    protected function getFindOneCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.findOne("app_news", {
    title: "foo"
}, {
    projection: {
        _id: 0,
        title: 1
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFindOneBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getFindOneAndDeleteCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.findOneAndDelete("app_news", {
    title: "foo"
}, {
    projection: {
        _id: 0,
        title: 1
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFindOneAndDeleteBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getFindOneAndUpdateCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.findOneAndUpdate("app_news", {
    title: "foo"
}, {
    title: "foobar"
}, {
    projection: {
        _id: 0,
        title: 1
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFindOneAndUpdateBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }
    
    protected function getFindOneAndReplaceCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.findOneAndReplace("app_news", {
    title: "foo"
}, {
    title: "foobar"
}, {
    projection: {
        _id: 0,
        title: 1
    }
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFindOneAndReplaceBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }
    
    protected function getInsertOneCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.insertOne("app_news", {
    title: "foobar"
});

var insertId = result.getInsertedId();

response.setStatusCode(200);
response.setBody({
    success: true,
    result: {
        insertedCount: result.getInsertedCount(),
        acknowledged: result.isAcknowledged()
    }
});

JAVASCRIPT;
    }

    protected function getInsertOneBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getInsertManyCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.insertMany("app_news", [{
    title: "foobar"
}, {
    title: "foo bar"
}]);

var insertedIds = result.getInsertedIds();

response.setStatusCode(200);
response.setBody({
    success: true,
    result: {
        insertedCount: result.getInsertedCount(),
        acknowledged: result.isAcknowledged()
    }
});

JAVASCRIPT;
    }

    protected function getInsertManyBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getUpdateOneCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.updateOne("app_news", {
    title: "foo"
}, {
    title: "foobar"
});

var upsertedId = result.getUpsertedId();

response.setStatusCode(200);
response.setBody({
    success: true,
    result: {
        matchedCount: result.getMatchedCount(),
        modifiedCount: result.getModifiedCount(),
        upsertedCount: result.getUpsertedCount(),
        acknowledged: result.isAcknowledged()
    }
});

JAVASCRIPT;
    }

    protected function getUpdateOneBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getUpdateManyCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.updateMany("app_news", {
    title: "foo"
}, {
    title: "foobar"
});

var upsertedId = result.getUpsertedId();

response.setStatusCode(200);
response.setBody({
    success: true,
    result: {
        matchedCount: result.getMatchedCount(),
        modifiedCount: result.getModifiedCount(),
        upsertedCount: result.getUpsertedCount(),
        acknowledged: result.isAcknowledged()
    }
});

JAVASCRIPT;
    }

    protected function getUpdateManyBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getDeleteOneCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.deleteOne("app_news", {
    title: "foo"
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: {
        deletedCount: result.getDeletedCount(),
        acknowledged: result.isAcknowledged()
    }
});

JAVASCRIPT;
    }

    protected function getDeleteOneBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getDeleteManyCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.deleteOne("app_news", {
    title: "foo"
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: {
        deletedCount: result.getDeletedCount(),
        acknowledged: result.isAcknowledged()
    }
});

JAVASCRIPT;
    }

    protected function getDeleteManyBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getReplaceOneCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.replaceOne("app_news", {
    title: "foo"
}, {
    title: "foobar"
});

var upsertedId = result.getUpsertedId();

response.setStatusCode(200);
response.setBody({
    success: true,
    result: {
        matchedCount: result.getMatchedCount(),
        modifiedCount: result.getModifiedCount(),
        upsertedCount: result.getUpsertedCount(),
        acknowledged: result.isAcknowledged()
    }
});

JAVASCRIPT;
    }

    protected function getReplaceOneBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "title": "foo"
    }
}
JSON;
    }

    protected function getCountCode()
    {
        return <<<'JAVASCRIPT'

var connection = connector.get("mongodb");
var result = connection.count("app_news", {
    title: "foo"
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
    "result": 1
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
