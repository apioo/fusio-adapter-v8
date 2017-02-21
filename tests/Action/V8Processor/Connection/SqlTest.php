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

use Fusio\Adapter\Sql\Connection\Sql;
use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;
use Fusio\Engine\Model\Connection;

/**
 * SqlTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SqlTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $connection = new Connection();
        $connection->setId(1);
        $connection->setName('sql');
        $connection->setClass(Sql::class);
        $connection->setConfig([
            'type'     => 'pdo_mysql',
            'host'     => '127.0.0.1',
            'username' => 'root',
            'password' => '',
            'database' => 'app_news',
        ]);

        $this->getConnectionRepository()->add($connection);

        // add sql test data
        $this->setUpFixture($connection->getConfig());
    }

    public function providerHandler()
    {
        return [
            [$this->getFetchAllCode(), 200, [], $this->getFetchAllBody()],
            [$this->getFetchAssocCode(), 200, [], $this->getFetchAssocBody()],
            [$this->getFetchColumnCode(), 200, [], $this->getFetchColumnBody()],
            [$this->getExecuteUpdateCode(), 200, [], $this->getExecuteUpdateBody()],
        ];
    }

    protected function getFetchAllCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("sql");
var result = connection.fetchAll("SELECT * FROM app_news WHERE id = :id", {
    id: 1
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFetchAllBody()
    {
        return <<<JSON
{
    "success": true,
    "result": [
        {
            "id": "1",
            "title": "foo",
            "content": "bar",
            "date": "2015-02-27 19:59:15"
        }
    ]
}
JSON;
    }

    protected function getFetchAssocCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("sql");
var result = connection.fetchAssoc("SELECT * FROM app_news WHERE id = :id", {
    id: 1
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFetchAssocBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "id": "1",
        "title": "foo",
        "content": "bar",
        "date": "2015-02-27 19:59:15"
    }
}
JSON;
    }

    protected function getFetchColumnCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("sql");
var result = connection.fetchColumn("SELECT COUNT(*) FROM app_news WHERE id = :id", {
    id: 1
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getFetchColumnBody()
    {
        return <<<JSON
{
    "success": true,
    "result": 1
}
JSON;
    }

    protected function getExecuteUpdateCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("sql");
var result = connection.executeUpdate("UPDATE app_news SET title = :title WHERE id = :id", {
    title: "bar",
    id: 1
});

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getExecuteUpdateBody()
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
        $factory    = $this->getConnectionFactory()->factory(Sql::class);
        /** @var $connection \Doctrine\DBAL\Connection */
        $connection = $factory->getConnection($this->getParameters($config));

        $fromSchema = $connection->getSchemaManager()->createSchema();
        $toSchema   = new \Doctrine\DBAL\Schema\Schema();

        $table = $toSchema->createTable('app_news');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('title', 'string');
        $table->addColumn('content', 'text');
        $table->addColumn('date', 'datetime');
        $table->setPrimaryKey(['id']);

        $queries = $fromSchema->getMigrateToSql($toSchema, $connection->getDatabasePlatform());
        foreach ($queries as $query) {
            $connection->query($query);
        }

        $data = $this->getFixtures();
        foreach ($data as $row) {
            $connection->insert('app_news', $row);
        }
    }

    protected function getFixtures()
    {
        $result = [];
        $result[] = [
            'id' => 1,
            'title' => 'foo',
            'content' => 'bar',
            'date' => '2015-02-27 19:59:15',
        ];
        $result[] = [
            'id' => 2,
            'title' => 'bar',
            'content' => 'foo',
            'date' => '2015-02-27 19:59:15',
        ];

        return $result;
    }
}
