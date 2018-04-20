<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tokens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Token', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'token',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
