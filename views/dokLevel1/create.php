<?php
/* @var $this DokLevel1Controller */
/* @var $model DokLevel1 */

$this->breadcrumbs=array(
	'Dok Level1s'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DokLevel1', 'url'=>array('index')),
	array('label'=>'Manage DokLevel1', 'url'=>array('admin')),
);
?>

<h1>Create DokLevel1</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>