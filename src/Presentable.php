<?php namespace FelipeeDev\Utilities;

use FelipeeDev\Utilities\Exceptions\PresenterException;

/**
 * @property string|Presenter presenter
 */
trait Presentable
{
    /**
     * Presenter instance
     *
     * @var Presenter|null
     */
    protected $presenterInstance;

    /**
     * Make presenter.
     *
     * @return Presenter
     * @throws PresenterException
     */
    public function present()
    {
        if (!$this->presenter || !class_exists($this->presenter)) {
            throw new PresenterException('Please set the $presenter property to your presenter path.');
        }

        if (!$this->presenterInstance) {
            $this->presenterInstance = new $this->presenter($this);
        }

        return $this->presenterInstance;
    }
}
