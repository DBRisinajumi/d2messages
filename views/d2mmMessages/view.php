<?php
    $this->setPageTitle(
        Yii::t('D2messagesModule.model', 'D2mm Messages')
        . ' - '
        . Yii::t('D2messagesModule.crud', 'View')
        . ': '   
        . $model->getItemLabel()            
);    
$this->breadcrumbs[Yii::t('D2messagesModule.model','D2mm Messages')] = array('admin');
$this->breadcrumbs[$model->{$model->tableSchema->primaryKey}] = array('view','id' => $model->{$model->tableSchema->primaryKey});
$this->breadcrumbs[] = Yii::t('D2messagesModule.crud', 'View');
$cancel_buton = $this->widget("bootstrap.widgets.TbButton", array(
    #"label"=>Yii::t("D2messagesModule.crud","Cancel"),
    "icon"=>"chevron-left",
    "size"=>"large",
    "url"=>(isset($_GET["returnUrl"]))?$_GET["returnUrl"]:array("{$this->id}/admin"),
    "visible"=>(Yii::app()->user->checkAccess("D2messages.D2mmMessages.*") || Yii::app()->user->checkAccess("D2messages.D2mmMessages.View")),
    "htmlOptions"=>array(
                    "class"=>"search-button",
                    "data-toggle"=>"tooltip",
                    "title"=>Yii::t("D2messagesModule.crud","Back"),
                )
 ),true);
    
?>
<?php $this->widget("TbBreadcrumbs", array("links"=>$this->breadcrumbs)) ?>
<div class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="btn-group"><?php echo $cancel_buton;?></div>
        <div class="btn-group">
            <h1>
                <i class=""></i>
                <?php echo Yii::t('D2messagesModule.model','D2mm Messages');?>                <small><?php echo$model->itemLabel?></small>
            </h1>
        </div>
        <div class="btn-group">
            <?php
            
            $this->widget("bootstrap.widgets.TbButton", array(
                "label"=>Yii::t("D2messagesModule.crud","Delete"),
                "type"=>"danger",
                "icon"=>"icon-trash icon-white",
                "size"=>"large",
                "htmlOptions"=> array(
                    "submit"=>array("delete","d2mm_id"=>$model->{$model->tableSchema->primaryKey}, "returnUrl"=>(Yii::app()->request->getParam("returnUrl"))?Yii::app()->request->getParam("returnUrl"):$this->createUrl("admin")),
                    "confirm"=>Yii::t("D2messagesModule.crud","Do you want to delete this item?")
                ),
                "visible"=> (Yii::app()->request->getParam("d2mm_id")) && (Yii::app()->user->checkAccess("D2messages.D2mmMessages.*") || Yii::app()->user->checkAccess("D2messages.D2mmMessages.Delete"))
            ));
            ?>
        </div>
    </div>
</div>



<div class="row">
    <div class="span12">
        <h2>
            <?php echo Yii::t('D2messagesModule.crud','Data')?>            <small>
                #<?php echo $model->d2mm_id ?>            </small>
        </h2>

        <?php
        $this->widget(
            'TbDetailView',
            array(
                'data' => $model,
                'attributes' => array(
                
                array(
                    'name' => 'd2mm_id',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_id',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_priority',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'type' => 'select',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                            'source' => $model->getEnumFieldLabels('d2mm_priority'),
                            'attribute' => 'd2mm_priority',
                            //'placement' => 'right',
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_status',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'type' => 'select',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                            'source' => $model->getEnumFieldLabels('d2mm_status'),
                            'attribute' => 'd2mm_status',
                            //'placement' => 'right',
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_model',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_model',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_model_record_id',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_model_record_id',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_sender_pprs_id',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'type' => 'select',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                            'source' => CHtml::listData(PprsPerson::model()->findAll(array('limit' => 1000)), 'pprs_id', 'itemLabel'),
                            'attribute' => 'd2mm_sender_pprs_id',
                            //'placement' => 'right',
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_thread_id',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_thread_id',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_read',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_read',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_ds',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_ds',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_created',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_created',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_subject',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_subject',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),

                array(
                    'name' => 'd2mm_text',
                    'type' => 'raw',
                    'value' => $this->widget(
                        'EditableField',
                        array(
                            'model' => $model,
                            'attribute' => 'd2mm_text',
                            'url' => $this->createUrl('/d2messages/d2mmMessages/editableSaver'),
                        ),
                        true
                    )
                ),
           ),
        )); ?>
    </div>

    </div>
    <div class="row">

    <div class="span12">
        <?php $this->renderPartial('_view-relations_grids',array('modelMain' => $model, 'ajax' => false,)); ?>    </div>
</div>

<?php echo $cancel_buton; ?>