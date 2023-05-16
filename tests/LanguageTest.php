<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Language\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Language\Language;

/**
 * LanguageTest
 */
class LanguageTest extends TestCase
{
    public function testLanguage()
    {
        $language = new Language(
            locale: 'en-US',
            iso: 'en',
            region: 'US',
            name: 'U S',
            key: 'en-us',
            id: 1,
            slug: 'enus',
            directory: 'en_us',
            direction: 'ltr',
            area: 'front',
            domain: 'en.example.com',
            url: 'https://en.example.com',
            fallback: 'de',
            default: true,
            active: true,
            editable: true,
            order: 100,
        );
        
        $this->assertSame('en-US', $language->locale());
        $this->assertSame('en', $language->iso());
        $this->assertSame('US', $language->region());
        $this->assertSame('U S', $language->name());
        $this->assertSame('en-us', $language->key());
        $this->assertSame(1, $language->id());
        $this->assertSame('enus', $language->slug());
        $this->assertSame('en_us', $language->directory());
        $this->assertSame('ltr', $language->direction());
        $this->assertSame('front', $language->area());
        $this->assertSame('en.example.com', $language->domain());
        $this->assertSame('https://en.example.com', $language->url());
        $this->assertSame('de', $language->fallback());
        $this->assertTrue($language->default());
        $this->assertTrue($language->active());
        $this->assertTrue($language->editable());
        $this->assertSame(100, $language->order());
    }
    
    public function testLanguageWithNameMethod()
    {
        $language = new Language(
            locale: 'en-US',
            iso: 'en',
            region: 'US',
            name: 'U S',
            key: 'en-us',
            id: 1,
            slug: 'enus',
            directory: 'en_us',
            direction: 'ltr',
            area: 'front',
        );
        
        $languageNew = $language->withName('New Name');
            
        $this->assertFalse($language === $languageNew);
        $this->assertSame('New Name', $languageNew->name());
    }
}