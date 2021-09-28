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
use \Tobento\Service\Language\AreaLanguages;
use \Tobento\Service\Language\AreaLanguagesInterface;

/**
 * AreaLanguagesTest tests
 */
class AreaLanguagesTest extends TestCase
{
    public function testThrowsLanguageExceptionIfNoDefaultLanguageIsFound()
    {
        $this->expectException(LanguageException::class);
        
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage('en', area: 'frontend', default: true),
            $factory->createLanguage('de', area: 'frontend'),
            $factory->createLanguage('en', area: 'backend'),
            $factory->createLanguage('de', area: 'backend'), 
        );
    }
    
    public function testGetMethodReturnsLanguagesInstance()
    {        
        $factory = new LanguageFactory();

        $languages = new AreaLanguages(
            null,
            $factory->createLanguage('en', area: 'frontend', default: true),
            $factory->createLanguage('de', area: 'frontend'),
            $factory->createLanguage('en', area: 'backend'),
            $factory->createLanguage('de', area: 'backend', default: true), 
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
            $factory->createLanguage('en', area: 'frontend', default: true),
            $factory->createLanguage('de', area: 'frontend'),
            $factory->createLanguage('en', area: 'backend'),
            $factory->createLanguage('de', area: 'backend', default: true), 
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
            $factory->createLanguage('en', area: 'frontend', default: true),
            $factory->createLanguage('de', area: 'frontend'),
            $factory->createLanguage('en', area: 'backend'),
            $factory->createLanguage('de', area: 'backend', default: true), 
        );
        
        $this->assertTrue($languages->has('frontend'));
        $this->assertFalse($languages->has('api'));
    }    
}