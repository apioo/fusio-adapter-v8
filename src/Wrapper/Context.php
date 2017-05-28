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

namespace Fusio\Adapter\V8\Wrapper;

use Fusio\Engine\ContextInterface;
use PSX\V8\ObjectInterface;

/**
 * Context
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Context implements ObjectInterface
{
    /**
     * @var \Fusio\Engine\ContextInterface
     */
    protected $context;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var User
     */
    protected $user;

    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
        $this->app     = new App($context->getApp());
        $this->user    = new User($context->getUser());
    }

    public function getRouteId()
    {
        return $this->context->getRouteId();
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getProperties()
    {
        return [
            'getRouteId' => [$this, 'getRouteId'],
            'getApp'     => [$this, 'getApp'],
            'getUser'    => [$this, 'getUser'],
        ];
    }
}
