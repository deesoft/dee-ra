<?php

namespace app\models\sales\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\sales\Price as PriceModel;

/**
 * Price represents the model behind the search form about `app\models\sales\Price`.
 */
class Price extends PriceModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'price_category_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['price'], 'number'],
            [['product_name', 'product_code'], 'safe'],
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
        $query = PriceModel::find();
        $query->select(['price.*', 'product.*']);
        $query->joinWith(['product']);
        $query->orderBy(['product.name' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'product_id' => $this->product_id,
            'price_category_id' => $this->price_category_id,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        $query->andWhere(['like', 'lower(product.name)', strtolower($this->product_name)]);
        $query->andWhere(['like', 'lower(product.code)', strtolower($this->product_code)]);

        return $dataProvider;
    }
}
