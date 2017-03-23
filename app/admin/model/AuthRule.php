<?php
namespace app\admin\model;

use \think\Config;
use \think\Model;
use \think\Session;


/**
 * 权限规则
 *
 * @author chengbin
 */
class AuthRule extends Admin
{
    public function getList()
    {
       	$total = $this->count('id');
        
        $rows = $this->order('id asc')->select();
        
        return $rows;
    }

    public function saveData($data)
    {
        if(isset($data['rule_val'])) {
            $data['rule_val'] = strtolower($data['rule_val']);
        }
        
        $data['update_time']=time();
        
        if(isset($data['id']) && !empty($data['id'])) {
            $this->allowField(true)->save($data, ['id' => $data['id']]);
        } else {
            $this->insert($data);
        }
    }

    //是否需要检查节点，如果不存在权限节点数据，则不需要检查
    public function isCheck( $rule_val )
    {
        $rule_val = strtolower($rule_val);
        $map = ['rule_val'=>$rule_val];
        if($this->where($map)->count()){
            return true;
        }
        return false;
    }

    public function deleteById($id)
    {
        $result = AuthRule::destroy($id);
        if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }
    }

    public function getLevelData()
    {
        $data = $this->order('pid asc')->select();
        if( empty($data) ) {
            return $data;
        }

        $ret = [];
        $list = [];
        foreach($data as $key=>$val) {
        	
            if( $val->pid == 0 ) {
                //一级权限
            	$ret[$val->id] = ['id'=>$val->id,'title'=>$val->title,'pid'=>$val->pid, 'rule_val'=>$val->rule_val];
                unset($data[$key]);
            } 
			else
			{
				//二级权限（包含三级权限）
				$list[$val->id] = ['id'=>$val->id,'title'=>$val->title,'pid'=>$val->pid, 'rule_val'=>$val->rule_val];
			}
            
        }
        
        if(!empty($data)){
	        foreach($data as $val) 
	        {
	        	//判断划分二、三级权限
	        	if(isset($list[$val->pid]))
	        	{
	        		$list[$val->pid]['f'][$val->id] = ['id'=>$val->id,'title'=>$val->title,'pid'=>$val->pid, 'rule_val'=>$val->rule_val];
	        		unset($list[$val->id]);//去除重复权限项
	        	}
	        	
	        }
        }
        
        if(!empty($list))
        {
        	//将二、三级权限项组赋值对应一级权限下
        	foreach ($list as $row)
        	{
        		$ret[$row['pid']]['c'][] = $row;
        	}
        }
        
        return $ret;
    }
}
