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
use \Tobento\Service\Language\AreaLanguages;
use \Tobento\Service\Language\AreaLanguagesInterface;

/**
 * AreaLanguagesTest tests
 */
class AreaLanguagesTest extends TestCase
{
    public function testGetMethodReturnsLanguagesInstance()
    {        
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'de', area: 'frontend'),
            $factory->createLanguage(locale: 'en', area: 'backend'),
            $factory->createLanguage(locale: 'de', area: 'backend', default: true), 
        );
        
        $this->assertInstanceOf(
            LanguagesInterface::class,
            $languages->get('frontend')
        );
    }
    
    public function testGetMethodReturnsNullIfNotFound()
    {        
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'de', area: 'frontend'),
            $factory->createLanguage(locale: 'en', area: 'backend'),
            $factory->createLanguage(locale: 'de', area: 'backend', default: true), 
        );
        
        $this->assertSame(
            null,
            $languages->get('api')
        );
    }
    
    public function testHasMethod()
    {        
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'de', area: 'frontend'),
            $factory->createLanguage(locale: 'en', area: 'backend'),
            $factory->createLanguage(locale: 'de', area: 'backend', default: true), 
        );
        
        $this->assertTrue($languages->has('frontend'));
        $this->assertFalse($languages->has('api'));
    }
    
    public function testAddMethod()
    {        
        $factory = new LanguageFactory();
        
        $frontend = new Languages(
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
        );

        $languages = new AreaLanguages();
        
        $languages->add('frontend', $frontend);
        
        $this->assertTrue($frontend === $languages->get('frontend'));
    }
    
    public function testAllMethod()
    {
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'de', area: 'frontend'),
            $factory->createLanguage(locale: 'en', area: 'backend', default: true),
        );

        $all = $languages->all();

        $this->assertSame(['frontend', 'backend'], array_keys($all));
        $this->assertInstanceOf(LanguagesInterface::class, $all['frontend']);
        $this->assertInstanceOf(LanguagesInterface::class, $all['backend']);
        $this->assertSame($languages->get('frontend'), $all['frontend']);
        $this->assertSame($languages->get('backend'), $all['backend']);
    }

    public function testAllMethodReturnsEmptyArrayIfNoLanguages()
    {
        $languages = new AreaLanguages();

        $this->assertSame([], $languages->all());
    }

    public function testAllMethodIncludesAddedLanguages()
    {
        $factory = new LanguageFactory();

        $api = new Languages(
            $factory->createLanguage(locale: 'en', area: 'api', default: true),
        );

        $languages = new AreaLanguages();
        $languages->add('api', $api);

        $this->assertSame(['api' => $api], $languages->all());
    }

    public function testCountMethodCountsLanguagesOfAllAreas()
    {
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'de', area: 'frontend'),
            $factory->createLanguage(locale: 'en', area: 'backend'),
            $factory->createLanguage(locale: 'de', area: 'backend', default: true),
        );

        $this->assertSame(4, $languages->count());
    }

    public function testCountMethodReturnsZeroIfNoLanguages()
    {
        $languages = new AreaLanguages();

        $this->assertSame(0, $languages->count());
    }

    public function testCountMethodIncludesAddedLanguages()
    {
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
        );

        $languages->add('backend', new Languages(
            $factory->createLanguage(locale: 'en', area: 'backend', default: true),
            $factory->createLanguage(locale: 'de', area: 'backend'),
        ));

        $this->assertSame(3, $languages->count());
    }

    public function testCountMethodAfterReplacingAreaLanguages()
    {
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'de', area: 'frontend'),
        );

        $this->assertSame(2, $languages->count());

        // Adding to an existing area replaces its languages:
        $languages->add('frontend', new Languages(
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
        ));

        $this->assertSame(1, $languages->count());
    }

    public function testIsTraversable()
    {
        $this->assertInstanceOf(\Traversable::class, new AreaLanguages());
    }

    public function testGetIteratorMethodIteratesLanguagesKeyedByArea()
    {
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'en', area: 'backend', default: true),
        );

        $areas = [];

        foreach ($languages as $area => $areaLanguages) {
            $this->assertInstanceOf(LanguagesInterface::class, $areaLanguages);
            $areas[] = $area;
        }

        $this->assertSame(['frontend', 'backend'], $areas);
    }

    public function testGetIteratorMethodReturnsSameLanguagesAsAll()
    {
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage(locale: 'en', area: 'frontend', default: true),
            $factory->createLanguage(locale: 'en', area: 'backend', default: true),
        );

        $this->assertSame($languages->all(), iterator_to_array($languages->getIterator()));
    }

    public function testGetIteratorMethodIsEmptyIfNoLanguages()
    {
        $languages = new AreaLanguages();

        $this->assertSame([], iterator_to_array($languages));
    }
}