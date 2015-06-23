<?php

use yii\helpers\Html;

?>
<?php if(empty($_search)) : ?>
    <?php return ?>
<?php endif ?>

<div class="search-form input-group-sm">
    <div class="modal-dialog search-form-dialog modal-xsm" id="<?= $_key ?>-search-form">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-toggle="collapse" data-target="#<?= $_key
                    ?>-search-form"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Поиск</h4>
            </div>
            <div class="modal-body">

                <?php require $_search ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#<?= $_key
                    ?>-search-form">Закрыть</button>

                <a class="btn btn-default" href="<?= Html::encode(\yii::$app->urlManager->
                    createUrl(['/admin/' . $_key])) ?>">Сбросить</a>

                <button type="submit" class="btn btn-primary">Поиск</button>
            </div>
        </div>
    </div>
</div>