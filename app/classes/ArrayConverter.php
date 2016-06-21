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
    private $_data;


    public function afterFind()
    {
        
    }
}
