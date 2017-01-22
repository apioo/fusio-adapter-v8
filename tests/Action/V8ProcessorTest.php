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

    protected function setUp()
    {
        parent::setUp();

        $action = new Action();
        $action->setId(1);
        $action->setName('baz');
        $action->setClass(CallbackAction::class);
        $action->setConfig([
            'callback' => function(Response\FactoryInterface $response){
                return $response->build(200, [], ['baz' => 'bar']);
            },
        ]);

        $this->getActionRepository()->add($action);
    }

    public function testHandle()
    {
        $script = <<<JAVASCRIPT

// get connection and query
var connection = connector.get('sql');
var result = connection.fetchAll('SELECT * FROM app_news WHERE id = :id', {id: 1});

// call other action
var cached = false;
var called;
if (!cache.has('bar')) {
    called = processor.execute('baz', {
        bar: 'foo'
    });
    cache.set('bar', called);
} else {
    cached = true;
    called = cache.get('bar');
}

// log request
console.log('An incoming request');

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
    appParameter: context.getApp().getParameter('foo'),
    appAppKey: context.getApp().getAppKey(),

    // user
    userIsAnonymous: context.getUser().isAnonymous(),
    userId: context.getUser().getId(),
    userStatus: context.getUser().getStatus(),
    userName: context.getUser().getName(),

    result: result,
    called: called,
    cached: cached
});

JAVASCRIPT;

        $parameters = $this->getParameters([
            'code' => $script,
        ]);
 
        $body   = Record::fromArray(['foo' => 'bar']);
        $action = $this->getActionFactory()->factory(V8Processor::class);

        for ($i = 0; $i < 6; $i++) {
            // handle request
            $response = $action->handle(
                $this->getRequest('GET', ['foo' => 'bar'], ['foo' => 'bar'], ['Content-Type' => 'application/json'], $body), 
                $parameters, 
                $this->getContext()
            );

            $cached = $i === 0 ? 'false' : 'true';
            $actual = json_encode($response->getBody());
            $expect = <<<JSON
{
    "method": "GET",
    "header": "application/json",
    "uriFragment": "bar",
    "uriFragments": {
        "foo": "bar"
    },
    "parameter": "bar",
    "parameters": {
        "foo": "bar"
    },
    "body": {
        "foo": "bar"
    },
    "routeId": 34,
    "appIsAnonymous": false,
    "appId": 3,
    "appUserId": 2,
    "appStatus": 1,
    "appName": "Foo-App",
    "appUrl": "http://google.com",
    "appScopes": [
        "foo",
        "bar"
    ],
    "appHasScope": true,
    "appParameter": "bar",
    "appAppKey": "5347307d-d801-4075-9aaa-a21a29a448c5",
    "userIsAnonymous": false,
    "userId": 2,
    "userStatus": 0,
    "userName": "Consumer",
    "result": [
        {
            "content": "bar",
            "date": "2015-02-27 19:59:15",
            "id": "1",
            "tags": "[\"foo\",\"bar\"]",
            "title": "foo"
        }
    ],
    "called": {
        "baz": "bar"
    },
    "cached": {$cached}
}
JSON;

            $this->assertInstanceOf(ResponseInterface::class, $response);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals(['x-foo' => 'bar'], $response->getHeaders());
            $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
        }
    }

    /**
     * @dataProvider httpProvider
     */
    public function testHandleHttp($connection)
    {
        $script = <<<JAVASCRIPT

// get http connection
var connection = connector.get('{$connection}');

var result = connection.request('GET', '/get?foo=bar', {"X-Custom-Header": "foo"});
var getData = JSON.parse(result.getBody());
getData = cleanResponse(getData);

var result = connection.request('POST', '/post', {"Content-Type": "application/json"}, JSON.stringify({foo: "bar"}));
var postData = JSON.parse(result.getBody());
postData = cleanResponse(postData);

response.setStatusCode(200);
response.setBody({
    get: getData,
    post: postData
});

function cleanResponse(data) {
    delete data['origin'];
    delete data['headers']['User-Agent'];
    return data;
}

JAVASCRIPT;

        $parameters = $this->getParameters([
            'code' => $script,
        ]);

        $body   = Record::fromArray(['foo' => 'bar']);
        $action = $this->getActionFactory()->factory(V8Processor::class);

        // handle request
        $response = $action->handle(
            $this->getRequest('GET', ['foo' => 'bar'], ['foo' => 'bar'], ['Content-Type' => 'application/json'], $body),
            $parameters,
            $this->getContext()
        );

        $actual = json_encode($response->getBody());
        $expect = <<<JSON
{
  "get": {
    "args": {
      "foo": "bar"
    },
    "headers": {
      "Host": "httpbin.org",
      "X-Custom-Header": "foo"
    },
    "url": "{$connection}://httpbin.org/get?foo=bar"
  },
  "post": {
    "args": {},
    "data": "{\"foo\":\"bar\"}",
    "files": {},
    "form": {},
    "headers": {
      "Content-Length": "13",
      "Host": "httpbin.org",
      "Content-Type": "application/json"
    },
    "json": {
      "foo": "bar"
    },
    "url": "{$connection}://httpbin.org/post"
  }
}
JSON;

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function httpProvider()
    {
        return [
            ['http'],
            ['https'],
        ];
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
