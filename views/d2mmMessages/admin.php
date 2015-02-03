<?php
$this->setPageTitle(
        Yii::t('D2messagesModule.model', 'Messages'));


?>
<div class="clearfix">
    <div class="btn-toolbar pull-left">
        <div class="btn-group">
            <h1>
                <i class="icon-envelope"></i>
                <?php echo Yii::t('D2messagesModule.model', 'Messages'); ?>
            </h1>
        </div>
    </div>
</div>

<?php
$this->widget('D2Mail', array(
    //'model_name' => get_class($model), //optional - filter messages by model name
    //'model_id' => $model->primaryKey,
    'pprs_id' => Yii::app()->getModule('user')->user()->profile->person_id,
    'write_mail' => array(
        'label' => Yii::t('D2companyModule.crud', 'Write message'),
    ),
    'left_tabs' => array(
        array(
            'label' => Yii::t('D2companyModule.crud', 'Inbox'),
            'tab_code' => 'messages',
            'icon' => 'icon-inbox',
            'icon_color' => 'blue',
            'active' => true,
            'url' => array('d2messages/ajax/List'),
        ),
        array(
            'label' => Yii::t('D2companyModule.crud', 'Sent'),
            'tab_code' => 'sent',
            'icon' => 'icon-arrow-right',
            'icon_color' => '',
            'active' => true,
            'url' => array('d2messages/ajax/sent'),
        ),
    ),
    'messages_format' => array(
        //show columns in messages list
        'columns' => array(
            'unread',
            'sender',
            'subject',
            'summary',
            'time',
            'model_label',
            'model_name',
        ),
    ),
    //mesage list title big
    'title_big' => Yii::t('D2companyModule.crud', 'Messages'),
        )
);
