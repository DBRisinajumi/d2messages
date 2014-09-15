<?php
$this->setPageTitle(
    Yii::t('D2messagesModule.model', 'D2mm Messages')
    . ' - '
    . Yii::t('D2messagesModule.crud', 'Manage')
);

$this->breadcrumbs[] = Yii::t('D2messagesModule.model', 'D2mm Messages');

?>

<?php $this->widget("TbBreadcrumbs", array("links" => $this->breadcrumbs)) ?>
<div class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="btn-group">
        <?php 
        $this->widget('bootstrap.widgets.TbButton', array(
             'label'=>Yii::t('D2messagesModule.crud','Create'),
             'icon'=>'icon-plus',
             'size'=>'large',
             'type'=>'success',
             'url'=>array('create'),
             'visible'=>(Yii::app()->user->checkAccess('D2messages.D2mmMessages.*') || Yii::app()->user->checkAccess('D2messages.D2mmMessages.Create'))
        ));  
        ?>
</div>
        <div class="btn-group">
            <h1>
                <i class=""></i>
                <?php echo Yii::t('D2messagesModule.model', 'D2mm Messages');?>            </h1>
        </div>
    </div>
</div>

<?php Yii::beginProfile('D2mmMessages.view.grid'); ?>


<?php
$this->widget('TbGridView',
    array(
        'id' => 'd2mm-messages-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        #'responsiveTable' => true,
        'template' => '{summary}{pager}{items}{pager}',
        'pager' => array(
            'class' => 'TbPager',
            'displayFirstAndLast' => true,
        ),
        'columns' => array(
            array(
                'class' => 'CLinkColumn',
                'header' => '',
                'labelExpression' => '$data->itemLabel',
                'urlExpression' => 'Yii::app()->controller->createUrl("view", array("d2mm_id" => $data["d2mm_id"]))'
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_id',
                'editable' => array(
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'numeric-column',
                ),
            ),
            array(
                    'class' => 'editable.EditableColumn',
                    'name' => 'd2mm_priority',
                    'editable' => array(
                        'type' => 'select',
                        'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        'source' => $model->getEnumFieldLabels('d2mm_priority'),
                        //'placement' => 'right',
                    ),
                   'filter' => $model->getEnumFieldLabels('d2mm_priority'),
                ),
            array(
                    'class' => 'editable.EditableColumn',
                    'name' => 'd2mm_status',
                    'editable' => array(
                        'type' => 'select',
                        'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        'source' => $model->getEnumFieldLabels('d2mm_status'),
                        //'placement' => 'right',
                    ),
                   'filter' => $model->getEnumFieldLabels('d2mm_status'),
                ),
            array(
                //varchar(50)
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_model',
                'editable' => array(
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_model_record_id',
                'editable' => array(
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'numeric-column',
                ),
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_sender_pprs_id',
                'editable' => array(
                    'type' => 'select',
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    'source' => CHtml::listData(PprsPerson::model()->findAll(array('limit' => 1000)), 'pprs_id', 'itemLabel'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_thread_id',
                'editable' => array(
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'numeric-column',
                ),
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_read',
                'editable' => array(
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'numeric-column',
                ),
            ),
            /*
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_ds',
                'editable' => array(
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'numeric-column',
                ),
            ),
            array(
                //timestamp
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_created',
                'editable' => array(
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_subject',
                'editable' => array(
                    'type' => 'textarea',
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'editable.EditableColumn',
                'name' => 'd2mm_text',
                'editable' => array(
                    'type' => 'textarea',
                    'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            */

            array(
                'class' => 'TbButtonColumn',
                'buttons' => array(
                    'view' => array('visible' => 'Yii::app()->user->checkAccess("D2messages.D2mmMessages.View")'),
                    'update' => array('visible' => 'FALSE'),
                    'delete' => array('visible' => 'Yii::app()->user->checkAccess("D2messages.D2mmMessages.Delete")'),
                ),
                'viewButtonUrl' => 'Yii::app()->controller->createUrl("view", array("d2mm_id" => $data->d2mm_id))',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete", array("d2mm_id" => $data->d2mm_id))',
                'deleteConfirmation'=>Yii::t('D2messagesModule.crud','Do you want to delete this item?'),                    
                'viewButtonOptions'=>array('data-toggle'=>'tooltip'),   
                'deleteButtonOptions'=>array('data-toggle'=>'tooltip'),   
            ),
        )
    )
);
?>
<?php Yii::endProfile('D2mmMessages.view.grid'); ?>