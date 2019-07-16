<?php

// 200（OK）- 如果现有资源已被更改
// 201（created）- 如果新资源被创建
// 202（accepted）- 已接受处理请求但尚未完成（异步处理）
// 301（Moved Permanently）- 资源的URI被更新
// 303（See Other）- 其他（如，负载均衡）
// 400（bad request）- 指代坏请求
// 404 （not found）- 资源不存在
// 406 （not acceptable）- 服务端不支持所需表示
// 409 （conflict）- 通用冲突
// 412 （Precondition Failed）- 前置条件失败（如执行条件更新时的冲突）
// 415 （unsupported media type）- 接受到的表示不受支持
// 500 （internal server error）- 通用错误响应
// 503 （Service Unavailable）- 服务当前无法处理请求

// 未登录
defined('ERROR_NOT_LOGGED_ON') or define('ERROR_NOT_LOGGED_ON', 402);
// 缺少用户信息
defined('ERROR_NOT_FOUND_USER_INFO') or define('ERROR_NOT_FOUND_USER_INFO', 4022);
// 缺少参数
defined('ERROR_MISSING_PARAMETER') or define('ERROR_MISSING_PARAMETER', 404);
// 找不到obj
defined('ERROR_OBJ_NOT_FOUND') or define('ERROR_OBJ_NOT_FOUND', 404);
// 找不到object
defined('ERROR_OBJECT_NOT_FOUND') or define('ERROR_OBJECT_NOT_FOUND', 404);
// 找不到上传文件
defined('ERROR_UPLOAD_FILE_NOT_FOUND') or define('ERROR_UPLOAD_FILE_NOT_FOUND', 404);
// 非法参数
defined('ERROR_ILLEGAL_PARAMETER') or define('ERROR_ILLEGAL_PARAMETER', 405);
// 非法请求
defined('ERROR_ILLEGAL_REQUEST') or define('ERROR_ILLEGAL_REQUEST', 406);
// 数据库错误
defined('ERROR_DATABASE_ERROR') or define('ERROR_DATABASE_ERROR', 407);
// 数据已过期
defined('ERROR_HAS_EXPIRED') or define('ERROR_HAS_EXPIRED', 408);
// 账号或密码错误
defined('ERROR_ACCOUNT_OR_PASSWORD') or define('ERROR_ACCOUNT_OR_PASSWORD', 400);
// 服务器错误
defined('ERROR_SERVER_ERROR') or define('ERROR_SERVER_ERROR', 500);
// 上传文件错误
defined('ERROR_UPLOAD_FILE_ERROR') or define('ERROR_UPLOAD_FILE_ERROR', 501);
// 上传文件错误
defined('ERROR_FILE_HAS_BEEN_UPLOADED') or define('ERROR_FILE_HAS_BEEN_UPLOADED', 503);


if ( !function_exists('outRight') ) {

    /**
     * 返回信息
     * @param $ret
     * @param array $header
     */
    function outRight($ret, $header = [])
    {
        response(array(
            'errno' => getErrno(),
            'errstr' => getErrorString(),
            'data' => $ret,
            'errpos' => getErrorPos()
        ), 200, $header, 'json')->send();
    }
}


if ( !function_exists('outError') ) {
    /**
     * 返回错误信息
     * @param  [integer] $errno  [错误号]
     * @param  [string] $errstr [错误提示]
     * @param  [array] $errstr [请求头]
     * @return [type]         [description]
     */
    function outError($errno = null, $errstr = '', $header = [])
    {
        if ($errno === null) {
            $errno = getErrno();
        }

        if(defined('DEBUG_ERROR_POS') && DEBUG_ERROR_POS && (!isset($GLOBALS['_TP']['errorpos']) || empty($GLOBALS['_TP']['errorpos']))) {
            $info = debug_backtrace();
            $GLOBALS['_TP']['errorpos'] = $info[0]['file'] . ' +' . $info[0]['line'];
        }
        // 发送给前端, 后续代码继续执行
        return response(
        	array(
	        	'errno' => $errno, 
	        	'errstr' => $errstr ? $errstr : getErrorString($errno),
	        	'errpos' => getErrorPos()
        	),
        	200,
        	$header,
        	'json'
        	)->send();
    }
}



