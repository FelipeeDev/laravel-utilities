<?php namespace FelipeeDev\Utilities;

use EnterCode\Kernel\Exceptions\NoModelSetException;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Overview
 * ==============
 * Base model's repositories wrapper.
 * Class containing the following trait should have declared `model` field with is an:
 * `\Illuminate\Database\Eloquent\Model`
 * reference.
 *
 * @property   Model model
 */
trait RepositoryTrait
{
    /**
     * Find a model by its primary key.
     *
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->query()->find($id, $columns);
    }

    /**
     * Find a model by its primary key. If not then ne instance is being created.
     *
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOrCreate($id, array $columns = ['*'])
    {
        $model = $this->query()->find($id, $columns);
        if (!$model) {
            return $this->model->newInstance();
        }

        return $model;
    }

    /**
     * Alias of findOrCreate.
     *
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOrNew($id, array $columns = ['*'])
    {
        return $this->findOrCreate($id, $columns);
    }

    /**
     * Find a many models by its primary keys.
     *
     * @param array $ids
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany(array $ids, array $columns = ['*'])
    {
        return $this->query()->findMany($ids, $columns);
    }

    /**
     * Find a model by its primary key. If not then exception is being handle.
     *
     * @param int $id
     * @param array $columns
     * @return Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, array $columns = ['*'])
    {
        return $this->query()->findOrFail($id, $columns);
    }

    /**
     * Get all result.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get()
    {
        return $this->query()->get();
    }

    /**
     * Alias of a get method.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->get();
    }

    /**
     * Creates model's new instance.
     *
     * @param array $data
     * @param bool $exists
     * @return Model $model
     */
    public function newInstance(array $data = [], $exists = false)
    {
        return $this->getModel()->newInstance($data, $exists);
    }

    /**
     * Get repository model's instance.
     *
     * @return Model
     *
     * @throws NoModelSetException
     */
    public function getModel()
    {
        if (!$this->model || !$this->model instanceof Model) {
            throw new NoModelSetException;
        }

        return $this->model;
    }

    /**
     * Get name of a class.
     *
     * @return string
     */
    public function getModelClass()
    {
        return get_class($this->getModel());
    }

    /**
     * @return string
     */
    public function getMorphClass()
    {
        return $this->getModel()->getMorphClass();
    }

    /**
     * Gets new Eloquent model's query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->getModel()->newQuery();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery()
    {
        return $this->query()->getQuery();
    }
}
