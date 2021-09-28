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

use Exception;
use Throwable;

/**
 * CurrentLanguageResolverException
 */
class CurrentLanguageResolverException extends Exception
{
    /**
     * Create a new CurrentLanguageResolverException
     *
     * @param int|string $currentLanguage The current language locale, key, slug or id.
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected int|string $currentLanguage,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Get the current language.
     *
     * @return int|string
     */
    public function currentLanguage(): int|string
    {
        return $this->currentLanguage;
    }    
}