<?php

namespace app\classes;

use yii\base\Object;
use yii\db\BaseActiveRecord;

/**
 * Description of ARCollection
 *
 * @property BaseActiveRecord[] $records
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ARCollection extends Object implements \IteratorAggregate, \ArrayAccess, \Countable
{
    public $modelClass;

    /**
     * @var BaseActiveRecord
     */
    public $model;

    /**
     *
     * @var array
     */
    public $link;

    /**
     *
     * @var string 
     */
    public $name;

    /**
     *
     * @var BaseActiveRecord[];
     */
    private $_records;

    /**
     *
     * @var BaseActiveRecord[];
     */
    private $_deleteds = [];
    private $_scenario;

    public function __construct($records = [], $config = [])
    {
        $this->_records = $records;
        parent::__construct($config);
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param integer $offset the offset to set element
     * @param BaseActiveRecord|array $item the element value
     */
    public function set($offset, $item)
    {
        if (isset($this->_records[$offset]) && !$this->_records[$offset]->isNewRecord) {
            if ($item instanceof $this->modelClass) {
                if ($item->isNewRecord || $item->oldPrimaryKey == $this->_records[$offset]->oldPrimaryKey) {
                    $item->oldAttributes = $this->_records[$offset]->oldAttributes;
                } else {
                    $this->_deleteds[] = $this->_records[$offset];
                }
                $this->_records[$offset] = $item;
                if ($this->_scenario) {
                    $this->_records[$offset]->setScenario($this->_scenario);
                }
            } else {
                if ($this->_scenario) {
                    $this->_records[$offset]->setScenario($this->_scenario);
                }
                $this->_records[$offset]->setAttributes($item);
            }
        } elseif ($item instanceof $this->modelClass) {
            $this->_records[$offset] = $item;
            if ($this->_scenario) {
                $this->_records[$offset]->setScenario($this->_scenario);
            }
        } else {
            $class = $this->modelClass;
            $this->_records[$offset] = new $class();
            if ($this->_scenario) {
                $this->_records[$offset]->setScenario($this->_scenario);
            }
            $this->_records[$offset]->setAttributes($item);
        }
        foreach ($this->link as $from => $to) {
            $this->_records[$offset]->$from = $this->model->$to;
        }
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param integer $offset the offset to retrieve element.
     * @return mixed the element at the offset, null if no element is found at the offset
     */
    public function get($offset)
    {
        return isset($this->_records[$offset]) ? $this->_records[$offset] : null;
    }

    public function add($item)
    {
        if ($item instanceof $this->modelClass) {
            if ($this->_scenario) {
                $item->setScenario($this->_scenario);
            }
            $this->_records[] = $item;
        } else {
            $class = $this->modelClass;
            $attributes = $item;
            $item = new $class;
            if ($this->_scenario) {
                $item->setScenario($this->_scenario);
            }
            $item->setAttributes($attributes);
            $this->_records[] = $item;
        }
        foreach ($this->link as $from => $to) {
            $item->$from = $this->model->$to;
        }
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param mixed $offset the offset to unset element
     */
    public function remove($offset)
    {
        if (isset($this->_records[$offset]) && !$this->_records[$offset]->isNewRecord) {
            $this->_deleteds[] = $this->_records[$offset];
        }
        unset($this->_records[$offset]);
    }

    public function setRecords($items)
    {
        $olds = $this->_records;
        foreach ($items as $i => $item) {
            $this->set($i, $item);
            unset($olds[$i]);
        }
        foreach ($olds as $i => $item) {
            if (!$item->isNewRecord) {
                $this->_deleteds[] = $item;
            }
            unset($this->_records[$i]);
        }
    }

    public function getRecords()
    {
        return $this->_records;
    }

    public function getScenario()
    {
        return $this->_scenario;
    }

    public function setScenario($value)
    {
        foreach ($this->_records as $item) {
            $item->setScenario($value);
        }
        $this->_scenario = $value;
    }

    public function validate()
    {
        $valid = true;
        foreach ($this->_records as $i => $model) {
            if (!$model->validate()) {
                $valid = false;
                foreach ($model->getFirstErrors() as $attribute => $error) {
                    $this->model->addError("{$this->name}-{$i}-$attribute", $error);
                }
            }
        }
        return $valid;
    }

    public function save($runValidate = true)
    {
        if (!$runValidate || $this->validate()) {
            foreach ($this->_deleteds as $model) {
                $model->delete();
            }
            foreach ($this->_records as $model) {
                foreach ($this->link as $from => $to) {
                    $model->$from = $this->model->$to;
                }
                $model->save(false);
            }
            $this->_deleteds = [];
            return true;
        }
        return false;
    }

    /**
     * Returns an iterator for traversing the data.
     * This method is required by the SPL interface [[\IteratorAggregate]].
     * It will be implicitly called when you use `foreach` to traverse the collection.
     * @return \ArrayIterator an iterator for traversing the cookies in the collection.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_records);
    }

    /**
     * Returns the number of data items.
     * This method is required by Countable interface.
     * @return integer number of data elements.
     */
    public function count()
    {
        return count($this->_records);
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param mixed $offset the offset to check on
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_records[$offset]);
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param integer $offset the offset to retrieve element.
     * @return mixed the element at the offset, null if no element is found at the offset
     */
    public function offsetGet($offset)
    {
        return isset($this->_records[$offset]) ? $this->_records[$offset] : null;
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param integer $offset the offset to set element
     * @param mixed $item the element value
     */
    public function offsetSet($offset, $item)
    {
        $this->set($offset, $item);
    }

    /**
     * This method is required by the interface [[\ArrayAccess]].
     * @param mixed $offset the offset to unset element
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
