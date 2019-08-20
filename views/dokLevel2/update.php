<?php
/* @var $this DokLevel2Controller */
/* @var $model DokLevel2 */

$this->breadcrumbs=array(
	'Dok Level2s'=>array('index'),
	$model->NoDokumen=>array('view','id'=>$model->NoDokumen),
	'Update',
);

$this->menu=array(
	array('label'=>'List DokLevel2', 'url'=>array('index')),
	array('label'=>'Create DokLevel2', 'url'=>array('create')),
	array('label'=>'View DokLevel2', 'url'=>array('view', 'id'=>$model->NoDokumen)),
	array('label'=>'Manage DokLevel2', 'url'=>array('admin')),
);
?>

<h1>Update DokLevel2 <?php echo $model->NoDokumen; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>