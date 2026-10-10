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

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * AreaLanguages
 */
class AreaLanguages implements AreaLanguagesInterface
{        
    /**
     * @var array<mixed> Holds the languages.
     */
    protected array $languages = [];

    /**
     * Create a new AreaLanguages.
     *
     * @param null|LanguagesFactoryInterface $languagesFactory
     * @param LanguageInterface ...$languages
     */
    public function __construct(
        null|LanguagesFactoryInterface $languagesFactory = null,
        LanguageInterface ...$languages
    ) {
        $languagesFactory = $languagesFactory ?: new LanguagesFactory();
        
        $areas = [];
        
        foreach($languages as $language)
        {
            $areas[$language->area()][] = $language;
        }
        
        foreach($areas as $area => $languages)
        {
            $this->languages[$area] = $languagesFactory->createLanguages(...$languages);    
        }
    }
    
    /**
     * If languages by area exists.
     *
     * @param string $area
     * @return bool
     */
    public function has(string $area): bool
    {
        return isset($this->languages[$area]);
    }

    /**
     * Get the languages by area.
     *
     * @param string $area
     * @return null|LanguagesInterface
     */    
    public function get(string $area): null|LanguagesInterface
    {
        return $this->languages[$area] ?? null;    
    }
    
    /**
     * Adds languages for the given area.
     *
     * @param string $area
     * @param LanguagesInterface $languages
     * @return static $this
     */    
    public function add(string $area, LanguagesInterface $languages): static
    {
        $this->languages[$area] = $languages;
        return $this;
    }
    
    /**
     * Get all languages key by area.
     *
     * @return array<string, LanguagesInterface>
     */
    public function all(): array
    {
        return $this->languages;
    }
    
    /**
     * Return the total number of languages
     *
     * @return int
     */
    public function count(): int
    {
        $count = 0;

        foreach ($this->languages as $languages) {
            $count += iterator_count($languages);
        }

        return $count;
    }
    
    /**
     * Get iterator.
     *
     * @return Traversable<string, LanguagesInterface>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->all());
    }
}