<?php

/**
 * روت های مربوط به Auth رو نگهداری میکنه
 **/
Route::group([], function ($router) {
    $router->group(['namespace' => '\Laravel\Passport\Http\Controllers'], function ($router) {
        $router->post('login', [
            'as' => 'auth.login',
            'middleware' => ['throttle'],
            'uses' => 'AccessTokenController@issueToken',
        ]);
    });

    $router->post('register', [
        'as' => 'auth.register',
        'uses' => 'AuthController@register',
    ]);

    $router->post('register-verify', [
        'as' => 'auth.register.verify',
        'uses' => 'AuthController@registerVerify',
    ]);

    $router->post('resend-verification-code', [
        'as' => 'auth.register.resend.verification.code',
        'uses' => 'AuthController@resendVerificationCode',
    ]);
});

/**
 * روت های مربوط به User
 */
Route::group(['middleware' => ['auth:api']], function ($router) {
    $router->post('change-email', [
        'as' => 'change.email',
        'uses' => 'UserController@changeEmail'
    ]);

    $router->post('change-email-submit', [
        'as' => 'change.email.submit',
        'uses' => 'UserController@changeEmailSubmit'
    ]);

    $router->match(['post', 'put'], 'change-password', [
        'as' => 'password.change',
        'uses' => 'UserController@changePassword'
    ]);

    Route::group(['prefix' => 'user'], function ($router) {
        $router->match(['post', 'get'], '/{channel}/follow', [
            'as' => 'user.follow',
            'uses' => 'Usercontroller@follow',
        ]);

        $router->match(['post', 'get'], '/{channel}/unfollow', [
            'as' => 'user.unfollow',
            'uses' => 'Usercontroller@unfollow',
        ]);

        $router->get('/followings', [
            'as' => 'user.followings',
            'uses' => 'UserController@followings'
        ]);

        $router->get('/followers', [
            'as' => 'user.followers',
            'uses' => 'UserController@followers'
        ]);

        $router->delete('/me', [
            'as' => 'user.unregister',
            'uses' => 'UserController@unregister'
        ]);
    });
});

/**
 * روت های کانال
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/channel'], function ($router) {
    $router->put('/{id?}', [
        'as' => 'channel.update',
        'uses' => 'ChannelController@update',
    ]);

    $router->match(['post', 'put'], '/', [
        'as' => 'channel.upload.banner',
        'uses' => 'ChannelController@uploadBanner',
    ]);

    $router->match(['post', 'put'], '/socials', [
        'as' => 'chanel.update.socials',
        'uses' => 'Channelcontroller@updateSocials',
    ]);

    $router->get('/statistics', [
        'as' => 'chanel.statistics',
        'uses' => 'Channelcontroller@statistics',
    ]);
});

/**
 * روت های ویدیو ها
 */
Route::group(['middleware' => [], 'prefix' => '/video'], function ($router) {
    $router->match(['get', 'post'], '/{video}/like', [
        'as' => 'video.like',
        'uses' => 'VideoController@like'
    ]);

    $router->match(['get', 'post'], '/{video}/unlike', [
        'as' => 'video.unlike',
        'uses' => 'VideoController@unlike'
    ]);

    $router->get('/', [
        'as' => 'video.list',
        'uses' => 'VideoController@list'
    ]);

    // روت هایی که کاربران وارد شده دسترسی دارند
    Route::group(['middleware' => ['auth:api']], function ($router) {
        $router->post('/upload', [
            'as' => 'video.upload',
            'uses' => 'VideoController@upload'
        ]);

        $router->post('/upload-banner', [
            'as' => 'video.upload.banner',
            'uses' => 'VideoController@uploadBanner'
        ]);

        $router->post('/', [
            'as' => 'video.create',
            'uses' => 'VideoController@create'
        ]);

        $router->post('/{video}/republish', [
            'as' => 'video.republish',
            'uses' => 'VideoController@republish'
        ]);

        $router->put('/{video}/state', [
            'as' => 'video.change.state',
            'uses' => 'VideoController@changeState'
        ]);

        $router->put('/{video}', [
            'as' => 'video.update',
            'uses' => 'VideoController@update'
        ]);

        $router->get('/liked', [
            'as' => 'video.liked',
            'uses' => 'VideoController@likedByCurrentUser'
        ]);

        $router->get('/{video}/statistics', [
            'as' => 'video.statistics',
            'uses' => 'VideoController@statistics'
        ]);

        $router->get('/favourites', [
            'as' => 'video.favourites',
            'uses' => 'VideoController@favourites'
        ]);

        $router->delete('/{video}', [
            'as' => 'video.delete',
            'uses' => 'VideoController@delete'
        ]);
    });

    $router->get('/{video}', [
        'as' => 'video.show',
        'uses' => 'VideoController@show'
    ]);
});

/**
 * روت های دسته بندی ها
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/category'], function ($router) {
    $router->get('/', [
        'as' => 'category.all',
        'uses' => 'CategoryController@index'
    ]);

    $router->get('/my', [
        'as' => 'category.my',
        'uses' => 'CategoryController@my'
    ]);

    $router->post('/upload-banner', [
        'as' => 'category.upload.banner',
        'uses' => 'CategoryController@uploadBanner'
    ]);

    $router->post('/', [
        'as' => 'category.create',
        'uses' => 'CategoryController@create'
    ]);
});

/**
 * روت های لیست های پخش
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/playlist'], function ($router) {
    $router->get('/', [
        'as' => 'playlist.all',
        'uses' => 'PlaylistController@index'
    ]);

    $router->get('/my', [
        'as' => 'playlist.my',
        'uses' => 'PlaylistController@my'
    ]);

    $router->get('/{playlist}', [
        'as' => 'playlist.show',
        'uses' => 'PlaylistController@show'
    ]);

    $router->post('/', [
        'as' => 'playlist.create',
        'uses' => 'PlaylistController@create'
    ]);

    $router->match(['post', 'put'], '/{playlist}/sort', [
        'as' => 'playlist.sort',
        'uses' => 'PlaylistController@sortVideos'
    ]);

    $router->match(['post', 'put'], '/{playlist}/{video}', [
        'as' => 'playlist.add-video',
        'uses' => 'PlaylistController@addVideo'
    ]);
});

/**
 * روت های تگ ها
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/tag'], function ($router) {
    $router->get('/', [
        'as' => 'tag.all',
        'uses' => 'TagController@index'
    ]);

    $router->post('/', [
        'as' => 'tag.create',
        'uses' => 'TagController@create'
    ]);
});

/**
 * روت های دیدگاه ها
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/comment'], function ($router) {
    $router->get('/', [
        'as' => 'comment.all',
        'uses' => 'CommentController@index'
    ]);

    $router->post('/', [
        'as' => 'comment.create',
        'uses' => 'CommentController@create'
    ]);

    $router->match(['post', 'put'], '/{comment}/state', [
        'as' => 'comment.change.state',
        'uses' => 'CommentController@changeState'
    ]);

    $router->delete('/{comment}', [
        'as' => 'comment.delete',
        'uses' => 'CommentController@delete'
    ]);
});
