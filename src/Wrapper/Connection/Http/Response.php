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

namespace Fusio\Adapter\V8\Wrapper\Connection\Http;

use Psr\Http\Message\ResponseInterface;
use PSX\V8\ObjectInterface;

/**
 * Response
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Response implements ObjectInterface
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $result  = [];
        $headers = $this->response->getHeaders();
        foreach ($headers as $name => $values) {
            $result[$name] = implode(', ', $values);
        }

        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name)
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return (string) $this->response->getBody();
    }

    public function getProperties()
    {
        return [
            'getStatusCode' => [$this, 'getStatusCode'],
            'getHeaders'    => [$this, 'getHeaders'],
            'getHeader'     => [$this, 'getHeader'],
            'getBody'       => [$this, 'getBody'],
        ];
    }
}
