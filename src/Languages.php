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

use Generator;

/**
 * Languages
 */
final class Languages implements LanguagesInterface
{        
    /**
     * @var array<mixed> Holds the languages.
     */
    protected array $languages = [];
    
    /**
     * @var array<mixed> Holds the active languages.
     */
    protected array $activeLanguages = [];    

    /**
     * @var null|array<mixed> The fallbacks. ['key' => ['en' => 'de']]
     */
    protected null|array $fallbacks = null;

    /**
     * Create a new Languages.
     *
     * @param LanguageInterface ...$languages
     */
    public function __construct(
        LanguageInterface ...$languages
    ) {        
        $this->addLanguages(...$languages);
    }
    
    /**
     * If has language by key.
     *
     * @param string|int $languageKey The language key, locale or id
     * @param bool $activeOnly
     * @return bool True language key exists, otherwise false.
     */    
    public function has(string|int $languageKey, bool $activeOnly = true): bool
    {        
        // by id.
        if (is_int($languageKey))
        {
            $languages = $this->all('id', $activeOnly);
            return isset($languages[$languageKey]);
        }

        // by locale
        $languages = $this->all('locale', $activeOnly);
        
        if (isset($languages[$languageKey]))
        {
            return true;
        }

        // by key
        $languages = $this->all('key', $activeOnly);
        
        return isset($languages[$languageKey]);
    }
    
    /**
     * Gets a language.
     *
     * @param string|int $languageKey The language key, locale or id
     * @param bool $fallback If language does not exist, it gets the fallback if set to true.
     * @return null|LanguageInterface
     */    
    public function get(string|int $languageKey, bool $fallback = true): ?LanguageInterface
    {
        // by id.
        if (is_int($languageKey))
        {
            $languages = $this->all('id');

            if (isset($languages[$languageKey]))
            {
                return $languages[$languageKey];
            }
        } 
        else
        {
            // check by locale
            $languages = $this->all('locale');

            if (isset($languages[$languageKey]))
            {
                return $languages[$languageKey];
            }
            
            // check by key
            $languages = $this->all('key');
            
            if (isset($languages[$languageKey]))
            {
                return $languages[$languageKey];
            }
            
            // check by slug
            $languages = $this->all('slug');
            
            if (isset($languages[$languageKey]))
            {
                return $languages[$languageKey];
            }            
        }
        
        return $fallback === true ? $this->getFallback($languageKey) : null;        
    }
    
    /**
     * Gets language column.
     *
     * @param string $columnKey
     * @param string $indexKey
     * @param bool $activeOnly
     * @return array
     */    
    public function column(string $columnKey = 'locale', null|string $indexKey = null, bool $activeOnly = true): array
    {
        return array_column($this->all('locale', $activeOnly), $columnKey, $indexKey);
    }    
    
    /**
     * Gets the default language.
     *
     * @return LanguageInterface
     */
    public function default(): LanguageInterface
    {        
        return $this->languages['default'];
    }
    
    /**
     * Gets the current language.
     *
     * @param null|string|int $currentLanguageKey If set it changes the current language.
     * @return LanguageInterface
     */    
    public function current(null|string|int $currentLanguageKey = null): LanguageInterface
    {
        // change the current language.
        if ($currentLanguageKey !== null)
        {
            $this->setCurrent($currentLanguageKey);
        }
        
        // set the current language from the default if not set.
        if (!isset($this->languages['current']))
        {
            $this->languages['current'] = $this->default();
        }

        return $this->languages['current'];
    }
    
    /**
     * Returns the first language.
     *
     * @param bool $activeOnly
     * @return null|LanguageInterface
     */    
    public function first(bool $activeOnly = true): null|LanguageInterface
    {
        $languages = $this->all(activeOnly: $activeOnly);
        
        $key = array_key_first($languages);
        
        if (is_null($key)) {
            return null;
        }
        
        return $languages[$key];
    }
    
    /**
     * Gets all languages.
     *
     * @param string $indexKey The index key such as id, key, locale, slug.
     * @param bool $activeOnly If true returns only active languages, otherwise all.
     * @return array The languages.
     */    
    public function all(string $indexKey = 'locale', bool $activeOnly = true): array
    {
        if ($activeOnly)
        {
            if (isset($this->activeLanguages[$indexKey]))
            {
                return $this->activeLanguages[$indexKey];
            }

            // return and store it for reusage.
            return $this->activeLanguages[$indexKey] = $this->reindex($this->activeLanguages, $indexKey);            
        }
        
        if (isset($this->languages[$indexKey]))
        {
            return $this->languages[$indexKey];
        }
        
        // return and store it for reusage.
        return $this->languages[$indexKey] = $this->reindex($this->languages, $indexKey);
    }
    
    /**
     * Returns an iterator for the languages.
     *
     * @return Generator
     */
    public function getIterator(): Generator
    {
        foreach($this->all() as $key => $language) {
            yield $key => $language;
        }
    }
    
