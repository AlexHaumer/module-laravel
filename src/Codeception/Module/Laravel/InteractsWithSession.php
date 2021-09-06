<?php

declare(strict_types=1);

namespace Codeception\Module\Laravel;

use Illuminate\Contracts\Session\Session;

trait InteractsWithSession
{
    /**
     * Assert that a session variable exists.
     *
     * ``` php
     * <?php
     * $I->seeInSession('key');
     * $I->seeInSession('key', 'value');
     * ```
     *
     * @param string|array $key
     * @param mixed|null $value
     */
    public function seeInSession($key, $value = null): void
    {
        if (is_array($key)) {
            $this->seeSessionHasValues($key);
            return;
        }

        /** @var Session $session */
        $session = $this->app['session'];

        if (!$session->has($key)) {
            $this->fail("No session variable with key '$key'");
        }

        if (! is_null($value)) {
            $this->assertEquals($value, $session->get($key));
        }
    }

    /**
     * Assert that the session has a given list of values.
     *
     * ``` php
     * <?php
     * $I->seeSessionHasValues(['key1', 'key2']);
     * $I->seeSessionHasValues(['key1' => 'value1', 'key2' => 'value2']);
     * ```
     *
     * @param array $bindings
     */
    public function seeSessionHasValues(array $bindings): void
    {
        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                $this->seeInSession($value);
            } else {
                $this->seeInSession($key, $value);
            }
        }
    }
}