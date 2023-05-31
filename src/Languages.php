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
     * @var array<int, LanguageInterface> Holds the languages.
     */
    protected array $languages = [];
    
    /**
     * @var null|LanguageInterface
     */
    protected null|LanguageInterface $defaultLanguage = null;
    
    /**
     * @var null|LanguageInterface
     */
    protected null|LanguageInterface $currentLanguage = null;

    /**
     * @var null|array<mixed> The fallbacks. ['key' => ['en' => 'de']]
     */
    protected null|array $fallbacks = null;
    
    /**
     * @var array
     */
    protected array $cache = [];

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
     * Returns true if language exist, otherwise false.
     *
     * @param string|int $languageKey The language key, locale or id. (case-insensitive)
     * @return bool
     */
    public function has(string|int $languageKey): bool
    {
        return $this->get($languageKey, false) ? true : false;
    }
    
    /**
     * Returns a language by the specified parameters.
     *
     * @param string|int $languageKey The language key, locale, slug or id. (case-insensitive)
     * @param bool $fallback If language does not exist, it gets the fallback if set to true.
     * @return null|LanguageInterface
     */
    public function get(string|int $languageKey, bool $fallback = true): null|LanguageInterface
    {
        // by id:
        if (is_int($languageKey)) {
            
            if (array_key_exists($languageKey, $this->cache['id'] ?? [])) {
                return $this->languages[$this->cache['id'][$languageKey]] ?? null;
            }
            
            return null;
        }
        
        $langKey = strtolower($languageKey);
        
        // check by locale:
        if (array_key_exists($langKey, $this->cache['locale'] ?? [])) {
            return $this->languages[$this->cache['locale'][$langKey]] ?? null;
        }

        // check by key:
        if (array_key_exists($langKey, $this->cache['key'] ?? [])) {
            return $this->languages[$this->cache['key'][$langKey]] ?? null;
        }

        // check by slug:
        if (array_key_exists($langKey, $this->cache['slug'] ?? [])) {
            return $this->languages[$this->cache['slug'][$langKey]] ?? null;
        }
        
        return $fallback === true ? $this->getFallback($languageKey) : null;        
    }
    
    /**
     * Gets language column.
     *
     * @param string $columnKey
     * @param string $indexKey
     * @return array
     */
    public function column(string $columnKey = 'locale', null|string $indexKey = null): array
    {
        return array_column($this->all(), $columnKey, $indexKey);
    }
    
    /**
     * Returns the default language.
     *
     * @return LanguageInterface
     * @throws LanguageException If no default language is found.
     */
    public function default(): LanguageInterface
    {
        if (is_null($this->defaultLanguage)) {
            throw new LanguageException('No default language found');
        }
        
        return $this->defaultLanguage;
    }
    
    /**
     * Returns the current language.
     *
     * @param null|string|int $currentLanguageKey (case-insensitive) If set it changes the current language.
     * @return LanguageInterface
     */    
    public function current(null|string|int $currentLanguageKey = null): LanguageInterface
    {
        // change the current language.
        if ($currentLanguageKey !== null) {
            
            $language = $this->get($currentLanguageKey, fallback: false);

            if (!is_null($language)) {
                $this->currentLanguage = $language;
            }
        }
        
        // set the current language from the default if not set.
        if (is_null($this->currentLanguage)) {
            $this->currentLanguage = $this->default();
        }

        return $this->currentLanguage;
    }
    
    /**
     * Returns the first language.
     *
     * @return null|LanguageInterface
     */    
    public function first(): null|LanguageInterface
    {
        $languages = $this->all();
        
        $key = array_key_first($languages);
        
        if (is_null($key)) {
            return null;
        }
        
        return $languages[$key];
    }
    
    /**
     * Returns all languages.
     *
     * @return array<int, LanguageInterface>
     */    
    public function all(): array
    {
        return $this->languages;
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
        $filtered = array_filter($this->all(), $callback);
        
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
        
        foreach($this->all() as $language) {
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
        $languages = $this->all();
        
        usort($languages, $callback);
        
        return new static(...$languages);
    }
    
    /**
     * Returns a new instance with the active or inactive languages.
     *
     * @param bool $active
     * @return static
     */
    public function active(bool $active = true): static
    {
        return $this->filter(fn(LanguageInterface $l): bool => $l->active() === $active);
    }
    
    /**
     * Returns a new instance with the specified domain filtered.
     *
     * @param null|string $domain
     * @return static
     */
    public function domain(null|string $domain): static
    {
        return $this->filter(fn(LanguageInterface $l): bool => $l->domain() === $domain);
    }

    /**
     * Returns the fallbacks.
     *
     * @param string $indexKey The key to get set.
     * @return array The fallbacks.
     */
    public function fallbacks(string $indexKey = 'locale'): array
    {
        if (is_null($this->fallbacks)) {
            
            $this->fallbacks = [];
            
            foreach($this->all() as $language) {
                
                if (is_null($language->fallback())) {
                    continue;
                }
                
                $fallbackLanguage = $this->get($language->fallback(), false);

                if (!is_null($fallbackLanguage)) {
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
     * Returns the fallback language for the given language key.
     *
     * @param string|int $languageKey The language key, locale, slug or id.
     * @return LanguageInterface
     */
    public function getFallback(string|int $languageKey): LanguageInterface
    {
        if (is_int($languageKey)) {
            $fallbacks = $this->fallbacks('id');
            $fallbackKey = $fallbacks[$languageKey] ?? null;
        } else {
            $fallbacks = $this->fallbacks('locale');
            $fallbackKey = $fallbacks[$languageKey] ?? null;
            
            if ($fallbackKey === null) {
                $fallbacks = $this->fallbacks('key');
                $fallbackKey = $fallbacks[$languageKey] ?? null;
            }
            
            if ($fallbackKey === null) {
                $fallbacks = $this->fallbacks('slug');
                $fallbackKey = $fallbacks[$languageKey] ?? null;
            }
        }
        
        if ($fallbackKey === null) {
            return $this->default();
        }
        
        return $this->get($fallbackKey) ?: $this->default();
    }

    /**
     * Add languages.
     *
     * @param LanguageInterface $language
     * @return void
     */
    protected function addLanguages(LanguageInterface ...$languages): void
    {
        $this->languages = $languages;
        
        foreach($this->languages as $key => $language) {
            // cache data:
            $this->cache['locale'][strtolower($language->locale())] = $key;
            $this->cache['key'][strtolower($language->key())] = $key;
            $this->cache['slug'][strtolower($language->slug())] = $key;
            $this->cache['id'][$language->id()] = $key;
            
            // set default language:
            if (
                is_null($this->defaultLanguage)
                && $language->default()
            ) {
                $this->defaultLanguage = $language;
            }
        }
    }
}