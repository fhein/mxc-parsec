<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Qi\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Exception\UnknownCastException;
use Mxc\Parsec\Attribute\Optional;
use Mxc\Parsec\Attribute\Unused;
use Mxc\Parsec\Support\NamedObject;

abstract class Parser extends NamedObject
{
    protected $domain;
    protected $attribute;
    protected $iterator;

    public function __construct(Domain $domain)
    {
        parent::__construct();
        $this->domain = $domain;
        $this->iterator = $domain->getInputIterator();
    }

    abstract public function parse($skipper = null);

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
        $this->iterator->setData($source);
        return $this->iterator;
    }

    protected function validate($expectedValue, $value)
    {
        if ($expectedValue === null || $expectedValue === $value) {
            $this->assignTo($value, $attributeType);
            return true;
        }
        return false;
    }

    protected function validateChar($expectedValue, $value, $attributeType)
    {
        if ($expectedValue === null || $this->iterator->compareChar($expectedValue, $value)) {
            $this->assignTo($value, $attributeType);
            $this->iterator->next();
            return true;
        }
        return false;
    }

    protected function skipOver($skipper = null)
    {
        if ($skipper === null || $skipper instanceof UnusedSkipper) {
            return;
        }
        while ($this->iterator->valid() && $skipper->doParse(null)) {
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
                return new Unused();

            case 'array':
                return [ $value ];

            case 'string':
                if (is_null($value)
                    || is_scalar($value)
                    || is_object($value) && method_exists($value, '__toString')
                ) {
                    return strval($value);
                }
                break;

            default:
                if (is_object($targetType)) {
                    print(get_class($targetType)."\n");
                    die();
                }
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

    public function assignUnusedOptional($attributeType = null)
    {
        $this->assignTo(new Optional(new Unused()), $attributeType);
    }

    protected function assignTo($value, $attributeType)
    {
        $attributeType = $attributeType ?? $this->defaultType ?? null;

        // unused values can not be casted
        if ($value instanceof Unused) {
            if ($attributeType === 'optional') {
                $this->attribute = new Optional($value);
            }
            return;
        }

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
                $this->attribute = new Unused();
                return;

            case 'string':
                if (is_array($value)) {
                    foreach ($value as $entry) {
                        $this->assignTo($entry, 'string');
                    }
                    return;
                }
                $this->attribute .= $value;
                return;

            case 'array':
                if (! is_array($value)) {
                    $this->attribute [] = $value;
                    return;
                }
                $this->attribute = $value;
                return;

            case 'optional':
                if ($this->attribute instanceof Optional) {
                    $this->attribute->set($value);
                    return;
                }
                $this->attribute = new Optional($value);
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
        return $this->shortClassName() . '[' . $this->name . ']';
    }

    public function shortClassName()
    {
        return substr(strrchr(get_class($this), '\\'), 1);
    }

    public function __debugInfo()
    {
        return [
            'name'      => $this->name ?? 'n/a',
            'attribute' => $this->attribute ?? 'n/a',
        ];
    }
}
