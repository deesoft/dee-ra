<?php

namespace app\models\accounting\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\accounting\Payment as PaymentModel;

/**
 * Payment represents the model behind the search form about `app\models\accounting\Payment`.
 */
class Payment extends PaymentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'branch_id', 'vendor_id', 'coa_id', 'potongan_coa_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['number', 'date', 'method'], 'safe'],
            [['value', 'potongan'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PaymentModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'branch_id' => $this->branch_id,
            'vendor_id' => $this->vendor_id,
            'date' => $this->date,
            'coa_id' => $this->coa_id,
            'value' => $this->value,
            'potongan_coa_id' => $this->potongan_coa_id,
            'potongan' => $this->potongan,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'method', $this->method]);

        return $dataProvider;
    }
}
