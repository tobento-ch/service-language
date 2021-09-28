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
 * CurrentLanguageResolverInterface
 */
interface CurrentLanguageResolverInterface
{
    /**
     * Resolve the current language.
     *
     * @param LanguagesInterface $languages
     * @return void
     *
     * @throws CurrentLanguageResolverException If the current language could not be resolved.
     */
    public function resolve(LanguagesInterface $languages): void;
}