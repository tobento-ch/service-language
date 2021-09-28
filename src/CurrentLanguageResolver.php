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

namespace Tobento\Service\Language;

/**
 * CurrentLanguageResolver
 */
class CurrentLanguageResolver implements CurrentLanguageResolverInterface
{
    /**
     * Create a new CurrentLanguageResolver.
     *
     * @param int|string $currentLanguage The current language locale, key, slug or id.
     * @param bool $allowFallbackToDefaultLanguage
     */
    public function __construct(
        protected int|string $currentLanguage,
        protected bool $allowFallbackToDefaultLanguage = true,
    ) {}
    
    /**
     * Resolve the current language.
     *
     * @param LanguagesInterface $languages
     * @return void
     *
     * @throws CurrentLanguageResolverException If the current language could not be resolved.
     */
    public function resolve(LanguagesInterface $languages): void
    {
        // check for for fallback:
        if ($this->allowFallbackToDefaultLanguage === false)
        {
            $language = $languages->get($this->currentLanguage, fallback: false);
            
            if (is_null($language))
            {
                throw new CurrentLanguageResolverException(
                    $this->currentLanguage,
                    'Current language not found'
                );
            }
        }
        
        $languages->current($this->currentLanguage);
    }
}