<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Window */

$this->title = 'Update Window: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Windows', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="window-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