if ( !function_exists('setError') ) {
    /**
     * 设置错误信息
     * @param $errno
     * @param string $errstr
     * @return bool
     */
    function setError($errno, $errstr = '')
    {
        $GLOBALS['_TP']['errorno'] = $errno;
        if (!$errstr) {
            $GLOBALS['_TP']['errorstr'] = getErrorString();
        } else {
            $GLOBALS['_TP']['errorstr'] = $errstr;
        }
        if((defined('DEBUG_ERROR_POS') && DEBUG_ERROR_POS) || (function_exists('config') && config('DEBUG_ERROR_POS'))) {
            $info = debug_backtrace();
            $GLOBALS['_TP']['errorpos'] = $info[0]['file'] . ' +' . $info[0]['line'];
        }
        return false;
    }
}

if ( !function_exists('getErrorString') ) {
    /**
     * 获取错误提示字符串
     * @param  [integer] $err [错误号]
     * @return [string]      [错误提示]
     */
    function getErrorString($err = null) {
        if($err === null) {
            $err = getErrno();
        }
        if ($err == 0) {
            return 'ERROR_OK';
        }
        if (($errstr = getErrstr())) {
            return $errstr;
        }
        $c = get_defined_constants();
        foreach($c as $k => $v) {
            if (strncmp($k, 'ERROR_', 6) == 0) {
                if ($v == $err) {
                    return $k;
                }
            }
        }

        return 'UNDEFINED ERROR CODE!';
    }
}

if ( !function_exists('getErrorPos') ) {
    /**
     * 获取错误位置
     * @return [string] [错误位置]
     */
    function getErrorPos() {
        return isset($GLOBALS['_TP']['errorpos']) ? $GLOBALS['_TP']['errorpos'] : '';
    }
}


if ( !function_exists('getErrno') ) {
    /**
     * 获取错误号
     * @return [string] [错误号]
     */
    function getErrno()
    {
        return isset($GLOBALS['_TP']['errorno']) ? $GLOBALS['_TP']['errorno'] : 0;
    }
}


if ( !function_exists('getErrstr') ) {
    /**
     * 获取错误提示
     * @return [string] [错误提示]
     */
    function getErrstr()
    {
        return isset($GLOBALS['_TP']['errorstr']) ? $GLOBALS['_TP']['errorstr'] : '';
    }
}


if ( !function_exists('hashMd5') ) {
    /**
     * 加密
     * @param $str
     * @return string
     */
    function hashMd5($str)
    {
        return md5(md5($str . 'zhiqiang'));
    }
}


if (!function_exists('checkInt')) {
    /**
     * 检查整型
     * @param $var
     * @param int $default
     * @return int
     */
    function checkInt($var, $default = 0) {
        return  is_numeric($var) ? intval($var, (strncasecmp($var, '0x', 2) == 0 || strncasecmp($var, '-0x', 3) == 0) ? 16 : 10) : $default;
    }
}



if (!function_exists('checkFloat')) {
    /**
     * 检查浮点
     * @param $var
     * @param int $default
     * @return float|int
     */
    function checkFloat($var, $default = 0) {
        return  is_numeric($var) ? floatval($var) : $default;
    }
}


if (!function_exists('checkBool')) {
    /**
     * 检查布尔
     * @param $var
     * @param bool $default
     * @return bool
     */
    function checkBool($var, $default = false) {
        if (is_bool($var))
        {
            return $var;
        }
        static $f = array('false', '0', 'no', 'off', 'null', 'nil', 'nan');

        if (in_array(strtolower($var), $f))
        {
            return false;
        }
        return $var ? true : $default;
    }
}


if (!function_exists('checkString')) {
    /**
     * 检查字符串
     * @param $var
     * @param string $check
     * @param string $default
     * @return mixed|string
     */
    function checkString($var, $check = '', $default = '') {
        if (!is_string($var)) {
            if(is_numeric($var)) {
                $var = (string)$var;
            }
            else {
                return $default;
            }
        }
        if ($check) {
            return (preg_match($check, $var, $ret) ? $ret[1] : $default);
        }
        return $var;
    }
}







