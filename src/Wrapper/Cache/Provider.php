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

namespace Fusio\Adapter\V8\Wrapper\Cache;

use Fusio\Engine\Cache\ProviderInterface;
use PSX\V8\ObjectInterface;

/**
 * Provider
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Provider implements ObjectInterface
{
    /**
     * @var \Fusio\Engine\Cache\ProviderInterface
     */
    protected $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function get($id)
    {
        return $this->provider->fetch($id);
    }

    public function has($id)
    {
        return $this->provider->contains($id);
    }

    public function set($id, $data, $lifeTime = 0)
    {
        return $this->provider->save($id, $data, $lifeTime);
    }

    public function delete($id)
    {
        return $this->provider->delete($id);
    }

    public function getProperties()
    {
        return [
            'get'    => [$this, 'get'],
            'has'    => [$this, 'has'],
            'set'    => [$this, 'set'],
            'delete' => [$this, 'delete'],
        ];
    }
}
