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
 * ContextTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ContextTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function providerHandler()
    {
        return [
            [$this->getContextCode(), 200, [], $this->getContextBody()]
        ];
    }

    protected function getContextCode()
    {
        return <<<JAVASCRIPT

var result = {
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
    userName: context.getUser().getName()
};

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getContextBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
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
    }
}
JSON;
    }
}
