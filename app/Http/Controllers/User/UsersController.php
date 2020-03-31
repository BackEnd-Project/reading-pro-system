<?php

namespace App\Http\Controllers\User;

use App\User;
//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Users;


class UsersController
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
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function index()
    {

    }

    public function create(Request $request)
    {
        // 获取数据
        $data = $request->validate([
            'username'  =>  'required',
            'password'   =>  'required',
            'sex'   =>  'required',
            'email'   =>  'required'
        ]);
        // 实例化模型
        $user = new Users;
        $user->uuid = rand();
        $user->username = $data['username'];
        $user->password  = $data['password'];
        $user->sex  = $data['sex'];
        $user->email  = $data['email'];
        // 向数据库中插入一条记录,返回值为新增数据数组对象
        $result = $user->save();
        var_dump($result);
    }


    public $speak;
    public $sex;
    private $name;
    private $age;

    public function __construct($name="", $age=25, $sex='男', $speak = 'Hello World')
    {
        $this->name = $name;
        $this->age = $age;
        $this->sex = $sex;
        $this->speak = $speak;
    }

    /**
     * @param $content
     *
     * @return bool
     */
    public function __isset($content) {
        echo "当在类外部使用isset()函数测定私有成员{$content}时，自动调用<br>";
        echo isset($this->$content);
    }
}


$person = new UsersController("小明", 25); // 初始赋值
echo $person->speak,"<br>";

