<?php

namespace Mxc\Parsec\Qi;

use IntlChar;
use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Exception\UnknownCastException;

abstract class Parser
{
    protected static $typeNames = null;

    const NUMBER_CAST = [
        'boolean' => 'boolval',
        'integer' => 'intval',
        'float'   => 'floatval',
        'double'  => 'doubleval',
    ];

    protected $domain;
    protected $attribute;
    protected $defaultType;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    protected function validate($expectedValue, $value, $attributeType)
    {
        if ($expectedValue === null || $expectedValue === $value) {
            $this->assignTo($value, $attributeType);
            return true;
        }
        return false;
    }

    abstract public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null);

    // you can set the input via any parser
    // the new source gets applied to ALL parsers
    public function setSource($source)
    {
        $this->domain->setSource($source);
        $this->attribute = null;
        return $this->domain->getInputIterator();
    }

    public function getAttribute()
    {
        $result = $this->attribute;
        $this->attribute = null;
        return $result;
    }

    // return simple class name w/o namespace
    public function what()
    {
        return substr(strrchr(get_class($this), '\\'), 1);
    }

    protected function getSubject()
    {
        return [];
    }

    protected function skipOver($iterator, $skipper = null)
    {
        // do not skip if we are not allowed to, or have no skipper, or have UnusedSkipper
        if ($skipper === null || $skipper instanceof UnusedSkipper) {
            return;
        }

        // let skipper parse as long as it succeeds
        while ($iterator->valid() && $skipper->doParse($iterator, null, null, null)) {
            /***/ ;
        }
    }

    protected function getType($value)
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    protected function castTo($targetType, $value)
    {
        if ($targetType === null
            || $targetType === 'unknown'
            || $targetType === $this->getType($value)) {
            return $value;
        }
        if (isset(self::NUMBER_CAST[$targetType])) {
            return (self::NUMBER_CAST[$targetType])($value);
        }
        if ($targetType === 'array') {
            return [ $value ];
        } elseif ($targetType === 'string') {
            if (is_null($value)
                || is_scalar($value)
                || (is_object($value) && method_exists($value, '__toString'))) {
                return strval($value);
            }
            if (is_array($value)) {
                $result = '';
                foreach ($value as $val) {
                    $result .= $this->castTo($val, $targetType);
                }
                return $result;
            }
        } elseif (class_exists($targetType)) {
            return new $targetType($value);
        }

        throw new UnknownCastException(
            sprintf(
                "%s: Don't now how to cast to %s.",
                $this->what(),
                var_export($targetType, true)
            )
        );
        return $value;
    }

    protected function assignTo($value, $attributeType)
    {
        // unused values can not be casted
        if ($value instanceof Unused) {
            return;
        }

        $attributeType = $attributeType ?? $this->defaultType;

        // float, double, int and boolean
        if (isset(self::NUMBER_CAST[$attributeType])) {
            $this->attribute = (self::NUMBER_CAST[$attributeType])($value);
            unset($this->typeTag);
            return;
        }

        // Both $attributeType and $defaultType are null
        if ($attributeType === null) {
            $this->attribute = $value;
            unset($this->typeTag);
            return;
        }

        // unused type
        if ($attributeType === 'unused') {
            $this->attribute = $this->domain->getUnused();
            return;
        }

        // string type
        // assigning means appending to string
        if ($attributeType === 'string') {
            // currently NULL or unused
            if (isset($this->typeTag)) {
                $this->attribute = '';
                unset($this->typeTag);
            }
            $this->attribute .= $value;
            return;
        }

        // array type
        // assigning means appending to array
        if ($attributeType === 'array') {
            // currently NULL or unused
            if (isset($this->typeTag)) {
                $this->attribute = [];
                unset($this->typeTag);
            }
            $this->attribute [] = $value;
            return;
        }

        // arbitrary class which is $attributeType
        // constructable
        if (class_exists($attributeType)) {
            $this->attribute = new $attributeType($value);
            unset($this->typeTag);
            return;
        }

        throw new InvalidArgumentException(
            sprintf('%s: Unknown attribute type %s', $this->what(), $attributeType)
        );
    }

    public function __debugInfo()
    {
        return [
            'attribute' => $this->attribute ?? 'n/a',
            'defaultType' => $this->defaultType ?? 'n/a',
        ];
    }
}
