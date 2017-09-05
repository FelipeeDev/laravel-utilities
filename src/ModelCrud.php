<?php namespace FelipeeDev\Utilities;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Overview
 * ==============
 * Application's services trait.
 *
 * @property \EnterCode\Kernel\Validation\Validating|null validator
 * @property string|\EnterCode\Kernel\Repository\Reposable repository
 * @property string|\EnterCode\Kernel\Adapter|null adapter
 */
trait ModelCrud
{
    /**
     * @param array $input
     * @param array $options
     * @return Model
     */
    public function store(array $input = [], array $options = [])
    {
        $model = $this->getRepository()->newInstance();
        $input = $this->adaptInput($model, $input, array_get($options, 'defaults', []));
        $this->preserve($model, 'store', $input, $options);

        return $model;
    }

    /**
     * @param Model $model
     * @param array $input
     * @param array $options
     */
    public function update(Model $model, array $input, array $options = [])
    {
        $input = $this->adaptInput($model, $input, array_get($options, 'defaults', []));
        $input[$model->getKeyName()] = $model->getKey();
        $this->preserve($model, 'update', $input, $options);
    }

    /**
     * @param Model $model
     * @param array $input
     * @param array $options
     */
    public function storeOrUpdate(Model $model, array $input, array $options = [])
    {
        $input = $this->adaptInput($model, $input, array_get($options, 'defaults', []));
        if (!$model->getKey()) {
            $type = 'store';
        } else {
            $type = 'update';
            $input[$model->getKeyName()] = $model->getKey();
        }
        $this->preserve($model, $type, $input, $options);
    }

    /**
     * @param Model $model
     * @param string $rulesType
     * @param array $input
     * @param array $options
     */
    public function validate(
        Model $model,
        $rulesType = 'store',
        array $input = [],
        array $options = []
    ) {
        if (!$this->validator) {
            return;
        }
        $messages = array_get($options, 'messages', []);
        $customAttributes = array_get($options, 'customAttributes', []);
        $this->validator->validateModel($model, $input, $rulesType, $messages, $customAttributes);
    }

    /**
     * @param Model $model
     * @return bool
     * @throws \Exception
     */
    public function destroy(Model $model)
    {
        return $model->delete();
    }

    /**
     * @return \EnterCode\Kernel\Repository\Reposable
     */
    public function getRepository()
    {
        if (!is_object($this->repository)) {
            $this->repository = app($this->repository);
        }

        return $this->repository;
    }

    /**
     * @param Model $model
     * @param array $input
     * @param array $defaults
     * @return array
     */
    protected function adaptInput(Model $model, array $input = [], $defaults = []): array
    {
        if (!isset($this->adapter)) {
            return $input;
        }

        $adapter = new $this->adapter($input, $model, $defaults);
        return $adapter->toArray();
    }

    /**
     * @param Model $model
     * @param string $type
     * @param array $input
     * @param array $options
     */
    protected function preserve(Model $model, $type, array $input, array $options = [])
    {
        $this->validate($model, $type, $input, $options);
        $model->fill($input);
        $model->save();
    }
}
