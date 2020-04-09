<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\User as Users;
use PDO;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller implements JWTSubject
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

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function __construct()
    {
        # jwt认证中间件
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    /**
     * 获取单个用户信息
     */
    public function show(Request $request, $uuid)
    {
        # 根据uuid查询
        $user = Users::find($uuid);
        if (!$user) {
            $this->echoJson(-1, 'fail. data not exist!');
            exit();
        }
        # 结果集对象转数组
        $info = $user->toArray();
        if ($info) {
            $this->echoJson(0, 'ok',
                $info
            );
        } else {
            $this->echoJson(-1, 'fail',
                $info
            );
        }

    }

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
            $this->echoJson(0, 'ok', [
                'data' => $dataList,
                'totalCount' => $dataCount,
            ]);
        } else {
            $this->echoJson(-1,'fail', [
                'data' => $dataList,
                'totalCount' => $dataCount,
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
            'phone'    => 'required',
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
    public function update(Request $request, $uuid)
    {
        // 更新数据前需要先获取当前数据
        $user = Users::find($uuid);
        // 判断更新的数据是否存在
        if (!$user) {
            $this->echoJson(-1, 'fail. data not exist!');
            exit();
        }

        // 获取和设置要进行更新的数据
        $map = $request->except('id');

        if (empty($map)) {
            $this->echoJson(-1, 'fail. data not exist!');
            exit();
        }

        foreach ($map as $k => $v) {
            if ($k == '_url') {
                continue;
            }
            if ($k == 'password') $v = sha1($v);
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
            $this->echoJson(-1, 'fail. data not exist!');
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
        $res = Users::where($where)->first();
        $result = $res->toArray();
        # 用户不存在
        if (count($result) == 0) {
            $this->echoJson(-1, 'fail. data not exist!');
            exit();
        }
        # 校验密码
        if (sha1($request->password) === $result['password']) {
            # 登录成功，则创建token并返回
            $token = JWTAuth::fromUser($res);
            $this->echoJson(0, 'ok', [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        } else {
            $this->echoFail();
        }

    }

}



