<?php
namespace alexkacharin\faqmodule;
use Yii;
use yii\base\BootstrapInterface;
class Bootstrap implements BootstrapInterface{
    //Метод, который вызывается автоматически при каждом запросе
    public function bootstrap($app)
    {
        $app->setModule('faqmodule', 'alexkacharin\faqmodule\Module');
    }
}