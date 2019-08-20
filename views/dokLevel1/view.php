<?php
/* @var $this DokLevel1Controller */
/* @var $model DokLevel1 */

$this->breadcrumbs=array(
	'Dok Level1s'=>array('index'),
	$model->NoDokumen,
);

$this->menu=array(
	array('label'=>'List DokLevel1', 'url'=>array('index')),
	array('label'=>'Create DokLevel1', 'url'=>array('create')),
	array('label'=>'Update DokLevel1', 'url'=>array('update', 'id'=>$model->NoDokumen)),
	array('label'=>'Delete DokLevel1', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->NoDokumen),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DokLevel1', 'url'=>array('admin')),
);
?>

<h1>View DokLevel1 #<?php echo $model->NoDokumen; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'NoDokumen',
		'JenisDokumen',
	),
)); ?>
