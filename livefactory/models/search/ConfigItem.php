<?php

namespace livefactory\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use livefactory\models\ConfigItem as ConfigItemModel;

/**
 * ConfigItem represents the model behind the search form about `\livefactory\models\ConfigItem`.
 */
class ConfigItem extends ConfigItemModel
{
    public function rules()
    {
        return [
            [['id', 'added_at', 'updated_at'], 'integer'],
            [['config_item_name', 'config_item_value', 'config_item_description'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ConfigItemModel::find()->where("config_item_name !='LICENSE'");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'added_at' => $this->added_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'config_item_name', $this->config_item_name])
            ->andFilterWhere(['like', 'config_item_value', $this->config_item_value])
            ->andFilterWhere(['like', 'config_item_description', $this->config_item_description]);

        return $dataProvider;
    }
}
