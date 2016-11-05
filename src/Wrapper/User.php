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

use Fusio\Engine\Model\UserInterface;
use PSX\V8\ObjectInterface;

/**
 * User
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class User implements ObjectInterface
{
    /**
     * @var \Fusio\Engine\Model\UserInterface
     */
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function isAnonymous()
    {
        return $this->user->isAnonymous();
    }

    public function getId()
    {
        return $this->user->getId();
    }

    public function getStatus()
    {
        return $this->user->getStatus();
    }

    public function getName()
    {
        return $this->user->getName();
    }

    public function getProperties()
    {
        return [
            'isAnonymous'  => [$this, 'isAnonymous'],
            'getId'        => [$this, 'getId'],
            'getStatus'    => [$this, 'getStatus'],
            'getName'      => [$this, 'getName'],
        ];
    }
}
