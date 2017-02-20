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

use Fusio\Adapter\Redis\Connection\Redis;
use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;
use Fusio\Engine\Model\Connection;

/**
 * RedisTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RedisTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $connection = new Connection();
        $connection->setId(1);
        $connection->setName('redis');
        $connection->setClass(Redis::class);
        $connection->setConfig([
            'host' => '127.0.0.1',
            'port' => '',
        ]);

        $this->getConnectionRepository()->add($connection);
    }

    public function providerHandler()
    {
        return [
            [$this->getCacheCode(), 200, [], $this->getCacheBody()],
            [$this->getCacheExistingCode(), 200, [], $this->getCacheExistingBody()],
            [$this->getCacheDeleteCode(), 200, [], $this->getCacheDeleteBody()],
        ];
    }

    protected function getCacheCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("redis");
var result = null;
var success = false;
if (!connection.exists("bar")) {
    success = true;
    result = connection.set("bar", {
        foo: "bar"
    });
}

response.setStatusCode(200);
response.setBody({
    success: success,
    result: result
});

JAVASCRIPT;
    }

    protected function getCacheBody()
    {
        return <<<JSON
{
    "success": true,
    "result": null
}
JSON;
    }

    protected function getCacheExistingCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("redis");
var result = null;
var success = false;
if (connection.exists("bar")) {
    success = true;
    result = connection.get("bar");
}

response.setStatusCode(200);
response.setBody({
    success: success,
    result: result
});

JAVASCRIPT;
    }

    protected function getCacheExistingBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "foo": "bar"
    }
}
JSON;
    }

    protected function getCacheDeleteCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("redis");
var result = null;
var success = false;
if (connection.exists("bar")) {
    success = true;
    connection.del("bar");
    result = connection.get("bar");
}

response.setStatusCode(200);
response.setBody({
    success: success,
    result: result
});

JAVASCRIPT;
    }

    protected function getCacheDeleteBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "foo": "bar"
    }
}
JSON;
    }
}
