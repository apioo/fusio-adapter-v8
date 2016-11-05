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

use Doctrine\DBAL\Connection as DBALConnection;
use Fusio\Engine\ConnectorInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ProcessorInterface;
use Fusio\Engine\Request as EngineRequest;
use PSX\Data\Record\Transformer;
use PSX\Uri\Uri;
use PSX\V8\ObjectInterface;
use PSX\Http\Request as HttpRequest;

/**
 * Processor
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Processor implements ObjectInterface
{
    /**
     * @var \Fusio\Engine\ProcessorInterface
     */
    protected $processor;

    /**
     * @var \Fusio\Engine\ContextInterface
     */
    protected $context;

    public function __construct(ProcessorInterface $processor, ContextInterface $context)
    {
        $this->processor = $processor;
        $this->context   = $context;
    }

    public function execute($actionId, $body, $uriFragments = [], $parameters = [], $method = 'GET')
    {
        $request  = $this->buildRequest($body, $uriFragments, $parameters, $method);
        $response = $this->processor->execute($actionId, $request, $this->context);
        $body     = $response->getBody();

        if (empty($body)) {
            return new \stdClass();
        } else {
            return $body;
        }
    }

    public function getProperties()
    {
        return [
            'execute' => [$this, 'execute'],
        ];
    }

    protected function buildRequest($body, $uriFragments, $parameters, $method)
    {
        $httpRequest = new HttpRequest(
            new Uri('/'),
            $method
        );

        return new EngineRequest(
            $httpRequest,
            !empty($uriFragments) ? (array) $uriFragments : [],
            !empty($parameters) ? (array) $parameters : [],
            Transformer::toRecord($body)
        );
    }
}
