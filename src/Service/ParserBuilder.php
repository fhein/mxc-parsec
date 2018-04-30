<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\ServiceManager;
use Mxc\Parsec\Support\Uuid;

class ParserBuilder extends ServiceManager
{
    protected $abstractFactories = [
        ComplexParserFactory::class
    ];

    public function __construct(ParserManager $pm, array $options = [])
    {
        $this->services['parser_manager'] = $pm;
        $this->parserManager = $pm;
        parent::__construct();
    }

    public function setDefinitions($definitions)
    {
        $this->services['parser_definitions'] = $definitions;
        $this->getRootUuid();
        $this->setAllowOverride(true);
        $this->createParserMap($definitions);
    }

    protected function prepareOptions(array $options)
    {
        $dependencies = [];
        foreach ($options as $key => $option) {
            if (is_array($option)) {
                $options[$key] = $this->registerParser($option) ?? $this->prepareOptions($option);
            }
        }
        return $options;
    }

    public function getParser($name)
    {
        return $this->get($this->definitions[$name]);
    }

    public function getRule($name)
    {
        return $this->get($this->definitions[$this->rules[$name]]);
    }

    protected function registerParser(array $definition)
    {
        if (count($definition) !== 2) {
            return;
        }
        $parser = $definition[0];
        $options = $definition[1];
        if (is_string($parser)
            && is_array($options)
            && $this->parserManager->has($parser)
        ) {
            $options = $this->prepareOptions($options);

            $value = json_encode([$definition[0], $options]);
            $key = Uuid::v5($this->rootId, $value);
            $this->definitions[$key] = $value;
//            $this->factories[$key] = ParserJsonFactory::class;
            return $key;
        }
    }

    protected function createParserMap(array $options)
    {
        foreach ($options as $name => $definition) {
            $this->rules[$name] = $this->registerParser($definition);
        }
        $this->services['parser_definitions'] = $this->definitions;
        $this->setService('rules', $this->rules);
    }

    protected function getRootUuid()
    {
        $this->rootId = Uuid::v5(
            '00000000-0000-0000-0000-000000000000',
            'maxence business consulting gmbh'
        );
    }
}
