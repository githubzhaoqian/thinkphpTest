<?php
namespace Base\Controller;

use Think\Controller;
use Common\FunctionTrait\FunctionTrait;
use Think\Storage;
use Think\Hook;

class BaseController extends Controller {
    use FunctionTrait;

    /**
     * 视图实例对象
     * @var view
     * @access protected
     */    
    protected $viewi     =  null;

    protected $themei = null;

    /**
     * 架构函数 取得模板对象实例
     * @access public
     */
    public function __construct() {
        parent::__construct();
        //实例化视图类
        $this->viewi = new BaseView();

    }

    /**
     * 封装 theme 模板主题和 diplay 模板显示调用内置的抹布安引擎显示方法
     * 增加模板配置文件和
     * @param string $theme 模版主题
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    protected function themeDisplay($theme='',$templateFile='',$charset='',$contentType='',$content='',$prefix=''){

        if(defined('TMPL_PATH')){
            $this->viewi->display($templateFile,$charset,$contentType,$content,$prefix);
        }
        
        $this->view->display($templateFile,$charset,$contentType,$content,$prefix);

     //    $themePath = TMPL_PATH.'config.php';

    	// if(Storage::has($themePath)){
     //        $themeConfig = include($themePath);
     //    }
        
    	// //print_r($themeConfig);

    	// // $this->view->theme($theme)->display($templateFile,$charset,$contentType,$content,$prefix);
     //    G('viewStartTime');
     //    // 视图开始标签
     //    Hook::listen('view_begin',$templateFile);
     //    // 解析并获取模板内容
     //    $content = $this->baseFetch($templateFile,$content,$prefix);
     //    // 输出模板内容
     //    //$this->render($content,$charset,$contentType);
     //    // 视图结束标签
     //    //Hook::listen('view_end');// 
    }

    /**
     * 模板主题设置
     * @access protected
     * @param string $theme 模版主题
     * @return Action
     */
    protected function themei($theme=''){
        if(empty($theme) && empty($this->$baseTheme)){
            $theme = 'default';
        }
        $this->$themei = $theme;
        $this->viewi->theme($theme);
        return $this;
    }



}