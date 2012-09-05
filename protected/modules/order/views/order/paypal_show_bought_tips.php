<html>
<style>
    table th, table td
    {
        font-family: Calibri;
        padding: 3px 3px 7px 9px;
        
    }
</style>
<table>
    <thead><td>Date</td><td>Time</td><td>Championship</td><td>Game</td><td>Bet</td><td>Odd</td><td>Final Score</td><td>Bet result</td></thead>
<?php foreach($tips as $tip):?>
<tr>
    <td><span class="s"><?=Yii::app()->dateFormatter->format('dd.MM.yy',$tip['untillDate'])?></span></td>
     <td>   <span class="s"><?=Yii::app()->dateFormatter->format('HH:mm',$tip['untillTime'])?></span>
    </td>
    <td><span class="s"><?=$tip['championship']?></span></td>
    <td><span class="s"><?=$tip['gamename']?></span></td>
    <td><span class="s"><?=$tip['stavka']?></span></td>

    <td><span class="s"><?=Yii::app()->numberFormatter->format('#.00',$tip['ratio'])?></span></td>
    <td><span class="s"><?=$tip['finalscore']?></span></td>
    <?php if (mb_strtoupper($tip['victory'])==mb_strtoupper('win')) $style='btn-success';
    else if (mb_strtoupper($tip['victory'])==mb_strtoupper('draw')) $style='btn-warning';
    else if (mb_strtoupper($tip['victory'])==mb_strtoupper('postp')) $style='btn-warning';
    else if (mb_strtoupper($tip['victory'])==mb_strtoupper('lose')) $style='btn-danger';
    ?>
    <td><a href="#" class="btn <?=$style?>"><?=mb_strtoupper($tip['victory'])?></a></td>
    </tr>
<?endforeach;?>
</table>
</html>