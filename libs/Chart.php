<?php 
/**
 * @abstract 按照开源Open Flash Char2的Json数据格式，进行了数据封装
 * @author Yuntaohu <yuntaohu@51f.com>
 * @date 2011-03-04
 * @lastModify By Yuntaohu 2011-03-04
 *
 */
class Chart{
    
    private $mOFC;
    private $mTitle;
    private $mValue;
    private $mLabel;
    private $mYLeftMin;
    private $mYLeftMax;
    private $mYRightMin;
    private $mYRightMax;
    private $mLineColor;
    private $mLegend;
    private $mType;
    private $sType;
    private $mUserRightChart = array();
    private $mRotate;
    private $mSteps = 5;
    private $mWindow = '#val#<br>#x_label#';
    
    public function __construct( $type = 'line', $sType = '' )
    {
        $this->mLineColor = array(
            '#f56200',
            '#5735ae',
            '#799e00',
            '#0897ed'
        );
        $this->mType = $type;
        $this->sType = $sType;
    }
    
    public function __destruct()
    {
        
    }
    
    /**
     * 设置标题
     *
     * @param string $title
     */
    public function set_title( $title = '' ){
        if ( !empty($title) ) {
            $this->mTitle = $title;
        }
    }
    
    /**
     * 设置曲线数据
     *
     * @param array $values
     */
    public function set_value( $value = array() ){
        $this->mValue = $value;
    }
    
    /**
     * 设置横坐标刻度显示
     *
     * @param array $label
     */
    public function set_label( $label = array() ){
        $this->mLabel = $label;
    }
    
    /**
     * 设置横坐标刻度偏移量
     *
     * @param int $num 0-360
     */
    public function set_label_rotate( $num = 0 ){
        $this->mRotate = $num;
    }
    
    /**
     * 设置左侧纵坐标最小刻度
     *
     * @param int $num
     */
    public function set_y_left_min( $num = 0 ){
        $this->mYLeftMin = $num;
    }
    
    /**
     * 设置左侧纵坐标最大刻度
     *
     * @param int $num
     */
    public function set_y_left_max( $num = 1 ){
        $this->mYLeftMax = $num;
    }
    
    /**
     * 设置左侧纵坐标说明
     *
     * @param string $legend
     */
    public function set_legend( $legend = 'Open Flash Chart' ){
        $this->mLegend = $legend;
        //$this->mLegend = $legend;
    }
    
    /**
     * 设置右侧纵坐标最小刻度
     *
     * @param int $num
     */
    public function set_y_right_min( $num = 0 ){
        $this->mYRightMin = $num;
    }
    
    /**
     * 设置右侧纵坐标最大刻度
     *
     * @param int $num
     */
    public function set_y_right_max( $num = 1 ){
        $this->mYRightMax = $num;
    }
    
    /**
     * 设置有坐标显示的曲线图
     *
     * @param unknown_type $line
     */
    public function set_use_right_chart( $line = array() ){
        $this->mUserRightChart = $line;
    }
    
    /**
     * 设置纵坐标间隔数量
     *
     * @param int $s
     */
    public function set_steps($s = 5){
    	$this->mSteps = $s;
    }
    
    public function set_window($s){
    	$this->mWindow = $s;
    }
    
