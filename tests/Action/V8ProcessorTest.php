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

namespace Fusio\Adapter\V8\Tests\Action;

use Fusio\Adapter\V8\Action\V8Processor;
use Fusio\Adapter\V8\Tests\DbTestCase;
use Fusio\Engine\Form\Builder;
use Fusio\Engine\Form\Container;
use Fusio\Engine\Model\Action;
use Fusio\Engine\Model\Connection;
use Fusio\Engine\Response;
use Fusio\Engine\ResponseInterface;
use Fusio\Engine\Test\CallbackAction;
use Fusio\Engine\Test\CallbackConnection;
use Fusio\Engine\Test\EngineTestCaseTrait;
use PSX\Framework\Test\Environment;
use PSX\Record\Record;

/**
 * V8ProcessorTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class V8ProcessorTest extends DbTestCase
{
    use EngineTestCaseTrait;

    public function testHandle()
    {
        $script = <<<JAVASCRIPT

var connection = connector.get('foo');
var result = connection.fetchAll('SELECT * FROM app_news');

response.setStatusCode(200);
response.setHeaders({
    'X-Foo': 'bar'
});
response.setBody({
    // request
    method: request.getMethod(),
    header: request.getHeader('Content-Type'),
    uriFragment: request.getUriFragment('foo'),
    uriFragments: request.getUriFragments(),
    parameter: request.getParameter('foo'),
    parameters: request.getParameters(),
    body: request.getBody(),

    // context
    routeId: context.getRouteId(),

    // app
    appIsAnonymous: context.getApp().isAnonymous(),
    appId: context.getApp().getId(),
    appUserId: context.getApp().getUserId(),
    appStatus: context.getApp().getStatus(),
    appName: context.getApp().getName(),
    appUrl: context.getApp().getUrl(),
    appScopes: context.getApp().getScopes(),
    appHasScope: context.getApp().hasScope('foo'),
    appParameters: context.getApp().getParameters(),
    appAppKey: context.getApp().getAppKey(),

    // user
    userIsAnonymous: context.getUser().isAnonymous(),
    userId: context.getUser().getId(),
    userStatus: context.getUser().getStatus(),
    userName: context.getUser().getName(),

    result: result
});

JAVASCRIPT;

        $parameters = $this->getParameters([
            'code' => $script,
        ]);
 
        $body     = Record::fromArray(['foo' => 'bar']);
        $action   = $this->getActionFactory()->factory(V8Processor::class);
        $response = $action->handle($this->getRequest('GET', ['foo' => 'bar'], ['foo' => 'bar'], ['Content-Type' => 'application/json'], $body), $parameters, $this->getContext());

        $actual = json_encode($response->getBody());
        $expect = <<<JSON
{
}
JSON;

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGetForm()
    {
        $action  = $this->getActionFactory()->factory(V8Processor::class);
        $builder = new Builder();
        $factory = $this->getFormElementFactory();

        $action->configure($builder, $factory);

        $this->assertInstanceOf(Container::class, $builder->getForm());
    }
}
