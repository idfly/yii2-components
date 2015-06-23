<?php

use yii\helpers\Html;

?>
<div class="<?= $_key ?>-list-container elements-list-container">
    <?php if(!\yii::$app->request->isAjax && !empty($_search)) : ?>
        <div class="search-form input-group-sm">
            <div class="modal-dialog search-form-dialog modal-xsm" id="<?= $_key ?>-search-form">
                <div class="modal-content">
                    <form role="form" class="form-vertcal">
                        <div class="modal-header">
                            <button type="button" class="close" data-toggle="collapse" data-target="#<?= $_key
                                ?>-search-form"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Поиск</h4>
                        </div>
                        <div class="modal-body">

                            <?php require $_search ?>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#<?=
                                $_key ?>-search-form">Закрыть</button>

                            <a class="btn btn-default" href="<?= Html::encode(\yii::$app->urlManager->
                                createUrl(['/admin/' . $_key])) ?>">Сбросить</a>

                            <button type="submit" class="btn btn-primary">Поиск</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if(\Yii::$app->request->isAjax): ?>
        <div class="row">
            <?php if(!empty($_search)) : ?>
                <div class="col-sm-3">
                    <form role="form" class="form-vertcal">
                        <?php require $_search ?>
                    </form>
                </div>
            <?php endif ?>
            <div class="<?= $_key ?>-list elements-list <? if(!empty($_search)) { echo 'col-sm-9'; }
                ?>" element-id-key="<?= Html::encode($_keyOne) ?>-id" style="overflow: auto">
                <?php require $_list ?>
                <?php require $_footer ?>
            </div>
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
                    </h2>
                    <div class="action-list inline to-right right col-sm-6">
                        <?php if(!empty($_search)) : ?>
                            <a class="action btn btn-sm btn-primary" data-toggle="collapse"
                                data-target="#<?= $_key ?>-search-form">Поиск</a>
                        <?php endif ?>
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