/*
	绘图
	$option = array(
		back_ground => array(
			'path' => 图片路径
			'w'
			'h'
		),
		image => array(
		array(
			'data' => //图片数据，与path二选一
			'path' =>

			'size' => array(w,h)
			'point' => array(x,y)
			'circle' => 是否圆形图像
		)
		),
		string => array(
		array(
			'content' => 文字内容
			'color' => #ff0000

			'size' => 字体大小px
			'point' => array(x,y)
			'center' => 是否居中对齐
		)
		),
	)

	输出一张png图片到浏览器
*/
if (!function_exists('uct_gd_draw_poster')) {
    /**
     * 画图
     * @param $option
     * @return false|string
     */
    function uct_gd_draw_poster($option) {
        //1. 背景图
        $r_bg = imagecreatefromstring(file_get_contents($option['back_ground']['path']));
        $w = isset($option['back_ground']['size']['0']) ? $option['back_ground']['size']['0'] : 0;
        $h = isset($option['back_ground']['size']['1']) ? $option['back_ground']['size']['1'] : 0;
        if($w && $h) {
            $r_bg = uct_gd_resize($r_bg, $w, $h);
        }

        //2. 贴图
        if(!empty($option['image']))
            foreach($option['image'] as $i) {
                if (isset($i['resource'])) {
                    $r_i = $i['resource'];
                } elseif (isset($i['data'])) {
                    $r_i = imagecreatefromstring($i['data']);
                } elseif (isset($i['path'])) {
                    $r_i = imagecreatefromstring(file_get_contents($i['path']));
                }

                if(!empty($i['size']['0']) && !empty($i['size']['1'])) {
                    $r_i = uct_gd_resize($r_i, $i['size']['0'], $i['size']['1']);
                }
                uct_gd_paste($r_bg, $r_i, $i['point']['0'], $i['point']['1'], !empty($i['circle']) || !empty($i['l']));
                imagedestroy($r_i);
            }

        //3. 文字
        if(!empty($option['string'])) {
            foreach($option['string'] as $s) {
                $bold    = !empty($s['bold']) ? 'bd' : '';
                $font    = PUBLIC_PATH . '/font/msyh' . $bold . '.ttf';
                $angle   = isset($s['angle']) ? $s['angle']: 0;
                if(!empty($s['center'])) {
                    //文字居中
                    $box = imagettfbbox($s['size'], $angle, $font, $s['content']);
                    $width = abs($box[4] - $box[0]);
                    $height = abs($box[5] - $box[1]);
                    $x = $s['point']['0'] - $width/2;
                    $y = $s['point']['1'] + $height/2;
                } else {
                    $x = $s['point']['0'];
                    $y = $s['point']['1'];
                }

                imagettftext($r_bg, $s['size'], $angle, $x, $y, uct_gd_get_color($r_bg, $s['color']), $font, $s['content']);
            }
        }

        header('Content-Type: ', 'image/png');
        header('Cache-Control: public');
        header('Last-Modified: ' . $_SERVER['REQUEST_TIME']);
        ob_start();
        imagepng($r_bg);
        return ob_get_clean();
    }
}



if (!function_exists('uct_gd_resize')) {
    /**
     * 图片缩放
     * @param $r [gd图片资源]
     * @param $w [宽]
     * @param $h [高]
     * @return false|resource
     */
    function uct_gd_resize($r, $w, $h) {
        $size_src = array(imagesx($r), imagesy($r));
        if($size_src[0] == $w && $size_src[1] == $h) {
            return $r;
        }
        $new = imagecreatetruecolor($w, $h);
        imagecopyresampled($new, $r, 0, 0, 0, 0, $w, $h, $size_src['0'], $size_src['1']);

        return $new;
    }
}


if (!function_exists('uct_gd_paste')) {
    /**
     * 把src画到dst上去，
     * @param $r_dst
     * @param $r_src
     * @param $x [x轴]
     * @param $y [y轴]
     * @param bool $round [是否圆形图片]
     */
    function uct_gd_paste($r_dst, $r_src, $x, $y, $round = false) {
        $size_src = array(imagesx($r_src), imagesy($r_src));
        if(!$round) {
            imagecopy($r_dst, $r_src, $x, $y, 0, 0, $size_src['0'], $size_src['1']);
        } else {
            $r=($size_src['0'] * $size_src['0']/4 + $size_src['1'] * $size_src['1']/4)/2;
            for($i = 0; $i < $size_src['0']; $i++) {
                for($j = 0; $j < $size_src['1']; $j++) {
                    if(($i-$size_src['0']/2)*($i-$size_src['0']/2) +
                        ($j-$size_src['1']/2)*($j-$size_src['1']/2)<= $r) {
                        imagesetpixel($r_dst, $x+$i, $y+$j, imagecolorat($r_src, $i, $j));
                    }
                }
            }

        }
    }
}


if (!function_exists('uct_gd_get_color')) {
    /**
     * 获取图片背景色
     * @param $img
     * @param $str
     * @return false|int
     */
    function uct_gd_get_color($img, $str) {
        $str = ltrim($str, '#');
        $r = base_convert(substr($str, 0 ,2), 16, 10);
        $g = base_convert(substr($str, 2 ,2), 16, 10);
        $b = base_convert(substr($str, 4 ,2), 16, 10);

        return imagecolorallocate($img, $r, $g, $b) ?: 2;
    }
}