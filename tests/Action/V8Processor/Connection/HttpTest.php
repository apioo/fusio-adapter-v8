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

use Fusio\Adapter\Http\Connection\Http;
use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;
use Fusio\Engine\Model\Connection;

/**
 * HttpTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class HttpTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $connection = new Connection();
        $connection->setId(1);
        $connection->setName('http');
        $connection->setClass(Http::class);
        $connection->setConfig([
            'url'      => 'http://httpbin.org/',
            'username' => '',
            'password' => '',
            'proxy'    => '',
        ]);

        $this->getConnectionRepository()->add($connection);

        $connection = new Connection();
        $connection->setId(2);
        $connection->setName('https');
        $connection->setClass(Http::class);
        $connection->setConfig([
            'url'      => 'https://httpbin.org/',
            'username' => '',
            'password' => '',
            'proxy'    => '',
        ]);

        $this->getConnectionRepository()->add($connection);
    }

    public function providerHandler()
    {
        return [
            [$this->getHttpCode('http'), 200, [], $this->getHttpBody('http')],
            [$this->getHttpCode('https'), 200, [], $this->getHttpBody('https')],
        ];
    }

    protected function getHttpCode($scheme)
    {
        return <<<JAVASCRIPT

// get http connection
var connection = connector.get('{$scheme}');

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
    }

    protected function getHttpBody($scheme)
    {
        return <<<JSON
{
  "get": {
    "args": {
      "foo": "bar"
    },
    "headers": {
      "Host": "httpbin.org",
      "X-Custom-Header": "foo",
      "Connection": "close"
    },
    "url": "{$scheme}://httpbin.org/get?foo=bar"
  },
  "post": {
    "args": {},
    "data": "{\"foo\":\"bar\"}",
    "files": {},
    "form": {},
    "headers": {
      "Content-Length": "13",
      "Host": "httpbin.org",
      "Content-Type": "application/json",
      "Connection": "close"
    },
    "json": {
      "foo": "bar"
    },
    "url": "{$scheme}://httpbin.org/post"
  }
}
JSON;
    }
}
