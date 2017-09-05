<?php namespace FelipeeDev\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * Class Validates
 *
 * @property array|null rules
 * @property Model model
 */
trait ValidatorTrait
{
    /**
     * @param string $rulesType
     * @return array|mixed
     */
    public function getRules($rulesType = 'store')
    {
        $methodName = 'getRules';

        if (!empty($rulesType)) {
            $methodName = camel_case('get_' . $rulesType . '_rules');
        }

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        if (array_has($this->rules, $rulesType)) {
            return array_get($this->rules, $rulesType);
        }

        return (array)$this->rules;
    }

    /**
     * Validates data.
     *
     * @param array $data
     * @param string $type
     * @param array $messages
     * @param array $customAttributes
     * @throws ValidationException
     */
    public function validate(array $data, $type = '', array $messages = [], array $customAttributes = [])
    {
        $rules = $this->getRules($type);

        $validator = app('validator')->make(array_filter($data), $rules, $messages, $customAttributes);

        if (method_exists($this, 'preValidation')) {
            $this->preValidation($validator);
        }

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

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
    ) {
        $this->model = $model;
        $this->validate($data, $type, $messages, $customAttributes);
    }
}
