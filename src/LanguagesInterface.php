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

use IteratorAggregate;

/**
 * LanguagesInterface
 */
interface LanguagesInterface extends IteratorAggregate
{
    /**
     * If has language by key.
     *
     * @param string|int $languageKey The language key, locale or id
     * @param bool $activeOnly
     * @return bool True language key exists, otherwise false.
     */    
    public function has(string|int $languageKey, bool $activeOnly = true): bool;
    
    /**
     * Gets a language.
     *
     * @param string|int $languageKey The language key, locale or id
     * @param bool $fallback If language does not exist, it gets the fallback if set to true.
     * @return null|LanguageInterface
     */    
    public function get(string|int $languageKey, bool $fallback = true): ?LanguageInterface;
    
    /**
     * Gets language column.
     *
     * @param string $columnKey
     * @param string $indexKey
     * @param bool $activeOnly
     * @return array
     */    
    public function column(string $columnKey = 'locale', null|string $indexKey = null, bool $activeOnly = true): array;  
    
    /**
     * Gets the default language.
     *
     * @return LanguageInterface
     */
    public function default(): LanguageInterface;
    
    /**
     * Gets the current language.
     *
     * @param null|string|int $currentLanguageKey If set it changes the current language.
     * @return LanguageInterface
     */    
    public function current(null|string|int $currentLanguageKey = null): LanguageInterface;
    
    /**
     * Returns the first language.
     *
     * @param bool $activeOnly
     * @return null|LanguageInterface
     */    
    public function first(bool $activeOnly = true): null|LanguageInterface;
    
    /**
     * Gets all languages.
     *
     * @param string $indexKey The index key such as id, key, locale, slug.
     * @param bool If true returns only active languages, otherwise all.
     * @return array The languages.
     */    
    public function all(string $indexKey = 'locale', bool $activeOnly = true): array;
    
    /**
     * Returns a new instance with the filtered languages.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static;
    
    /**
     * Returns a new instance with the mapped languages.
     *
     * @param callable $mapper
     * @return static
     */
    public function map(callable $mapper): static;
    
    /**
     * Returns a new instance with the languages sorted.
     *
     * @param callable $callback
     * @return static
     */
    public function sort(callable $callback): static;

    /**
     * Gets the fallbacks.
     *
     * @param string $indexKey The key to get set.
     * @return array The fallbacks.
     */    
    public function fallbacks(string $indexKey = 'locale'): array;

    /**
     * Gets the fallback language for the given language key.
     *
     * @param string|int $languageKey The language key, locale, id or slug.
     * @return LanguageInterface
     */    
    public function getFallback(string|int $languageKey): LanguageInterface;
}