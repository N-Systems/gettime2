<?php

class TipsController extends YFrontController
{
    public $CurrentTip='';
    public $layout="//layouts/newspage";

public function actionIndex()
{

    $tips=Tips::model()->allArchived()->findAll();
    if (count($tips)<=0)
    {
        $this->render('nomoretips');
    }
    else
    {
	    $this->render('tips',array('tips'=>$tips));
    }
}
public function actionShow()
{
    $slug = Yii::app()->request->getQuery('slug');

    if (!$slug)
        throw new CHttpException('404', Yii::t('page', 'Page not found!'));

    $tips = null;
    $tips=Tips::model()->allArchived()->findAll();



    $this->render('tipsbymonth',array('tips'=>$tips));
}

public function actionBuy()
{

    $this->layout="//layouts/newspage";

    $slug = Yii::app()->request->getQuery('slug');
    if (!$slug)
            throw new CHttpException('404', Yii::t('tips', 'Tip not found!'));

    $tip = Tips::model()->find('guid = :guid', array(':guid' => $slug));
     if (!$tip)
            throw new CHttpException('404', Yii::t('tip', 'tip not found!'));

    Yii::app()->shoppingCart->update($tip,1); //1 item with id=1, quantity=1.

    $shoppingCart=Yii::app()->shoppingCart->getPositions();
    $total=Yii::app()->shoppingCart->getCost();

    $this->render('buytips', array('tip' => $tip,'shoppingCart'=>$shoppingCart,'total'=>$total));
}


}
