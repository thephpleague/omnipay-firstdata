<?php

namespace Omnipay\FirstData\Exception;

use Omnipay\Common\Exception\OmnipayException;

/**
 * Invalid Ach Exception
 *
 * Thrown when a ach is invalid or missing required fields.
 */
class InvalidAuthResponseException extends \Exception implements OmnipayException
{
    public function __construct($message = "Unauthorized Request. Bad or missing credentials.", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
