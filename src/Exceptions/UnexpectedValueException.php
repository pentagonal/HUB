<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Exceptions;

use Pentagonal\Hub\Interfaces\ThrowableInterface;

class UnexpectedValueException extends \UnexpectedValueException implements ThrowableInterface
{
}
