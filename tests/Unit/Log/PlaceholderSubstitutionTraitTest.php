<?php

declare(strict_types=1);

namespace Tests\Unit\Log;

use PHPUnit\Framework\TestCase;
use Efl\Log\PlaceholderSubstitutionTrait;

/**
 * Tests the PlaceholderSubstitutionTrait substitution function
 *
 * @group Logging
 * @author dev
 */
class PlaceholderSubstitutionTraitTest extends TestCase
{
    use PlaceholderSubstitutionTrait;

    /**
     * @small
     * @return void
     */
    public function testScalarPlaceholders(): void
    {
        $message = 'It is {bool} that variable {string} = {float}';
        $sustituted = $this->substitutePlaceholders(
            $message,
            ['bool' => true, 'string' => '$var', 'float' => 11.4]
        );
        $this->assertEquals('It is 1 that variable $var = 11.4', $sustituted);
    }

    /**
     * @small
     * @return void
     */
    public function testInvalidPlaceholdersHaveNoEffect(): void
    {
        $message = 'It is {bool} that variable {string} = {float}';
        $sustituted = $this->substitutePlaceholders(
            $message,
            ['invbool' => true, 'string' => '$var', 'float' => 11.4]
        );
        $this->assertEquals('It is {bool} that variable $var = 11.4', $sustituted);
    }

    /**
     * @small
     * @return void
     */
    public function testArraysAndObjects(): void
    {
        $message = 'Object is {obj} and array is {arr}';
        $sustituted = $this->substitutePlaceholders(
            $message,
            ['obj' => new \stdClass(), 'arr' => []]
        );

        $this->assertStringContainsString('Object is stdClass', $sustituted);
        $this->assertStringContainsString('array is Array', $sustituted);
    }
}
