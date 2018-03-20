<?php

namespace Mxc\Parsec\Qi;

use Mxc\Parsec\Domain;
use Mxc\Parsec\Exception\InvalidArgumentException;
use Mxc\Parsec\Exception\UnknownCastException;

abstract class Parser
{

    const TT_UNUSED     = 0x0001;
    const TT_NULL       = 0x0002;
    const TT_BOOLEAN    = 0x0004;
    const TT_INGEGER    = 0x0008;
    const TT_FLOAT      = 0x0010;
    const TT_DOUBLE     = 0x0020;
    const TT_STRING     = 0x0040;
    const TT_ARRAY      = 0x0080;
    const TT_OBJECT     = 0x0100;
    const TT_UNKNOWN    = 0x0200;
    const TT_DEFAULT    = 0x0400;
    const TT_RESOURCE   = 0x0800;   // just to be complete, not used

    const TYPE_TAG =
    [
        self::TT_UNUSED => 'unused',
        self::TT_NULL   => 'NULL',
    ];

    const NO_CAST =
    [
        'unused' => 1,
        'NULL'   => 1,
    ];

    const TYPES = [
        'unused'        => self::TT_UNUSED,
        'NULL'          => self::TT_NULL,
        'boolean'       => self::TT_BOOLEAN,
        'integer'       => self::TT_INGEGER,
        'float'         => self::TT_FLOAT,
        'double'        => self::TT_DOUBLE,
        'string'        => self::TT_STRING,
        'array'         => self::TT_ARRAY,
        'object'        => self::TT_OBJECT,
        'unknown type'  => self::TT_UNKNOWN,
        'default'       => self::TT_DEFAULT,
    ];

    protected static $typeNames = null;

    const NUMBER_CAST = [
        'boolean' => 'boolval',
        'integer' => 'intval',
        'float'   => 'floatval',
        'double'  => 'doubleval',
    ];

    protected $domain;
    protected $attribute;
    protected $typeTag = self::TT_UNUSED;
    protected $defaultType;
    protected $skip = false;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
        // @todo: Better done lazily?
        if (self::$typeNames === null) {
            self::$typeNames = array_flip(self::TYPES);
        }
    }

    abstract public function parse($iterator, $expectedValue = null, $attributeType = null, $skipper = null);

    // you can set the input via any parser
    // the new source gets applied to ALL parsers
    public function setSource($source)
    {
        $this->domain->setSource($source);
        return $this->domain->getInputIterator();
    }

    public function getAttribute()
    {
        if (! isset($this->typeTag)) {
            return $this->attribute;
        }
        if ($this->typeTag === self::TT_UNUSED) {
            return $this->domain->getUnusedAttribute();
        }
        if ($this->typeTag === self::TT_NULL) {
            return null;
        }
    }

    public function getAttributeType()
    {
        if (isset($this->typeTag)) {
            return self::TYPE_TAG[$this->typeTag];
        }
        return $this->getType($this->attribute);
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
        if ((! $this->skip) || ($skipper === null) || $skipper instanceof UnusedSkipper) {
            return;
        }

        while ($iterator->valid() && $skipper->doParse($iterator, null, null, null)) {
            /***/ ;
        }
    }

    protected function getType($value)
    {
        $result = gettype($value);
        if ($result === 'object') {
            $result = get_class($value);
            if ($result === UnusedAttribute::class) {
                return 'unused';
            }
        }
        return $result;
    }

    public function getRawAttribute()
    {
        return $this->attribute;
    }

    protected function castTo($targetType, $value)
    {
        if ($targetType === null
            || isset(self::NO_CAST[$targetType])
            || $targetType === $this->getType($value)) {
            return $value;
        }
        if (isset(self::NUMBER_CAST[$targetType])) {
            return (self::NUMBER_CAST[$targetType])($value);
        }
        if ($targetType === 'array') {
            return (is_array($value) ? $value : [ $value ]);
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
        // float, double, int and boolean
        if (isset(self::NUMBER_CAST[$attributeType])) {
            $this->attribute = (self::NUMBER_CAST[$attributeType])($value);
            unset($this->typeTag);
            return;
        }

        // @todo: default parameter of parse -> get rid off?
        if ($attributeType === null) {
            if ($this->defaultType !== null) {
                $this->assignTo($value, $this->defaultType);
                return;
            }
            $this->attribute = $value;
            unset($this->typeTag);
            return;
        }

        // NULL type
        if ($attributeType === 'NULL') {
            $this->typeTag = self::TT_NULL;
            $this->attribute = $value;
            return;
        }

        // unused type
        if ($attributeType === 'unused') {
            $this->typeTag = self::TT_UNUSED;
            $this->attribute = $value;
            return;
        }

        // string type
        // assigning means appending
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
        // assigning means appending
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
            $this->attribute = new $attributeType($this->attribute);
            unset($this->typeTag);
            return;
        }

        throw new InvalidArgumentException(
            sprintf('%s: Unknown attribute type %s', $this->what(), $attributeType)
        );
    }

    public function matchesExpected($expectedValue, $value, $type)
    {
        $value = $this->castTo($this->defaultType, $value);
        if ($expectedValue === null || $expectedValue === $value) {
            $this->assignTo($value, $type);
            return true;
        }
        return false;
    }
}
