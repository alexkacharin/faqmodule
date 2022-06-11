<?php

namespace alexkacharin\faqmodule\models;

use Yii;
use yii\base\BaseObject;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * This is the model class for table "faq_article".
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $content
 *
 * @property FaqCategory $category
 */
class FaqArticle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $category_ids;
    public $search;

    public static function tableName()
    {
        return 'faq_article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'category_ids','title', 'content'], 'required'],
            ['category_ids', 'safe'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'content' => 'Контент',
            'categoryList' => 'Категории',
            'status' => 'Архив'
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasOne(FaqCategory::className(), ['id' => 'category_id']);
    }

    public function getCategories()
    {
        return $this->hasMany(FaqCategory::className(), ['id' => 'category_id'])
            ->viaTable('faq_article_to_faq_categories', ['article_id' => 'id']);
    }
    public function getCategoryList()
    {
        $selectCategoryies = $this -> getCategories()->select('title')->asArray()->all();
        $array = ArrayHelper::getColumn($selectCategoryies,'title');
        $array = implode(', ', $array);
        return $array;
    }
    public function getSelectCategoryies()
    {
       $selectCategoryies = $this -> getCategories()->select('id')->asArray()->all();
        return ArrayHelper::getColumn($selectCategoryies,'id');
    }
    public function saveCategories($categories)
    {
        if (is_array($categories))
        {
            $this->clearCurrentCategoryies();
            foreach ($categories as $category_id)
            {
                $category = FaqCategory::findOne($category_id);
                $this->link('categoryies',$category);

            }
        }

    }
    /*
    public function getCategoryArr()
    {
        $arr = FaqCategory::find()->asArray()->all();
        return $arr;
    }*/
    
    public function clearCurrentCategoryies()
    {
     Yii::$app->db->createCommand(' DELETE FROM faq_article_to_faq_categories WHERE article_id = :id',['id' => $this->id])->queryAll();
    }

    public function save($runValidation = true,$attributeNames = null)
    {
        parent::Save();
        $this->clearCurrentCategoryies();
          foreach($this->category_ids as $category_id)
        {
            $category = FaqCategory::findOne($category_id);
            $this->link('categories',$category);
        }

    }
    public function getSearchResult($search)
    {
        $array = [];
        $query = self::find()->where(['like', 'title', $search])->andWhere(['status' => 1])->all();
            foreach ($query as $r) {
                array_push($array, $r->id);
            }
         return $array;
    }


    /**
     * Вспомогательная функция, очищает строку поискового запроса с сайта
     * от всякого мусора
     */
    protected function cleanSearchString($search) {
        $search = iconv_substr($search, 0, 64);
        // удаляем все, кроме букв и цифр
        $search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $search);
        // сжимаем двойные пробелы
        $search = preg_replace('#\s+#u', ' ', $search);
        $search = trim($search);
        return $search;
    }

    public function createHtml($articles,$url)
    {
         $return = ' <code class="php">
            <div class="code-ajax">
<div id="listView">';
        $return .= '<article class="post" >';
        foreach($articles as $article)
        {
            $return .='
               <div class="list-group w-100">        
                   <div  data-mdb-toggle="collapse" aria-expanded="false"
                      aria-controls="shortExampleAnswer1collapse" class="list-group-item list-group-item-action">
                       <div class="d-flex w-100 justify-content-between">
                       '
               ;
              $return .= '<h5 class="mb-1">'.$article->title.'</h5></div>';
            if (Yii::$app->user->can('admin') || Yii::$app->user->can('superadmin'))
            {
                $return .= Html::a(' 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                               <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                               <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                           </svg>', ['/faqmodule/article/update', 'id' =>$article->id], [
                    'data' => [
                        'method' => 'post',
                    ],
                ]) ;
                $return .= Html::a(' 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
                               <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                           </svg>', ['/faqmodule/article/update-status', 'id' =>$article->id, 'url' => $url  ], [
                    'data' => [
                        'confirm' => 'Вы действительно хотите отправить статью в архив?',
                        'method' => 'post',
                    ],
                ]) ;
                $return .=Html::a(' 
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                               <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                               <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                           </svg>', ['/faqmodule/article/deleted', 'id' =>$article->id,'url' => $url], [
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить статью?',
                            'method' => 'post',
                        ],
                    ]) .'<p> Категория:';

            }
            $model = Yii::$app->db->createCommand('SELECT * FROM faq_article_to_faq_categories WHERE article_id = :id',['id' => $article->id])->queryAll();
                foreach($model as $r){
                    $models = \alexkacharin\faqmodule\models\FaqCategory::find()->where(['id' => $r["category_id"]])->all();

                    foreach($models as $r)
                    {
                        $return .= "<a href='";
                        $return .= Url::to(['/faqmodule/article/category?id='.$r["id"].'.&url='.$url]);
                        $return .= " '> $r->title</a>";
                        $return .= '<br>';
                    }

                }
                $return .=' </p><p>  <p class="mb-1">';
                $return .= \yii\helpers\StringHelper::truncate($article->content,300,'...');
                $return .= '</p><button type="button" class="btn btn-outline-primary" onclick="window.location.href =';
                $return .= ' \'/faq/article/view?id='.$article->id.'\'">Подробнее</button>';

                 $return .= '</div></article>';

        }


        return $return;
    }
}
