<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\menu */

$this->title = '修改菜单: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '菜单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    	'permissionArray' => $permissionArray,
    	'menuArray' => $menuArray
    ]) ?>

</div>
