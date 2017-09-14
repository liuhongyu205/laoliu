<?php

namespace system\model;

use houdunwang\model\Model;

class Grade extends Model {
	/**
	 * 添加
	 * @param $data	post提交的数据
	 *
	 * @return array
	 */
	public function add($data){
		//1.验证数据不能为空
		if(!trim ($data['gname'])) return ['code'=>0,'msg'=>'班级名称不能为空'];
		//3.班级名称不能重复
		$gradeData = $this->where("gname='{$data['gname']}'")->getAll();
		if($gradeData) return ['code'=>0,'msg'=>'班级已经存在，请勿重复添加'];
		//2.执行添加
		$this->insert($data);
		return ['code'=>1,'msg'=>'添加成功'];
	}

	public function edit($gid,$data){
		//1.验证数据不能为空
		if(!trim ($data['gname'])) return ['code'=>0,'msg'=>'班级名称不能为空'];
		//2.班级不能重复
		$gradeData = $this->where("gname='{$data['gname']}' and gid !={$gid}")->getAll();
		if($gradeData) return ['code'=>0,'msg'=>'班级已经存在'];
		//3.执行更新
		$res = $this->where("gid={$gid}")->update($data);
		if($res){
			return ['code'=>1,'msg'=>'更新成功'];
		}
		return ['code'=>0,'msg'=>'数据未更新'];
	}
}