<?php

namespace app\modules\orders\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\orders\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `app\modules\orders\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public $stime;
    public $etime;
    public function rules()
    {
        return [
            [['site','stime','etime', 'order_no', 'number','address', 'shop_name'],'safe'],
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
        $query = Orders::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $stime = isset($params['OrdersSearch']['stime']) ? strtotime($params['OrdersSearch']['stime']) : '';
        $etime = isset($params['OrdersSearch']['etime']) ? strtotime($params['OrdersSearch']['etime']) : '';
        unset($params['stime']);
        unset($params['etime']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'site' => $this->site,
            'size' => $this->size,
            'amount' => $this->amount,
            'number' => $this->number,
        ]);
        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'shop_name', $this->shop_name]);

        if($stime)
        {
            $query->andFilterWhere(['>', 'created_at', $stime]);
        }
        if($etime)
        {
            $query->andFilterWhere(['<', 'created_at', $etime]);
        }

        return $dataProvider;
    }
}
