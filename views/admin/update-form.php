<?php $form = idfly\porto\ActiveForm::begin([
    'options' => [
        'class' => $_keyOne . '-form update-form',
        'enctype' => 'multipart/form-data',
    ],
]); ?>

<?php require $_form ?>

<div class="row">
    <div class="col-sm-3 col-sm-offset-3">
        <input type="submit" class="btn btn-success" value="Сохранить">
    </div>
</div>

<?php idfly\porto\ActiveForm::end(); ?>
