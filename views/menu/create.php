<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\menu\models\menu */

$this->title = '创建菜单';
$this->params['breadcrumbs'][] = ['label' => '菜单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    	'permissionArray' => $permissionArray,
    	'menuArray' => $menuArray
    ]) ?>

</div>
