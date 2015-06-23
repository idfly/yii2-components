<?php

namespace idfly\components;
use yii\helpers\ArrayHelper;

class QueryBuilder {

    public static function build($modelOrQuery, $params, $options = [])
    {
        if($modelOrQuery === null) {
            $query = new \yii\db\Query();
        } elseif($modelOrQuery instanceof \app\db\Query) {
            $query = $modelOrQuery;
        } else {
            $query = $modelOrQuery::find();
        }

        $allowedKeys = ArrayHelper::getValue($options, 'allowedKeys');

        foreach($params as $key => $value) {
            if(empty($value)) {
                continue;
            }

            if($allowedKeys !== null && !in_array($key, $allowedKeys)) {
                continue;
            }

            $keyParts = explode('?', $key);

            $field = $keyParts[0];
            if(sizeof($keyParts) === 1) {
                $query->andWhere([$field => $value]);
                continue;
            }

            $operator = $keyParts[1];

            if($operator === 'from') {
                $query->andWhere(['>=', $field, $value]);
                continue;
            }

            if($operator === 'to') {
                $query->andWhere(['<=', $field, $value]);
                continue;
            }

            if($operator === 'greater') {
                $query->andWhere(['>', $field, $value]);
                continue;
            }

            if($operator === 'less') {
                $query->andWhere(['<', $field, $value]);
                continue;
            }

            if($operator === 'like') {
                $query->andWhere(['like', $field, $value]);
                continue;
            }
        }

        return $query;
    }

    protected function escapeField($key)
    {
        return preg_replace('{[^\w\d_]}', '', $key);
    }

}