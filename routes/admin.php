<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| 后台公共路由部分
|
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin'],function (){
    //登录、注销
    Route::get('login','LoginController@showLoginForm')->name('admin.loginForm');
    Route::post('login','LoginController@login')->name('admin.login');
    Route::get('logout','LoginController@logout')->name('admin.logout');

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| 后台需要授权的路由 admins
|
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'auth'],function (){
    //后台布局
    Route::get('/','IndexController@layout')->name('admin.layout');
    //后台首页
    Route::get('/index','IndexController@index')->name('admin.index');
    Route::get('/index1','IndexController@index1')->name('admin.index1');
    Route::get('/index2','IndexController@index2')->name('admin.index2');
    //图标
    Route::get('icons','IndexController@icons')->name('admin.icons');
});

//系统管理
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:system.manage']],function (){
    //数据表格接口
    Route::get('data','IndexController@data')->name('admin.data')->middleware('permission:system.role|system.user|system.permission');
    //用户管理
    Route::group(['middleware'=>['permission:system.user']],function (){
        Route::get('user','UserController@index')->name('admin.user');
        //添加
        Route::get('user/create','UserController@create')->name('admin.user.create')->middleware('permission:system.user.create');
        Route::post('user/store','UserController@store')->name('admin.user.store')->middleware('permission:system.user.create');
        //编辑
        Route::get('user/{id}/edit','UserController@edit')->name('admin.user.edit')->middleware('permission:system.user.edit');
        Route::put('user/{id}/update','UserController@update')->name('admin.user.update')->middleware('permission:system.user.edit');
        //删除
        Route::delete('user/destroy','UserController@destroy')->name('admin.user.destroy')->middleware('permission:system.user.destroy');
        //分配角色
        Route::get('user/{id}/role','UserController@role')->name('admin.user.role')->middleware('permission:system.user.role');
        Route::put('user/{id}/assignRole','UserController@assignRole')->name('admin.user.assignRole')->middleware('permission:system.user.role');
        //分配权限
        Route::get('user/{id}/permission','UserController@permission')->name('admin.user.permission')->middleware('permission:system.user.permission');
        Route::put('user/{id}/assignPermission','UserController@assignPermission')->name('admin.user.assignPermission')->middleware('permission:system.user.permission');
    });
    //角色管理
    Route::group(['middleware'=>'permission:system.role'],function (){
        Route::get('role','RoleController@index')->name('admin.role');
        //添加
        Route::get('role/create','RoleController@create')->name('admin.role.create')->middleware('permission:system.role.create');
        Route::post('role/store','RoleController@store')->name('admin.role.store')->middleware('permission:system.role.create');
        //编辑
        Route::get('role/{id}/edit','RoleController@edit')->name('admin.role.edit')->middleware('permission:system.role.edit');
        Route::put('role/{id}/update','RoleController@update')->name('admin.role.update')->middleware('permission:system.role.edit');
        //删除
        Route::delete('role/destroy','RoleController@destroy')->name('admin.role.destroy')->middleware('permission:system.role.destroy');
        //分配权限
        Route::get('role/{id}/permission','RoleController@permission')->name('admin.role.permission')->middleware('permission:system.role.permission');
        Route::put('role/{id}/assignPermission','RoleController@assignPermission')->name('admin.role.assignPermission')->middleware('permission:system.role.permission');
    });
    //权限管理
    Route::group(['middleware'=>'permission:system.permission'],function (){
        Route::get('permission','PermissionController@index')->name('admin.permission');
        //添加
        Route::get('permission/create','PermissionController@create')->name('admin.permission.create')->middleware('permission:system.permission.create');
        Route::post('permission/store','PermissionController@store')->name('admin.permission.store')->middleware('permission:system.permission.create');
        //编辑
        Route::get('permission/{id}/edit','PermissionController@edit')->name('admin.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('permission/{id}/update','PermissionController@update')->name('admin.permission.update')->middleware('permission:system.permission.edit');
        //删除
        Route::delete('permission/destroy','PermissionController@destroy')->name('admin.permission.destroy')->middleware('permission:system.permission.destroy');
    });
    //菜单管理
    Route::group(['middleware'=>'permission:system.menu'],function (){
        Route::get('menu','MenuController@index')->name('admin.menu');
        Route::get('menu/data','MenuController@data')->name('admin.menu.data');
        //添加
        Route::get('menu/create','MenuController@create')->name('admin.menu.create')->middleware('permission:system.menu.create');
        Route::post('menu/store','MenuController@store')->name('admin.menu.store')->middleware('permission:system.menu.create');
        //编辑
        Route::get('menu/{id}/edit','MenuController@edit')->name('admin.menu.edit')->middleware('permission:system.menu.edit');
        Route::put('menu/{id}/update','MenuController@update')->name('admin.menu.update')->middleware('permission:system.menu.edit');
        //删除
        Route::delete('menu/destroy','MenuController@destroy')->name('admin.menu.destroy')->middleware('permission:system.menu.destroy');
    });
});


