<?php

namespace Concrete\Tests\Localization\Adapter\Plain;

use Concrete\Core\Localization\Translator\Adapter\Plain\TranslatorAdapterFactory;
use Concrete\Tests\TestCase;

/**
 * Tests for:
 * Concrete\Core\Localization\Translator\Adapter\Plain\TranslatorAdapterFactory.
 *
 * @author Antti Hukkanen <antti.hukkanen@mainiotech.fi>
 */
class TranslatorAdapterFactoryTest extends TestCase
{
    protected $factory;

    public function setUp()
    {
        $this->factory = new TranslatorAdapterFactory();
    }

    public function testCreateTranslatorAdapter()
    {
        $adapter = $this->factory->createTranslatorAdapter('en_US');

        $this->assertInstanceOf('Concrete\Core\Localization\Translator\Adapter\Plain\TranslatorAdapter', $adapter);
    }
}
