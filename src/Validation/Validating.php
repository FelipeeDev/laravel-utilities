<?php namespace FelipeeDev\Utilities\Validation;

use Illuminate\Validation\Validator;

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
     * @param Validator $validator
     * @param string $type (optional) Type of validation.
     * @return void
     */
    public function onValidate(Validator $validator, string $type = null);
}
