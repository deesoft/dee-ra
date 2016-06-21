<?php

namespace app\controllers\purchase;

use Yii;
use app\models\purchase\Purchase;
use app\models\purchase\search\Purchase as PurchaseSearch;
use app\classes\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\master\Draft;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * PurchaseController implements the CRUD actions for Purchase model.
 */
class PurchaseController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Purchase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Purchase models.
     * @return mixed
     */
    public function actionDraft()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Draft::find(),
        ]);

        return $this->render('draft', [
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchase model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Purchase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($draft_id = null)
    {
        $model = new Purchase();
        $request = Yii::$app->getRequest();
        if ($draft_id && ($draft = Draft::findOne(['id' => $draft_id, 'type' => 210])) !== null) {
            $model->load($draft->value);
        }
        if ($model->load($request->post())) {
            $model->status = 10;
            $model->type = 1;
            if ($request->post('action') == 'draft') {
                if ($model->validate() && $model->items->validate()) {
                    if (!isset($draft)) {
                        $draft = new Draft([
                            'type' => 210,
                        ]);
                    }
                    $draft->description = $model->vendor_name;
                    $draft->value = $request->post();
                    if ($draft->save()) {
                        return $this->redirect(['draft']);
                    }
                }
            } else {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save() && $model->items->save()) {
                        if(isset($draft)){
                            $draft->delete();
                        }
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    $transaction->rollBack();
                } catch (\Exception $exc) {
                    $transaction->rollBack();
                    throw $exc;
                }
            }
        }

        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing Purchase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->vendor){
            $model->vendor_name = $model->vendor->name;
        }
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save() && $model->items->save()) {

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                $transaction->rollBack();
            } catch (\Exception $exc) {
                $transaction->rollBack();
                throw $exc;
            }
        }
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Purchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Purchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteDraft($id)
    {
        Draft::findOne($id)->delete();

        return $this->redirect(['draft']);
    }

    public function actionMaster()
    {
        $masters = [];
        $masters['products'] = (new Query)->select(['i.*', 'price' => 'c.last_purchase_price'])
            ->from(['i' => '{{%product_detail}}'])
            ->innerJoin(['p' => '{{%product}}'], '[[i.product_id]]=[[p.id]]')
            ->leftJoin(['c' => '{{%cogs}}'], '[[c.product_id]]=[[p.id]]')
            ->all();
        $masters['vendors'] = (new Query())
            ->from('{{%vendor}}')
            ->where(['type' => 2])
            ->all();

        Yii::$app->getResponse()->format = 'js';
        return 'var MASTERS = ' . json_encode($masters) . ';';
    }

    /**
     * Finds the Purchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
