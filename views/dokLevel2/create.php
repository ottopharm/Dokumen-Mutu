<?php
/* @var $this DokLevel2Controller */
/* @var $model DokLevel2 */

$this->breadcrumbs=array(
	'Dok Level2s'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DokLevel2', 'url'=>array('index')),
	array('label'=>'Manage DokLevel2', 'url'=>array('admin')),
);
?>

<h1>Create DokLevel2</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>