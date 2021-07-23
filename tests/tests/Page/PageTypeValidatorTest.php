<?php

namespace Concrete\Tests\Page;

use Concrete\TestHelpers\Page\SomethingCoolPageTypeValidator;
use Core;
use Concrete\Tests\TestCase;

class PageTypeValidatorTest extends TestCase
{
    public function testPageTypeValidatorManagerLoading()
    {
        $manager = Core::make('manager/page_type/validator');
        $manager->extend('something_cool', function ($app) {
            return new SomethingCoolPageTypeValidator();
        });

        $this->assertInstanceOf('\Concrete\Core\Page\Type\Validator\Manager', $manager);
        $this->assertInstanceOf('\Concrete\Core\Page\Type\Validator\StandardValidator', $manager->driver('blog_entry'));
        $this->assertInstanceOf('\Concrete\Core\Page\Type\Validator\StandardValidator', $manager->driver('page'));

        $this->assertInstanceOf(SomethingCoolPageTypeValidator::class, $manager->driver('something_cool'));

        $this->assertInstanceOf('\Concrete\Core\Page\Type\Validator\StandardValidator', $manager->driver('another'));
        $this->assertInstanceOf('\Concrete\Core\Page\Type\Validator\StandardValidator', $manager->driver());

        $type1 = new \Concrete\Core\Page\Type\Type();
        $type1->ptHandle = 'something_cool';
        $type2 = new \Concrete\Core\Page\Type\Type();
        $type2->ptHandle = 'blog_entry';

        $this->assertInstanceOf(SomethingCoolPageTypeValidator::class, $type1->getPageTypeValidatorObject());
        $this->assertInstanceOf('\Concrete\Core\Page\Type\Validator\StandardValidator', $type2->getPageTypeValidatorObject());
        $this->assertInstanceOf('\Concrete\Core\Page\Type\Type', $type2->getPageTypeValidatorObject()->getPageTypeObject());
    }
}
