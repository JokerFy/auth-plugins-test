<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/3
 * Time: 15:53
 */
namespace Finley\authPlugins\Model;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = "test";
    public function getOne($id){
        return Test::where('bcr_id', '=', $id)->get();
    }
}