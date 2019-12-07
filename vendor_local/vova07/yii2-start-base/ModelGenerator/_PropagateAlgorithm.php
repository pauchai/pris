<?php
function PropagateModelSave($model)
{
    $parentModel = Yii::createObject(get_parent_class($model));
    $parentRelations = PropagateModelSave($parentModel);
}