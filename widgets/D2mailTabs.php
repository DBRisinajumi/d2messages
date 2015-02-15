<?php

/**
 * create tabs for emaill. Include all JS and css for mail list, edit, show
 */
class D2mailTabs extends CWidget {

    public $write_mail = false;

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
    public $widgets_view_path;

    public $recipient = false;
    public $recipient_names = false;
    
    public function init() {
        
        //load module settings
        $module_d2messages = Yii::app()->getModule('d2messages');
        if ($module_d2messages->write) {
            if (isset($module_d2messages->write['recipient'])) {
                $this->recipient = $module_d2messages->write['recipient'];
                
            }
        }

        //load recipient names
        if ($this->recipient && $this->recipient_names === false) {
            $this->recipient_names = array();
            if (in_array('person_user', $this->recipient)) {
                $persons = PprsPerson::getPersonsUsers();
                foreach ($persons as $row){
                    $this->recipient_names[] = $row['full_name'];
                }
            }

            if (in_array('roles', $this->recipient)) {
                $roles = Yii::app()->getModule('user')->UserAdminRoles;
                    $this->recipient_names = array_merge($this->recipient_names,$roles);
            }        
        }
        $this->initCss();
        $this->initJs();
    }

    /**
     * @todo move to uldisn/ace
     */    
    public function initJs() {


        if (!empty($this->write_mail)) {
            
            $module_d2messages = Yii::app()->getModule('d2messages');
            if ($module_d2messages->write 
                    && isset($module_d2messages->write['recipient'])
            ) {            

                Yii::app()->clientScript->registerScript('D2mailTabsWriteFillRecipients', ' 
                function define_recipients(){
                    var tag_input = $("#form-field-recipient");
                    if(! ( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase())) ) 
                                    //tag_input.tag({placeholder:tag_input.attr("placeholder")});
                    {
                        tag_input.tag(
                          {
                            placeholder:tag_input.attr("placeholder"),
                            source:["'.implode('","',$this->recipient_names).'"],
                          }
                        );
                    }else {
                        //display a textarea for old IE, because it doesnt support this plugin or another one I tried!
                        tag_input.after(\'<textarea id="\'+tag_input.attr(\'id\')+\'" name="\'+tag_input.attr(\'name\')+\'" rows="3">\'+tag_input.val()+\'</textarea>\').remove();
                        //$("#form-field-tags").autosize({append: "\n"});
                    }            
                }                
                ');
            }else{
                //create empty funcion - no recipients
                Yii::app()->clientScript->registerScript('D2mailTabsWriteFillRecipients', ' 
                    function define_recipients(){
                    }                
                ');
                
            }
            Yii::app()->clientScript->registerScript('D2mailTabsWrite', ' 
            var yii_request_url = "' . Yii::app()->request->url . '"; 
            var prevTab = \'inbox\';
            $(document).on(\'click\',\'#inbox-tabs a[data-toggle="tab"]\', function (e) {
                $(".message-container").append(\'<div class="message-loading"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>\');
                var currentTab = $(e.currentTarget).attr(\'data-target\');
                if(currentTab == \'write\') {
                        var url = "' . $this->write_mail['form_url'] . '" 
                                + "&model_name=" + d2mail_model_name
                                + "&model_id=" + d2mail_model_id 
                                + "&add_data=" + list_add_data;
                        $.get(
                            url, 
                            function( data ) {
                                $("div.message-container").html(data);
                                $(".message-container").find(".message-loading").remove();
                                define_recipients();
                            }                    
                        ) 
                        return;
                }
                if(currentTab == \'sent\') {
                        var url = "/?r=d2messages/ajax/sent";
                        $.get(
                            url + "&add_data=" + list_add_data , 
                            function( data ) {
                                $("div.message-container").html(data);
                                $(".message-container").find(".message-loading").remove();
                            }                    
                        );                             
                        return;
                }
                if(prevTab == \'write\'){
                         var url = "' . $this->message_list_ajax_url . '";
                        $.get(
                            url + "&add_data=" + list_add_data , 
                            function( data ) {
                                $("div.message-container").html(data);
                                $(".message-container").find(".message-loading").remove();
                            }                    
                        );                             
                    }
                    //add other tab reading
                    prevTab = currentTab;
                    return;
            });
			//back to message list
            $(document).on(\'click\',\'#id-message-new-navbar .btn-back-message-list\', function(e) {
					e.preventDefault();
                    $(".message-container").append(\'<div class="message-loading"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>\');                    
                    $(\'#inbox-tabs a[data-target="write"]\').parent().removeClass("active");                    
                    var url = "' . $this->message_list_ajax_url . '";
                    $.get(
                       url + "&add_data=" + list_add_data , 
                       function( data ) {
                           $("div.message-container").html(data);
                           $(".message-container").find(".message-loading").remove();
                       }                    
                   );                                                 
            });            
            
            //send
            $(document).on(\'click\',\'.btn-send-message\', function(e) {
				e.preventDefault();
                $(".message-container").append(\'<div class="message-loading"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>\');
                
                //to url add additional data
                var url = "' . $this->write_mail['form_url'] . '" 
                        + "&model_name=" + d2mail_model_name
                        + "&model_id=" + d2mail_model_id 
                        + "&add_data=" + list_add_data;
                
                //add recipients to post
                var post_data = $("#id-message-form").serializeArray();
                
                //post form with recipients
                $.post(
                    url, 
                    post_data,
                    function( data ) {
                        $("div.message-container").html(data);
                        $(\'#inbox-tabs a[data-target="write"]\').parent().removeClass("active");  
                        $(".message-container").find(".message-loading").remove();
                    }                    
                );
            });             
            ');
        }
        //for search
        Yii::app()->clientScript->registerScript('D2mailSearch', ' 
            $(document).on(\'submit\',\'#messages_search_form\', function(e) {  
                e.preventDefault();
                $(".message-container").append(\'<div class="message-loading"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>\');
                var url = "' . $this->message_list_ajax_url . '" 
                    + "&search=" + encodeURIComponent($("#messages_search_input").val());
                $.get(
                    url + "&add_data=" + list_add_data, 
                    function( data ) {
                        $("div.message-container").html(data);
                        $(".message-container").find(".message-loading").remove();
                    }                    
                );                                    
            })
        ');
        
        //pagenator
        Yii::app()->clientScript->registerScript('D2mailTabsList', ' 
            $(document).on(\'click\',\'.message-footer a\', function(e) {
                e.preventDefault();
                $(".message-container").append(\'<div class="message-loading"><i class="icon-spin icon-spinner orange2 bigger-160"></i></div>\');
                var url = "' . $this->message_list_ajax_url . '";
                if($("#messages_search_input").val().length > 0){    
                    url = url.concat(\'&search=\' + encodeURIComponent($(\'#messages_search_input\').val()));
                }
                var a_href = $(e.currentTarget).attr("href");    
                var page = a_href.substring(a_href.lastIndexOf("=") + 1);
                $.get(
                    url + "&add_data=" + list_add_data +"&page=" + page, 
                    function( data ) {
                        $("div.message-container").html(data);
                        $(".message-container").find(".message-loading").remove();
                    }                    
                );                        
            });
            
            //show/hide inline messages
            $(document).on("click",".message-list .message-item .text", function(e) {
                var for_append = $(e.currentTarget).parent().parent();
                var last = $(for_append).children().last();
                if($(last).hasClass("message-content")){
                    $(last).remove();
                     $(for_append).removeClass("message-unread");
                }else{
                    var post_url = "' . CHtml::normalizeUrl($this->show_inline_url) . '&d2mm_id="+$(for_append).attr("data-internalid");
                    $.post(
                        post_url, 
                        $("#id-message-form").serialize(),
                        function( data ) {
                            $(for_append).append(data);
                        }                    
                    );    
                }
            });
         
        ');
        
        //recipients
        Yii::app()->clientScript->registerScript('D2mailFormRecipients', '

                ');
    }

    /**
     * @todo move to uldisn/ace
     */
    public function initCss() {

        /**
         * - time column larger
         * - added column subject
         */
        Yii::app()->clientScript->registerCss('d2messages_fix_time_width', ' 
            .message-item .time {width: 120px;}
            .message-item .subject {
                display: inline-block;
                margin-left: 30px;
                max-width: calc(100% - 200px);
                min-width: 200px;
                position: relative;
                vertical-align: middle;
                white-space: nowrap;
            }
            .message-item .subject .text {
                color: #555;
                cursor: pointer;
                display: inline-block;
                height: 18px;
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
                white-space: nowrap;
                width: auto;
            }            
            .message-item .model_name {
                display: inline-block;
                margin-left: 30px;
                max-width: calc(100% - 100px);
                min-width: 200px;
                position: relative;
                vertical-align: middle;
                white-space: nowrap;
            }
            .message-item .model_name .text {
                color: #555;
                cursor: pointer;
                display: inline-block;
                height: 18px;
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
                white-space: nowrap;
                width: auto;
            }            
            .message-item .model_label {
                display: inline-block;
                margin-left: 30px;
                max-width: calc(100% - 200px);
                min-width: 200px;
                position: relative;
                vertical-align: middle;
                white-space: nowrap;
            }
            .message-item .model_label .text {
                color: #555;
                cursor: pointer;
                display: inline-block;
                height: 18px;
                max-width: 100%;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
                white-space: nowrap;
                width: auto;
            }            
        ');
        
        Yii::app()->clientScript->registerCss('d2messages_form_recipient', ' 
            .tags {width:100%  !important}
 }
        ');        
    }

    public function run() {
        $this->render($this->widgets_view_path . '.D2MailTabs', array(
            'write_mail' => $this->write_mail,
            'left_tabs' => $this->left_tabs,
        ));
    }

}
