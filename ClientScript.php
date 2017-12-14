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

    /**
     * @see {@link CClientScript::registerScript} id parameter
     * @var string
     */
    protected $id;
    /**
     * @see {@link CClientScript::registerScript} position parameter
     * @var integer
     */
    protected $position;
    
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

        $script = preg_replace('/\s*<\/?script(.*)>\s*/i', '', ob_get_clean());
        Yii::$app->getView()->registerJs($script, $this->position, $this->id);
    }
}
