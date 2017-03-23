<?php
namespace app\admin\model;

use \think\Config;
use \think\Model;
use \think\Session;
use think\Loader;


/**
 * 操作日志记录
 */
class LogRecord extends Admin
{
    protected $updateTime = false;
    protected $insert     = ['ip', 'user_id','browser','os'];
    protected $type       = [
        'create_time' => 'int',
    ];

    /**
     * 记录ip地址
     */
    protected function setIpAttr()
    {
        return \app\common\tools\Visitor::getIP();
    }

    /**
     * 浏览器把版本
     */
    protected function setBrowserAttr()
    {
        return \app\common\tools\Visitor::getBrowser().'-'.\app\common\tools\Visitor::getBrowserVer();
    }

    /**
     * 系统类型
     */
    protected function setOsAttr()
    {
        return \app\common\tools\Visitor::getOs();
    }

    /**
     * 用户id
     */
    protected function setUserIdAttr()
    {
        $user_id = 0;
        if (Session::has('userinfo', 'admin') !== false) {
            $user = Session::get('userinfo','admin');
            $user_id = $user['id'];
        }
        return $user_id;
    }
 
    public function record($remark)
    {
        $this->save(['remark' => $remark]);
    }


    public function UniqueIpCount()
    {   
        $data = $this->column('ip');
        $data = count( array_unique($data) );
        return $data;
    }

    public function getList($request)
    {
    	$request = $this->fmtRequest( $request );
    
    	$total = $this->where($request['map'])->count('id');
    
    	$rows = $this->order('id desc')->where($request['map'])->limit($request['offset'], $request['limit'])->select();
    
    	return ['rows' => $rows, 'total' => $total];
    }
    
    
}
