<?php

namespace Concrete\Tests\Validator;

use Concrete\Tests\TestCase;

class PasswordValidatorServiceProviderTest extends TestCase
{
    public function testIsInstance()
    {
        $app = \Core::make('app');

        $provider = new \Concrete\Core\Validator\PasswordValidatorServiceProvider($app);
        $provider->register();

        $this->assertEquals($app->make('validator/password'), $app->make('validator/password'), 'Password validator not bound as instance.');
    }

    public function testRegistered()
    {
        $app = new \Concrete\Core\Application\Application();

        $provider = new \Concrete\Core\Validator\PasswordValidatorServiceProvider($app);
        $provider->register();

        $this->assertTrue($app->bound('validator/password'));
    }
}
