<?php namespace FelipeeDev\Utilities\Validation;

/**
 * Interface overview.
 * ===================
 *
 * Model rule validator's custom attributes interface.
 */
interface CustomAttributes
{
    public function getCustomAttributes(string $type = null): array;
}
