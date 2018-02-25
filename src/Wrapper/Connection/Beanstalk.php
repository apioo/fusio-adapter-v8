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

use Pheanstalk\Pheanstalk;
use PSX\V8\ObjectInterface;

/**
 * Beanstalk
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Beanstalk implements ObjectInterface
{
    /**
     * @var \Pheanstalk\Pheanstalk
     */
    protected $connection;

    /**
     * @param \Pheanstalk\Pheanstalk $connection
     */
    public function __construct(Pheanstalk $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $tube
     * @param string $data
     * @return integer
     */
    public function putInTube($tube, $data)
    {
        return $this->connection->putInTube($tube, $data);
    }

    public function getProperties()
    {
        return [
            'putInTube' => [$this, 'putInTube'],
        ];
    }
}
