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
 * AreaLanguagesInterface
 */
interface AreaLanguagesInterface
{
    /**
     * If languages by area exists.
     *
     * @param string $area
     * @return bool
     */
    public function has(string $area): bool;

    /**
     * Get the languages by area.
     *
     * @param string $area
     * @return null|LanguagesInterface
     */    
    public function get(string $area): null|LanguagesInterface;
}