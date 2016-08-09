<?php

namespace app\classes;

/**
 * Description of StatusBehavior
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class StatusBehavior extends \yii\base\Behavior
{
    public $lookups = [];
    public $attribute = 'status';
    public $lookupName;

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return (strncmp($name, 'is', 2) === 0 && in_array(substr($name, 2), $this->lookups)) || $name === $this->lookupName;
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return (strncmp($name, 'is', 2) === 0 && in_array(substr($name, 2), $this->lookups)) || $name === $this->lookupName;
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($name === $this->lookupName) {
            $result = [];
            $status = (int) $this->owner->{$this->attribute};
            $x = 1;
            foreach ($this->lookups as $part) {
                if ($x & $status) {
                    $result[] = $part;
                }
                $x *= 2;
            }
            return $result;
        } elseif (strncmp($name, 'is', 2) === 0) {
            $name = strtolower(substr($name, 2));
            $status = (int) $this->owner->{$this->attribute};
            $x = 1;
            foreach ($this->lookups as $part) {
                if ($part === $name) {
                    return $x & $status !== 0;
                }
                $x *= 2;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if ($name === $this->lookupName && is_array($value)) {
            $result = 0;
            $x = 1;
            foreach ($this->lookups as $part) {
                if (in_array($part, $value)) {
                    $result = $result | $x;
                }
                $x *= 2;
            }
            $this->owner->{$this->attribute} = $result;
        } elseif (strncmp($name, 'is', 2) === 0) {
            $name = strtolower(substr($name, 2));
            $status = (int) $this->owner->{$this->attribute};
            $x = 1;
            foreach ($this->lookups as $part) {
                if ($part === $name) {
                    if ($value) {
                        $this->owner->{$this->attribute} = $status | $x;
                    } else {
                        $this->owner->{$this->attribute} = ($status | $x) ^ $x;
                    }
                    return;
                }
                $x *= 2;
            }
        }
    }

    public function addFilter($query, $operator, array $values, $alias = null)
    {
        $x = 1;
        $field = $alias ? $alias . '.' . $this->attribute : $this->attribute;
        $filters = [];
        foreach ($this->lookups as $part) {
            if (in_array($part, $values)) {
                $filters[] = "([[$field]] & $x) = $x";
            }
            $x *= 2;
        }
        /* @var  $query \yii\db\Query*/
        $query->andWhere(implode(" $operator ", $filters));
    }
}
