<?php

namespace App\Http\Controllers\User;

use App\User;
//use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
        // 冒泡排序(第一层是循环的次数，第二层是比较）
        $arr = [1, 5, 6, 9, 7, 4, 10, 36, 8, 43];
        $length = count($arr);
        for ($i = 0; $i < $length - 1; $i ++) {
            for ($j = 0; $j < $length - 1 - $i; $j ++) {
                if ($arr[$j] > $arr[$j + 1]) {
                    $temp = $arr[$j + 1];
                    $arr[$j + 1] = $arr[$j];
                    $arr[$j] = $temp;
                }
            }
        }
//        return $arr;
//        echo '<pre>';
//        var_dump($arr);
//        echo '<br>';

        // 定义一个随机的数组（第一层和第二层做比较）
        $a = array(23,15,43,25,54,2,6,82,11,5,21,32,65);
        // 第一层可以理解为从数组中键为0开始循环到最后一个
        for ($i = 0; $i < count($a) ; $i++) {
            // 第二层为从$i+1的地方循环到数组最后
            for ($j = $i+1; $j < count($a); $j++) {
                if ($a[$i] > $a[$j]) {
                    $tem = $a[$i]; // 这里临时变量，存贮$i的值
                    $a[$i] = $a[$j]; // 第一次更换位置
                    $a[$j] = $tem; // 完成位置互换
                }
            }
        }
//        echo '<pre>';
//        var_dump($a);

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

