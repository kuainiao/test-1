<?php

date_default_timezone_set('asia/shanghai');
class Test extends CI_Controller
{
	
    private static $_filepath; //文件路径
    private static $_filename; //日志文件名
    private static $_filehandle; //文件句柄
    /**
     *作用:初始化记录类,写入记录
     *输入:文件的路径,要写入的文件名,要写入的记录
     *输出:无
     */
    function __construct() {
        parent::__construct();
        $this->load->model('account_model', 'accountM');
        $this->load->model('pay_model', 'payM');
        $this->load->model('gindex_model', 'indexM');
		//$this->load->model('game_model_100', 'gameM');
    }
	
	
	public static function addLog($serviceName, $parameter)
    {	
		error_reporting(1);
        //默认路径为当前路径
        //$_SERVER['DOCUMENT_ROOT']
        self::$_filepath = "/tmp";
		//echo __FILE__;
		//echo  self::$_filepath;
        //默认为以时间＋.log的文件文件
        self::$_filename = $serviceName.'_'.date('Y-m-d_H', time()).'.log';
        //生成路径字串
		//echo  self::$_filename;
		
        $path = self::_createPath(self::$_filepath, self::$_filename);
        //判断是否存在该文件
		echo $path;
        if (!self::_isExist($path))
        {
            //不存在
            //没有路径的话，默认为当前目录
            if (!empty(self::$_filepath))
            {
                //创建目录
                if (!self::_createDir(self::$_filepath))
                {
                    //创建目录不成功的处理
                    die("创建目录失败!");
                }
            }
            //创建文件
            if (!self::_createLogFile($path))
            {
                //创建文件不成功的处理
                die("创建文件失败!");
            }
        }
        //生成路径字串
        $path = self::_createPath(self::$_filepath, self::$_filename);
		
        //打开文件
        self::$_filehandle = fopen($path, "a+");


        //传入的数组记录
        $str[] = "[".date("i:s")."]"."    ";
        $str[] = $parameter."\r\n";
        $str = implode('',$str);

        //写日志
        if (!fwrite(self::$_filehandle, $str))
        {
            //写日志失败
            die("写入日志失败");
        }
    }
    /**
     *作用:判断文件是否存在
     *输入:文件的路径,要写入的文件名
     *输出:true | false
     */
    private static function _isExist($path)
    {
        return file_exists($path);
    }
    /**
     *作用:创建目录
     *输入:要创建的目录
     *输出:true | false
     */
    private static function _createDir($dir)
    {
        var_dump($dir);
		var_dump(is_dir($dir));
		
		return is_dir($dir) or (self::_createDir(dirname($dir)) and mkdir($dir));
    }
    /**
     *作用:创建日志文件
     *输入:要创建的目录
     *输出:true | false
     */
    private static function _createLogFile($path)
    {
        $handle = fopen($path, "w"); //创建文件
        fclose($handle);
        return self::_isExist($path);
    }
    /**
     *作用:构建路径
     *输入:文件的路径,要写入的文件名
     *输出:构建好的路径字串
     */
    private static function _createPath($dir, $filename)
    {
        if (empty($dir)) {
            return $filename;
        } else {
            return $dir . "/" . $filename;
        }
    }

    /**
     *功能: 析构函数，释放文件句柄
     *输入: 无
     *输出: 无
     */
    function __destruct()
    {
        //关闭文件
        fclose(self::$_filehandle);
    }
}
?>
