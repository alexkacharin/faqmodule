<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model alexkacharin\faqmodule\models\FaqCategory */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="faq-category-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]); ?>
    <?php
    $exclude = 0;
    if (!empty($model->id)) {
        $exclude = $model->id;
    }
    $parents = $model::getTree($exclude, true);
    echo $form->field($model, 'parent_id')->dropDownList([null => 'Родительская категория',$parents])
    ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
