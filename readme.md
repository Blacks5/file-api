# filesystem by lumen
> 阿里云 oss , 七牛云 
> 可以通过微信`media-id`获取微信图片上传
[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

> base url : 

# POST `/`
> 上传照片

- POST参数
```json
# 微信medie-id
{
    image:'media-id'
}
# 文件上传
{
    image:multipart/form-data
}
```
- 响应数据
```
{
    "success":true,
    "error":[],
    "data":{
        "status":1,
        "message":"文件保存成功",
        "realName":"images/2017/10xxxx.jpg",
        "uuid":"5243778087dc4fd5afefe25d9fbd15e0",
        "path":"2017/10/5243778087dc4fd5afefe25d9fbd15e0",
        "provider":"aliyun"  //[aliyun, qiniu]
    }
}
```
# GET `/{uuid}`
> 获取单张图片地址    
> 包括 微信上传,阿里云 OSS,七牛云

- 响应数据
```
{
    "success":true,
    "error":[],
    "data":{
        "path":"http://tnew.oss-cn-shenzhen.aliyuncs.com/2017%2F10%2F?OSSAccessKeyId=LTAILQA5vx0OdUtJ&Signature=CjVfNhPDwtkD1Nh%2BHLC5eHh3fFg%3D&Expires=1509069351"
    }
}
```
# GET `/?images[]={uuid}&images[]={uuid}...`
> 批量获取图片地址

- 响应数据
```
{
    "success":ture,
    "error":[],
    "data":{
        "path":[
            {
                "uuid":"d8e58c3bf3ba49c1a1678aeffbfc9934",
                "path":"http://tnew.oss-cn-shenzhen.aliyuncs.com/2017%2F10%2Fd8e58c3bf3ba49c1a1678aeffbfc9934?OSSAccessKeyId=LTAILQA5vx0OdUtJ&Signature=4FI9EztrRnshIUQ6nvUPAMOAl%2Fs%3D&Expires=1509069754"
            },
            {
                "uuid":"d8e58c3bf3ba49c1a1678aeffbfc9934",
                "path":"http://tnew.oss-cn-shenzhen.aliyuncs.com/2017%2F10%2Fd8e58c3bf3ba49c1a1678aeffbfc9934?OSSAccessKeyId=LTAILQA5vx0OdUtJ&Signature=4FI9EztrRnshIUQ6nvUPAMOAl%2Fs%3D&Expires=1509069754"
            }
            ...
        ]
    }
}
```
# DELETE `/{uuid}`
> 删除图片

- 响应数据
```
{
    "success":true,
    "error":[],
    "data":{
        "status":1
        "message":"图片删除成功"
    }
}
```

## License

You can use this, see [MIT license](http://opensource.org/licenses/MIT)
