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

use Fusio\Adapter\V8\Wrapper\Connection\Http\Response;
use GuzzleHttp\Client;
use PSX\V8\ObjectInterface;

/**
 * Http
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Http implements ObjectInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $connection;

    /**
     * @param \GuzzleHttp\Client $connection
     */
    public function __construct(Client $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @param string|null $body
     * @return Response
     */
    public function request($method, $uri, $headers = [], $body = null)
    {
        $options = [];

        if (!empty($headers)) {
            $options['headers'] = (array) $headers;
        }

        if (!empty($body)) {
            $options['body'] = (string) $body;
        }

        $response = $this->connection->request($method, $uri, $options);

        return new Response($response);
    }

    public function getProperties()
    {
        return [
            'request' => [$this, 'request'],
        ];
    }
}
