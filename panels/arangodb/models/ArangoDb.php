<?php

namespace devgroup\arangodb\panels\arangodb\models;

use yii;
use yii\data\ArrayDataProvider;
use yii\debug\components\search\Filter;
use yii\debug\models\search\Base;

class ArangoDb extends Base
{
    /**
     * @var integer query attribute input search value
     */
    public $query;

    public $type;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['query', 'type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'query' => 'Query',
        ];
    }

    /**
     * Returns data provider with filled models. Filter applied if needed.
     *
     * @param array $params an array of parameter values indexed by parameter names
     * @param array $models data to return provider for
     * @return \yii\data\ArrayDataProvider
     */
    public function search($params, $models)
    {
        $dataProvider = new ArrayDataProvider([
            'allModels' => $models,
            'pagination' => false,
            'sort' => [
                'attributes' => ['duration', 'seq', 'query', 'type'],
                'defaultOrder' => [
                    'duration' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $filter = new Filter();
        $this->addCondition($filter, 'type', true);
        $this->addCondition($filter, 'query', true);
        $dataProvider->allModels = $filter->filter($models);

        return $dataProvider;
    }
}
