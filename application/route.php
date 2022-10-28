<?php

use think\Route;

/**
 * API路由
 */
//Route::alias('fake_test','/api/v1/fake/test')->group('fake',function () {
//    Route::post('test','/api/v1/fake/test');
//});
Route::group('fake',function () {
    Route::post('test','/api/v1/fake/test');
});


