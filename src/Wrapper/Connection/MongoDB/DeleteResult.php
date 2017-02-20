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

namespace Fusio\Adapter\V8\Wrapper\Connection\MongoDB;

use PSX\V8\ObjectInterface;

/**
 * DeleteResult
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class DeleteResult implements ObjectInterface
{
    /**
     * @var \MongoDB\DeleteResult
     */
    protected $result;

    public function __construct(\MongoDB\DeleteResult $result)
    {
        $this->result = $result;
    }

    public function getDeletedCount()
    {
        return $this->result->getDeletedCount();
    }

    public function isAcknowledged()
    {
        return $this->result->isAcknowledged();
    }

    public function getProperties()
    {
        return [
            'getDeletedCount' => [$this, 'getDeletedCount'],
            'isAcknowledged' => [$this, 'isAcknowledged'],
        ];
    }
}
