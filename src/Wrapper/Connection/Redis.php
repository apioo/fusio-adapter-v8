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

namespace Fusio\Adapter\V8\Wrapper\Connection;

use Predis\Client;
use PSX\V8\ObjectInterface;

/**
 * Redis
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Redis implements ObjectInterface
{
    /**
     * @var \Predis\Client
     */
    protected $connection;

    /**
     * @param \Predis\Client
     */
    public function __construct(Client $connection)
    {
        $this->connection = $connection;
    }

    public function get($key)
    {
        return $this->connection->get($key);
    }

    public function set($key, $value, $expiration = null)
    {
        return $this->connection->set($key, $value, $expiration);
    }

    public function delete($key)
    {
        return $this->connection->del((array) $key);
    }

    public function getProperties()
    {
        return [
            'get' => [$this, 'get'],
            'set' => [$this, 'set'],
            'delete' => [$this, 'delete'],
        ];
    }
}