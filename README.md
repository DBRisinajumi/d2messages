/config/main.php
----------------

Add under import:

        'vendor.uldisn.ace.*', 

add under module: 

        'd2messages' => array( 
            'class' => 'vendor.dbrisinajumi.d2messages.D2messagesModule',
            //link definiton in message to model record view
            'path_to_models_records' => array(
                '[model_name]' => array(
                    'route' => '[module]/[controler]/[action]',
                    'params' => array(), //additional parameters
                    'model_id_name' => 'id', //model ph field name
                    
                )
            ),
        ),
        

under parameters add path to widgets views: 
    
        'theme_settings' => array(
            'widgets_view_path' => 'vendor.uldisn.ace.widgets.views',
            ),    

/config/consile.php
----------------

add under commandMap ==> migrate ==> modulePaths: 

        'd2messages'              => 'vendor.dbrisinajumi.d2messages.migrations',  
        
migration
---------
run in app directory 

        yiic.php migrate.php
            
        
        
Widget
------

    $this->widget('D2Mail', array(
        'rcp_role' => 'Accountant', //optional - filter messages by recepment roles 
        'model_name' => 'ccmp_companies '  //optional - filter messages by model name
        'write_mail' => false,       //can not write mail
        // or define label of button
        'write_mail' => array(
            'label' => 'Write message',
        ),        
        'left_tabs' => array(
            array(
                'label' => 'Messages',
                'tab_code' => 'messages',
                'icon' => 'icon-inbox',
                'icon_color' => 'blue',
                'active' => true,
                'url' => array('AjaxShowMessages', 'aobj_id' => $model->primaryKey),
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
        'title_big' => 'Ziņojumi',
        )
    );
    
translation
-----------
    
model names translation in application/en/models.ph 
    

