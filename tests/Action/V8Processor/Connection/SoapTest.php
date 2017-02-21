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

use Fusio\Adapter\Soap\Connection\Soap;
use Fusio\Adapter\V8\Tests\Action\V8ProcessorTestCase;
use Fusio\Engine\Model\Connection;

/**
 * SoapTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SoapTest extends V8ProcessorTestCase
{
    protected function setUp()
    {
        parent::setUp();
        
        $connection = new Connection();
        $connection->setId(1);
        $connection->setName('soap');
        $connection->setClass(Soap::class);
        $connection->setConfig([
            'wsdl'     => 'http://www.webservicex.net/geoipservice.asmx?WSDL',
            'location' => '',
            'uri'      => '',
            'version'  => SOAP_1_2,
            'username' => '',
            'password' => '',
        ]);

        $this->getConnectionRepository()->add($connection);
    }

    public function providerHandler()
    {
        return [
            [$this->getSoapCode(), 200, [], $this->getSoapBody()],
        ];
    }

    protected function getSoapCode()
    {
        return <<<JAVASCRIPT

var connection = connector.get("soap");

var result = connection.call("GetGeoIP", [{
    IPAddress: "8.8.8.8"
}]);

response.setStatusCode(200);
response.setBody({
    success: true,
    result: result
});

JAVASCRIPT;
    }

    protected function getSoapBody()
    {
        return <<<JSON
{
    "success": true,
    "result": {
        "GetGeoIPResult": {
            "ReturnCode": 1,
            "IP": "8.8.8.8",
            "ReturnCodeDetails": "Success",
            "CountryName": "United States",
            "CountryCode": "USA"
        }
    }
}
JSON;
    }
}
