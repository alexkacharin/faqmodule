<?php

namespace alexkacharin\faqmodule\models;

use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "faq_category".
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $title
 *
 * @property FaqArticle[] $faqArticles
 */
class FaqCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id'],'integer',],
            [['parent_id'],'default', 'value' => null],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер категории',
            'parent_id' => 'Родительская категория',
            'title' => 'Категория',
        ];
    }

    /**
     * Gets query for [[FaqArticles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaqArticles()
    {
        return $this->hasMany(FaqArticle::className(), ['category_id' => 'id']);
    }

    public function getArticlies()
    {
        return $this->hasMany(FaqArticle::className(), ['id' => 'article_id'])
            ->viaTable('faq_article_to_category', ['category_id' => 'id']);
    }
    public static function getAllCategories($parent = NULL, $level = 0, $exclude = 0) {
        $children = self::find()
            ->where(['parent_id' => $parent])
            ->asArray()
            ->all();
        $result = [];
        foreach ($children as $category) {
            // при выборе родителя категории нельзя допустить
            // чтобы она размещалась внутри самой себя
            if ($category['id'] == $exclude) {
                continue;
            }
            if ($level) {
                $category['title'] = str_repeat('— ', $level) . $category['title'];
            }
            $result[] = $category;
            $result = array_merge(
                $result,
                self::getAllCategories($category['id'], $level+1, $exclude)
            );
        }
        return $result;
    }

    /**
     * Возвращает массив всех категорий каталога для возможности
     * выбора родителя при добавлении или редактировании товара
     * или категории
     */
    public static function getTree($exclude = 0, $root = false) {
        $data = self::getAllCategories(null, 0, $exclude);
        $tree = [];
        // при выборе родителя категории можно выбрать значение «Без родителя»,
        // т.е. создать категорию верхнего уровня, у которой не будет родителя
        foreach ($data as $item) {
            $tree[$item['id']] = $item['title'];
        }
        return $tree;
    }
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id' => 'id']);
    }

    public function getParent($parent = 0, $exclude = 0)
    {
        $children = self::find()
        ->where(['parent_id' => $parent])
        ->asArray()
        ->all();
        $result = [];
        foreach ($children as $category) {
            if ($category['id'] == $exclude) {
                continue;
            }
            $result[] = $category['id'];
            $result = array_merge(
                $result,
                self::getAllCategories($category['id'], $exclude)
            );
        }
        $result = ArrayHelper::getColumn($result,'id');
        return $result;
    }
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        $arr = $this->getParent('id');
        foreach ($arr as $arr_id) {
            Yii::$app
                ->db
                ->createCommand()
                ->delete('faq_category', ['id' => $arr_id])
                ->execute();
        }
        return true;
    }
    public static function buildArray($items, $currentElementId = 0, $idKeyname = 'id', $parentIdKeyname = 'parent_id', $parentarrayName = 'childs')
    {
        if(empty($items)) return array();
        $return = [];
        foreach($items as $item) {
            if($item[$parentIdKeyname] == $currentElementId) {
                $item[$parentarrayName] = self::buildArray($items, $item[$idKeyname], $idKeyname, $parentIdKeyname, $parentarrayName);
                $return[] = $item;
            }
        }
        return $return;
    }
    public static function treeBuild($items,$request)
    {
        $return = '';
        foreach($items as $item) {
            if (empty($item['parent_id'])) {
                $return .= '<ul class="main-list" style="list-style-type:none; ">';
                $return .= '<li>';
                $return .= '<a href="';
                $return .= Url::to(['/faqmodule/article/category?id='.$item['id'].'&url='.$request]);
                $return .= '">';
                $return .= $item['title'];
                $return .= '</a>';
                $return .= FaqCategory::treeBuild($item['childs'],$request);
                $return .= '</li>';
                $return .= '</ul>';
            }
            else{
                $return .= '<ul class="sub-list">';
                $return .= '<li style="list-style-type:none;">';
                $return .= '<a href="';
                $return .= Url::to(['/faqmodule/article/category?id='.$item['id'].'&url='.$request]);
                $return .= '">';
                $return .= $item['title'];
                $return .= '</a>';
                $return .= FaqCategory::treeBuild($item['childs'],$request);
                $return .= '</li>';
                $return .= '</ul>';
            }

        }

        return $return;
    }
    public function getTreeCategory($request)
    {
        $model = new FaqCategory();
        $list = $model::find()->asArray()->all();
        $arr = $model::buildArray($list);
        $arr1 = $model::treeBuild($arr,$request);
        return $arr1;
    }
}


