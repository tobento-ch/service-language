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
 * LanguageInterface
 */
interface LanguageInterface
{
    /**
     * Get the locale.
     *
     * @return string
     */
    public function locale(): string;
    
    /**
     * Get the iso such as en, de.
     *
     * @return string
     */
    public function iso(): string;
    
    /**
     * Get the region such as DE, US.
     *
     * @return null|string
     */
    public function region(): null|string;  
    
    /**
     * Get the name.
     *
     * @return string
     */
    public function name(): string;
    
    /**
     * Get the key.
     *
     * @return string
     */
    public function key(): string;
    
    /**
     * Get the id.
     *
     * @return int
     */
    public function id(): int;
    
    /**
     * Get the slug.
     *
     * @return string
     */
    public function slug(): string;   

    /**
     * Get the directory.
     *
     * @return string
     */
    public function directory(): string;

    /**
     * Get the area.
     *
     * @return string
     */
    public function area(): string;
    
    /**
     * Get the domain.
     *
     * @return null|string
     */
    public function domain(): null|string;
    
    /**
     * Get the url.
     *
     * @return null|string
     */
    public function url(): null|string;    
    
    /**
     * Get the fallback.
     *
     * @return null|string
     */
    public function fallback(): null|string;   

    /**
     * Get default.
     *
     * @return bool
     */
    public function default(): bool;
    
    /**
     * Get active.
     *
     * @return bool
     */
    public function active(): bool;
}