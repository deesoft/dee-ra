<?php

namespace app\controllers\purchase;

use Yii;
use yii\web\Controller;
use app\models\purchase\Purchase;
use classes\jeasyui\SerializeFilter;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * Description of PurchaseController
 *
 * @property SerializeFilter $serializer
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class PurchaseController extends Controller
{

    public function behaviors()
    {
        return[
            [
                'class' => VerbFilter::className(),
                'actions' => [
                    'save' => ['post']
                ]
            ],
            [
                'class' => SerializeFilter::className(),
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionData()
    {
        $query = Purchase::find();

        return $query;
    }

    public function actionDatum($id)
    {
        return $this->findModel($id);
    }

    public function actionSave($id = null)
    {
        $model = $id === null ? new Purchase() : $this->findModel($id);

        $model->load(Yii::$app->getRequest()->post(), '');
        $model->save();
        return $model;
    }

    public function actionDelete($id)
    {
        return $this->findModel($id)->delete();
    }

    /**
     *
     * @param integer $id
     * @return Purchase
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Purchase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Requested object not found');
        }
    }
}
