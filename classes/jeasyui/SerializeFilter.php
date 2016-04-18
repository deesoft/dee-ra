<?php

namespace classes\jeasyui;

use Yii;
use yii\base\Model;
use yii\db\QueryInterface;
use yii\web\Request;
use yii\web\Response;
use yii\base\ActionFilter;

/**
 * Description of SerializeFilter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SerializeFilter extends ActionFilter
{
    /**
     *
     * @var string Response format
     */
    public $format = 'json';
    /**
     *
     * @var string
     */
    public $alias;
    /**
     *
     * @var array
     */
    public $fieldMap = [];
    /**
     *
     * @var Request
     */
    public $request;
    /**
     *
     * @var Response
     */
    public $response;
    /**
     *
     * @var boolean
     */
    public $onlyAjax = true;
    public $meta;
    public $envelope = 'rows';
    /**
     *
     * @var array expanded field
     */
    private $_expands;
    private $_excepts = [];
    private $_q;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->request === null) {
            $this->request = Yii::$app->getRequest();
        }
        if ($this->response === null) {
            $this->response = Yii::$app->getResponse();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        if ($result instanceof Response) {
            return $result;
        }
        $result = $this->serializeData($result);
        if ($this->format && is_array($result)) {
            $this->response->format = $this->format;
        }
        return $result;
    }

    /**
     *
     * @return static
     */
    public function getSerializer()
    {
        return $this;
    }

    /**
     * Serializes the given data into a format that can be easily turned into other formats.
     * This method mainly converts the objects of recognized types into array representation.
     * It will not do conversion for unknown object types or non-object data.
     * The default implementation will handle [[Model]] and [[DataProviderInterface]].
     * You may override this method to support more object types.
     * @param mixed $data the data to be serialized.
     * @return mixed the converted data.
     */
    public function serializeData($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            $this->response->setStatusCode(422, 'Data Validation Failed.');
            return $data->getFirstErrors();
        } elseif (is_array($data) || $data instanceof Model) {
            return $this->serializeObject($data, $this->getExpands(), $this->_excepts);
        } elseif ($data instanceof QueryInterface) {
            return $this->serializeQuery($data);
        }
        return $data;
    }

    /**
     * Serializes a model object.
     * @param mixed $object
     * @return array the array representation of the model
     */
    protected function serializeObject($object, $expands, $excepts)
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
                    $itemExpands = isset($expands[$key]) ? $this->resolveExpand($expands[$key]) : [];
                    $itemExcepts = isset($excepts[$key]) ? $this->resolveExpand($excepts[$key]) : [];
                    if (isset($excepts['*'])) {
                        foreach ($excepts['*'] as $field) {
                            $itemExcepts['*'][] = $field;
                        }
                    }
                }
                $data[$key] = $this->serializeObject($value, $itemExpands, $itemExcepts);
            }
        }
        return $data;
    }

    /**
     * Serializes a query.
     * @param QueryInterface $query
     * @return array the array representation of the data provider.
     */
    protected function serializeQuery($query)
    {
        $alias = $this->resolveAlias($query);
        $this->resolveFilter($query, $alias);
        if (($sorting = $this->getSorting($alias)) !== false) {
            $query->orderBy($sorting);
        }
        if (($pagination = $this->getPagination()) !== false) {
            list($limit, $offset) = $pagination;
            $total = $query->count();
            $query->limit($limit)->offset($offset);
        }

        $models = $this->serializeModels($query->all());
        $meta = [
            'status' => 'success',
        ];
        if (isset($total)) {
            $meta['total'] = $total;
        }
        $result = [];
        if ($this->envelope) {
            if ($this->meta) {
                $result[$this->meta] = $meta;
            } else {
                $result = $meta;
            }
            $result[$this->envelope] = $models;
        } else {
            $result = $models;
        }

        return $result;
    }

    /**
     * Get limit offset
     * @return array|boolean
     */
    protected function getPagination()
    {
        $request = $this->request;
        if (($limit = $request->get('rows'))) {
            $offset = ($request->get('page', 1) - 1) * $limit;
            return [$limit, $offset];
        }
        return false;
    }

    /**
     * Get sorting
     * @param string $alias
     * @return array|boolean
     */
    protected function getSorting($alias)
    {
        $request = $this->request;
        $sorts = preg_split('/\s*,\s*/', $request->get('sort'), -1, PREG_SPLIT_NO_EMPTY);
        if (count($sorts)) {
            $orders = preg_split('/\s*,\s*/', $request->get('order'), -1, PREG_SPLIT_NO_EMPTY);
            $sorting = [];
            foreach ($sorts as $i => $name) {
                if (isset($this->fieldMap[$name])) {
                    $name = $this->fieldMap[$name];
                }
                if (strpos($name, '.') === false) {
                    $name = $alias . $name;
                }
                $sorting[$name] = (!isset($orders[$i]) || $orders[$i] == 'asc') ? SORT_ASC : SORT_DESC;
            }
            return $sorting;
        }
        return false;
    }

    /**
     * @param QueryInterface $query
     * @return string
     */
    protected function resolveAlias($query)
    {
        if ($this->alias === null) {
            if (!empty($query->join) || !empty($query->joinWith) || !empty($query->with)) {
                if (!empty($query->from)) {
                    foreach ($query->from as $as => $table) {
                        if (is_string($as)) {
                            $this->alias = $as;
                        } elseif (preg_match('/^(.*?)(?i:\s+as|)\s+([^ ]+)$/', $table, $matches)) {
                            $this->alias = $matches[2];
                        } else {
                            $this->alias = $table;
                        }
                        break;
                    }
                } elseif (isset($query->modelClass)) {
                    $class = $query->modelClass;
                    $this->alias = $class::tableName();
                }
            } else {
                $this->alias = '';
            }
        }
        return empty($this->alias) ? '' : $this->alias . '.';
    }

    /**
     * @param QueryInterface $query
     * @param string $alias
     */
    protected function resolveFilter($query, $alias)
    {
        if (empty($this->_q || !is_array($this->_q))) {
            return;
        }
        $opMap = [
            '=' => 'LIKE',
            '!=' => 'NOT LIKE',
            '==' => '=',
            '!==' => '<>',
        ];
        foreach ($this->_q as $field => $value) {
            if (empty($value)) {
                continue;
            }
            if (is_array($value)) {
                $op = isset($value['op']) ? strtolower($value['op']) : '=';
                $v = isset($value['v']) ? $value['v'] : '';
                $v2 = isset($value['v2']) ? $value['v2'] : '';
            } else {
                $op = '=';
                $v = $value;
                $v2 = '';
            }
            if ($v === '' && $v2 === '') {
                continue;
            }
            if (isset($this->fieldMap[$field])) {
                $field = $this->fieldMap[$field];
            }
            if (strpos($field, '.') === false) {
                $field = $alias . $field;
            }

            $operator = isset($opMap[$op]) ? $opMap[$op] : $op;
            switch ($operator) {
                case '[]':
                case '![]':
                    if ($v !== '' && $v2 !== '') {
                        $query->andWhere([$operator == '[]' ? 'BETWEEN' : 'NOT BETWEEN', $field, $v, $v2]);
                    } elseif ($v !== '' && $v2 === '') {
                        $query->andWhere([$operator == '[]' ? '>=' : '<', $field, $v]);
                    } elseif ($v === '' && $v2 !== '') {
                        $query->andWhere([$operator == '[]' ? '<=' : '>', $field, $v2]);
                    }
                    break;
                default:
                    $query->andWhere([$operator, $field, $v]);
                    break;
            }
        }
    }

    /**
     * Serializes a set of models.
     * @param array $models
     * @return array the array representation of the models
     */
    protected function serializeModels(array $models)
    {
        $expands = $this->getExpands();
        $excepts = $this->_excepts;
        foreach ($models as $i => $model) {
            $models[$i] = $this->serializeObject($model, $expands, $excepts);
        }
        return $models;
    }

    /**
     * Set expand field
     * @param array $expands
     * @param boolean $replace
     */
    public function setExpands($expands, $replace = false)
    {
        if (!is_array($expands)) {
            $expands = preg_split('/\s*,\s*/', $expands, -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->_expands = $this->resolveExpand($expands, $replace ? [] : $this->getExpands());
    }

    /**
     * Set expand field
     * @param array $fields
     * @param boolean $replace
     */
    public function setExceptField($fields, $replace = false)
    {
        if (!is_array($fields)) {
            $fields = preg_split('/\s*,\s*/', $fields, -1, PREG_SPLIT_NO_EMPTY);
        }
        $this->_excepts = $this->resolveExpand($fields, $replace ? [] : $this->_excepts);
    }

    /**
     *
     * @param type $q
     * @return static
     */
    public function setFilter(array $q)
    {
        $this->_q = $q;
        return $this;
    }

    /**
     *
     * @param type $q
     * @return static
     */
    public function addFilter(array $q)
    {
        $this->_q = array_merge(empty($this->_q) ? [] : $this->_q, $q);
        return $this;
    }

    /**
     * Get expand field
     * @return array
     */
    protected function getExpands()
    {
        if ($this->_expands === null) {
            $expands = preg_split('/\s*,\s*/', $this->request->get('expands'), -1, PREG_SPLIT_NO_EMPTY);
            $this->_expands = $this->resolveExpand($expands);
        }
        return $this->_expands;
    }

    /**
     *
     * @param array $expands
     * @return array Description
     */
    protected function resolveExpand(array $expands, $olds = [])
    {
        $olds = [];
        foreach ($expands as $field) {
            $fields = explode('.', $field, 2);
            $olds[$fields[0]][] = isset($fields[1]) ? $fields[1] : false;
        }

        return array_map('array_filter', $olds);
    }

    /**
     * @inheritdoc
     */
    protected function isActive($action)
    {
        return parent::isActive($action) && (!$this->onlyAjax || $this->request->getIsAjax());
    }
}
