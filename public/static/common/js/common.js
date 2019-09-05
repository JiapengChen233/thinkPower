/**
 * ajax请求
 * @param async {boolean}                  同步\异步，默认为true，所有请求均为异步请求，如果需要发送同步请求，请将此选项设置为false
 * @param cache {boolean}                  缓存，默认为true，dataType为script和jsonp时默认为false，设置为false将不缓存此页面
 * @param contentType {string|boolean}     发送信息至服务器时内容编码类型，默认为"application/x-www-form-urlencoded"
 * @param data {string|object}             发送到服务器的数据，将自动转换为请求字符串格式
 * @param dataType {string}                预期服务器返回的数据类型
 * @param processData {boolean}            是否处理待发送的数据，默认为true，如果不希望处理，请将此选项设置为false
 * @param type {string}                    请求方法，默认为GET
 * @param url {string}                     请求地址
 * @param beforeSend {function}            发送请求前调用此函数
 * @param error {function}                 请求失败时调用此函数
 * @param success {function}               请求成功时调用此函数
 * @param complete {function}              请求完成时调用此函数
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-6 09:30:48
 */
function ajaxRequest(async, cache, contentType, data, dataType, processData, type, url, beforeSend, error, success, complete) {
    $.ajax({
        async: async,
        cache: cache,
        contentType: contentType,
        data: data,
        dataType: dataType,
        processData: processData,
        type: type,
        url: url,
        beforeSend: beforeSend,
        error: error,
        success: success,
        complete: complete
    });
}

/**
 * ajax请求（默认设置）
 * @param data {string}            发送到服务器的数据，将自动转换为请求字符串格式
 * @param url {string}             请求地址
 * @param success {function}       请求成功时调用此函数
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-6 10:09:36
 */
function ajaxDefalutRequest(data, url, success) {
    ajaxRequest(true, true, "application/x-www-form-urlencoded", data, "json", true, "POST", url, null, null, success, null);
}

/**
 * 上传图片
 * @param url {string}          请求地址
 * @param file {File}           包含图片的File对象
 * @param error {function}      请求失败时调用此函数
 * @param success {function}    请求成功时调用此函数
 * @param complete {function}   请求完成时调用此函数
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-6 10:09:48
 */
function uploadImage(url, file, error, success, complete) {
    if (!file instanceof File) {
        layer.alert("请选择图片上传！", {icon: 5});
        return false;
    }
    // 校验图片
    if (file.size > 2 * 1024 * 1024) {
        layer.alert("图片大小不得超过2M！", {icon: 5});
        return false;
    }
    const extArr = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "image/gif"
    ];
    if (extArr.indexOf(file.type) === -1) {
        layer.alert("图片格式仅支持jpg|jpeg|png|gif！", {icon: 5});
        return false;
    }
    const formData = new FormData();
    formData.append("file", file);
    ajaxRequest(true, false, false, formData, "json", false, "POST", url, null, error, success, complete);
}

/**
 * 更改记录状态
 * @param title {string|object}   提示语
 * @param obj {object}            元素对象
 * @param params {string}         请求参数
 * @param url {string}            请求地址
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-8 18:34:58
 */
function stateStop(title, obj, params, url) {
    if (title === undefined || title == null) {
        if ($(obj).attr('title') === '停用') {
            title = "确认要停用吗？";
        } else {
            title = "确认要启用吗？";
        }
    }
    layer.confirm(title, function () {
        //发异步把用户状态进行更改
        ajaxDefalutRequest(params, url, function (res) {
            if (res.state === -1) {
                layer.alert(res.message, {icon: 5});
            } else if (res.state === 400) {
                window.location.href = "{:url('admin/login/login')}";
            } else {
                if ($(obj).attr('title') === '启用') {
                    $(obj).attr('title', '停用');
                    $(obj).find('i').html('&#xe601;');
                    $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                    layer.msg('已启用!', {icon: 6, time: 1000});
                } else {
                    $(obj).attr('title', '启用');
                    $(obj).find('i').html('&#xe62f;');
                    $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                    layer.msg('已停用!', {icon: 5, time: 1000});
                }
            }
        });
    });
}

/**
 * 删除记录
 * @param title {string|object}   提示语
 * @param obj {object}            元素对象
 * @param params {string}         请求参数
 * @param url {string}            请求地址
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-8 18:35:10
 */
function del(title, obj, params, url) {
    if (title === undefined || title == null) {
        title = "确认要删除吗？";
    }
    layer.confirm(title, function () {
        //发异步删除数据
        ajaxDefalutRequest(params, url, function (res) {
            if (res.state === -1) {
                layer.alert(res.message, {icon: 5});
            } else if (res.state === 400) {
                window.location.href = "{:url('admin/login/login')}";
            } else {
                $(obj).parents("tr").remove();
                layer.msg('已删除!', {icon: 6, time: 1000});
            }
        });
    });
}

/**
 * 批量删除记录
 * @param title {string|object}   提示语
 * @param url {string}            请求地址
 * @author RonaldoC
 * @version 1.0.0
 * @date 2019-8-8 23:32:25
 */
function delAll(title, url) {
    let obj = $("input[lay-filter=single]:checked");
    if (obj.length === 0) {
        layer.msg('请选择要删除的记录!', {icon: 5, time: 1000});
        return false;
    }
    let arr1 = [];
    let arr2 = [];
    obj.each(function (index, el) {
        let arr3 = el.value.split("-");
        arr1.push(arr3[0]); // 登录名
        arr2.push(arr3[1]); // ID
    });
    if (title === undefined || title == null) {
        title = "确认要删除 " + arr1 + " 吗？";
    }
    del(title, obj, "ids=" + arr2, url);
}


