<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model alexkacharin\faqmodule\models\FaqCategory */

$this->title = 'Создать категорию';
$this->params['breadcrumbs'][] = ['label' => 'Faq Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-category-create">

    <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
