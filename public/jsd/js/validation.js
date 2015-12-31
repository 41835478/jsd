/*验证*/
$.minLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength >= length;
};

$.maxLength = function(value, length , isByte) {
	var strLength = $.trim(value).length;
	if(isByte)
		strLength = $.getStringLength(value);
		
	return strLength <= length;
};
$.getStringLength=function(str)
{
	str = $.trim(str);
	
	if(str=="")
		return 0; 
		
	var length=0; 
	for(var i=0;i <str.length;i++) 
	{ 
		if(str.charCodeAt(i)>255)
			length+=2; 
		else
			length++; 
	}
	
	return length;
};

$.checkMobilePhone = function(value){
	if($.trim(value)!='')
	{
		var reg = /^(1[3458]\d{9})$/;
		return reg.test($.trim(value));
	}		
	else
		return true;
};
$.checkEmail = function(val){
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; 
	return reg.test(val);
};


/**
 * 检测密码的复杂度
 * @param pwd
 * 分数 1-2:弱 3-4:中 5-6:强 
 * 返回 0:弱 1:中 2:强 -1:无
 */
function checkPwdFormat(pwd)
{
	var regex0 = /[a-z]+/;  
	var regex1 = /[A-Z]+/;  
	var regex2 = /[0-9]+/;
	var regex3 = /\W+/;   //符号
	var regex4 = /\S{6,8}/;	    
	var regex5 = /\S{9,}/;   
	
	
	var result = 0;
	
	if(regex0.test(pwd))result++;
	if(regex1.test(pwd))result++;
	if(regex2.test(pwd))result++;
	if(regex3.test(pwd))result++;
	if(regex4.test(pwd))result++;
	if(regex5.test(pwd))result++;
	
	if(result>=1&&result<=2)
		result=0;
	else if(result>=3&&result<=4)
		result=1;
	else if(result>=5&&result<=6)
		result=2;
	else 
		result=-1;
	
	return result;
}