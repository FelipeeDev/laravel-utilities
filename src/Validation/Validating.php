<?php namespace FelipeeDev\Utilities\Validation;

/**
 * Validating interface overview
 * =============================
 *
 * An on before validate interface.
 */
interface Validating
{
    /**
     * Before validation hook. Should throw ValidationException on fail.
     *
     * @param array $data    Array of validating data.
     * @param string $type   (optional) Type of validation.
     * @return void
     */
    public function onValidate(array $data, string $type = null);
}
