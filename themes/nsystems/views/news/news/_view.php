<div class="post">
    <div class="title">
        <h1><?php echo CHtml::link(CHtml::encode($data->title), array('/news/news/show', 'title' => $data->alias)); ?></h1>
    </div>
    <div class="content">
        <p><?php echo $data->full_text; ?></p>
    </div>
    <div class="nav">
        <?php echo CHtml::link('Permalink', array('/news/news/show', 'title' => $data->alias));?>
        | last update <?php echo $data->change_date;?>
    </div>
</div>
