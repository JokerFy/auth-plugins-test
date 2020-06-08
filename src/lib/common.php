<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

//生成随机字符串
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;
    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}

//因二维内部的一维数组不能完全相同，而删除重复项
function array_unique_fb($arr,$key) {
    $tmp_arr = array();
    foreach($arr as $k => $v)
    {
        if(in_array($v[$key], $tmp_arr))   //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
        {
            unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值
        }
        else {
            $tmp_arr[$k] = $v[$key];  //将不同的值放在该数组中保存
        }
    }
    //ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值
    return $arr;
}

// 接口返回形式
function SuccessNotify($data = [])
{
    $returnArr = [
        'msg'=>"操作成功",
        'data'=>$data
    ];
    return $returnArr;
}


/**
 * 将菜单进行无限极递归分类
 * @param $menuData
 * @param int $parent_id
 * @return array
 */
function treeData($menuData,$parent_id=0){
    $treeData = [];
    foreach ($menuData as $key => $val){
        if($val['parent_id'] == $parent_id){
            //通过type将路由菜单的显示定在二级为止
            if($val['type'] == 0) {
                $val['list'] = treeData($menuData, $val['menu_id']);
            }
            $treeData[] = $val;
        }
    }
    return $treeData;
}