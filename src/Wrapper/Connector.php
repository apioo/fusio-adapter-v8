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

namespace Fusio\Adapter\V8\Wrapper;

use Doctrine\DBAL\Connection as DBALConnection;
use Fusio\Engine\ConnectorInterface;
use MongoDB\Database;
use Pheanstalk\Pheanstalk;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PSX\V8\ObjectInterface;

/**
 * Connector
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Connector implements ObjectInterface
{
    /**
     * @var \Fusio\Engine\ConnectorInterface
     */
    protected $connector;

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    public function get($name)
    {
        $connection = $this->connector->getConnection($name);

        if ($connection instanceof AMQPStreamConnection) {
            return new Connection\Amqp($connection);
        } elseif ($connection instanceof Pheanstalk) {
            return new Connection\Beanstalk($connection);
        } elseif ($connection instanceof DBALConnection) {
            return new Connection\DBAL($connection);
        } elseif ($connection instanceof \Memcached) {
            return new Connection\Memcache($connection);
        } elseif ($connection instanceof Database) {
            return new Connection\MongoDB($connection);
        } else {
            return null;
        }
    }

    public function getProperties()
    {
        return [
            'get' => [$this, 'get'],
        ];
    }
}
