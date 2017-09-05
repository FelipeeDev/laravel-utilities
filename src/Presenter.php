<?php namespace FelipeeDev\Utilities;

use Illuminate\Database\Eloquent\Model;

abstract class Presenter
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Presenter constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Access model's attribute via presenter.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        $method = camel_case($property);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->model->{$property};
    }
}
