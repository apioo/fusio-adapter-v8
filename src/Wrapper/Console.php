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

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use PSX\V8\ObjectInterface;

/**
 * Console
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Console implements ObjectInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function log($message, $level = Logger::DEBUG)
    {
        $this->logger->log($level, $message);
    }

    public function info($message)
    {
        $this->logger->log(Logger::INFO, $message);
    }

    public function warn($message)
    {
        $this->logger->log(Logger::WARNING, $message);
    }

    public function error($message)
    {
        $this->logger->log(Logger::ERROR, $message);
    }

    public function getProperties()
    {
        return [
            'log'   => [$this, 'log'],
            'info'  => [$this, 'info'],
            'error' => [$this, 'error'],
            'warn'  => [$this, 'warn'],
        ];
    }
}
