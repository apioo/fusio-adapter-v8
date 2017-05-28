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

namespace Fusio\Adapter\V8\Wrapper\Connection;

use PSX\V8\ObjectInterface;

/**
 * Soap
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Soap implements ObjectInterface
{
    /**
     * @var \SoapClient
     */
    protected $connection;

    /**
     * @param \SoapClient $connection
     */
    public function __construct(\SoapClient $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $functionName
     * @param array $arguments
     * @return mixed
     */
    public function call($functionName, $arguments)
    {
        if (empty($arguments)) {
            $arguments = [];
        } else {
            $arguments = (array) $arguments;
        }

        return $this->connection->__soapCall($functionName, $arguments);
    }

    public function getProperties()
    {
        return [
            'call' => [$this, 'call'],
        ];
    }
}
