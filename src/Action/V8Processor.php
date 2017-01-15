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

namespace Fusio\Adapter\V8\Action;

use Fusio\Adapter\V8\Exception\ScriptException;
use Fusio\Adapter\V8\Wrapper;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\V8\Environment;
use V8\Exceptions\TryCatchException;

/**
 * V8Processor
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class V8Processor extends ActionAbstract
{
    public function getName()
    {
        return 'V8-Processor';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        if (!class_exists('V8\Context')) {
            throw new \RuntimeException('It looks like the PHP V8 extension is not installed. Please take a look at https://github.com/pinepain/php-v8');
        }

        $response = new Wrapper\Response();
        $environment = new Environment();
        $environment->set('request', new Wrapper\Request($request));
        $environment->set('response', $response);
        $environment->set('context', new Wrapper\Context($context));
        $environment->set('connector', new Wrapper\Connector($this->connector));
        $environment->set('processor', new Wrapper\Processor($this->processor, $context));
        $environment->set('console', new Wrapper\Console($this->logger));
        $environment->set('cache', new Wrapper\Cache($this->cache));

        try {
            $environment->run($configuration->get('code'));
        } catch (TryCatchException $e) {
            // if an error occurred inside a php function which was called from the js context
            $previous = $e->getPrevious();
            if ($previous instanceof \Exception) {
                throw $previous;
            }

            // js errors
            $tryCatch = $e->GetTryCatch();
            if ($tryCatch->Message() !== null) {
                $message = $tryCatch->Message();
                throw new ScriptException($message->Get() . ' on line ' . $message->GetLineNumber());
            }

            throw $e;
        }

        return $this->response->build(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody()
        );
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newTextArea('code', 'Code', 'javascript', 'Javascript code which handles the request and returns the response. Click <a ng-click="help.showDialog(\'help/action/v8.md\')">here</a> for more information about the available functions.'));
    }
}
