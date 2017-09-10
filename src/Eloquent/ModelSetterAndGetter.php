<?php namespace FelipeeDev\Utilities\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * ModelSetterAndGetter Trait overview
 * ==============================
 *
 * Trait for cover ModelRule interface.
 */
trait ModelSetterAndGetter
{
    /**
     * Rules's model.
     *
     * @var Model
     */
    private $ruleModel;

    /**
     * Set validating model.
     *
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->ruleModel = $model;
    }

    /**
     * Get's validating model.
     */
    public function getModel(): Model
    {
        return $this->ruleModel;
    }
}
