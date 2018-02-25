<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2018 Christoph Kappestein <christoph.kappestein@gmail.com>
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
 * Memcache
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Memcache implements ObjectInterface
{
    /**
     * @var \Memcached
     */
    protected $connection;

    /**
     * @param \Memcached
     */
    public function __construct(\Memcached $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->connection->get($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @param integer|null $expiration
     * @return bool
     */
    public function set($key, $value, $expiration = null)
    {
        return $this->connection->set($key, $value, $expiration);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        return $this->connection->delete($key);
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
