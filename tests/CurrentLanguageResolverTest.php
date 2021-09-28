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
use Tobento\Service\Language\CurrentLanguageResolver;
use Tobento\Service\Language\CurrentLanguageResolverInterface;
use Tobento\Service\Language\CurrentLanguageResolverException;

/**
 * CurrentLanguageResolverTest tests
 */
class CurrentLanguageResolverTest extends TestCase
{
    public function testIsInstanceofCurrentLanguageResolverInterface()
    {        
        $this->assertInstanceOf(
            CurrentLanguageResolverInterface::class,
            new CurrentLanguageResolver('fr')
        );
    }
    
    public function testThrowsCurrentLanguageResolverExceptionNotAllowFallback()
    {
        $this->expectException(CurrentLanguageResolverException::class);
                
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', default: true),
            $factory->createLanguage('de-CH'),
        );
        
        $resolver = new CurrentLanguageResolver('fr', false);
        
        $resolver->resolve($languages);
    }
    
    public function testFallbacksToDefaultLanguageIfNotExist()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', default: true),
            $factory->createLanguage('de-CH'),
        );
        
        $resolver = new CurrentLanguageResolver('fr', true);
        
        $resolver->resolve($languages);

        $this->assertSame(
            'en-US',
            $languages->current()->locale()
        );     
    }
    
    public function testChangesToCurrentLanguage()
    {
        $factory = new LanguageFactory();

        $languages = new Languages(
            $factory->createLanguage('en-US', default: true),
            $factory->createLanguage('de-CH'),
        );
        
        $resolver = new CurrentLanguageResolver('de-CH', true);
        
        $resolver->resolve($languages);

        $this->assertSame(
            'de-CH',
            $languages->current()->locale()
        );     
    }    
}