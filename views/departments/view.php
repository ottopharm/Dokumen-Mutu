<?php
/* @var $this DepartmentsController */
/* @var $model Departments */

$this->breadcrumbs=array(
	'Departments'=>array('index'),
	$model->DeptID,
);

$this->menu=array(
	array('label'=>'List Departments', 'url'=>array('index')),
	array('label'=>'Create Departments', 'url'=>array('create')),
	array('label'=>'Update Departments', 'url'=>array('update', 'id'=>$model->DeptID)),
	array('label'=>'Delete Departments', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->DeptID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Departments', 'url'=>array('admin')),
);
?>

<h1>View Departments #<?php echo $model->DeptID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'DeptID',
		'Department',
	),
)); ?>
