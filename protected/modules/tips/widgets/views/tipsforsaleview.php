<?php if(empty($tips)):?>
    <tr style="height: 10px;">
        <td colspan="5"><h2>No more betting tips today. Please come back tomorrow!</h2></td>
    </tr>
<?php endif;?>
<script type="text/javascript">
    function recalc()
        {
            $i=0;
            $("input:checkbox").each(function()
            {
                if (this.checked==true)
                  $i++;
            });

            if ($i==0)
            {
                $("#summary").html('No tips selected');
                    $(".bonustips").html('Choose all to get for free');
                $(".bonustipsprice").html('n/a');
            }

                if ($i==1)
                {
                    $("#summary").html('1 tip selected, Total &#8364;50');
                        $(".bonustips").html('Choose all to get for free');
                    $(".bonustipsprice").html('n/a');
                }
                if ($i==2)
                {
                    $("#summary").html('2 tips selected, Total &#8364;80 (You save &#8364;20!)');
                    $(".bonustips").html('Choose all to get for free');
                    $(".bonustipsprice").html('n/a');
                }
                if ($i==3)
                {
                    $("#summary").html('3 tips + Bonus tips, Total &#8364;100 (You save &#8364;50!)');
                    $(".bonustips").html('');
                    $(".bonustipsprice").html('free');

                }



        }

    $(document).ready(function()
    {


    $.noConflict();

    $("input:checkbox").click(function()
    {
      recalc();
    });
    })

</script>
<?php $i=0;
foreach($tips as $tip):?>
<tr>
    <td>
    <?php if (mb_strtoupper(substr($tip['tip_number'],0,1))!="B")
    { ?>
        <input type="checkbox" name="tip[]" value="Betting tip: #<?=$tip['tip_number']?>">
    <?php } else {
        ?>
        <span class="bonustips">Choose all to get for free</span>
    <?php } ?>
    </td>
    <td><?=$tip['tip_number']?></td>
    <td><?=Yii::app()->dateFormatter->format('HH:mm',$tip['untillTime'])?></td>
    <td><?=Yii::app()->numberFormatter->format('#.00',$tip['ratio'])?></td>
    <td><?php
        if (mb_strtoupper(substr($tip['tip_number'],0,1))!="B") {
        ?>
        &#8364;<?=$tip['price']?>
        <?php } else { ?><span class="bonustipsprice"></span><?php } ?>
        </td>

</tr>
<?endforeach;?>