<?php

namespace alexkacharin\faqmodule;

use yii\web\AssetBundle;

class FaqAsset extends AssetBundle
{
    public $sourcePath = '@vendor/alexkacharin/yii2-faqmodule/assets';
    public $css = [
        'css/category.css',
        'css/search.css',
    ];
    public $js = [
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'app\assets\AppAsset'
    ];
}