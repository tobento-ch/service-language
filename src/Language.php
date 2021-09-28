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
 * Language
 */
class Language implements LanguageInterface
{
    /**
     * Create a new Language.
     *
     * @param string $locale
     * @param string $iso
     * @param null|string $region
     * @param string $name
     * @param string $key
     * @param int $id
     * @param string $slug
     * @param string $directory
     * @param string $area
     * @param null|string $domain
     * @param null|string $url
     * @param null|string $fallback
     * @param bool $default
     * @param bool $active
     */
    public function __construct(
        protected string $locale,
        protected string $iso,
        protected null|string $region,
        protected string $name,
        protected string $key,
        protected int $id,
        protected string $slug,
        protected string $directory,
        protected string $area,
        protected null|string $domain = null,
        protected null|string $url = null,
        protected null|string $fallback = null,
        protected bool $default = false,
        protected bool $active = true,
    ) {}
        
    /**
     * Get the locale.
     *
     * @return string
     */
    public function locale(): string
    {
        return $this->locale;
    }
    
    /**
     * Get the iso such as en, de.
     *
     * @return string
     */
    public function iso(): string
    {
        return $this->iso;
    }
    
    /**
     * Get the region such as DE, US.
     *
     * @return null|string
     */
    public function region(): null|string
    {
        return $this->region;
    }    
    
    /**
     * Get the name.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
    
    /**
     * Get the key.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }
    
    /**
     * Get the id.
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }
    
    /**
     * Get the slug.
     *
     * @return string
     */
    public function slug(): string
    {
        return $this->slug;
    }    

    /**
     * Get the directory.
     *
     * @return string
     */
    public function directory(): string
    {
        return $this->directory;
    }

    /**
     * Get the area.
     *
     * @return string
     */
    public function area(): string
    {
        return $this->area;
    }
    
    /**
     * Get the domain.
     *
     * @return null|string
     */
    public function domain(): null|string
    {
        return $this->domain;
    }
    
    /**
     * Get the url.
     *
     * @return null|string
     */
    public function url(): null|string
    {
        return $this->url;
    }    
    
    /**
     * Get the fallback.
     *
     * @return null|string
     */
    public function fallback(): null|string
    {
        return $this->fallback;
    }    

    /**
     * Get default.
     *
     * @return bool
     */
    public function default(): bool
    {
        return $this->default;
    }
    
    /**
     * Get active.
     *
     * @return bool
     */
    public function active(): bool
    {
        return $this->active;
    }   
    
    /**
     * __get For array_column object support
     */
    public function __get(string $prop)
    {
        return $this->{$prop}();
    }

    /**
     * __isset For array_column object support
     */
    public function __isset(string $prop): bool
    {
        return method_exists($this, $prop);
    }    
}