<?php

namespace classes\jeasyui;

use yii\helpers\Inflector;

/**
 * Description of DataGrid
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DataGrid extends Widget
{
    public $tag = 'table';
    public $columns = [];
    protected $plugin = 'datagrid';

    /**
     * @inheritdoc
     */
    protected function getClientOptions()
    {
        $columns = [];
        foreach ($this->columns as $key => $column) {
            if (is_string($column)) {
                if (is_int($key)) {
                    $column = [
                        'field' => $column,
                        'title' => Inflector::humanize($column),
                    ];
                } else {
                    $column = [
                        'field' => $key,
                        'title' => $column,
                    ];
                }
            } elseif (is_string($key) && !isset($column['filed'])) {
                $column['field'] = $key;
            }
            $columns[] = $column;
        }
        $this->clientOptions['columns'][] = $columns;
        return $this->clientOptions;
    }
}
