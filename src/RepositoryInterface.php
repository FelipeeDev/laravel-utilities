<?php namespace FelipeeDev\Utilities;

/**
 * An model's repository contract.
 */
interface RepositoryInterface
{
    /**
     * Find a model by its primary key.
     *
     * @param int $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find($id, array $columns = ['*']);

    /**
     * Find a model by its primary key. If not then ne instance is being created.
     *
     * @param int $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrCreate($id, array $columns = ['*']);

    /**
     * Alias of findOrCreate.
     *
     * @param int $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrNew($id, array $columns = ['*']);

    /**
     * Find a many models by its primary keys.
     *
     * @param array $ids
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany(array $ids, array $columns = ['*']);

    /**
     * Find a model by its primary key. If not then exception is being handle.
     *
     * @param int $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, array $columns = ['*']);

    /**
     * Get all result.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get();

    /**
     * Alias of a get method.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all();

    /**
     * Creates model's new instance.
     *
     * @param array $data
     * @param bool $exists
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function newInstance(array $data = [], $exists = false);

    /**
     * Get repository model's instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel();

    /**
     * Get name of a class.
     *
     * @return string
     */
    public function getModelClass();

    /**
     * Gets new Eloquent model's query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query();

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery();
}
