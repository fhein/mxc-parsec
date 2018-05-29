<?php

namespace Mxc\Parsec\Qi\Directive;

use Mxc\Parsec\Qi\DelegatingParser;

class HoldDirective extends DelegatingParser
{
    // the hold directive is here only for compatibility
    // reasons

    // sub parsers do not modify the outer attribute
    // so there is no need for a rollback
}
