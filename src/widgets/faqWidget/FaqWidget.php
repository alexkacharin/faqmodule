<?php


namespace alexkacharin\faqmodule\widgets\faqWidget;

use alexkacharin\faqmodule\models\FaqArticle;
use Yii;
use yii\base\BaseObject;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

class FaqWidget extends Widget
{
    public $articles = null;
    public $pagination = null;
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $articles = FaqArticle::find()->where(['status' => 1]);
        $pagination = new Pagination(['totalCount' => $articles->count(), 'pageSize' => $this->pagination,'forcePageParam' => false, 'pageSizeParam' => false, 'pageParam' => 'city-page',]);
        $articles = $articles->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'articles' => $articles,
            'pagination' => $pagination,
        ]);
    }

}