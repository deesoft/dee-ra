<?php

namespace app\controllers\sales;

use Yii;
use app\classes\Controller;
use yii\db\Query;

/**
 * Description of SalesController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SalesController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMasterItem($q='')
    {
        Yii::$app->getResponse()->format = 'json';
        
        $items = (new Query())
            ->select(['pd.id', 'pd.code', 'p.name', 'pc.price'])
            ->from(['pd' => '{{%product_detail}}'])
            ->innerJoin(['p' => '{{%product}}'], '[[p.id]]=[[pd.product_id]]')
            ->innerJoin(['pc' => '{{%price}}'], '[[pd.id]]=[[pc.item_id]]')
            ->where(['pc.category_id' => 1, 'pd.isi' => 1])
            ->andFilterWhere(['like','p.name',$q])
            ->orderBy(['p.name'=>SORT_ASC])
            ->limit(10)->all();

        return $items;
    }
}
