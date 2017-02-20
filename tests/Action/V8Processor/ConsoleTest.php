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

namespace Fusio\Adapter\V8\Tests\Action\V8Processor;

use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;

/**
 * ConsoleTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ConsoleTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function providerHandler()
    {
        return [
            [$this->getLogCode(), 200, [], $this->getLogBody()],
            [$this->getInfoCode(), 200, [], $this->getInfoBody()],
            [$this->getWarnCode(), 200, [], $this->getWarnBody()],
            [$this->getErrorCode(), 200, [], $this->getErrorBody()],
        ];
    }

    protected function getLogCode()
    {
        return <<<JAVASCRIPT

var result = console.log("foo", "debug");

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getLogBody()
    {
        return <<<JSON
{
    "success": true,
    "result": null
}
JSON;
    }

    protected function getInfoCode()
    {
        return <<<JAVASCRIPT

var result = console.info("foo");

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getInfoBody()
    {
        return <<<JSON
{
    "success": true,
    "result": null
}
JSON;
    }

    protected function getWarnCode()
    {
        return <<<JAVASCRIPT

var result = console.warn("foo");

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getWarnBody()
    {
        return <<<JSON
{
    "success": true,
    "result": null
}
JSON;
    }

    protected function getErrorCode()
    {
        return <<<JAVASCRIPT

var result = console.error("foo");

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getErrorBody()
    {
        return <<<JSON
{
    "success": true,
    "result": null
}
JSON;
    }
}
