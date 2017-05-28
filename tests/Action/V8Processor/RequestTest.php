<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace Fusio\Adapter\V8\Tests\Action\V8Processor;

use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;

/**
 * RequestTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class RequestTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function providerHandler()
    {
        return [
            [$this->getRequestCode(), 200, [], $this->getRequestBody()]
        ];
    }

    protected function getRequestCode()
    {
        return <<<JAVASCRIPT

var result = {
    // request
    method: request.getMethod(),
    header: request.getHeader('Content-Type'),
    uriFragment: request.getUriFragment('foo'),
    uriFragments: request.getUriFragments(),
    parameter: request.getParameter('foo'),
    parameters: request.getParameters(),
    body: request.getBody()
};

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getRequestBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
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
        }
    }
}
JSON;
    }
}
