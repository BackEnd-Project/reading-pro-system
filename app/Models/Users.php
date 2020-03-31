<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table='rp_users'; // 为模型指定表名
    protected $primaryKey    = 'uuid'; // 默认情况下指定'id'作为表主键，也可以指定主键名
    public $timestamps    = false; // 默认情况下，Eloquent 期望数据表中存在 created_at 和 updated_at 字段，设置false可以取消
    protected $fillable      = ['email', 'username', 'password', 'phone', 'head', 'sex', 'uuid']; // 定义允许添加、更新的字段白名单，不设置则无法添加数据
//    protected $dateFormat    = 'U'; // 定制时间戳的格式
//    protected $guarded       = ['uuid']; // 定义不允许更新的字段黑名单

}
