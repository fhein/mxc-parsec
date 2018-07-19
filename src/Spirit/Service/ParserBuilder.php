<?php

namespace Mxc\Parsec\Service;

use Zend\ServiceManager\ServiceManager;

class ParserBuilder extends ServiceManager
{
    protected $definitions;

    protected $abstractFactories = [
        JsonParserFactory::class
    ];

    public function __construct(ParserManager $pm, array $options = [])
    {
        $this->services['parser_manager'] = $pm;
        $this->parserManager = $pm;
        parent::__construct();
    }

    public function setDefinitions($definitions)
    {
        $this->setAllowOverride(true);
        foreach ($definitions as $definition) {
            $this->registerParser($definition);
        }
        $this->setAllowOverride(false);
    }

    public function getDefinitions()
    {
        return $this->definitions;
    }

    public function getParser($name)
    {
        return $this->get($this->definitions[$name]);
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
            // first value of $options is Blockly's block id
            $this->definitions[$options[0]] = $value;
            return $options[0];
        }
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
}
