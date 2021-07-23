<?php

namespace Concrete\Tests\Validator;

use Concrete\Tests\TestCase;

class UserEmailValidatorServiceProviderTest extends TestCase
{
    public function testIsInstance()
    {
        $app = \Core::make('app');

        $provider = new \Concrete\Core\Validator\PasswordValidatorServiceProvider($app);
        $provider->register();

        $this->assertSame($app->make('validator/user/email'), $app->make('validator/user/email'), 'User email validator not bound as instance.');
    }

    public function testRegistered()
    {
        $app = new \Concrete\Core\Application\Application();

        $provider = new \Concrete\Core\Validator\UserEmailValidatorServiceProvider($app);
        $provider->register();

        $this->assertTrue($app->bound('validator/user/email'));
    }
}
