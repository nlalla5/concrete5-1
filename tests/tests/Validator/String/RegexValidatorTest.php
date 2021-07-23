<?php

namespace Concrete\Tests\Validator\String;

use Concrete\Tests\TestCase;

class RegexValidatorTest extends TestCase
{
    public function testIsValid()
    {
        $validator = new \Concrete\Core\Validator\String\RegexValidator('//');
        $validator->setPattern('/(pass)/');

        $this->assertNotEmpty($validator->getRequirementStrings());

        $this->assertFalse($validator->isValid('fail'));
        $this->assertTrue($validator->isValid('pass'));
        $this->assertFalse($validator->isValid('this should still fail'));
        $this->assertTrue($validator->isValid('this should still pass'));
    }

    public function testErrorAdded()
    {
        $validator = new \Concrete\Core\Validator\String\RegexValidator('/test/');

        $this->assertFalse($validator->isValid('123456', $error = new \ArrayObject()));
        $this->assertNotEmpty($error);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidInput()
    {
        $validator = new \Concrete\Core\Validator\String\RegexValidator('');

        $validator->isValid($validator);
        $validator->isValid('');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testInvalidRegularExpression()
    {
        $validator = new \Concrete\Core\Validator\String\RegexValidator('Invalid regex');
        $validator->isValid('test');
    }
}
