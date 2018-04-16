<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Exception\UnknownCastException;

abstract class Parser
{
    protected $domain;
    protected $attribute;
    protected $defaultType;
    protected $name = 'no name';

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    abstract public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null);

    /**
     * Return and reset the current attribute value.
     *
     * @return mixed    attribute value
     */
    public function getAttribute()
    {
        $result = $this->attribute;
        $this->attribute = null;
        return $result;
    }

    // you can set the input via any parser
    // the new source gets applied to ALL parsers
    public function setSource($source)
    {
        return $this->domain->setSource($source);
    }

    protected function validate($expectedValue, $value, $attributeType)
    {
        if ($expectedValue === null || $expectedValue === $value) {
            $this->assignTo($value, $attributeType);
            return true;
        }
        return false;
    }

    protected function validateChar($expectedValue, $value, $attributeType)
    {
        $iterator = $this->domain->getInputIterator();
        if ($expectedValue === null || $iterator->compareChar($expectedValue, $value)) {
            $this->assignTo($value, $attributeType);
            $iterator->next();
            return true;
        }
        return false;
    }

    protected function skipOver($iterator, $skipper = null)
    {
        if ($skipper === null || $skipper instanceof UnusedSkipper) {
            return;
        }
        while ($iterator->valid() && $skipper->doParse($iterator, null, null, null)) {
            /***/ ;
        }
    }

    protected function castTo($targetType, $value)
    {
        $type = is_object($value) ? get_class($value) : gettype($value);
        if ($targetType === $type || $value instanceof Unused) {
            return $value;
        }

        switch ($targetType) {
            case null:
                return $value;

            case 'boolean':
                return boolval($value);

            case 'integer':
                return intval($value);

            case 'float':
                return floatval($value);

            case 'double':
                return doubleval($value);

            case 'unused':
                return $this->domain->getUnused();
                return;

            case 'array':
                return [ $value ];

            case 'string':
                if (is_null($value)
                    || is_scalar($value)
                    || is_object($value) && method_exists($value, '__toString')
                ) {
                    return strval($value);
                }
                if (is_array($value)) {
                    $result = '';
                    foreach ($value as $val) {
                        $result .= $this->castTo($val, $targetType);
                    }
                    return $result;
                }
                break;

            default:
                if (class_exists($targetType)) {
                    return new $targetType($value);
                }
        }

        throw new UnknownCastException(
            sprintf(
                "%s: Don't know how to cast from %s to %s.",
                $this->what(),
                var_export($type, true),
                var_export($targetType, true)
            )
        );
    }

    protected function assignTo($value, $attributeType)
    {
        // unused values can not be casted
        if ($value instanceof Unused) {
            return;
        }

        $attributeType = $attributeType ?? $this->defaultType;

        switch ($attributeType) {
            case null:
                $this->attribute = $value;
                return;

            case 'boolean':
                $this->attribute = boolval($value);
                return;

            case 'integer':
                $this->attribute = intval($value);
                return;

            case 'float':
                $this->attribute = floatval($value);
                return;

            case 'double':
                $this->attribute = doubleval($value);
                return;

            case 'unused':
                $this->attribute = $this->domain->getUnused();
                return;

            case 'string':
                $this->attribute .= $value;
                return;

            case 'array':
                $this->attribute [] = $value;
                return;

            default:
                if (class_exists($attributeType)) {
                    $this->attribute = new $attributeType($value);
                    return;
                }
        }

        throw new InvalidArgumentException(
            sprintf('%s: Unknown attribute type %s', $this->what(), $attributeType)
        );
    }

    // return simple class name w/o namespace
    public function what()
    {
        return substr(strrchr(get_class($this), '\\'), 1);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __debugInfo()
    {
        return [
            'attribute' => $this->attribute ?? 'n/a',
            'defaultType' => $this->defaultType ?? 'n/a',
        ];
    }
}
