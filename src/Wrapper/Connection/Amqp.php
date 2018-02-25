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

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PSX\V8\ObjectInterface;

/**
 * Amqp
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Amqp implements ObjectInterface
{
    /**
     * @var \PhpAmqpLib\Connection\AMQPStreamConnection
     */
    protected $connection;

    /**
     * @param \PhpAmqpLib\Connection\AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $queue
     * @param string $body
     * @param array $properties
     */
    public function basicPublish($queue, $body, $properties = [])
    {
        $properties = (array) $properties;
        if (!isset($properties['delivery_mode'])) {
            $properties['delivery_mode'] = 2;
        }

        $message = new AMQPMessage($body, $properties);

        $channel = $this->connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->basic_publish($message, '', $queue);
        $channel->close();
    }

    public function getProperties()
    {
        return [
            'basicPublish' => [$this, 'basicPublish'],
        ];
    }
}
