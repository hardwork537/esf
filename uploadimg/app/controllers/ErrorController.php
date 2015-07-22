<?php
/**
 * @abstract  错误处理
 */

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
