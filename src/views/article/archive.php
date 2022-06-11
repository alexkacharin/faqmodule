
<?php

use alexkacharin\faqmodule\FaqAsset;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
$request = Yii::$app->request;
?>

<div>
    <?php FaqAsset::register($this);?>
    <?php if (Yii::$app->user->can('admin')||Yii::$app->user->can('superadmin')): ?>
    <?= Html::a('Вопросник', ['/faq/article'], ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Категории', ['/faq/category'], ['class'=>'btn btn-primary']) ?>
    <?php endif; ?>
    <?php
    $model = new \alexkacharin\faqmodule\models\FaqCategory();
    $model2 = new \alexkacharin\faqmodule\models\FaqArticle();
    ?>
    <body>
    <script>
        var person ;
        function myFunction()
        {
            $.ajax({
                url: '<?php echo Yii::$app->request->baseUrl. '/faq/article/search' ?>',
                type: 'post',
                data: {
                    searchname: $("#searchname").val(),
                    url: location.href,
                    _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
                },
                dataType: "json",
                success: function (data) {
                    person =  JSON.stringify(data.search)
                    person = JSON.parse(person)
                    console.log(data.url)
                    $('#listView').html(person);
                }
            });
        }
    </script>
    <div class="container">
        <code class="php"><?php Pjax::begin(); ?>
            <div class="code-ajax">
                <div id="listView">
                    <article class="post" >
                        <?php foreach($articles as $article):?>

                            <div class="list-group w-100">
                                <div  data-mdb-toggle="collapse" aria-expanded="false"
                                      aria-controls="shortExampleAnswer1collapse" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?= $article->title?></h5>
                                    </div>
                                    <?php if (Yii::$app->user->can('admin')||Yii::$app->user->can('superadmin')): ?>
                                        <?= Html::a(' 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                               <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                               <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                           </svg>', ['/faq/article/update', 'id' =>$article->id], [
                                            'data' => [
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                        <?= Html::a(' 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
                               <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                           </svg>', ['/faq/article/update-status', 'id' =>$article->id, 'url' => $request->absoluteUrl  ], [
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите извлечь из архива?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                        <?= Html::a(' 
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                               <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                               <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                           </svg>', ['/faq/article/deleted', 'id' =>$article->id,'url' => $request->absoluteUrl], [
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить статью?',
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif; ?>
                                    <p>
                                        Категория:
                                        <?php
                                        $model = Yii::$app->db->createCommand('SELECT * FROM faq_article_to_faq_categories WHERE article_id = :id',['id' => $article->id])->queryAll();
                                        foreach($model as $r){
                                            $models = \alexkacharin\faqmodule\models\FaqCategory::find()->where(['id' => $r["category_id"]])->all();;
                                            foreach($models as $r)
                                            {
                                                echo "<a href='";
                                                echo Url::to(['/faq/article/category?id='.$r["id"].'.&url='.$request->absoluteUrl]);
                                                echo " '> $r->title</a>";
                                                echo "<br>";
                                            }

                                        }
                                        ?>

                                    <p>
                                    <p class="mb-1">
                                        <?= \yii\helpers\StringHelper::truncate($article->content,300,'...');?>
                                    </p>
                                    <button type="button" class="btn btn-outline-primary" onclick="window.location.href = '<?= Url::toRoute(['/faq/article/view', 'id'=>$article->id]);?>'">Подробнее</button>


                                </div>

                            </div>

                        <?php endforeach; ?>
                    </article>





                </div>

    </body>

</div>
</div>
</div>

<?php Pjax::end(); ?>


</code>
