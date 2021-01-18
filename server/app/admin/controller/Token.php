<?php
declare (strict_types = 1);

namespace app\admin\controller;

use think\facade\Db;

class Token extends BaseController {
    /**
     * 生成用户登录令牌
     * @param $user_id
     * @return string|null
     */
    public static function refreshUserToken(int $user_id): string | null {
        if (!$user_id) {
            return false;
        }
        //生成token
        $rand_num    = rand(10, 99999); //随机数
        $time        = time(); //时间戳
        $expire_time = $time + 3600 * 24; //过期时间为1天
        $token       = md5($user_id . $rand_num . $time);
        //更新当前用户token 和 有效期
        $update = [
            'id'          => $user_id,
            'token'       => $token,
            'expire_time' => $expire_time,
        ];
        $res = Db::name('admin')->update($update);
        if ($res !== false) {
            return $token;
        } else {
            return null;
        }

    }
}