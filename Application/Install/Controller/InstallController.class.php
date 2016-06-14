<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Install\Controller;
use Base\Controller\BaseController;
use Think\Db;
use Think\Storage;
use Common\Check\Check;

class InstallController extends BaseController{

    protected function _initialize(){
        if(Storage::has( 'Conf/install.lock')){
            $this->error(L('installed'));
        }
    }

    public function step($step){
        $func = 'step'.$step;

        $this->$func();
    }

    //安装第一步，检测运行所需的环境设置
    private function step1(){
        session('error', false);

        //目录文件读写检测
        if(IS_WRITE){
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        session('step', 1);

        $this->displayi(__FUNCTION__);
    }

    //安装第二步，创建数据库
    private function step2($db = null, $admin = null){
        if(IS_POST){


            //检测管理员信息
            if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
                $this->error('请填写完整管理员信息');
            } else if($admin[1] != $admin[2]){
                $this->error('确认密码和密码不一致');
            } else {
                $info = array();
                list($info['username'], $info['password'], $info['repassword'], $info['email'])
                    = $admin;
                //缓存管理员信息
                session('admin_info', $info);
            }

            //检测数据库配置
            if(!is_array($db) || empty($db[0]) ||  empty($db[1]) || empty($db[2]) || empty($db[3])){
                $this->error('请填写完整的数据库配置');
            } else {
                $DB = array();
                list($DB['DB_TYPE'], $DB['DB_HOST'], $DB['DB_NAME'], $DB['DB_USER'], $DB['DB_PWD'],
                    $DB['DB_PORT'], $DB['DB_PREFIX']) = $db;
                //缓存数据库配置
                cookie('db_config',$DB);

                //创建数据库
                $dbname = $DB['DB_NAME'];
                unset($DB['DB_NAME']);

                $db  = Db::getInstance($DB);

                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";

                try{
                    $db->execute($sql);
                }catch (\Think\Exception $e){
                    if(strpos($e->getMessage(),'getaddrinfo failed')!==false){
                        $this->error( '数据库服务器（数据库服务器IP） 填写错误。','很遗憾，创建数据库失败，失败原因');// 提示信息
                    }
                   if(strpos($e->getMessage(),'Access denied for user')!==false){
                       $this->error('数据库用户名或密码 填写错误。','很遗憾，创建数据库失败，失败原因');// 提示信息
                   }else{
                       $this->error( $e->getMessage());// 提示信息
                   }
                }
                session('step',2);
                // $this->error($db->getError());exit;
            }

            //跳转到数据库安装页面
            $this->redirect('step3');
        } else {
                session('error') && $this->error('环境检测没有通过，请调整环境后重试！');

                $step = session('step');
                if($step != 1 && $step != 2){
                   // $this->redirect('step1');
                }

                session('step', 2);
                $this->displayi(__FUNCTION__);

        }
    }

    //安装第三步，安装数据表，创建配置文件
    private function step3(){
       /* if(session('step') != 2){
            $this->redirect('step2');
        }*/

        $this->displayi(__FUNCTION__);

            //连接数据库
            $dbconfig = cookie('db_config');
            $db = Db::getInstance($dbconfig);
            //创建数据表

            create_tables($db, $dbconfig['DB_PREFIX']);
            //注册创始人帐号
            $auth  = build_auth_key();
            $admin = session('admin_info');
            register_administrator($db, $dbconfig['DB_PREFIX'], $admin, $auth);

            //创建配置文件
            $conf   =   write_config($dbconfig, $auth);
            session('config_file',$conf);


        if(session('error')){
            show_msg(session('error'));
        } else {
            session('step', 3);

            echo "<script type=\"text/javascript\">setTimeout(function(){location.href='".U('Index/complete')."'},5000)</script>";
            ob_flush();
            flush();
        }
    }

    public function error($info,$title='很遗憾，安装失败，失败原因'){
        $this->assign('info',$info);// 提示信息
        $this->assign('title',$title);
        $this->displayi(__FUNCTION__);exit;
    }
}
