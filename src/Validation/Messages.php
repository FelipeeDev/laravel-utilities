<?php namespace FelipeeDev\Utilities\Validation;

/**
 * Interface Messages
 * @package FelipeeDev\Utilities\Validation
 *
 * Model's validation custom messages
 */
interface Messages
{
    public function getMessages(string $type = null): array;
}
