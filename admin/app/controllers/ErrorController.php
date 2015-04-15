<?php
/**
 * @abstract  错误处理
 */

define("NO_NEED_LOGIN", true);
define("NO_NEED_POWER", true);

class ErrorController extends ControllerBase
{

    public function nofoundAction()
    {
        $this->show(null, $data, 'layoutsingle');
    }

    public function noaccessAction()
    {
        $this->show(null, null, 'layoutsingle');
    }

}
