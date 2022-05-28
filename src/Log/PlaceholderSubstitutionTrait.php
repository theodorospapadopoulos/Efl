<?php

declare(strict_types=1);

namespace Efl\Log;

/**
 * Helper for log messages placeholders substitution
 *
 * @author Theodoros Papadopoulos
 */
trait PlaceholderSubstitutionTrait
{
    /**
     * Substitutes placeholders of the form {placeholder}
     * with the values in context array. The values are treated loosely
     * as PSR-3 specifies.
     * - Scalar or Stringable types are substituted as normal strings
     * - Object and arrays are substituted via print_r like strings
     *
     * About the special 'exception' key in the context array
     * that PSR-3 talks about: An Exception object is Stringable
     * so it will be substituted successfully. So no special treatment
     * is done for this key.
     *
     * @param string|Stringable $message The string with placeholders
     * @param array<string, mixed> $context
     * @param string $openvar
     * @param string $closevar
     * @return string
     */
    protected function substitutePlaceholders(
        string|\Stringable $message,
        array $context
    ): string {
        $replace = [];
        foreach ($context as $key => $val) {
            if (is_scalar($val) || $val instanceof \Stringable) {
                $replace['{' . $key . '}'] = strval($val);
            } elseif (is_object($val) || is_array($val)) {
                $replace['{' . $key . '}'] = print_r($val, true);
            }
        }

        return strtr($message, $replace);
    }
}
