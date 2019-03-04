<?php

namespace TrueLayer;

class Data
{
    /**
     * Hold our data as dotted.
     *
     * @return array
     */
    protected $data = [];
    public $should_dot = true;

    /**
     * A magic getter
     * @param $name
     * @param $arguments
     * @return mixed|null
     */
    public function __call($name, $arguments)
    {
        $name = str_replace("get_", "", $this->convert($name));
        $properties = get_object_vars($this);
        return in_array($name, $properties) ? $this->{$name} : null;
    }

    /**
     * An empty map
     *
     * @return array
     */
    public function map()
    {
        return [];
    }

    /**
     * Build out a mode from an array
     *
     * @param array data
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
        if (count($this->map()) > 0) {
            foreach ($this->map() as $key => $to) {
                if (isset($to['collect'])) {
                    $collection = [];
                    foreach ($to['collect'] as $ref => $lookup) {
                        $collection[$ref] = $this->getDataValue($lookup);
                    }
                    $this->{$key} = $collection;
                } else if (isset($to['key'])) {
                    $this->{$key} = $this->getDataValue($to['key']);
                } else {
                    continue;
                }

                if (isset($to['callback']) && is_callable($to['callback'])) {
                    $this->{$key} = $to['callback']($this->{$key});
                }
            }
        }
    }

    /**
     * Set out data array
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $this->should_dot ? $this->dot($data) : $data;
        return $this;
    }

    /**
     * Return a value to set
     *
     * @param string $key
     * @return mixed|string
     */
    public function getDataValue($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : "";
    }

    /**
     * Function to covert from camel
     *
     * @param string $string
     * @return string $string
     */
    public function convert($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        $vars = get_object_vars($this);
        $array = [];
        foreach ($vars as $var) {
            $array[$var] = $this->{$var};
        }

        return $array;
    }

    /**
     * Convert to json string
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array $array
     * @param  string $prepend
     * @return array
     */
    public function dot($array, $prepend = '')
    {
        $results = [];
        foreach ($array as $key => $value) {

            if (is_array($this->should_dot) && in_array($key, $this->should_dot)) {
                $results[$key] = $value;
                continue;
            }

            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, $this->dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        return $results;
    }
}