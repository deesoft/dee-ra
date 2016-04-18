<?php

namespace app\controllers\master;

use Yii;
use yii\web\Controller;
use app\models\master\Product;
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
class ProductController extends Controller
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
        $serializer = $this->serializer;
        $serializer->setExpands(['nmStatus', 'category']);
        $serializer->setExceptField(['*.created_at']);
        $serializer->fieldMap = [
            
        ];
        $serializer->setFilter(Yii::$app->getRequest()->get('q', []));
        $query = Product::find()
            ->alias('p')
            ->joinWith('category');

        return $query;
    }

    public function actionDatum($id)
    {
        return $this->findModel($id);
    }

    public function actionSave($id = null)
    {
        $model = $id === null ? new Product() : $this->findModel($id);

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
     * @return Product
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Requested object not found');
        }
    }
}
