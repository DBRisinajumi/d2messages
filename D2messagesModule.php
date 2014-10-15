<?php

class D2messagesModule extends CWebModule
{
    /**
     * define url to model record. Example:
        array(
                'AobjObjects' => array(
                    'route' => 'ras/aobjObjects/view',
                    'params' => array(),
                    'model_id_name' => 'aobj_id',
                    
                )
            ),
     * 
     * @var array 
     */
    public $path_to_models_records;
	
    public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'd2messages.models.*',
			'd2messages.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
    
    /**
     * create url to model record
     * @param string $model_name
     * @param integer $model_record_id
     * @return boolean/string
     */
    public function createUrlToModelRecord($model_name,$model_record_id){
        
        //no defined link - return false
        if(!isset($this->path_to_models_records[$model_name])){
            return false;
        }
        
        $def = $this->path_to_models_records[$model_name];

        //create params list
        $params = array($def['model_id_name'] => $model_record_id);
        if(isset($def['params'])){
            $params = array_merge($def['params'], $params);
        }
        
        return Yii::app()->createurl($def['route'],$params);
    }
    
    public function getModelReverseTranslation(){
        $translation = array();
        foreach($this->path_to_models_records as $model_name => $def){
            $translation[Yii::t('models',$model_name)] = $model_name;
        }
        
        return $translation;
        
    }
}
