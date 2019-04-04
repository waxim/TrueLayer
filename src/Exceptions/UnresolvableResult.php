<?php

namespace TrueLayer\Exceptions;

class UnresolvableResult extends \Exception
{
    protected $message = 'The given result was not resolvable.';
}
