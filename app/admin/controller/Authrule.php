<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;

/**
* 登录
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class Authrule extends Admin
{
	/**
	 * 规则列表
	 *
	 * @author chengbin
	 */
	public function index()
	{
		return view();
	}

	/**
	 * 异步获取列表数据
	 *
	 * @author chengbin
	 * @return mixed
	 */
	public function getData()
	{
		if(!request()->isAjax()) {
			$this->error(lang('Request type error'), 4001);
		}
		
		$list = model('AuthRule')->getList();
		$rows = sort_list($list);
		
		//拼接数据
		foreach ($rows as $i => $item) {
			$rows[$i]['title'] = $item['split'].$item['title'];
		}
		
		return $list;
	}

	/**
	 * 添加规则
	 *
	 * @author chengbin
	 */
	public function add($pid = 0)
	{
		$this->assign( 'pid', $pid );
		return view();
	}

	public function setauth()
	{
		$levelData = model('AuthRule')->getLevelData();
		
		$rid = intval(input('get.id'));
		
		$ids = model('AuthAccess')->getRuleIds($rid);
		
		$this->assign('data', $levelData);
		$this->assign('rule_ids', $ids);
		return view();
	}

	/**
	 * 编辑规则
	 *
	 * @author chengbin
	 */
	public function edit( $id = '' )
	{
		$data = model('AuthRule')->get(['id'=>$id]);
		$this->assign( 'data', $data );
		return view();
	}

	/**
	 * 保存数据
	 */
	public function saveData()
	{
		$this->mustCheckRule( 'admin/authrule/edit' );
		if(!request()->isAjax()) {
			return info(lang('Request type error'));
		}
		$data = input('post.');
		model('AuthRule')->saveData($data);
		$this->success(lang('Save success'));
	}

	/**
	 * 删除
	 */
	public function delete($id = 0){
		if(empty($id)){
			return info(lang('Data ID exception'), 0);
		}
		return model('AuthRule')->deleteById($id);
	}

	public function saveAuthAccess()
	{
		if(!request()->isAjax()) {
			return info(lang('Request type error'));
		}
		$post_data = input('post.');
		$data = isset($post_data['authrule'])?$post_data['authrule']:[];
		
		$res = model('AuthAccess')->saveData($this->role_id, $data);
		if ($res['code'] == 1) {
			return $this->success();
		}
	}
}