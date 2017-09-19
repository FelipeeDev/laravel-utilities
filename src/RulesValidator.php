<?php namespace FelipeeDev\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class RulesValidator
{
    /**
     * Custom messages array.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Custom attributes array.
     *
     * @var array
     */
    protected $customAttributes = [];

    /**
     * Validates data.
     *
     * @param array $data
     * @param Validation\CustomAttributes|Validation\Messages|Validation\Rules|Validation\Validating $rules
     * @param string $type
     * @throws ValidationException
     */
    final public function validate(array $data, $rules, string $type = null)
    {
        try {
            $this->setMessages($rules);
            $this->setCustomAttributes($rules);

            $validator = app('validator')->make(
                array_filter($data),
                $rules->getRules($type),
                $this->messages,
                $this->customAttributes
            );

            $this->validating($validator, $rules, $type);
        } catch (\TypeError $e) {
        }

        if (isset($validator) && $validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Validates model's data.
     *
     * @param Model $model
     * @param Validation\Rules|Eloquent\ModelAccess $rules
     * @param string $type
     */
    public function validateModel(Model $model, Validation\Rules $rules, string $type = null)
    {
        try {
            $this->setModel($rules, $model);
        } catch (\TypeError $e) {
        }

        $this->validate($model->getAttributes(), $rules, $type);
    }

    private function setMessages(Validation\Messages $rules, string $type = null)
    {
        $this->messages = $rules->getMessages($type);
    }

    private function setCustomAttributes(Validation\CustomAttributes $rules, string $type = null)
    {
        $this->customAttributes = $rules->getCustomAttributes($type);
    }

    private function validating(Validator $validator, Validation\Validating $rules, string $type = null)
    {
        $rules->onValidate($validator, $type);
    }

    private function setModel(Eloquent\ModelAccess $rules, Model $model)
    {
        $rules->setModel($model);
    }
}
