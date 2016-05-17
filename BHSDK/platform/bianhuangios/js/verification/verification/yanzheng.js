// JavaScript Document
function __changeUserName(of){
  	  var username=$('#'+of).val();
  	  if(of=='email'){
  	      if (username.search(/^[\w\.+-]+@[\w\.+-]+$/) == -1) {
						//非法用户
						showTooltips('email_input','请输入正确的Email地址');
						return;
					}					
  	  }else{
  	      if(username=='' || !isMobilePhone(username)) {
		        showTooltips('mobile_input','请输入正确的手机号码');
		        return;
		      }
  	  }
  	  if($('#way_'+of).attr('checked') == true){
			   var d = loadJSONDoc("user/searchUser?username=" + username);
         d.addCallbacks(isUserExist, onFail);
			}
  }
  function __changeUserName1(of){
  	  var username=$('#'+of).val();
  	  if(of=='email1'){
  	      if (username.search(/^[\w\.+-]+@[\w\.+-]+$/) == -1) {
						//非法用户
						showTooltips('email_input1','请输入正确的Email地址');
						return;
					}					
  	  }else{
  	      if(username=='' || !isMobilePhone(username)) {
		        showTooltips('mobile_inpu1t','请输入正确的手机号码');
		        return;
		      }
  	  }
  	  if($('#way_'+of).attr('checked') == true){
			   var d = loadJSONDoc("user/searchUser?username=" + username);
         d.addCallbacks(isUserExist, onFail);
			}
  }
  
	isUserExist = function(data) {		
		  var way='email';
		  if($('#way_mobile').attr('checked') == true){
		      way='mobile';
		  }
      if(data.isExist==1) {
      	hideTooltips(way+'_input');
      	showTooltips(way+'_input','用户名已存在');
      } else {
      	hideTooltips(way+'_input');
      }
	}
	
	onFail = function(data) {
		
	}
	
	function checkPwd1(pwd1) {
		if (pwd1.search(/^.{6,20}$/) == -1) {
			showTooltips('password1_input','密码为空或位数太少');
		}else {
			hideTooltips('password1_input');
		}
	}
	function checkPwd2(pwd2) {
		if (pwd2.search(/^.{6,20}$/) == -1) {
			showTooltips('password2_input','密码为空或位数太少');
		}else {
			hideTooltips('password2_input');
		}
	}
	function checkEmail(email) {
		if (email.search(/^.+@.+$/) == -1) {
			showTooltips('email_input','邮箱格式不正确');
		}else {
			hideTooltips('email_input');
		}
    }

    function checkAuthCode(authcode) {
		if (authcode == '' || authcode.length != 4) {
			showTooltips('code_input','验证码不正确');
		}else {
			hideTooltips('code_input');
     }     
    }
	    function checkAuthCode1(authcode1) {
		if (authcode1 == '' || authcode1.length != 4) {
			showTooltips('code_input1','验证码不正确');
		}else {
			hideTooltips('code_input1');
     }     
    }
	
    function check() {
			hideAllTooltips();
			var ckh_result = true;
    	if ($('#email').val() == '') {
      	showTooltips('email_input','邮箱不能为空');
        ckh_result = false;
      }
      if ($('#password1').val() == '') {
      	showTooltips('password1_input','密码不能为空');
        ckh_result = false;
      } 
	  if ($('#password2').val() == '') {
      	showTooltips('password2_input','密码不能为空');
        ckh_result = false;
      }       
      if($('#mobile').val()=='' || !isMobilePhone($('#mobile').val())) {            
          showTooltips('mobile_input','手机号码不正确');
          ckh_result = false;
      }
      if ($('#code').val() == '' || $('#code').val().length !=4) {
      	showTooltips('code_input','验证码不正确');
        ckh_result = false;
      }
	   if ($('#code1').val() == '' || $('#code1').val().length !=4) {
      	showTooltips('code_input1','验证码不正确');
        ckh_result = false;
      }
      if ($('#verify').attr('checked') == false){
      	showTooltips('checkbox_input','对不起，您不同意闪卓网的《使用协议》无法注册');
      	ckh_result = false;
      }
      return ckh_result;
    }
    
    function checkMobilePhone(telphone) {
    	if(telphone=='' || !isMobilePhone(telphone)) {
        showTooltips('mobile_input','请输入正确的手机号码');
      }else{
        hideTooltips('mobile_input');
      }
    }
    
  
    function isMobilePhone(value) {
			if(value.search(/^(\+\d{2,3})?\d{11}$/) == -1)
			  return false;
			else
			  return true;
		} 