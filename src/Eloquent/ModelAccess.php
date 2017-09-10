<?php namespace FelipeeDev\Utilities\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Model's getter and setter interface.
 */
interface ModelAccess
{
    /**
     * Set validating model.
     *
     * @param Model $model
     */
    public function setModel(Model $model);

    /**
     * Get's validating model.
     */
    public function getModel(): Model;
}
