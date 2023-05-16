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
use Tobento\Service\Language\LanguageFactory;
use Tobento\Service\Language\LanguageInterface;

/**
 * LanguageFactoryTest tests
 */
class LanguageFactoryTest extends TestCase
{    
    public function testCreateLanguageReturnsLanguageInstance()
    {        
        $factory = new LanguageFactory();

        $language = $factory->createLanguage('en', area: 'frontend', default: true);
        
        $this->assertInstanceOf(
            LanguageInterface::class,
            $language
        );
    }
    
    public function testCreateLanguageMethod()
    {        
        $factory = new LanguageFactory();

        $language = $factory->createLanguage(
            locale: 'de-CH',
            name: 'Deutsch',
            key: 'de_ch',
            id: 1,
            iso: 'de',
            region: 'CH',
            slug: 'de-ch',
            directory: 'dech',
            direction: 'rtl',
            area: 'front',
            domain: 'example.de',
            url: 'https://eaxmple.com',
            fallback: 'en',
            default: true,
            active: false,
            editable: false,
            order: 2,
        );
        
        $this->assertSame(
            [
                'de-CH',
                'Deutsch',
                'de_ch',
                1,
                'de',
                'CH',
                'de-ch',
                'dech',
                'rtl',
                'front',
                'example.de',
                'https://eaxmple.com',
                'en',
                true,
                false,
                false,
                2,
            ],
            [
                $language->locale(),
                $language->name(),
                $language->key(),
                $language->id(),
                $language->iso(),
                $language->region(),
                $language->slug(),
                $language->directory(),
                $language->direction(),
                $language->area(),
                $language->domain(),
                $language->url(),
                $language->fallback(),
                $language->default(),
                $language->active(),
                $language->editable(),
                $language->order(),
            ]
        );
    }
    
    public function testCreateLanguagesFromArrayMethod()
    {        
        $factory = new LanguageFactory();
                    
        $languages = [
            [
                'locale' => 'de-CH',
                'name' => 'Deutsch',
                'key' => 'de_ch',
                'id' => 1,
                'iso' => 'de',
                'region' => 'CH',
                'slug' => 'de-ch',
                'directory' => 'dech',
                'direction' => 'rtl',
                'area' => 'front',
                'domain' => 'example.de',
                'url' => 'https://eaxmple.com',
                'fallback' => 'en',
                'default' => true,
                'active' => false,
                'editable' => false,
                'order' => 2,
            ]
        ];
        
        $language = $factory->createLanguagesFromArray($languages)[0];
            
        $this->assertSame(
            [
                'de-CH',
                'Deutsch',
                'de_ch',
                1,
                'de',
                'CH',
                'de-ch',
                'dech',
                'rtl',
                'front',
                'example.de',
                'https://eaxmple.com',
                'en',
                true,
                false,
                false,
                2,
            ],
            [
                $language->locale(),
                $language->name(),
                $language->key(),
                $language->id(),
                $language->iso(),
                $language->region(),
                $language->slug(),
                $language->directory(),
                $language->direction(),
                $language->area(),
                $language->domain(),
                $language->url(),
                $language->fallback(),
                $language->default(),
                $language->active(),
                $language->editable(),
                $language->order(),
            ]
        );
    }
}