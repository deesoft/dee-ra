<?php

namespace app\classes;

use yii\base\Behavior;

/**
 * Description of ArrayConverter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ArrayConverter extends Behavior
{
    public $attributes;

    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            $value = $this->owner->{$this->attributes[$name]};
            if (is_resource($value) && get_resource_type($value) === 'stream') {
                $value = stream_get_contents($value);
            }
            return $value ? json_decode($value, true) : null;
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->attributes[$name])) {
            $this->owner->{$this->attributes[$name]} = json_encode($value);
        }
    }

    public function canGetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canSetProperty($name, $checkVars);
    }
}
