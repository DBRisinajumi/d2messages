Require
-------


* Ace - Responsive Admin Template https://wrapbootstrap.com/theme/ace-responsive-admin-template-WB0B30DGR
* https://github.com/uldisn/ace - Ace widget views



/config/main.php
----------------

Add under import:

        'vendor.uldisn.ace.*',
        'vendor.dbrisinajumi.d2messages.*',          
        'vendor.dbrisinajumi.d2messages.models.*',          
        'vendor.dbrisinajumi.d2messages.widgets.*',        

add under module: 

        'd2messages' => array( 
            'class' => 'vendor.dbrisinajumi.d2messages.D2messagesModule',
            'write' => array(
                'recipient' => array(
                        'person_user',  //all users
                        'roles',        // all roles
                ),
                'default_recipient' => array(
                      'person_user' => false,
                      'role' => false,
                ),            
            ),    
           
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

/config/console.php
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
        'pprs_id' => Yii::app()->getModule('user')->user()->profile->person_id, //optional - filtr messages to person
        'model_name' => get_class($model),  //optional filter messages by model name
        'model_id' => $model->primaryKey,  //optional filter messages by model name        
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
    

menu item
---------

                        array(
                            'visible' => Yii::app()->user->checkAccess('Gramatvedis'),
                            'icon' => 'envelope white',
                            'badge_type' => 'info',
                            'badge_label' => D2mmMessages::getCountUnreadMessages(),
                            'url' => array('/???/d2mmMessages'),
                            'itemCssClass' => 'light-blue',

                        ),
