<?php namespace FelipeeDev\Utilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

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
        } catch (\TypeError $e) {
            $this->messages = [];
        }

        try {
            $this->setCustomAttributes($rules);
        } catch (\TypeError $e) {
            $this->customAttributes = [];
        }

        $validator = app('validator')->make(
            array_filter($data),
            $rules->getRules($type),
            $this->messages,
            $this->customAttributes
        );

        try {
            $this->validating($data, $rules, $type);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\TypeError $e) {
        }

        if ($validator->fails()) {
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

    private function validating(array $data, Validation\Validating $rules, string $type = null)
    {
        $rules->onValidate($data, $type);
    }

    private function setModel(Eloquent\ModelAccess $rules, Model $model)
    {
        $rules->setModel($model);
    }
}
