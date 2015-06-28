<?php

class Image extends BaseModel
{

    //图片分类
    const TYPE_DEFAULT = 1; //默认分类
    //图片来源
    const FROM_CRM_HOUSE = 1; //crm后台编辑图片
    const FROM_UEDITOR = 2; //百度编辑框
    //图片状态
    const STATUS_OK = 1; //有效
    const STATUS_DEL = -1; //删除
    const STATUS_NOPASS = 0; //审核失败

    public $imgId;
    public $imgType;
    public $imgExt;
    public $imgWidth;
    public $imgHeight;
    public $imgSize;
    public $imgMd5Data;
    public $imgMd5Url;
    public $imgFrom;
    public $imgFromId = 0;
    public $imgStatus;
    public $imgUpdate;

    public function getSource()
    {
        return 'image';
    }

    public function columnMap()
    {
        return array(
            'imgId' => 'imgId',
            'imgType' => 'imgType',
            'imgExt' => 'imgExt',
            'imgWidth' => 'imgWidth',
            'imgHeight' => 'imgHeight',
            'imgSize' => 'imgSize',
            'imgMd5Data' => 'imgMd5Data',
            'imgMd5Url' => 'imgMd5Url',
            'imgFrom' => 'imgFrom',
            'imgFromId' => 'imgFromId',
            'imgStatus' => 'imgStatus',
            'imgUpdate' => 'imgUpdate'
        );
    }

    public function initialize()
    {
        $this->setReadConnectionService('esfSlave');
        $this->setWriteConnectionService('esfMaster');
    }

    /**
     * 获取指定MD5值的图片信息
     *
     * @return object
     */
    public function getImageByMd5($imgMd5Data = '', $imgMd5Url = '')
    {
        if(empty($imgMd5Data) && empty($imgMd5Url))
        {
            return false;
        }
        if($imgMd5Data)
        {
            $strCond = "imgMd5Data = ?1";
            $arrParam = array(1 => $imgMd5Data);
        } else
        {
            $strCond = "imgMd5Url = ?1";
            $arrParam = array(1 => $imgMd5Url);
        }
        $objRes = self::findFirst(array(
                $strCond,
                "bind" => $arrParam
        ));
        return empty($objRes) ? array() : $objRes->toArray();
    }

}
