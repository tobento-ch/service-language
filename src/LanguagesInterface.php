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
     * Returns true if language exist, otherwise false.
     *
     * @param string|int $languageKey The language key, locale or id. (case-insensitive)
     * @return bool
     */
    public function has(string|int $languageKey): bool;
    
    /**
     * Returns a language by the specified parameters.
     *
     * @param string|int $languageKey The language key, locale, slug or id. (case-insensitive)
     * @param bool $fallback If language does not exist, it gets the fallback if set to true.
     * @return null|LanguageInterface
     */
    public function get(string|int $languageKey, bool $fallback = true): null|LanguageInterface;
    
    /**
     * Gets language column.
     *
     * @param string $columnKey
     * @param string $indexKey
     * @return array
     */
    public function column(string $columnKey = 'locale', null|string $indexKey = null): array;
    
    /**
     * Returns the default language.
     *
     * @return LanguageInterface
     * @throws LanguageException If no default language is found.
     */
    public function default(): LanguageInterface;
    
    /**
     * Returns the current language.
     *
     * @param null|string|int $currentLanguageKey (case-insensitive) If set it changes the current language.
     * @return LanguageInterface
     */    
    public function current(null|string|int $currentLanguageKey = null): LanguageInterface;
    
    /**
     * Returns the first language.
     *
     * @return null|LanguageInterface
     */    
    public function first(): null|LanguageInterface;
    
    /**
     * Returns all languages.
     *
     * @return array<int, LanguageInterface>
     */    
    public function all(): array;
    
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
     * Returns a new instance with the active or inactive languages.
     *
     * @param bool $active
     * @return static
     */
    public function active(bool $active = true): static;
    
    /**
     * Returns a new instance with the specified domain filtered.
     *
     * @param null|string $domain
     * @return static
     */
    public function domain(null|string $domain): static;

    /**
     * Returns the fallbacks.
     *
     * @param string $indexKey The key to get set.
     * @return array The fallbacks.
     */
    public function fallbacks(string $indexKey = 'locale'): array;

    /**
     * Returns the fallback language for the given language key.
     *
     * @param string|int $languageKey The language key, locale, slug or id.
     * @return LanguageInterface
     */
    public function getFallback(string|int $languageKey): LanguageInterface;
}