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
 * LanguageFactory
 */
class LanguageFactory implements LanguageFactoryInterface
{    
    /**
     * Create a new Language.
     *
     * @param string $locale
     * @param null|string $name
     * @param null|string $key
     * @param int $id
     * @param null|string $iso
     * @param null|string $region
     * @param null|string $slug
     * @param null|string $directory
     * @param string $area
     * @param null|string $domain
     * @param null|string $url
     * @param null|string $fallback
     * @param bool $default
     * @param bool $active     
     * @return LanguageInterface
     */
    public function createLanguage(
        string $locale,
        null|string $name = null,
        null|string $key = null,
        int $id = 0,
        null|string $iso = null,
        null|string $region = null,
        null|string $slug = null,
        null|string $directory = null,
        string $area = 'default',
        null|string $domain = null,
        null|string $url = null,
        null|string $fallback = null,
        bool $default = false,
        bool $active = true,
    ): LanguageInterface {
        
        $name = $name ?: $locale;
        $key = $key ?: strtolower($locale);
        $iso = $iso ?: strtolower(substr($locale, 0, 2));
        $region = $region ?: $this->extractRegionFromLocale($locale);
        $slug = $slug ?: $key;    
        $directory = $directory ?: $key;
        
        return new Language(
            $locale,
            $iso,
            $region,
            $name,
            $key,
            $id,
            $slug,
            $directory,
            $area,
            $domain,
            $url,
            $fallback,
            $default,
            $active,
        );
    }
    
    /**
     * Create languages from array.
     *
     * @param array<mixed> $languages 
     * @return array<int, LanguageInterface>
     */
    public function createLanguagesFromArray(array $languages): array
    {
        $created = [];
        
        foreach($languages as $language)
        {
            $created[] = $this->createLanguage(...$language);
        }
        
        return $created;
    }
    
    /**
     * Extract the region from the locale.
     *
     * @param string $locale
     * @return string
     */
    protected function extractRegionFromLocale(string $locale): null|string
    {
        if (str_contains($locale, '-')) {
            return explode('-', $locale)[1];
        }
        
        if (str_contains($locale, '_')) {
            return explode('_', $locale)[1];
        }
        
        return null;
    }
}