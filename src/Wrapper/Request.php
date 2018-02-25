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

use Fusio\Engine\RequestInterface;
use PSX\Data\Record\Transformer;
use PSX\V8\ObjectInterface;

/**
 * Request
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Request implements ObjectInterface
{
    /**
     * @var \Fusio\Engine\RequestInterface
     */
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function getHeader($name)
    {
        return $this->request->getHeader($name);
    }

    public function getUriFragment($name)
    {
        return $this->request->getUriFragment($name);
    }

    public function getUriFragments()
    {
        return $this->request->getUriFragments()->toArray();
    }

    public function getParameter($name)
    {
        return $this->request->getParameter($name);
    }

    public function getParameters()
    {
        return $this->request->getParameters()->toArray();
    }

    public function getBody()
    {
        return Transformer::toStdClass($this->request->getBody());
    }

    public function getProperties()
    {
        return [
            'getMethod'       => [$this, 'getMethod'],
            'getHeader'       => [$this, 'getHeader'],
            'getUriFragment'  => [$this, 'getUriFragment'],
            'getUriFragments' => [$this, 'getUriFragments'],
            'getParameter'    => [$this, 'getParameter'],
            'getParameters'   => [$this, 'getParameters'],
            'getBody'         => [$this, 'getBody'],
        ];
    }
}
