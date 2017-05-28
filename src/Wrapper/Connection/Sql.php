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

use Doctrine\DBAL\Connection;
use PSX\V8\ObjectInterface;

/**
 * Sql
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Sql implements ObjectInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, $params = [])
    {
        return $this->connection->fetchAll($sql, (array) $params);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAssoc($sql, $params = [])
    {
        return $this->connection->fetchAssoc($sql, (array) $params);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function fetchColumn($sql, $params = [])
    {
        return $this->connection->fetchColumn($sql, (array) $params);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     */
    public function executeUpdate($sql, $params = [])
    {
        return $this->connection->executeUpdate($sql, (array) $params);
    }

    public function getProperties()
    {
        return [
            'fetchAll' => [$this, 'fetchAll'],
            'fetchAssoc' => [$this, 'fetchAssoc'],
            'fetchColumn' => [$this, 'fetchColumn'],
            'executeUpdate' => [$this, 'executeUpdate'],
        ];
    }
}
