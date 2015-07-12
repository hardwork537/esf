/**
 * 用户注销
 * @returns  bool
 */
function loginout() {
    if (!confirm("确定要退出登录吗?"))
        return false;
    
    $.ajax({
        type: 'POST',
        url: '/login/out',   
        dataType: 'JSON',
        success: function (data) {
            if(data.status == 0) {
                location.href = '/home/';
            } 
        }
    });  
    
    return true;
}