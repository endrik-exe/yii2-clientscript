<?php

namespace endrikexe;

use Yii;
use yii\web\View;

/**
 * Allow you to write javascript inside <script> tag, no nowdoc string
 * 
 * Usage,
 * 
 * <?php $clientScript->beginScript('your-id'); ?>
 * //YOUR JS
 * <?php $clientScript->endScript(); ?>
 */
class ClientScript {
    
    private static $singleton;
    private static $lastId;
    
    public static function singleton()
    {
        if (self::$singleton == null)
        {
            self::$singleton = new ClientScript();
        }
        
        return self::$singleton;
    }
    
    /**
     * @var string
     */
    protected $id;
    /**
     * @var integer
     */
    protected $position;
    
    protected $script;
    
    /**
     * Begin script block
     * 
     * @param string $id
     * @param constant $pos
     */
    public function beginScript($id, $pos = View::POS_READY) {

        $this->id = $id;
        $this->position = $pos;     

        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * End script block, and register JS
     */
    public function endScript() {
        if (self::$lastId != null && self::$lastId == $this->id)
        {
            $this->script .= preg_replace('/\s*<\/?script(.*)>\s*/i', '', ob_get_clean());
        } else
        {
            $this->script = preg_replace('/\s*<\/?script(.*)>\s*/i', '', ob_get_clean());
        }
        
        Yii::$app->getView()->registerJs($this->script, $this->position, $this->id);
        self::$lastId = $this->id;
    }
}
