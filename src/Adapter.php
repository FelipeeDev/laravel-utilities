<?php namespace FelipeeDev\Utilities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Basic data adapter.
 */
abstract class Adapter implements Arrayable
{
    const ADAPT_PREFIX = 'adapt_';

    /**
     * @var Collection
     */
    protected $input;

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var Collection
     */
    protected $output;

    /**
     * Adapter constructor.
     *
     * @param array $input
     * @param array|Model $fillable
     * @param array $defaults
     * @return Adapter
     */
    final public function make(array $input, $fillable, array $defaults = []): Adapter
    {
        $this->input = Collection::make($input);
        try {
            $this->setFillable($fillable);
        } catch (\TypeError $e) {
            $this->fillable =  (array)$fillable;
        }
        try {
            $this->setDefaults($fillable, $defaults);
        } catch (\TypeError $e) {
            $this->defaults =  $defaults;
        }

        $this->output = Collection::make([]);
        $this->process();
        return $this;
    }

    /**
     * @param string $key
     * @return Collection|mixed
     */
    public function get($key = '')
    {
        if (!$key) {
            return $this->output;
        }

        return $this->output->get($key);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return Collection|mixed
     */
    public function getInput(string $key = '', $default = null)
    {
        if (empty($key)) {
            return $this->input;
        }
        return array_get($this->input, $key, $default);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->output->getIterator();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->output->toArray();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    private function setFillable(Model $model)
    {
        $this->fillable = $model->getFillable();
    }

    private function setDefaults(Model $model, array $defaults = [])
    {
        $this->defaults = array_merge($model->getAttributes(), $defaults);
    }

    private function process()
    {
        foreach ($this->fillable as $key) {
            $value = $this->input->get($key, array_get($this->defaults, $key));
            $method = camel_case(Adapter::ADAPT_PREFIX . $key);
            if (method_exists($this, $method)) {
                $value = $this->$method($value);
            }
            $this->output->put($key, $value);
        }
    }
}
