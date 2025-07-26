<?php

namespace Sms4jawaly\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Sms4jawaly\Laravel\Gateway;

/**
 * Facade for the Sms4jawaly Gateway.
 *
 * This allows you to call static-like methods on the Sms4jawaly facade which
 * are proxied to the underlying Gateway instance registered in the service
 * container. For example:
 *
 * ```php
 * use Sms4jawaly\Laravel\Facades\Sms4jawaly;
 *
 * Sms4jawaly::sendSms('Hello', ['966500000000'], '4jawaly');
 * ```
 */
class Sms4jawaly extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Gateway::class;
    }
}