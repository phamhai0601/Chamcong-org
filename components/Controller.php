<?php

namespace app\component;

class Controller extends \yii\web\Controller
{
    /**
     *
     */
    public function init()
    {
        parent::init();
        if (Yii::$app->user->isGuest) {
//            return $this->redirect(['site/login']);
            echo 'dont login'; die;

        }
    }
}