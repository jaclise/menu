<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => 145]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 145]) ?>

    <?= $form->field($model, 'orderNum')->textInput() ?>
        
    <?= $form->field($model, 'permissionName')->dropDownList($permissionArray) ?>
    
    <?= $form->field($model, 'parentId')->dropDownList($menuArray) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
