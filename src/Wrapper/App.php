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

namespace Fusio\Adapter\V8\Wrapper;

use Fusio\Engine\Model\AppInterface;
use PSX\V8\ObjectInterface;

/**
 * App
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class App implements ObjectInterface
{
    /**
     * @var \Fusio\Engine\Model\AppInterface
     */
    protected $app;

    public function __construct(AppInterface $app)
    {
        $this->app = $app;
    }

    public function isAnonymous()
    {
        return $this->app->isAnonymous();
    }

    public function getId()
    {
        return $this->app->getId();
    }

    public function getUserId()
    {
        return $this->app->getUserId();
    }

    public function getStatus()
    {
        return $this->app->getStatus();
    }

    public function getName()
    {
        return $this->app->getName();
    }

    public function getUrl()
    {
        return $this->app->getUrl();
    }

    public function getAppKey()
    {
        return $this->app->getAppKey();
    }

    public function getScopes()
    {
        return $this->app->getScopes();
    }

    public function hasScope($name)
    {
        return $this->app->hasScope($name);
    }

    public function getParameter($name)
    {
        return $this->app->getParameter($name);
    }

    public function getProperties()
    {
        return [
            'isAnonymous'  => [$this, 'isAnonymous'],
            'getId'        => [$this, 'getId'],
            'getUserId'    => [$this, 'getUserId'],
            'getStatus'    => [$this, 'getStatus'],
            'getName'      => [$this, 'getName'],
            'getUrl'       => [$this, 'getUrl'],
            'getAppKey'    => [$this, 'getAppKey'],
            'getScopes'    => [$this, 'getScopes'],
            'hasScope'     => [$this, 'hasScope'],
            'getParameter' => [$this, 'getParameter'],
        ];
    }
}
