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
 * LanguagesFactoryInterface
 */
interface LanguagesFactoryInterface
{
    /**
     * Create a new Languages.
     *
     * @param LanguageInterface ...$languages
     * @return LanguagesInterface
     */
    public function createLanguages(LanguageInterface ...$languages): LanguagesInterface;
}