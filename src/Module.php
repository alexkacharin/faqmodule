<?php

namespace alexkacharin\faqmodule;

use Yii;

/**
 * faq module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'alexkacharin\faqmodule\controllers';
    const accessRoles = ['admin', 'superadmin','guest'];
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

      //Yii::configure($this, require __DIR__ . '\config\web.php');
    }
}
