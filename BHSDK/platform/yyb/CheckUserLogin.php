    <?php
    require_once dirname(__FILE__).'/Api.php';
    require_once dirname(__FILE__).'/fun.php';
    require_once dirname(__FILE__).'/ManageYYBUser.php';
    $area = $_GET['area'];
    $openid = isset($_GET['openid'])? $_GET['openid'] :'';
    $server = isset($_GET['server'])?$_GET['server']:'';
    $openkey = isset($_GET['openkey'])? $_GET['openkey'] :'';
    //合并区服时使用  此加载文件只可在此区域加载
    require_once '../../ext/CheckArea.php';
    
    if(is_null($area))
    {
    $area = 100;
    }
    // 应用基本信息，需要替换为应用自己的信息，必须和客户端保持一致
    // 需要登录腾讯开放平台 open.qq.com，注册开发者，并创建移动应用，审核通过后可以获得APPID和APPKEY
    $appid = '1105389198';
    $appkey = 'kNWHgtMOy7w4tdQl';
    
    // YSDK后台API的服务器域名
    // 调试环境: ysdktest.qq.com
    // 正式环境: ysdk.qq.com   APP ID           APPKEY
    
    // 调试环境仅供调试时调用，调试完成发布至现网环境时请务必修改为正式环境域名
    $server_name = 'ysdktest.qq.com';
    $zoneId=1;
    // 创建YSDK实例
    /// 当前UNIX时间戳
    $ts=time();
    // 用户的IP，可选，默认为空
    $userip = '';
    
    $sdk = new Api($appid, $appkey);
    // 设置支付信息
    //$sdk->setPay($pay_appid, $pay_appkey);
    // 设置YSDK调用环境
    $sdk->setServerName($server_name);
    $params = array(
    'appid' => $appid,
    'openid' => $openid,
    'openkey' => $openkey,
    'userip' => $userip,
    'sig' =>   md5($appkey.$ts),
    'timestamp' => $ts,
    );
    $ras = qq_check_token($sdk, $params);
    if($ras['ret'] == 0){
    $new = new ManageYYB();
    $new->manageYYBUser($area,$openid,$server,$channel_type);
    }ELSE{
    ECHO json_encode($ras['msg']);
    }
    
    
    
    
    
    
    
    
    
    
    
    ?>