    /**
     * 生成JSON字符串
     *
     * @return string
     */
    public function toJson(){
        //处理生成数据源
        if ( !empty($this->mValue) ) {
            $i = 0;
            foreach ( $this->mValue as $key => $elements ) {
                $mLineName = $key;
                //$mLineName = $key;
                switch ( $this->mType ) {
                    case"line":
                        {
                            $mOFC['elements'][] = array(
                                'type'      => 'line',
                                'values'    => $elements,
                                'colour'    => $this->mLineColor[$i],
                                'text'      => $mLineName,
                                'font-size' => 12,
                                'width'     => 2,
                                'axis'      => in_array($key, $this->mUserRightChart) ? 'right' : '',
                                'dot-style' => array(
                                                   'type'               => empty($this->sType) ? 'anchor' : $this->sType, //star,bow,hollow-dot,anchor
                                                   //'dot-size'           => 5,
                                                   'dot-size'           => 4,
                                                   'halo-size'          => 2,
                                                   'tip'                => $mLineName.'<br>'.$this->mWindow,
                                                   'rotation'           => 100,
                                                   'sides'              => 10,
                                                   'alpha'              => 1,
                                                   'hollow'             => false,
                                                   'background-colour'  => '#FFFFFF',
                                                   'background-alpha'   => 0.2
                                               ),
                                /*'on-show'   => array(
                                                    'type'      => 'pop-up', //pop-up,explode,mid-slide,drop,fade-in,shrink-in
                                                    'cascade'   => 1,
                                                    'delay'     => 0.5
                                                )*/
                            );
                        }
                        break;
                    case"bar":
                        {
                            $mOFC['elements'][] = array(
                                'type'      => empty($this->sType) ? 'bar_filled' : $this->sType, //bar,bar_filled,bar_glass,bar_3d,bar_sketch,bar_cylinder,bar_cylinder_outline,bar_round_glass,bar_round,bar_dome
                                'values'    => $elements,
                                'colour'    => $this->mLineColor[$i],
                                'tip'       => $mLineName.'<br>'.$this->mWindow,
                                'on-show'   => array(
                                                    'type'      => 'grow-up', //pop-up,drop,fade-in,grow-up,grow-down,pop
                                                    'cascade'   => 1,
                                                    'delay'     => 0.5
                                                )
                            );
                        }
                        break;
                    case"pie":
                        {
                            $mOFC['elements'][] = array(
                                'type'          => empty($this->sType) ? 'pie' : $this->sType, 
                                'values'        => $elements,
                                'colours'       => $this->mLineColor,
                                'tip'           => '#val#<br>#total#(#percent#)',
                                'alpha'         => 0.6,
                                'start-angle'   => 30,
                                'animate'       => array(
                                                        'type'  => ''
                                                    )
                            );
                        }
                        break;
                }
                
                $i++;
            }
        }
        
        //处理生成标题
        if ( !empty($this->mTitle) ) {
            $mOFC['title'] = array(
                'text'       => $this->mTitle
            );
        }
        
        //处理生成左侧纵坐标显示
        if ( !empty($this->mYLeftMax) ) {
            $mOFC['y_axis'] = array(
                'min'         => empty($this->mYLeftMin) ? 0 : $this->mYLeftMin,
                'max'         => empty($this->mYLeftMax) ? 1 : $this->mYLeftMax,
                'steps'       => $this->mSteps,
                'stroke'      => 1,
                'colour'      => '#c6d9fd',
                'grid-colour' => '#dddddd'
            
            );
        }
        
        //处理生成右侧纵坐标显示
        if ( !empty($this->mYRightMax) ) {
            $mOFC['y_axis_right'] = array(
                'min'         => empty($this->mYRightMin) ? 0 : $this->mYRightMin,
                'max'         => empty($this->mYRightMax) ? 1 : $this->mYRightMax,
                'steps'       => $this->mSteps,
                'stroke'      => 1,
                'colour'      => '#c6d9fd',
                'grid-colour' => '#dddddd',
                'labels' => array(
                                'size' => 12,
                            )
            );
        }
        
        //处理生成横坐标显示
        $mOFC['x_axis'] = array(
            'labels'     => array(
                                'labels' => $this->mLabel,
                                'stroke'     => 1,
                                'size'       => 10,
                                'rotate'     => empty($this->mRotate) ? 0 : $this->mRotate
                            ),
            'offset'     => true,
            'grid-colour'=> '#dddddd',
            'colour'     => '#909090',
            '3d'         => $this->sType == 'bar_3d' ? 5 : 0
        );
        
        //设置左侧坐标轴说明
        if ( !empty($this->mLegend) ) {
            $mOFC['y_legend'] = array(
                'text'     => $this->mLegend,
                'style'    => array(
                                  'color'        => '#736AFF',
                                  'font-size'    => '30px;'
                              )
            );
        }
        
        //设置背景颜色
        $mOFC['bg_colour']  = '#ffffff';
        return json_encode($mOFC);
    }
}
?>