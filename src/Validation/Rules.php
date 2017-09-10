<?php namespace FelipeeDev\Utilities\Validation;

/**
 * Model's validation rules interface.
 */
interface Rules
{
    /**
     * Get's validation rules array.
     *
     * @param string $type
     * @return array
     */
    public function getRules(string $type = null): array;
}
