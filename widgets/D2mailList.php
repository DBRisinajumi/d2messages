<?php

/**
 * show messages list with pagenator
 */
class D2mailList extends CWidget {
    //public $tabs = array();

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
     * search expresion
     * @var type 
     */
    public $search = '';

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
    public $widgets_view_path;

    public function init() {
        $columns = array(
            'unread',
            'checkbox',
            'stared',
            'sender',
            'badge',
            'message_flags',
            'subject',
            'summary',
            'attachment',
            'time',
            'model_name',
            'model_label',
        );
        $setted_columns = $this->messages_format['columns'];
        unset($this->messages_format['columns']);
        foreach ($columns as $column) {
            $this->messages_format['columns'][$column] = in_array($column, $setted_columns);
        }

        if ($this->search === false) {
            $this->search = '';
        }
    }

    /**
     * get message ist from db and remap as requestes in maping
     * @return type
     */
    private function getModelData() {
        $model = new $this->data_model;
//        $data = $model->findAll($this->criteria);
//        var_dump($data);exit;        

        $count = $model->count($this->criteria);

        $this->_pages = new CPagination($count);
        $this->_pages->pageSize = $this->pageSize;
        $this->_pages->applyLimit($this->criteria);

        $data = $model->findAll($this->criteria);
        //var_dump($data);exit;
        $messages = array();
        while ($row = array_shift($data)) {
            $new_row = array();
            foreach ($this->maping as $to => $from) {
                if (!is_array($from)) {
                    $new_row[$to] = $row[$from];
                    continue;
                }
                if (count($from) == 1) {
                    $new_row[$to] = $row[$from[0]];
                    continue;
                }
                if (count($from) == 2) {
                    $new_row[$to] = $row[$from[0]][$from[1]];
                    continue;
                }
                if (count($from) == 3) {
                    $new_row[$to] = $row[$from[0]][$from[1]][$from[2]];
                    continue;
                }
            }

            $messages[] = $new_row;
        }
        //var_dump($messages);exit;
        return $messages;
    }

    public function run() {
        $this->render($this->widgets_view_path . '.D2mailList', array(
            'title_big' => $this->title_big,
            'title_small' => $this->title_small,
            'messages_format' => $this->messages_format,
            'messages' => $this->getModelData(),
            'pages' => $this->_pages,
            'search' => $this->search,
        ));
    }

}
