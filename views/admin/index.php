<?php

use yii\helpers\Html;

?>
<div class="row">

    <div class="<?php if(!empty($_search)) : ?>col-sm-9<? else: ?>col-sm-12<? endif ?>">

        <?php if(\Yii::$app->request->isAjax): ?>
            <?php if(!empty($_filter)) : ?>
                <?php require $_filter; ?>
            <?php endif ?>

            <div class="<?= $_key ?>-list elements-list">
                <?php require $_list ?>
                <?php require $_footer ?>
            </div>
        <?php else: ?>
            <?php if(!empty($_filter)) : ?>
                <?php require $_filter; ?>
            <?php endif ?>

            <section class="panel">
                <div class="panel-heading with-actions clearfix">
                    <div class="row" style="margin-left: 0px">
                        <h2 class="panel-title inline col-sm-6">
                            <?= Html::encode($_title) ?>
                            <?php if(\yii::$app->request->get('_filter') !== null) : ?>
                                / <?= Html::encode(\app\models\Filter::find()->
                                    where(['id' => \yii::$app->request->get('_filter')])->
                                    one()->
                                    name) ?>
                            <?php endif ?>
                        </h2>
                        <div class="action-list inline to-right right col-sm-6">
                            <a class="action btn btn-sm btn-primary" href="<?= Html::encode(\Yii::$app->
                                urlManager->createUrl(['admin/' . $this->context->id . '/create',
                                '_redirect' => \yii::$app->request->getAbsoluteUrl()])) ?>">Добавить</a>
                        </div>
                    </div>
                </div>
                <div class="panel-body <?= $_key ?>-list elements-list" element-id-key="<?=
                    Html::encode($_keyOne) ?>-id">
                    <?php require $_list ?>
                    <?php require $_footer ?>
                </div>
            </section>
        <?php endif ?>
    </div>

    <?php if(!empty($_search)) : ?>
        <div class="col-sm-3">
            <?php require $_search ?>
        </div>
    <?php endif ?>

</div>