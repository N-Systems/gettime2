<table class="table bordered">
               <thead>
                   <td>Date</td>
                   <td>Time</td>
                   <td>Championship</td>
                   <td>Game</td>
                   <td>Bet</td>
                   <td>Odd</td>
                   <td>Score</td>
                   <td>Result</td>
              </thead>
<?php $style='';?>
    <?php $lastTipMonth='';?>
    <?php $lastTipDay=''; $rowSpan=1;?>
<?php foreach($tips as $tip):?>
        <?php if ($lastTipMonth!=Yii::app()->dateFormatter->format('MMMM yyyy',$tip['untillDate'])):?>
            <tr><td colspan="8" class="monthrow"><h1><a href="/records/<?=Yii::app()->dateFormatter->format('MMyyyy',$tip['untillDate'])?>">
               <?=Yii::app()->dateFormatter->format('MMMM yyyy',$tip['untillDate'])?></a></h1>
            </td></tr>
            <?endif;?>
    <?php $lastTipMonth=Yii::app()->dateFormatter->format('MMMM yyyy',$tip['untillDate']);?>
    <?php $lastTipDay==Yii::app()->dateFormatter->format('dd',$tip['untillDate']); ?>
<?endforeach;?>
</table>

