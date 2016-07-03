<?php

namespace classes\jeasyui;

use Yii;
use classes\data\SerializeFilter as BaseSerializer;

/**
 * Description of SerializeFilter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SerializeFilter extends BaseSerializer
{
    /**
     * @inheritdoc
     */
    public $dataEnvelope = 'rows';

    /**
     * @inheritdoc
     */
    protected function getPagination()
    {
        $request = $this->request;
        if (($limit = $request->get('rows'))) {
            return [$limit, $request->get('page', 1)];
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function getSorting()
    {
        $request = $this->request;
        $sorts = preg_split('/\s*,\s*/', $request->get('sort'), -1, PREG_SPLIT_NO_EMPTY);
        if (count($sorts)) {
            $alias = empty($this->alias) ? '' : rtrim($this->alias, '.') . '.';
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
}
