<?php
namespace app\admin\controller;

class Logrecord extends Admin
{
	/**
	 * 记录列表
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
		$request = input('get.');
		
		$data = model('LogRecord')->getList( $request );
		return $data;
	}

}