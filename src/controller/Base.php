<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/4
 * Time: 10:30
 */

namespace Finley\authPlugins\controller;

require dirname(dirname(__FILE__))."\lib\common.php";
//require dirname(dirname(__FILE__))."\lib\initOrm.php";

class Base
{
    /**
     * 根据请求模型进行数据分页
     * @auth: finley
     * @date: 2018/11/7 下午3:46
     * @param $model 请求模型
     * @param int $page 当前请求页
     * @param int $limit 每页显示数量
     * @return mixed
     */
    public function pageList($model,$condition=[],$page=1,$limit=10)
    {
        //condition是二维数组
        /*
        ['username'  =>  ['=','fangyi'],
        'user_id'    =>  ['=',6], ]
         */
        if($condition['page']){
            $page = $condition['page'];
            unset($condition['page']);
        }
        if($condition['limit']){
            $limit = $condition['limit'];
            unset($condition['limit']);
        }

        //分页处理
        $pageOffset = ($page-1)*$limit;
        $pageList = $model->where($condition)->limit($pageOffset,$limit)->select()->toArray();
        $totalCount = $model->where($condition)->count();

        //根据接口信息对数据进行分页
        $page = [
            //数据的总条数
            'totalCount' => $totalCount,
            'pageSize' => $limit,
            //总条数/每页的数量等于总页数
            'totalPage' => ceil($totalCount / $limit),
            'currPage' => $page,
            'list' => $pageList,
        ];
        $data['page'] = $page;
        return $data;
    }
}