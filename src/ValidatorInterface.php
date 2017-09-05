<?php namespace EnterCode\Kernel\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * Model's validating interface.
 */
interface ValidatorInterface
{
    /**
     * Get's validation rules array.
     *
     * @param string $rulesType
     * @return array
     */
    public function getRules($rulesType = 'store');

    /**
     * Validates data.
     *
     * @param array $data
     * @param string $type
     * @param array $messages
     * @param array $customAttributes
     * @throws ValidationException
     */
    public function validate(array $data, $type = '', array $messages = [], array $customAttributes = []);

    /**
     * Validates data with model.
     *
     * @param Model $model
     * @param array $data
     * @param string $type
     * @param array $messages
     * @param array $customAttributes
     * @throws ValidationException
     */
    public function validateModel(
        Model $model,
        array $data,
        $type = '',
        array $messages = [],
        array $customAttributes = []
    );
}
