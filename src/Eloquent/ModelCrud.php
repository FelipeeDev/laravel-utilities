<?php namespace FelipeeDev\Utilities\Eloquent;

use FelipeeDev\Utilities\DependenciesTrait;
use FelipeeDev\Utilities\RulesValidator;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Overview
 * ==============
 * Application's services trait.
 *
 * @property \FelipeeDev\Utilities\RepositoryInterface    repository   The main repository.
 * @property \FelipeeDev\Utilities\Validation\Rules|null  rules        (optional) Rules instance.
 * @property \FelipeeDev\Utilities\Adapter|null           adapter      (optional) Input adapter instance.
 */
trait ModelCrud
{
    use DependenciesTrait;

    /**
     * @param array $input
     * @param array $defaults
     * @return Model
     */
    public function create(array $input = [], array $defaults = []): Model
    {
        $model = $this->repository->newInstance();
        $input = $this->adaptInput($model, $input, $defaults);
        $this->preserve($model, 'store', $input);

        return $model;
    }

    /**
     * @param Model $model
     * @param array $input
     * @param array $defaults
     */
    public function update(Model $model, array $input, array $defaults = [])
    {
        $input = $this->adaptInput($model, $input, $defaults);
        $input[$model->getKeyName()] = $model->getKey();
        $this->preserve($model, 'update', $input);
    }

    /**
     * @param Model $model
     * @param array $input
     * @param array $defaults
     */
    public function storeOrUpdate(Model $model, array $input, array $defaults = [])
    {
        $input = $this->adaptInput($model, $input, $defaults);
        if (!$model->getKey()) {
            $type = 'store';
        } else {
            $type = 'update';
            $input[$model->getKeyName()] = $model->getKey();
        }
        $this->preserve($model, $type, $input);
    }

    /**
     * @param Model $model
     * @param string $rulesType
     */
    public function validate(Model $model, $rulesType = 'store')
    {
        if (!$this->hasDependency('rules')) {
            return;
        }
        app(RulesValidator::class)->validateModel($model, $this->rules, $rulesType);
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
     * @param Model $model
     * @param array $input
     * @param array $defaults
     * @return array
     */
    protected function adaptInput(Model $model, array $input = [], $defaults = []): array
    {
        if (!$this->hasDependency('adapter')) {
            return $input;
        }

        return $this->adapter->make($input, $model, $defaults)->toArray();
    }

    /**
     * @param Model $model
     * @param string $type
     * @param array $input
     */
    protected function preserve(Model $model, $type, array $input)
    {
        $model->fill($input);
        $this->validate($model, $type);

        $model->save();
    }
}
