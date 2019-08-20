<?php
/* @var $this DokLevel1Controller */
/* @var $model DokLevel1 */

$this->breadcrumbs=array(
	'Dok Level1s'=>array('index'),
	$model->NoDokumen=>array('view','id'=>$model->NoDokumen),
	'Update',
);

$this->menu=array(
	array('label'=>'List DokLevel1', 'url'=>array('index')),
	array('label'=>'Create DokLevel1', 'url'=>array('create')),
	array('label'=>'View DokLevel1', 'url'=>array('view', 'id'=>$model->NoDokumen)),
	array('label'=>'Manage DokLevel1', 'url'=>array('admin')),
);
?>

<h1>Update DokLevel1 <?php echo $model->NoDokumen; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>