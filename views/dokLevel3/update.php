<?php
/* @var $this DokLevel3Controller */
/* @var $model DokLevel3 */

$this->breadcrumbs=array(
	'Dok Level3s'=>array('index'),
	$model->NoDokumen=>array('view','id'=>$model->NoDokumen),
	'Update',
);

$this->menu=array(
	array('label'=>'List DokLevel3', 'url'=>array('index')),
	array('label'=>'Create DokLevel3', 'url'=>array('create')),
	array('label'=>'View DokLevel3', 'url'=>array('view', 'id'=>$model->NoDokumen)),
	array('label'=>'Manage DokLevel3', 'url'=>array('admin')),
);
?>

<h1>Update DokLevel3 <?php echo $model->NoDokumen; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>