<?php
class CrmBaseModel extends BaseModel
{
	protected $rtn = array("status"=>0,"info"=>"success");

	/**
     * 返回特定自定义的数组
     * @return type
     */
    public function Rtn($data = array()){
        return array_merge($this->rtn,$data);
    }

    /**
     * 返回错误信息数组
     * @return type
     */
    public function Error($data = array()){
        $this->rtn['status'] = 1;
        if(is_array($data)){
            return array_merge($this->rtn,$data);
        }else{
            $this->rtn['info'] = $data;
            return array_merge($this->rtn);
        }

    }

    /**
     * 返回固定的特定的错误数组
     * @return type
     */
    public function RtnError($data = array()){
        $this->rtn = array("status"=>1,"info"=>"error");
        return array_merge($this->rtn,$data);
    }

}