<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Users;
use PDO;
use Ramsey\Uuid\Uuid;

class UsersController extends Controller
{
//    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];

    /**
     * 获取用户列表
     */
    public function index(Request $request)
    {
        # 条件
        $where = function ($query) use ($request) {
            if ($request->has('username') && $request->username) {
                $search = "%{$request->username}%";
                $query->where('username', 'like', $search);
            }
            if ($request->has('uuid') && $request->uuid) {
                $query->where('uuid', $request->uuid);
            }
            if ($request->has('end_at') && $request->end_at) {
                $query->where("update_time", "<=", "{$request->end_at} 23:59:59");
            }
            if ($request->has('start_at') and $request->start_at) {
                $query->where("create_time", ">=", $request->start_at);
            }
        };
        # 返回的字段
        $columns = ['uuid', 'email', 'username', 'english_name', 'type', 'phone', 'phonecode', 'create_time', 'update_time', 'status'];
        # 查询
        $result = Users::where($where)
                    ->orderBy('create_time', 'desc')
                    ->get($columns);

        # 结果集对象转数组
        $dataList = $result->toArray();
        $dataCount = count($dataList);
        if ($dataList) {
            $this->echoJson(1, [
                'data' => $dataList,
                'dataCount' => $dataCount,
            ]);
        } else {
            $this->echoJson(1, [
                'data' => $dataList,
                'dataCount' => $dataCount,
            ]);
        }

    }

    /**
     * 创建用户
     */
    public function store(Request $request)
    {
        // 参数验证
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'sex'      => 'required',
            'email'    => 'required'
        ]);

        // 实例化模型
        $user = new Users;
        $user->uuid = generateUuid();
        $user->create_time  = getNowTime();
        // 获取和设置要进行更新的数据
        $map = $request->except('id');
        foreach ($map as $key => $value) {
            if ($key == '_url') continue;
            if ($key == 'password') $value = sha1($value);
            $user->$key = $value;
        }
        // 向数据库中插入一条记录,返回值为新增数据数组对象
        $result = $user->save();
        if ($result) {
            $this->echoSuccess();
        } else {
            $this->echoFail();
        }
    }

    /**
     * 更新用户
     */
    public function edit(Request $request, $uuid)
    {
        // 更新数据前需要先获取当前数据
        $user = Users::find($uuid);
        // 判断更新的数据是否存在
        if (!$user) {
            $this->echoJson(-1, ['info' => 'data not exist!']);
            exit();
        }

        // 获取和设置要进行更新的数据
        $map = $request->except('id');

        if (empty($map)) {
            return '没有更新任何数据';
        }

        foreach ($map as $k => $v) {
            if ($k == '_url') {
                continue;
            }
            $user->$k = $v;
        }

        // 返回更新后的用户信息集合
        $result = $user->save();

        if ($result) {
            $this->echoSuccess();
        } else {
            $this->echoFail();
        }
    }

    /**
     * 删除用户
     */
    public function destroy(Request $request, $uuid)
    {
        $user = Users::find($uuid);
        // 需要判断删除的数据是否存在
        if (!$user) {
            $this->echoJson(-1, ['info' => 'data not exist!']);
            exit();
        }
        $result = $user->delete();

        if ($result) {
            $this->echoSuccess();
        } else {
            $this->echoFail();
        }
    }

    /**
     * 登录
     */
    public function login(Request $request)
    {
        # 条件
        $where = function ($query) use ($request) {
            if ($request->has('email') && $request->email) $query->where('email', $request->email);
            if ($request->has('phone') && $request->phone) $query->where('phone', $request->phone);
        };

        # 查询
        $result = Users::where($where)->first();
        $result = $result->toArray();
        # 用户不存在
        if (count($result) == 0) {
            $this->echoJson(-1, ['info' => 'data not exist!']);
            exit();
        }
        # 校验密码
        if (sha1($request->password) === $result['password']) {
            $this->echoSuccess();
        } else {
            $this->echoFail();
        }

    }
}