//生产管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:produce.manage']], function () {
    //产品管理
    Route::group(['middleware' => 'permission:produce.product'], function () {
        Route::get('product/data', 'ProductController@data')->name('admin.product.data');
        Route::get('product', 'ProductController@index')->name('admin.product');
        //添加
        Route::get('product/create', 'ProductController@create')->name('admin.product.create')->middleware('permission:produce.product.create');
        Route::post('product/store', 'ProductController@store')->name('admin.product.store')->middleware('permission:produce.product.create');
        //编辑
        Route::get('product/{id}/edit', 'ProductController@edit')->name('admin.product.edit')->middleware('permission:produce.product.edit');
        Route::put('product/{id}/update', 'ProductController@update')->name('admin.product.update')->middleware('permission:produce.product.edit');
        //删除
        Route::delete('product/destroy', 'ProductController@destroy')->name('admin.product.destroy')->middleware('permission:produce.product.destroy');
    });

    //批次管理
    Route::group(['middleware' => 'permission:produce.batch'], function () {
        Route::get('batch/data', 'BatchController@data')->name('admin.batch.data');
        Route::get('batch', 'BatchController@index')->name('admin.batch');
        //添加
        Route::get('batch/create', 'BatchController@create')->name('admin.batch.create')->middleware('permission:produce.batch.create');
        Route::post('batch/store', 'BatchController@store')->name('admin.batch.store')->middleware('permission:produce.batch.create');
        //编辑
        Route::get('batch/{id}/edit', 'BatchController@edit')->name('admin.batch.edit')->middleware('permission:produce.batch.edit');
        Route::put('batch/{id}/update', 'BatchController@update')->name('admin.batch.update')->middleware('permission:produce.batch.edit');
        //删除
        Route::delete('batch/destroy', 'BatchController@destroy')->name('admin.batch.destroy')->middleware('permission:produce.batch.destroy');
    });


    //出库管理
    Route::group(['middleware' => 'permission:produce.checkout'], function () {
        Route::get('checkout/data', 'CheckoutController@data')->name('admin.checkout.data');
        Route::get('checkout', 'CheckoutController@index')->name('admin.checkout');
        //添加
        Route::get('checkout/create', 'CheckoutController@create')->name('admin.checkout.create')->middleware('permission:produce.checkout.create');
        Route::post('checkout/store', 'CheckoutController@store')->name('admin.checkout.store')->middleware('permission:produce.checkout.create');
        //编辑
        Route::get('checkout/{id}/edit', 'CheckoutController@edit')->name('admin.checkout.edit')->middleware('permission:produce.checkout.edit');
        Route::put('checkout/{id}/update', 'CheckoutController@update')->name('admin.checkout.update')->middleware('permission:produce.checkout.edit');
        //删除
        Route::delete('checkout/destroy', 'CheckoutController@destroy')->name('admin.checkout.destroy')->middleware('permission:produce.checkout.destroy');
    });

     //物料管理
    Route::group(['middleware' => 'permission:produce.material'], function () {
        Route::get('material/data', 'MaterialController@data')->name('admin.material.data');
        Route::get('material', 'MaterialController@index')->name('admin.material');
        //添加
        Route::get('material/create', 'MaterialController@create')->name('admin.material.create')->middleware('permission:produce.material.create');
        Route::post('material/store', 'MaterialController@store')->name('admin.material.store')->middleware('permission:produce.material.create');
        //编辑
        Route::get('material/{id}/edit', 'MaterialController@edit')->name('admin.material.edit')->middleware('permission:produce.material.edit');
        Route::put('material/{id}/update', 'MaterialController@update')->name('admin.material.update')->middleware('permission:produce.material.edit');
        //删除
        Route::delete('material/destroy', 'MaterialController@destroy')->name('admin.material.destroy')->middleware('permission:produce.material.destroy');
    });
    
});


//财务管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:finance.manage']], function () {
    //流水管理
    Route::group(['middleware' => 'permission:finance.water'], function () {
        Route::get('water/data', 'WaterController@data')->name('admin.water.data');
        Route::get('water', 'WaterController@index')->name('admin.water');
        //添加
        Route::get('water/create', 'WaterController@create')->name('admin.water.create')->middleware('permission:finance.water.create');
        Route::post('water/store', 'WaterController@store')->name('admin.water.store')->middleware('permission:finance.water.create');
        //编辑
        Route::get('water/{id}/edit', 'WaterController@edit')->name('admin.water.edit')->middleware('permission:finance.water.edit');
        Route::put('water/{id}/update', 'WaterController@update')->name('admin.water.update')->middleware('permission:finance.water.edit');
        //删除
        Route::delete('water/destroy', 'WaterController@destroy')->name('admin.water.destroy')->middleware('permission:finance.water.destroy');
        //入帐
        Route::put('water/check', 'WaterController@check')->name('admin.water.check')->middleware('permission:finance.water.edit');
    });
    
});

