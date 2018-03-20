<?php namespace FelipeeDev\Utilities;

abstract class Dictionary
{
    /**
     * @var string
     */
    protected $prefix = 'dictionary';

    /**
     * Words with a static keys.
     *
     * @var array
     */
    protected $words = [];

    /**
     * Get a value by a given key from dictionary.
     *
     * @param $key
     * @return string
     */
    public function get($key)
    {
        return array_get($this->words, $key, $key);
    }

    /**
     * Get translation key.
     *
     * @param string|int $key
     * @return string
     */
    public function getTranslationKey($key): string
    {
        return sprintf('%s.%s', $this->prefix, kebab_case($this->get($key)));
    }

    /**
     * Get translated word from dictionary by given key.
     *
     * @param string|int $key
     * @return string
     */
    public function trans($key)
    {
        return trans($this->getTranslationKey($key));
    }

    /**
     * Get full list of dictionary.
     *
     * @return array
     */
    public function words(): array
    {
        return $this->words;
    }

    /**
     * Get full list of dictionary with translated words from dictionary.
     *
     * @return array
     */
    public function lists(): array
    {
        $data = [];
        foreach (array_keys($this->words) as $key) {
            $data[$key] = $this->trans($key);
        }

        return $data;
    }
}
