<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Vào ca';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>
	<?= /** @var TYPE_NAME $model */
	$form->field($model, 'id')->textInput([
		'maxlength' => true,
		'value'     => 3,
		'style'     => 'display:none',
	])->label(false) ?>
	<div class="form-group">
		<?= Html::submitButton('Vào ca', ['class' => 'btn btn-warning btn-lg']) ?>
	</div>
	<?php ActiveForm::end(); ?>

</div>
