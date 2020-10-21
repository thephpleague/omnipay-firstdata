<?php

namespace Omnipay\FirstData\Exception;

use Omnipay\Common\Exception\OmnipayException;

/**
 * Invalid Ach Exception
 *
 * Thrown when a ach is invalid or missing required fields.
 */
class InvalidAchException extends \Exception implements OmnipayException
{
}
