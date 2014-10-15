<?php

/**
 * message input form
 */
class D2mail extends CWidget {

    public $write_mail = array();

    /**
     *
     * @var type 
     */
    public $left_tabs = array();

    /**
     * array format url to message inline wieving action
     * @var array 
     */
    public $show_inline_url = false;

    /**
     * full url to message list ajax action
     * @var string
     */
    public $message_list_ajax_url = false;

    /**
     * title with large fonts
     * @var string 
     */
    public $title_big = false;

    /**
     * title with smallest font
     * @var string 
     */
    public $title_small = false;

    /**
     * column formar. Real example set visible columns:
     * 'columns' => array(
      'unread' => false,
      'checkbox' => false,
      'stared' => false,
      'sender' => true,
      'badge' => false,
      'message_flags' => false,
      'subject' => true,
      'summary' => true,
      'attachment' => false,
      'time' => true,
      ),
     * @var type 
     */
    public $messages_format = false;

    /**
     * filterin criteria
     * @var type 
     */
    public $criteria = false;

    /**
     * model name
     * @var type 
     */
    public $data_model = false;

    /**
     * column maping
     *      'maping' => array(
      'sender' => array('d2mmSenderPprs', 'itemLabel'),
      'subject' => 'd2mm_subject',
      'summary' => 'd2mm_text',
      'time' => 'd2mm_created',
      'id' => 'd2mm_id',
      ),
     * @var array
     */
    public $maping = false;
    public $pageSize = 10;

    /**
     * pagenator
     * @var CPagination 
     */
    private $_pages;
    
    public $model_name = false;
    public $model_id = false;
    public $rcp_role = false;
    public $rcp_pprs_id = false;
    
    public $theme_settings = false;
    
    public function init() {
        
        //defaults
        if(!empty($this->write_mail) && !isset($this->write_mail['form_url'])){
            $this->write_mail['form_url'] = Yii::app()->createurl('d2messages/ajax/write');
        }

        if(!$this->message_list_ajax_url){
            $this->message_list_ajax_url = Yii::app()->createurl('d2messages/ajax/list');
        }

        if(!$this->show_inline_url){
            $this->show_inline_url = Yii::app()->createurl('d2messages/ajax/MessageInline');
        }
        
        
        $this->maping = array(
            'sender' => array('d2mmSenderPprs', 'itemLabel'),
            'subject' => 'd2mm_subject',
            'summary' => 'd2mm_text',
            'time' => 'd2mm_created',
            'id' => 'd2mm_id',
            'model_name' => 'd2mm_model',
            'model_label' => 'd2mm_model_label',
            'unread' => array('d2mrRecipients',0,'d2mr_read_datetime'),
        );
        
        $this->theme_settings = Yii::app()->params['theme_settings'];
        
        //save setings for mail list
        $list_add_data = array(
            'maping' => $this->maping, 
            'title_big' => $this->title_big,
            'messages_format' => $this->messages_format,
            'widgets_view_path' => $this->theme_settings['widgets_view_path'],
            'model_name' => $this->model_name,
            'model_id' => $this->model_id,
            'rcp_role' => $this->rcp_role,
            'rcp_pprs_id' => $this->rcp_pprs_id,
        );
        
        Yii::app()->clientScript->registerScript('D2mailList', '
             var list_add_data = "'.urlencode(json_encode($list_add_data)).'";
             var d2mail_model_id = "'.$this->model_id.'";
             var d2mail_model_name = "'.$this->model_name.'";
        ');
    }

    public function run() {
        echo '<div class="tabbable">';

        //create tabs
        $this->widget('D2mailTabs', array(
            'write_mail' => $this->write_mail,
            'left_tabs' => $this->left_tabs,
            'message_list_ajax_url' => $this->message_list_ajax_url,
            'show_inline_url' => $this->show_inline_url,
            'widgets_view_path' => $this->theme_settings['widgets_view_path'],
        ));

        $this->widget('D2mailList', array(
            'data_model' => 'D2mmMessages',
            'criteria' => D2mmMessages::createListCriteria(array(            
                'model_name' => $this->model_name,
                'model_id' => $this->model_id,
                'rcp_role' => $this->rcp_role,
                'rcp_pprs_id' => $this->rcp_pprs_id,
                )),
            'maping' => $this->maping, 
            'title_big' => $this->title_big,
            'messages_format' => $this->messages_format,
            'widgets_view_path' => $this->theme_settings['widgets_view_path'],
        ));

        echo '</div>';
    }

}