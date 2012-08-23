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
            <tr><td colspan="8" class="monthrow"><h1>
               <?=Yii::app()->dateFormatter->format('MMMM yyyy',$tip['untillDate'])?></h1>
            </td></tr>
            <?endif;?>
<tr>
    <td
    <?php if ($lastTipDay==Yii::app()->dateFormatter->format('dd',$tip['untillDate'])) {?>
        <?php $rowSpan++;?>

            rowspan="<?=$rowSpan?>">
        <?php } else {  ?>
        ><?=Yii::app()->dateFormatter->format('dd.MM.yy',$tip['untillDate'])?>
        <?php $rowSpan=1; ?>
    <?php } ?>

       </td>
       <td><?=Yii::app()->dateFormatter->format('HH:mm',$tip['untillTime'])?></td>
    <td><?=$tip['championship']?></td>
    <td><?=$tip['gamename']?></td>
    <td><?=$tip['stavka']?></td>
    <td><?=$tip['ratio']?></td>
    <td><?=$tip['finalscore']?></td>
    <?php if (mb_strtoupper($tip['victory'])==mb_strtoupper('win')) $style='btn-success';
     else if (mb_strtoupper($tip['victory'])==mb_strtoupper('draw')) $style='btn-warning';
     else if (mb_strtoupper($tip['victory'])==mb_strtoupper('lose')) $style='btn-danger';
     ?>
     <td><a href="#" class="btn <?=$style?>"><?=mb_strtoupper($tip['victory'])?></a></td>
    </tr>
    <?php $lastTipMonth=Yii::app()->dateFormatter->format('MMMM yyyy',$tip['untillDate']);?>
    <?php $lastTipDay==Yii::app()->dateFormatter->format('dd',$tip['untillDate']); ?>
<?endforeach;?>
</table>

