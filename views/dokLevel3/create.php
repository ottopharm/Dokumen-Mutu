<?php
/* @var $this DokLevel3Controller */
/* @var $model DokLevel3 */

$this->breadcrumbs=array(
	'Dok Level3s'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DokLevel3', 'url'=>array('index')),
	array('label'=>'Manage DokLevel3', 'url'=>array('admin')),
);
?>

<h1>Create DokLevel3</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>