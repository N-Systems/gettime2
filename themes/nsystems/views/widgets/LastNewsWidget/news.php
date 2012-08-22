<div class="row emptyrow"></div>
<section class="news">
<div class="row">
    <div class="span12">

            <h2><span class="bet">Latest</span><span class="time"> news</span></h2>
    </div>
</div>
    <div class="row dottedrow"></div>
        <?php foreach ($news as $new): ?>
        <div class="row">
            <div class="span2">
                <h3><span class="bet"<?php echo CHtml::link(Yii::app()->dateFormatter->format('y-MM-dd',$new->date), array('/news/news/show/', 'title' => $new->alias));?></span></h3>
                </div>
            <div class="span10">
                <h3><span class="time"><?php echo CHtml::link($new->title, array('/news/news/show/', 'title' => $new->alias));?></span></h3>
            </div>
        </div>
            <div class="row">
                <div class="span12">
                    <?=$new->short_text?>
                </div>
            </div>
    <div class="row dottedrow"></div>
        <?php endforeach;?>

</section>
