<?php

namespace classes\data;

use yii\base\Behavior;

/**
 * Description of ProxySerializer
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ProxySerializer extends Behavior
{
    /**
     * @var SerializeFilter
     */
    public $serializer;

    /**
     * Get serialize object
     * @return SerializeFilter
     */
    public function getSerializer()
    {
        return $this->serializer;
    }
}
