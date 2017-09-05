<?php namespace FelipeeDev\Utilities;

use FelipeeDev\Utilities\Exceptions\DependencyNotFoundException;

/**
 * Trait Overview
 * ===============
 * Class that is using the following trait requires to have defined `dependencies` array with the pairs of a:
 * `[propertyName => Class\Name, ...]`
 *
 * @property array dependencies
 */
trait DependenciesTrait
{
    public function getDependency($name)
    {
        if (!($dependency = array_get($this->dependencies, $name))) {
            throw new DependencyNotFoundException();
        }

        if (!is_object($dependency)) {
            $this->dependencies[$name] = app($dependency);
        }

        return $this->dependencies[$name];
    }

    public function resetDependency($name)
    {
        if (!($dependency = array_get($this->dependencies, $name))) {
            throw new DependencyNotFoundException();
        }

        if (!is_object($dependency)) {
            return $this->dependencies[$name] = app($dependency);
        }

        return $this->dependencies[$name] = app(get_class($this->dependencies[$name]));
    }

    public function __get($name)
    {
        try {
            return $this->getDependency($name);
        } catch (DependencyNotFoundException $e) {
        }
    }
}
