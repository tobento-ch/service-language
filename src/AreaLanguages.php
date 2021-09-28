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
            try {
                $this->languages[$area] = $languagesFactory->createLanguages(...$languages);    
            } catch (LanguageException $e) {
                throw new LanguageException('No default language found for the '.$area.' area');
            }
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
}