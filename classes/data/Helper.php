<?php

namespace classes\data;

use yii\base\Model;

/**
 * Description of Helper
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Helper
{

    /**
     * Serializes a model object.
     * @param mixed $object
     * @param array $expands Expand field(s)
     * @param array $excepts Excluded field(s) from result
     *
     * @return array the array representation of the model
     */
    public function serializeObject($object, $expands = [], $excepts = [])
    {
        $expands = static::resolveExpand($expands);
        $excepts = static::resolveExpand($excepts);
        return static::serializeObjectRecursive($object, $expands, $excepts);
    }

    /**
     * Serializes a set of models.
     * @param array $models
     * @param array $expands Expand field(s)
     * @param array $excepts Excluded field(s) from result
     * @return array the array representation of the models
     */
    public static function serializeModels(array $models, $expands = [], $excepts = [])
    {
        $expands = static::resolveExpand($expands);
        $excepts = static::resolveExpand($excepts);
        foreach ($models as $i => $model) {
            $models[$i] = static::serializeObjectRecursive($model, $expands, $excepts);
        }
        return $models;
    }

    /**
     * Serializes a model object.
     * @param mixed $object
     * @param array $expands Expand field(s)
     * @param array $excepts Excluded field(s) from result
     *
     * @return array the array representation of the model
     */
    protected static function serializeObjectRecursive($object, $expands = [], $excepts = [])
    {
        if (is_object($object)) {
            if ($object instanceof Model) {
                $data = $object->attributes;
            } else {
                $data = [];
                foreach ($object as $key => $value) {
                    $data[$key] = $value;
                }
            }
            foreach (array_keys($expands) as $field) {
                if (!array_key_exists($field, $data)) {
                    $data[$field] = $object->$field;
                }
            }
        } else {
            $data = $object;
        }
        foreach ($excepts as $field => $child) {
            if (empty($child) && $field != '*') {
                unset($data[$field]);
            } elseif ($field == '*') {
                foreach ($child as $field) {
                    unset($data[$field]);
                }
            }
        }
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                if (is_int($key)) {
                    $itemExpands = $expands;
                    $itemExcepts = $excepts;
                } else {
                    $itemExpands = isset($expands[$key]) ? static::resolveExpand($expands[$key]) : [];
                    $itemExcepts = isset($excepts[$key]) ? static::resolveExpand($excepts[$key]) : [];
                    if (isset($excepts['*'])) {
                        foreach ($excepts['*'] as $field) {
                            $itemExcepts['*'][] = $field;
                        }
                    }
                }
                $data[$key] = static::serializeObjectRecursive($value, $itemExpands, $itemExcepts);
            }
        }
        return $data;
    }

    /**
     *
     * @param array $expands
     * @return array Description
     */
    public static function resolveExpand(array $expands, $olds = [])
    {
        foreach ($expands as $field) {
            $fields = explode('.', $field, 2);
            $olds[$fields[0]][] = isset($fields[1]) ? $fields[1] : false;
        }

        return array_map('array_filter', $olds);
    }

    /**
     * @param QueryInterface $query
     * @return string
     */
    public static function resolveAlias($query)
    {
        if (!empty($query->join) || !empty($query->joinWith) || !empty($query->with)) {
            if (!empty($query->from)) {
                foreach ($query->from as $alias => $table) {
                    if (is_string($alias)) {
                        return $alias;
                    } elseif (preg_match('/^(.*?)(?i:\s+as|)\s+([^ ]+)$/', $table, $matches)) {
                        return $matches[2];
                    } else {
                        return $table;
                    }
                }
            } elseif (isset($query->modelClass)) {
                $class = $query->modelClass;
                return $class::tableName();
            }
        }
        return '';
    }

    /**
     * Applying filter to query
     * @param QueryInterface $query Query to be applied
     * @param array $params parameter filter
     * @param array $fieldMap Mapping search param to database column
     * @param string $alias default alias for unscoped field
     */
    public static function applyFilter($query, $params = [], $fieldMap = [], $alias = null)
    {
        if (empty($params) || !is_array($params)) {
            return;
        }
        if ($alias === null) {
            $alias = static::resolveAlias($query);
        }
        $alias = empty($alias) ? '' : rtrim($alias, '.') . '.';
        $opMap = [
            '=' => 'LIKE',
            '!=' => 'NOT LIKE',
            '==' => '=',
            '!==' => '<>',
            'contain' => 'LIKE',
            'between' => '[]'
        ];
        foreach ($params as $field => $value) {
            if (empty($value)) {
                continue;
            }
            if (is_array($value)) {
                if (empty($value['value'])) {
                    continue;
                }
                if (isset($value['field'])) {
                    $field = $value['field'];
                }
                $op = isset($value['operator']) ? strtolower($value['operator']) : '=';
                $value = $value['value'];
            } else {
                $op = '=';
            }

            if (isset($fieldMap[$field])) {
                $field = $fieldMap[$field];
            }
            if (strpos($field, '.') === false) {
                $field = $alias . $field;
            }

            $operator = isset($opMap[$op]) ? $opMap[$op] : $op;
            switch ($operator) {
                case '[]':
                case '![]':
                    $v1 = isset($value[0]) ? $value[0] : '';
                    $v2 = isset($value[1]) ? $value[1] : '';
                    if ($v1 !== '' && $v2 !== '') {
                        $query->andWhere([$operator == '[]' ? 'BETWEEN' : 'NOT BETWEEN', $field, $v1, $v2]);
                    } elseif ($v1 !== '' && $v2 === '') {
                        $query->andWhere([$operator == '[]' ? '>=' : '<', $field, $v1]);
                    } elseif ($v1 === '' && $v2 !== '') {
                        $query->andWhere([$operator == '[]' ? '<=' : '>', $field, $v2]);
                    }
                    break;
                default:
                    $query->andWhere([$operator, $field, $value]);
                    break;
            }
        }
    }
}
