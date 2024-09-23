<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Exceptions;

use InvalidArgumentException;
use Pentagonal\Hub\Interfaces\ThrowableInterface;

class InvalidArgumentClassNameException extends InvalidArgumentException implements ThrowableInterface
{
}
