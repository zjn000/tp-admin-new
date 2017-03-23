<?php

function isTurnOn($rule_val='')
{
	return controller('Admin')->checkRule(session('userinfo.id','','admin'), $rule_val);
}


function info($msg = '', $code = '', $url = '',  $data = '', $wait = 3 )
{
	if (is_numeric($msg)) {
        $code = $msg;
        $msg  = '';
    }
    if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
        $url = $_SERVER["HTTP_REFERER"];
    } elseif ('' !== $url) {
        $url = preg_match('/^(https?:|\/)/', $url) ? $url : Url::build($url);
    }
	$result = [
        'code' => $code,
        'msg'  => $msg,
        'data' => $data,
        'url'  => $url,
        'wait' => $wait,
	];
	return $result;
}

/**
 * 数组排序
 * @param &$list 数据的引用地址
 * @param $pid 父ID
 * @param $index 索引
 * @return $data 返回新数据到原数据地址
 */
function sort_list(&$list, $pid = 0, $index = 0){
	if (empty($list)) {
		return;
	}
	$data = array();
	foreach ($list as $key => $value) {
		if ($value['pid'] == $pid) {
			unset($list[$key]);
			if ($pid > 0) {
				$split_str = '&emsp;├─';
				for ($i = $index - 1; $i > 0; $i --) {
					$split_str .= ' ─ ─ ';
				}
				$value['split'] = $split_str;
				$value['level'] = $index;
			}else{
				$value['split'] = '';
				$value['level'] = 0;
			}
			$data[] = $value;
			$children = sort_list($list, $value['id'], $index + 1);
			if(!empty($children)){
				$data = array_merge($data , $children);
			}
		}
	}

	// 把没有父节点的数据追加到返回结果中，避免数据丢失
	if($pid == 0 ){
		if(count($list) > 0){
			$data = array_merge($data, $list);
		}

		$list = $data;
		return $list;
	}
	return $data;
}

?>