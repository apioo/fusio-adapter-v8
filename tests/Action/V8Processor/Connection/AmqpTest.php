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

use Fusio\Adapter\Amqp\Connection\Amqp;
use Fusio\Adapter\V8\Tests\Action\V8Processor\V8ProcessorTestCase;
use Fusio\Engine\Model\Connection;

/**
 * AmqpTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class AmqpTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $connection = new Connection();
        $connection->setId(1);
        $connection->setName('amqp');
        $connection->setClass(Amqp::class);
        $connection->setConfig([
            'host'     => '127.0.0.1',
            'port'     => 5672,
            'user'     => 'guest',
            'password' => 'guest',
            'vhost'    => '/'
        ]);

        $this->getConnectionRepository()->add($connection);
    }

    public function providerHandler()
    {
        return [
            [$this->getBasicPublishCode(), 200, [], $this->getBasicPublishBody()]
        ];
    }

    protected function getBasicPublishCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("amqp");

var result = connection.basicPublish("test_queue", JSON.stringify({foo: "bar"}));

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getBasicPublishBody()
    {
        return <<<JSON
{
    "success": true,
    "result": null
}
JSON;
    }
}
