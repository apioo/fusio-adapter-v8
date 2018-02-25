<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2018 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace Fusio\Adapter\V8\Tests\Action;

use Fusio\Adapter\V8\Action\V8Processor;
use Fusio\Engine\Test\EngineTestCaseTrait;
use PSX\Http\Environment\HttpResponseInterface;
use PSX\Record\Record;

/**
 * V8ProcessorTestCase
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class V8ProcessorTestCase extends \PHPUnit_Framework_TestCase
{
    use EngineTestCaseTrait;

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @param string $code
     * @param integer $expectStatusCode
     * @param array $expectHeaders
     * @param string $expectBody
     * @dataProvider providerHandler
     */
    public function testHandle($code, $expectStatusCode, array $expectHeaders, $expectBody)
    {
        $action = $this->getActionFactory()->factory(V8Processor::class);

        // handle request
        $response = $action->handle(
            $this->getRequest(
                'GET', 
                ['foo' => 'bar'], 
                ['foo' => 'bar'], 
                ['Content-Type' => 'application/json'], 
                Record::fromArray(['foo' => 'bar'])
            ),
            $this->getParameters(['code' => $code]),
            $this->getContext()
        );

        $actual = json_encode($response->getBody());

        $this->assertInstanceOf(HttpResponseInterface::class, $response);
        $this->assertEquals($expectStatusCode, $response->getStatusCode());
        $this->assertEquals($expectHeaders, $response->getHeaders());
        $this->assertJsonStringEqualsJsonString($expectBody, $actual, $actual);
    }

    /**
     * @return array
     */
    abstract public function providerHandler();
}
