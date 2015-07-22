<?php

class AboutController extends ControllerBase
{
    const IMAGE_FILE = 'img';
    
    public function indexAction()
    {
        if(!$this->request->isPost())
        {
            $res = array('success' => false, 'errmsg' => '无效请求');
            $this->show('JSON', $res);
        }
        //常量定义
        $dirPath = IMAGE_ROOT_PATH;
        $imgFile = self::IMAGE_FILE;
        $maxSize = IMAGE_MAX_SIZE;
        $imgType = array(
            'jpg', 'jpeg', 'gif', 'png'
        );

        //获取文件保存路径
        $filePath = $_POST['filepath'];
        $fileName = $_POST['filename'];
        if(!$filePath || !$fileName)
        {
            $res = array('success' => false, 'errmsg' => '无效请求');
            $this->show('JSON', $res);
        }


        $file = $_FILES[$imgFile];
        if(!is_uploaded_file($file['tmp_name']))
        {
            //是否存在文件
            $res = array('success' => false, 'errmsg' => '图片不存在');
            $this->show('JSON', $res);
        }

        if($fileName != $file['name'])
        {
            //是否存在文件
            $res = array('success' => false, 'errmsg' => '请求图片无效');
            $this->show('JSON', $res);
        }

        $photoExt = pathinfo($file['name']);
        $photoExt = strtolower($photoExt['extension']);
        if(!in_array($photoExt, $imgType))
        {
            //图片有效性
            $res = array('success' => false, 'errmsg' => '无效的上传文件格式');
            $this->show('JSON', $res);
        }

        $fileSize = filesize($file);
        if($fileSize > $maxSize)
        {
            //图片大小
            $res = array('success' => false, 'errmsg' => '请不要上传大于2M的文件');
            $this->show('JSON', $res);
        }

        $dirDestiPath = $dirPath . trim($filePath, '/');
        if(!is_dir($dirDestiPath))
        {
            $mkRes = PublicFunction::createFold($dirDestiPath);

            if(!$mkRes)
            {
                $res = array('success' => false, 'errmsg' => '创建文件夹失败');
                $this->show('JSON', $res);
            }
        }
        $fileDestiPath = $dirDestiPath . '/' . $fileName;

        if(!move_uploaded_file($file['tmp_name'], $fileDestiPath))
        {
            $res = array('success' => false, 'errmsg' => '图片上传失败');
            $this->show('JSON', $res);
        }

        //上传成功
        $res = array('success' => true, 'errmsg' => '图片上传成功');
        $this->show('JSON', $res);
    }

}
