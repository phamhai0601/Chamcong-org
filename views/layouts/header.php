<?php
/**
 * Created by FesVPN.
 * @project Chamcong-org
 * @author  Pham Hai
 * @email   mitto.hai.7356@gmail.com
 * @date    7/11/2020
 * @time    8:10 AM
 */
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;


NavBar::begin([
	'brandLabel' => Yii::$app->name,
	'brandUrl'   => Yii::$app->homeUrl,
	'options'    => [
		'class' => 'navbar-inverse navbar-fixed-top',
	],
]);
echo Nav::widget([
	'options' => ['class' => 'navbar-nav navbar-right'],
	'items'   => [
		[
			'label' => 'Home',
			'url'   => ['/site/index']
		],
		[
			'label' => 'About',
			'url'   => ['/site/about']
		],
		[
			'label' => 'Contact',
			'url'   => ['/site/contact']
		],
		[
			'label' => 'Setting',
			'url'   => ['/setting/index']
		],
		Yii::$app->user->isGuest ? ([
			'label' => 'Login',
			'url'   => ['/site/login']
		]) : ('<li>' . Html::beginForm(['/site/logout'], 'post') . Html::submitButton('Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link logout']) . Html::endForm() . '</li>')
	],
]);
NavBar::end();
?>