    /**
     * Returns a new instance with the filtered languages.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        $filtered = array_filter($this->all(activeOnly: false), $callback);
        
        return new static(...$filtered);
    }
    
    /**
     * Returns a new instance with the mapped languages.
     *
     * @param callable $mapper
     * @return static
     */
    public function map(callable $mapper): static
    {
        $mapped = [];
        
        foreach($this->all(activeOnly: false) as $language) {
            $mapped[] = $mapper($language);
        }
        
        return new static(...$mapped);
    }
    
    /**
     * Returns a new instance with the languages sorted.
     *
     * @param callable $callback
     * @return static
     */
    public function sort(callable $callback): static
    {
        $languages = $this->all(activeOnly: false);
        
        usort($languages, $callback);
        
        return new static(...$languages);
    }

    /**
     * Gets the fallbacks.
     *
     * @param string $indexKey The key to get set.
     * @return array The fallbacks.
     */    
    public function fallbacks(string $indexKey = 'locale'): array
    {
        if (is_null($this->fallbacks))
        {
            $this->fallbacks = [];
            
            foreach($this->all('locale', false) as $language)
            {
                if (is_null($language->fallback()))
                {
                    continue;
                }
                
                $fallbackLanguage = $this->get($language->fallback(), false);

                if (!is_null($fallbackLanguage))
                {
                    $this->fallbacks['locale'][$language->locale()] = $fallbackLanguage->locale();
                    $this->fallbacks['key'][$language->key()] = $fallbackLanguage->key();
                    $this->fallbacks['id'][$language->id()] = $fallbackLanguage->id();
                    $this->fallbacks['slug'][$language->slug()] = $fallbackLanguage->slug();
                }
            }
        }
        
        switch ($indexKey) {
            case 'id':
                return $this->fallbacks['id'] ?? [];
            case 'key':
                return $this->fallbacks['key'] ?? [];
            case 'locale':
                return $this->fallbacks['locale'] ?? [];
            case 'slug':
                return $this->fallbacks['slug'] ?? [];              
        }
        
        return [];
    }

    /**
     * Gets the fallback language for the given language key.
     *
     * @param string|int $languageKey The language key, locale, id or slug.
     * @return LanguageInterface
     */    
    public function getFallback(string|int $languageKey): LanguageInterface
    {
        if (is_int($languageKey))
        {
            $fallbacks = $this->fallbacks('id');
            $fallbackKey = $fallbacks[$languageKey] ?? null;
        } else {
            $fallbacks = $this->fallbacks('locale');
            $fallbackKey = $fallbacks[$languageKey] ?? null;
            
            if ($fallbackKey === null)
            {
                $fallbacks = $this->fallbacks('key');
                $fallbackKey = $fallbacks[$languageKey] ?? null;
            }
            
            if ($fallbackKey === null)
            {
                $fallbacks = $this->fallbacks('slug');
                $fallbackKey = $fallbacks[$languageKey] ?? null;
            }
        }
        
        if ($fallbackKey === null)
        {
            return $this->default();
        }
        
        return $this->get($fallbackKey) ?: $this->default();
    }

    /**
     * Add languages.
     *
     * @param LanguageInterface $language
     * @return void
     *
     * @throws LanguageException If no default language is found.
     */    
    protected function addLanguages(LanguageInterface ...$languages): void
    {
        foreach($languages as $language)
        {
            $this->languages['locale'][$language->locale()] = $language;

            if ($language->active())
            {
                $this->activeLanguages['locale'][$language->locale()] = $language;
                
                // set default language.
                if (!isset($this->languages['default']) && $language->default())
                {
                    $this->languages['default'] = $language;
                }
            }
        }
        
        if (!isset($this->languages['default']))
        {
            throw new LanguageException('No default language found');
        }
    }
    
    /**
     * Set the current language
     *
     * @param string|int $current The language key, locale, id or slug.
     * @return bool True if language could be set, otherwise false;
     */    
    protected function setCurrent(string|int $current): bool
    {
        $language = $this->get($current, fallback: false);
        
        if (!is_null($language))
        {
            $this->languages['current'] = $language;
            return true;
        }
        
        return false;
    }    
    
    /**
     * Reindex the languages array.
     *
     * @param array $languages The languages.
     * @param string $indexKey The index key such as 'id', 'key', 'locale'.
     * @return array The languages.
     */    
    protected function reindex(array $languages, string $indexKey): array
    {
        $languages = $languages['locale'] ?? [];
        
        if (empty($languages)) {
            return [];
        }
        
        $reindexed = [];
        $method = $indexKey;
        
        foreach($languages as $language)
        {
            if (method_exists($language, $method))
            {
                $reindexed[$language->$method()] = $language;
            }
        }
        
        return $reindexed;
    }
}