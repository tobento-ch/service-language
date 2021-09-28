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
use Tobento\Service\Language\Languages;
use Tobento\Service\Language\LanguagesInterface;
use Tobento\Service\Language\LanguageInterface;
use Tobento\Service\Language\LanguageException;

/**
 * LanguagesTest tests
 */
class LanguagesTest extends TestCase
{
    public function testThrowsLanguageExceptionIfNoDefaultLanguageIsFound()
    {
        $this->expectException(LanguageException::class);
        
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US'),
            $factory->createLanguage('de-CH'),
        );
    }
    
    public function testThatInactiveLanguageCannotBeTheDefault()
    {
        $this->expectException(LanguageException::class);
        
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', active: false, default: true),
            $factory->createLanguage('de-CH'),
        );
    }
    
    public function testGetMethodReturnsLanguage()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', fallback: 'en'),
            $factory->createLanguage('fr', fallback: 'en', active: false),
        );

        $this->assertInstanceOf(
            LanguageInterface::class,
            $languages->get('en')
        );     
    }

    public function testGetMethodReturnsFallbackLanguage()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', fallback: 'en'),
            $factory->createLanguage('fr', fallback: 'de', active: false),
        );
        
        $this->assertSame(
            'de',
            $languages->get('fr')->locale()
        );        
    }
    
    public function testGetMethodReturnsDefaultLanguage()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', fallback: 'en'),
            $factory->createLanguage('fr', fallback: 'de', active: false),
        );
        
        $this->assertSame(
            'en',
            $languages->get('it')->locale()
        );        
    }
    
    public function testGetMethodReturnsDefaultLanguageIfFallbackLanguageMissing()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', fallback: 'en'),
            $factory->createLanguage('fr', fallback: 'it', active: false),
        );
        
        $this->assertSame(
            'en',
            $languages->get('fr')->locale()
        );        
    }
    
    public function testGetMethodReturnsDefaultLanguageIfFallbackLanguageIsInactive()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', active: false),
            $factory->createLanguage('fr', fallback: 'de', active: false),
        );
        
        $this->assertSame(
            'en',
            $languages->get('fr')->locale()
        );        
    }     
    
    public function testGetMethodWithoutFallbackReturnsNullIfLanguageMissing()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', fallback: 'en'),
            $factory->createLanguage('fr', fallback: 'en', active: false),
        );
        
        $this->assertSame(
            null,
            $languages->get('it', fallback: false)
        );        
    }
    
    public function testGetMethodWithId()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', id: 1, default: true),
            $factory->createLanguage('de', id: 2),
        );
        
        $this->assertSame(
            'de',
            $languages->get(2)->locale()
        );        
    }
    
    public function testGetMethodWithKey()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de-ch'),
        );
        
        $this->assertSame(
            'de-CH',
            $languages->get('de-ch')->locale()
        );        
    }
    
    public function testGetMethodWithSlug()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', slug: 'de-ch'),
        );
        
        $this->assertSame(
            'de-CH',
            $languages->get('de-ch')->locale()
        );        
    }    
    
    public function testHasMethod()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', fallback: 'en'),
            $factory->createLanguage('fr', fallback: 'en', active: false),
        );
        
        $this->assertTrue($languages->has('de'));
        $this->assertFalse($languages->has('fr'));
        $this->assertFalse($languages->has('it'));
    }
    
    public function testHasMethodWithInactive()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de', fallback: 'en'),
            $factory->createLanguage('fr', fallback: 'en', active: false),
        );
        
        $this->assertTrue($languages->has('de', activeOnly: false));
        $this->assertTrue($languages->has('fr', activeOnly: false));
        $this->assertFalse($languages->has('it', activeOnly: false));
    }
    
    public function testHasMethodWithId()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', id: 1, default: true),
            $factory->createLanguage('de', id: 2, active: false),
        );
        
        $this->assertTrue($languages->has(1));
        $this->assertFalse($languages->has(2));
        $this->assertFalse($languages->has(3));
    }
    
    public function testHasMethodWithKey()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'en-ch', active: false),
        );
        
        $this->assertTrue($languages->has('en-us'));
        $this->assertFalse($languages->has('de-ch'));
    }
    
    public function testHasMethodWithSlug()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', slug: 'en-ch', active: false),
        );
        
        $this->assertTrue($languages->has('en-us'));
        $this->assertFalse($languages->has('de-ch'));
    }
    
    public function testDefaultMethod()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', default: true),
            $factory->createLanguage('de-CH'),
        );
        
        $this->assertInstanceOf(
            LanguageInterface::class,
            $languages->default()
        );
    }
    
    public function testCurrentMethodReturnsDefaultIfNoneIsSet()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de'),
        );
        
        $this->assertSame(
            'en',
            $languages->current()->locale()
        );
    }
    
    public function testCurrentMethodSetInactiveCurrentFallbackToDefaultIfNoneIsSet()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de'),
            $factory->createLanguage('fr', active: false),
        );
        
        $languages->current('fr');
        
        $this->assertSame(
            'en',
            $languages->current()->locale()
        );
    }
    
    public function testCurrentMethodSetInactiveCurrentKeepsOldCurrent()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de'),
            $factory->createLanguage('fr', active: false),
        );
        
        $languages->current('de');
        $languages->current('fr');
        
        $this->assertSame(
            'de',
            $languages->current()->locale()
        );
    }

    public function testCurrentMethodSetInvalidCurrentFallbackToDefaultIfNoneIsSet()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de'),
            $factory->createLanguage('fr', active: false),
        );
        
        $languages->current('it');
        
        $this->assertSame(
            'en',
            $languages->current()->locale()
        );
    }
    
    public function testCurrentMethodSetInvalidCurrentKeepsOldCurrent()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en', default: true),
            $factory->createLanguage('de'),
            $factory->createLanguage('fr', active: false),
        );
        
        $languages->current('de');
        $languages->current('it');
        
        $this->assertSame(
            'de',
            $languages->current()->locale()
        );
    }    
    
    public function testCurrentMethodSetCurrentByLocale()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de-ch'),
        );
        
        $languages->current('de-CH');
        
        $this->assertSame(
            'de-CH',
            $languages->current()->locale()
        );
    }
    
    public function testCurrentMethodSetCurrentByKey()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de-ch'),
        );
        
        $languages->current('de-ch');
        
        $this->assertSame(
            'de-CH',
            $languages->current()->locale()
        );
    }
    
    public function testCurrentMethodSetCurrentById()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', id: 1, default: true),
            $factory->createLanguage('de-CH', id: 2),
        );
        
        $languages->current(2);
        
        $this->assertSame(
            'de-CH',
            $languages->current()->locale()
        );
    }
    
    public function testCurrentMethodSetCurrentBySlug()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', slug: 'de-ch'),
        );
        
        $languages->current('de-ch');
        
        $this->assertSame(
            'de-CH',
            $languages->current()->locale()
        );
    }
    
    public function testAllMethod()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', key: 'fr_CH', id: 3, slug: 'fr-ch', active: false),
        );
        
        $all = $languages->all();
        
        $this->assertSame(
            [
                'en-US' => $all['en-US'],
                'de-CH' => $all['de-CH'],
            ],
            $all
        );
    }
    
    public function testAllMethodIndexedByLocaleKeyIdSlug()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $all = $languages->all('locale');
        
        $this->assertSame(
            ['en-US', 'de-CH'],
            array_keys($all)
        );
        
        $all = $languages->all('key');
        
        $this->assertSame(
            ['en_us', 'de_ch'],
            array_keys($all)
        );
        
        $all = $languages->all('id');
        
        $this->assertSame(
            [1, 2],
            array_keys($all)
        );
        
        $all = $languages->all('slug');
        
        $this->assertSame(
            ['en-us', 'de-ch'],
            array_keys($all)
        );        
    }
    
    public function testAllMethodWithInactive()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $all = $languages->all(activeOnly: false);
        
        $this->assertSame(
            [
                'en-US' => $all['en-US'],
                'de-CH' => $all['de-CH'],
                'fr-CH' => $all['fr-CH'],
            ],
            $all
        );
    }
    
    public function testColumnMethod()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $this->assertSame(
            ['en-US', 'de-CH'],
            $languages->column('locale')
        );
        
        $this->assertSame(
            ['en_us', 'de_ch'],
            $languages->column('key')
        );
        
        $this->assertSame(
            [1, 2],
            $languages->column('id')
        );

        $this->assertSame(
            ['en-us', 'de-ch'],
            $languages->column('slug')
        );
        
        $this->assertSame(
            [null, null],
            $languages->column('url')
        );        
    }
    
    public function testColumnMethodWithIndex()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $this->assertSame(
            ['en-us' => 'en-US', 'de-ch' => 'de-CH'],
            $languages->column('locale', 'slug')
        );
        
        $this->assertSame(
            [1 => 'en-US', 2 => 'de-CH'],
            $languages->column('locale', 'id')
        );        
    }
    
    public function testColumnMethodWithInactive()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $this->assertSame(
            ['en-US', 'de-CH', 'fr-CH'],
            $languages->column('locale', activeOnly: false)
        );        
    }
    
    public function testFallbacksMethod()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', fallback: 'de-CH', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', fallback: 'en-US', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', fallback: 'de-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $this->assertSame(
            [
                'en-US' => 'de-CH',
                'de-CH' => 'en-US',
                'fr-CH' => 'de-CH',
            ],
            $languages->fallbacks()
        );
        
        $this->assertSame(
            [
                'en-US' => 'de-CH',
                'de-CH' => 'en-US',
                'fr-CH' => 'de-CH',
            ],
            $languages->fallbacks('locale')
        );
        
        $this->assertSame(
            [
                'en_us' => 'de_ch',
                'de_ch' => 'en_us',
                'fr_ch' => 'de_ch',
            ],
            $languages->fallbacks('key')
        );
        
        $this->assertSame(
            [
                1 => 2,
                2 => 1,
                3 => 2,
            ],
            $languages->fallbacks('id')
        );
        
        $this->assertSame(
            [
                'en-us' => 'de-ch',
                'de-ch' => 'en-us',
                'fr-ch' => 'de-ch',
            ],
            $languages->fallbacks('slug')
        );
    }
    
    public function testGetFallbackMethod()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', fallback: 'de-CH', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', fallback: 'en-US', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', fallback: 'de-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $this->assertSame(
            'de-CH',
            $languages->getFallback('fr-CH')->locale()
        );
        
        $this->assertSame(
            'de-CH',
            $languages->getFallback('fr-ch')->locale()
        );
        
        $this->assertSame(
            'de-CH',
            $languages->getFallback('fr_ch')->locale()
        );
        
        $this->assertSame(
            'de-CH',
            $languages->getFallback(3)->locale()
        );
    }
    
    public function testGetFallbackMethodReturnsDefaultIfNoFallbackIsSet()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', fallback: 'de-CH', key: 'en_us', id: 1, slug: 'en-us', default: true),
            $factory->createLanguage('de-CH', fallback: 'en-US', key: 'de_ch', id: 2, slug: 'de-ch'),
            $factory->createLanguage('fr-CH', key: 'fr_ch', id: 3, slug: 'fr-ch', active: false),
        );
        
        $this->assertSame(
            'en-US',
            $languages->getFallback('fr-CH')->locale()
        );
    }    
}