//配置管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:config.manage']], function () {
    //站点配置
    Route::group(['middleware' => 'permission:config.site'], function () {
        Route::get('site', 'SiteController@index')->name('admin.site');
        Route::put('site', 'SiteController@update')->name('admin.site.update')->middleware('permission:config.site.update');
    });
    //广告位
    Route::group(['middleware' => 'permission:config.position'], function () {
        Route::get('position/data', 'PositionController@data')->name('admin.position.data');
        Route::get('position', 'PositionController@index')->name('admin.position');
        //添加
        Route::get('position/create', 'PositionController@create')->name('admin.position.create')->middleware('permission:config.position.create');
        Route::post('position/store', 'PositionController@store')->name('admin.position.store')->middleware('permission:config.position.create');
        //编辑
        Route::get('position/{id}/edit', 'PositionController@edit')->name('admin.position.edit')->middleware('permission:config.position.edit');
        Route::put('position/{id}/update', 'PositionController@update')->name('admin.position.update')->middleware('permission:config.position.edit');
        //删除
        Route::delete('position/destroy', 'PositionController@destroy')->name('admin.position.destroy')->middleware('permission:config.position.destroy');
    });
    //广告信息
    Route::group(['middleware' => 'permission:config.advert'], function () {
        Route::get('advert/data', 'AdvertController@data')->name('admin.advert.data');
        Route::get('advert', 'AdvertController@index')->name('admin.advert');
        //添加
        Route::get('advert/create', 'AdvertController@create')->name('admin.advert.create')->middleware('permission:config.advert.create');
        Route::post('advert/store', 'AdvertController@store')->name('admin.advert.store')->middleware('permission:config.advert.create');
        //编辑
        Route::get('advert/{id}/edit', 'AdvertController@edit')->name('admin.advert.edit')->middleware('permission:config.advert.edit');
        Route::put('advert/{id}/update', 'AdvertController@update')->name('admin.advert.update')->middleware('permission:config.advert.edit');
        //删除
        Route::delete('advert/destroy', 'AdvertController@destroy')->name('admin.advert.destroy')->middleware('permission:config.advert.destroy');
    });
});
//会员管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:member.manage']], function () {
    //账号管理
    Route::group(['middleware' => 'permission:member.member'], function () {
        Route::get('member/data', 'MemberController@data')->name('admin.member.data');
        Route::get('member', 'MemberController@index')->name('admin.member');
        //添加
        Route::get('member/create', 'MemberController@create')->name('admin.member.create')->middleware('permission:member.member.create');
        Route::post('member/store', 'MemberController@store')->name('admin.member.store')->middleware('permission:member.member.create');
        //编辑
        Route::get('member/{id}/edit', 'MemberController@edit')->name('admin.member.edit')->middleware('permission:member.member.edit');
        Route::put('member/{id}/update', 'MemberController@update')->name('admin.member.update')->middleware('permission:member.member.edit');
        //删除
        Route::delete('member/destroy', 'MemberController@destroy')->name('admin.member.destroy')->middleware('permission:member.member.destroy');
    });
});
//消息管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:message.manage']], function () {
    //消息管理
    Route::group(['middleware' => 'permission:message.message'], function () {
        Route::get('message/data', 'MessageController@data')->name('admin.message.data');
        Route::get('message/getUser', 'MessageController@getUser')->name('admin.message.getUser');
        Route::get('message', 'MessageController@index')->name('admin.message');
        //添加
        Route::get('message/create', 'MessageController@create')->name('admin.message.create')->middleware('permission:message.message.create');
        Route::post('message/store', 'MessageController@store')->name('admin.message.store')->middleware('permission:message.message.create');
        //删除
        Route::delete('message/destroy', 'MessageController@destroy')->name('admin.message.destroy')->middleware('permission:message.message.destroy');
        //我的消息
        Route::get('mine/message', 'MessageController@mine')->name('admin.message.mine')->middleware('permission:message.message.mine');
        Route::post('message/{id}/read', 'MessageController@read')->name('admin.message.read')->middleware('permission:message.message.mine');

        Route::get('message/count', 'MessageController@getMessageCount')->name('admin.message.get_count');
    });

});
