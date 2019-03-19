<?php
use Illuminate\Support\Facades\Redirect;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['domain'=>'{domain}.scubaya.'.env('DOMAIN_EXTENSION'),'as'=>'scubaya::'], function ($domain) {
    if($domain == config('scubaya.admin'))      return Redirect::to(route("scubaya::admin::login"));
    if($domain == config('scubaya.merchants'))  return Redirect::to(route("scubaya::merchant::login"));
    if($domain == config('scubaya.user'))       return Redirect::to(route("scubaya::user::login"));

    /* Admin Module */
    Route::group(['domain'=>env('ADMIN_URL'),'as'=>'admin::','namespace'=>'Admin','middleware'=>'admin'],function(){
        Route::get('/.well-known/acme-challenge/aCAR3oc-nT4xt3DRNT95mBAzYsVDw3LjGFMMPS6ruRo',function(){
            return '';
        });
        Route::any('/',                         ['as'=>'login',                 'uses'=>'LoginController@login']);
        Route::any('/dashboard',                ['as'=>'dashboard',             'uses'=>'AdminController@dashboard']);

        //merchants route
        Route::any('/merchants',                ['as'=>'merchants_accounts',    'uses'=>'AdminController@merchantsAccounts']);
        Route::delete('/delete_merchant/{id}',  ['as'=>'delete_merchant',       'uses'=>'AdminController@deleteMerchant']);
        //Route::post('/edit_merchant',           ['as'=>'edit_merchant',         'uses'=>'AdminController@editMerchant']);

        //merchants details
        Route::group(['as'=>'merchants::'],function (){
            // Route::post('/add_merchant',        ['as'    =>  'add_merchant',    'uses'  =>  'MerchantController@createMerchant']);

            Route::get('/merchant/{id}/account/{detail_id}', ['as'=>'merchant', 'uses'=>'MerchantController@merchant']);

            /*save merchant pricing settings*/
            Route::post('/merchant_pricing_settings/{id}',   ['as'   =>  'merchant_pricing_settings',    'uses'  =>  'MerchantController@merchantPricingSettings']);

            /* update merchant details */
            Route::match(['get','post'],'/merchant/{id}/account_details/{detail_id}',['as'  =>  'account_details',   'uses'  =>  'MerchantController@updateMerchantDetails']);

            /*ajax request for main account status*/
            /*Route::post('/main_account_status',              ['as'   =>  'main_account_status','uses' =>  'MerchantController@mainAccountStatus']);*/
            Route::post('/update/main_account_status/{id}',  ['as'   =>  'update_main_account_status','uses' =>  'MerchantController@updateMainAccountStatus']);

            /*ajax request to change website account status*/
            Route::post('/website_account_status',           ['as'   =>  'website_account_status','uses' =>  'MerchantController@websiteAccountStatus']);
        });

        //add merchant route
        Route::match(['get','post'],'/add_merchant',['as'    =>  'add_merchant', 'uses'  =>  'AdminController@addMerchant']);

        //merchant policies routes
        Route::get('/merchant_policies',                ['as'       =>'merchants_policies',     'uses'=>'AdminController@merchantPolicies']);
        Route::post('/create_merchant_policies',        ['as'       =>'create_merchant_policy', 'uses'=>'AdminController@createMerchantPolicy']);
        Route::post('/edit_merchant_policies',          ['as'       =>'edit_merchant_policy',   'uses'=>'AdminController@editMerchantPolicy']);
        Route::post('/delete_merchant_policies/{id}',   ['as'  =>'delete_merchant_policy', 'uses'=>'AdminController@deleteMerchantPolicy']);

        //admin routes
        Route::post('/add_admin',                      ['as'=>'add_admin',             'uses'=>'AdminController@addAdmin']);
        Route::get('/manage_admins',                   ['as'=>'manage_admins',         'uses'=>'AdminController@manageAdmins']);
        Route::post('/block_admin',                    ['as'=>'block_admin',           'uses'=>'AdminController@blockAdmin']);
        Route::post('/delete_admin/{id}',              ['as'=>'delete_admin',          'uses'=>'AdminController@deleteAdmin']);

        //user group routes
        Route::get('/user_groups',              ['as'=>'user_groups',           'uses'=>'AdminController@userGroups']);
        Route::post('/create_group',            ['as'=>'create_group',          'uses'=>'AdminController@createGroup']);
        Route::post('/delete_group/{id}',       ['as'=>'delete_group',          'uses'=>'AdminController@deleteGroup']);
        Route::post('/edit_group',              ['as'=>'edit_group',            'uses'=>'AdminController@editGroup']);

        //menus routes
        Route::get('/menus',                    ['as'=>'menus',                 'uses'=>'AdminController@menus']);
        Route::post('/create_menu',             ['as'=>'create_menu',           'uses'=>'AdminController@createMenu']);
        Route::post('/delete_menu/{id}',        ['as'=>'delete_menu',           'uses'=>'AdminController@deleteMenu']);
        Route::post('/edit_menu',               ['as'=>'edit_menu',             'uses'=>'AdminController@editMenu']);


        //currency routes and settings
        Route::get('/currencies',                           ['as'=>'currencies',            'uses'=>'AdminController@currencies']);
        Route::post('/create_currency',                     ['as'=>'create_currency',       'uses'=>'AdminController@createCurrency']);
        Route::post('/delete_currency/{id}',                ['as'=>'delete_currency',       'uses'=>'AdminController@deleteCurrency']);
        Route::match(['get','post'],'/currency_settings',   ['as'=>'currency_settings',     'uses'=>'SettingsController@currency']);

        // admin profile settings routes
        Route::match(['get','post'],'/profile',     ['as'=>'admin_profile',            'uses'=>'ProfileController@adminProfile']);

        //Manage routes
        Route::group(['as'=>'manage::','prefix'=>'manage'],function(){
            Route::group(['prefix'=>'destinations'],function(){
                Route::get('/'   ,                                   ['as'=>'destinations'   ,'uses'=>'DestinationController@index']);
                Route::match(['get','post'],'/add_destination'   ,   ['as'=>'add_destination','uses'=>'DestinationController@addDestination']);
                Route::match(['get','post'],'/edit_destination/{id}',['as'=>'edit_destination','uses'=>'DestinationController@editDestination']);
                Route::delete('/delete_destination/{id}',            ['as'=>'delete_destination','uses'=>'DestinationController@deleteDestination']);
            });

            Route::group(['prefix'=>'affiliates'],function(){
                Route::get('/',                                     ['as'=>'affiliates',                'uses'=>'AffiliateController@index']);
                Route::match(['get','post'],'/add_affiliate',       ['as'=>'add_affiliate',             'uses'=>'AffiliateController@addAffiliate']);
                Route::post('/update_affiliate_status',             ['as'=>'update_affiliate_status',    'uses' =>'AffiliateController@updateAffiliateStatus' ]);
            });

            Route::group(['prefix'=>'marinelife'],function(){
                Route::get('/',                                       ['as'=> 'marine_life',      'uses'  =>  'MarineLifeController@index']);
                Route::match(['get','post'],'/add_marinelife',        ['as'=> 'add_marine_life',  'uses'  =>  'MarineLifeController@addMarineLife']);
                Route::match(['post','get'],'/edit_marinelife/{id}',  ['as'=> 'edit_marine_life', 'uses'  =>  'MarineLifeController@editMarineLife']);
            });

            Route::group(['prefix'=>'activities', 'as' => 'activities::'],function(){
                Route::get('/',           ['as'=> 'index',   'uses'  =>  'ActivityController@index']);
                Route::any('/add',        ['as'=> 'create',  'uses'  =>  'ActivityController@create']);
                Route::any('/edit/{id}',  ['as'=> 'update',  'uses'  =>  'ActivityController@update']);
                Route::any('/delete/{id}',['as'=> 'delete',  'uses'  =>  'ActivityController@delete']);
                Route::post('/is_non_diving',      ['as'=> 'non_diving',  'uses'  =>  'ActivityController@isNonDiving']);
            });

            Route::group(['prefix'=>'infrastructure', 'as' => 'infrastructure::'],function(){
                Route::get('/',           ['as'=> 'index',   'uses'  =>  'InfrastructureController@index']);
                Route::any('/add',        ['as'=> 'create',  'uses'  =>  'InfrastructureController@create']);
                Route::any('/edit/{id}',  ['as'=> 'update',  'uses'  =>  'InfrastructureController@update']);
                Route::any('/delete/{id}',['as'=> 'delete',  'uses'  =>  'InfrastructureController@delete']);
            });

            Route::group(['prefix'=>'specialities', 'as' => 'speciality::'],function(){
                Route::get('/',           ['as'=> 'index',   'uses'  =>  'SpecialityController@index']);
                Route::any('/add',        ['as'=> 'create',  'uses'  =>  'SpecialityController@create']);
                Route::any('/edit/{id}',  ['as'=> 'update',  'uses'  =>  'SpecialityController@update']);
                Route::any('/delete/{id}',['as'=> 'delete',  'uses'  =>  'SpecialityController@delete']);
            });

            Route::group(['prefix'=>'dive_center_facility', 'as' => 'center_facility::'],function(){
                Route::get('/',           ['as'=> 'index',   'uses'  =>  'DiveCenterFacilityController@index']);
                Route::any('/add',        ['as'=> 'create',  'uses'  =>  'DiveCenterFacilityController@create']);
                Route::any('/edit/{id}',  ['as'=> 'update',  'uses'  =>  'DiveCenterFacilityController@update']);
                Route::any('/delete/{id}',['as'=> 'delete',  'uses'  =>  'DiveCenterFacilityController@delete']);
            });

            Route::group(['prefix'=>'gear', 'as' => 'gear::'],function(){
                Route::get('/',           ['as'=> 'index',   'uses'  =>  'GearController@index']);
                Route::any('/add',        ['as'=> 'create',  'uses'  =>  'GearController@create']);
                Route::any('/edit/{id}', ['as'=> 'update',  'uses'  =>  'GearController@update']);
                Route::any('/delete/{id}',['as'=> 'delete',  'uses'  =>  'GearController@delete']);
            });

            Route::group(['prefix'=>'payment_method', 'as' => 'payment_method::'],function(){
                Route::get('/',           ['as'=> 'index',   'uses'  =>  'PaymentMethodController@index']);
                Route::any('/add',        ['as'=> 'create',  'uses'  =>  'PaymentMethodController@create']);
                Route::any('/edit/{id}',  ['as'=> 'update',  'uses'  =>  'PaymentMethodController@update']);
                Route::any('/delete/{id}',['as'=> 'delete',  'uses'  =>  'PaymentMethodController@delete']);
            });

            Route::group(['prefix'=>'dive_site', 'as' => 'dive_sites::'],function(){
                Route::get('/',           ['as'=> 'index',   'uses'  =>  'DiveSiteController@index']);
                Route::any('/add',        ['as'=> 'create',  'uses'  =>  'DiveSiteController@create']);
                Route::any('/edit/{id}',  ['as'=> 'update',  'uses'  =>  'DiveSiteController@update']);
                Route::any('/delete/{id}',['as'=> 'delete',  'uses'  =>  'DiveSiteController@delete']);
                Route::post('/active',    ['as'=> 'active',  'uses'  =>  'DiveSiteController@isActive']);
                Route::post('/need_a_boat',    ['as'=> 'needABoat',  'uses'  =>  'DiveSiteController@needABOat']);
            });

            Route::group(['prefix'=>'users'],function(){
                Route::get('/',                                             ['as'=>'users',         'uses'=>'UserController@index']);
                Route::match(['get','post'],'/add_user',                    ['as'=>'add_user',      'uses'=>'UserController@addUser']);
                Route::match(['get','edit','update'],'/edit_user/{id}',     ['as'=>'edit_user',     'uses'=>'UserController@editUser']);
                Route::match(['delete'],'/delete_user/{id}',                ['as'=>'delete_user',   'uses'=>'UserController@deleteUser']);
            });

            Route::group(['prefix'  =>  'dynamic_pages'],function(){
                Route::get('/',                                 ['as'   =>  'dynamic_pages',    'uses'  =>  'DynamicPagesController@index']);
                Route::match(['get','post'],'/add_page',        ['as'   =>  'add_page',         'uses'  =>  'DynamicPagesController@addPage']);
                Route::match(['get','post'],'/edit_page/{id}',  ['as'   =>  'edit_page',        'uses'  =>  'DynamicPagesController@editPage']);
                Route::match(['delete'],'/delete_page/{id}',    ['as'   =>  'delete_page',      'uses'  =>  'DynamicPagesController@deletePage']);
            });

            Route::group(['prefix'  =>  'email_templates'],function (){
                Route::get('/merchant_email_template'                    ,['as'  =>  'merchant_email_template','uses'  => 'EmailTemplateController@merchantindex']);
                Route::get('/admin_email_template'                       ,['as'  =>  'admin_email_template',   'uses'  => 'EmailTemplateController@adminindex']);
                Route::get('/user_email_template'                        ,['as'  =>  'user_email_template',    'uses'  => 'EmailTemplateController@userindex']);
                Route::match(['get','post'],'/add_email_template/{code}' ,['as'  =>  'add_email_template',     'uses'  => 'EmailTemplateController@addEmailTemplate']);
                Route::match(['get','post'],'/edit_email_template/{id}'  ,['as'  =>  'edit_email_template',    'uses'  => 'EmailTemplateController@editEmailTemplate']);
                Route::match(['delete'],'/delete_email_template/{id}'    ,['as'  =>  'delete_email_template',  'uses'  => 'EmailTemplateController@deleteEmailTemplate']);
            });

            Route::match(['get','post'],'/home_page' ,['as'  =>  'home_page',  'uses'  =>  'DynamicHomepageController@index']);

            Route::group(['prefix'  =>  'boat_types'],function (){
                Route::get('/'                                                  ,['as'  =>  'boat_types',       'uses'  =>  'BoatController@index']);
                Route::match(['add_boat_type','post'],'/add_boat_type',['as'  =>  'add_boat_type',   'uses'  =>  'BoatController@addBoatType']);
                Route::match(['get','post'],'/add_boat_type',['as'  =>  'add_boat_type',   'uses'  =>  'BoatController@addBoatType']);
                Route::match(['get','post'],'/edit_boat_type/{id}'         ,['as'  =>  'edit_boat_type',  'uses'  =>  'BoatController@editBoatType']);
                Route::match(['delete'],'/delete_boat_type/{id}'           ,['as'  =>  'delete_boat_type','uses'  =>  'BoatController@deleteBoatType']);
                Route::match(['update_boat_active_status','get'],'/update_boat_active_status'   ,['as'  => 'update_boat_active_status','uses'=>'BoatController@updateBoatActiveStatus']);
            });

            /* Popular destinations */
            Route::group(['prefix' => 'popular_destinations'], function(){
                Route::get('/' ,['as'  =>  'popular_destinations',       'uses'  =>  'DestinationController@getAllDestinations']);
                Route::post('{destination_id}', ['as' => 'is_destination_popular', 'uses' => 'DestinationController@isDestinationPopular']);
            });

            /* Popular Hotels */
            Route::group(['prefix' => 'popular_hotels'], function(){
                Route::get('/' ,['as'  =>  'popular_hotels',       'uses'  =>  'PopularHotelsController@index']);
                Route::post('{hotel_id}', ['as' => 'is_hotel_popular', 'uses' => 'PopularHotelsController@isHotelPopular']);
            });

            /* Popular Dive Centers */
            Route::group(['prefix' => 'popular_dive_centers'], function(){
                Route::get('/' ,['as'  =>  'popular_dive_centers',       'uses'  =>  'PopularDiveCentersController@index']);
                Route::post('{center_id}', ['as' => 'is_center_popular', 'uses' => 'PopularDiveCentersController@isCenterPopular']);
            });
        });

        //global settings controllers
        Route::group(['as'   => 'global_settings::'],function(){
            Route::group(['as' => 'merchants::'],function(){
                Route::any('/hotel_accommodation',['as'=>'hotel_accommodation','uses'=>'SettingsController@hotelAccommodation']);
            });
        });

        Route::match(['get','post'],'/create_instructor',        ['as'=>'create_instructor',  'uses'=>'AdminController@createInstructor']);
        Route::post('/delete_instructor/{id}',                   ['as'=>'delete_instructor',  'uses'=>'AdminController@deleteInstructor']);
        Route::get('/instructor/index',                          ['as'=>'instructors',        'uses'=>'AdminController@instructors']);

        // route for logout from system
        Route::get('/logout',                   ['as'=>'logout', 'uses'=>'LoginController@logout']);
    });

    /* User Module */
    Route::group(['domain'=>env('USER_URL'),'as'=>'user::','namespace'=>'User','middleware' =>  'cors'],function(){
        Route::any('/',                                     ['as'   =>  'login',            'uses'  =>  'LoginController@login']);
        Route::any('/dashboard',                            ['as'   =>  'dashboard',        'uses'  =>  'UserController@dashboard']);
        Route::post('/read_notification', ['as' => 'read_notification', 'uses' => 'NotificationController@readnotification']);
        Route::post('/verify_the_dive',['as' => 'verify_the_dive','uses'=>'NotificationController@verifyTheDive']);
        Route::get('/logout',                               ['as'   =>  'logout',           'uses'  =>  'LoginController@logout']);
        Route::any('/verify_user/{id}/{confirmation_code}', ['as'   =>  'verify_user',      'uses'  =>  'VerificationController@verification']);
        Route::get('/get_dive_site_info', ['as'   =>  'DiveSiteData',      'uses'  =>  'DiveLogsController@getDiveSiteData']);
        Route::get('/dive_site_filter',   ['as'   =>  'searchDiveSite',      'uses'  =>  'UserController@getDiveSiteData']);

        // Password Reset Routes
        Route::get('password/reset',    ['as' => 'password_reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
        Route::post('password/email',   ['as' => 'password_email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
        Route::get('password/reset/{token}', ['as' => 'password_reset_token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
        Route::post('password/reset',   ['as' => 'password_reset', 'uses' => 'Auth\ResetPasswordController@reset']);

        Route::group(['as'  =>  'settings::','prefix'   =>  '{user_id}'],function (){
            Route::match(['get','post'],'/preferences',          ['as'   =>  'preferences',          'uses'  =>  'SettingsController@preferences']);
            Route::match(['get','post'],'/privacy_settings',     ['as'   =>  'privacy_settings',     'uses'  =>  'SettingsController@privacySettings']);
            Route::match(['get','post'],'/personal_information', ['as'   =>  'personal_information', 'uses'  =>  'SettingsController@personalInformation']);
            Route::match(['get','post'],'/account_settings',     ['as'   =>  'account_settings',     'uses'  =>  'SettingsController@accountSettings']);
        });

        Route::group(['as'  =>  'dive_logs::','prefix'  =>  '{user_id}'],function (){
            Route::match(['get','post'],'/dive-logs',       ['as'   =>  'index' ,'uses'     =>  'DiveLogsController@index']);
            Route::match(['get','post'],'/dive-log/create', ['as'   =>  'create','uses'     =>  'DiveLogsController@create']) ;
            Route::match(['get','post'],'/dive-log/edit/{log_id}',   ['as'   =>  'update','uses'     =>  'DiveLogsController@update']) ;
            Route::match(['post'],'/dive-log/delete/{log_id}',  ['as'   =>  'delete','uses'     =>  'DiveLogsController@delete']) ;
            Route::get('/calculate_surface_interval',           ['as'   =>  'surface_interval','uses'     =>  'DiveLogsController@calculatewhiletriggering_ajax']);
            Route::post('/add_dive_site',           ['as'   =>  'add_dive_site','uses'     =>  'DiveLogsController@addDiveSite']);
        });

        Route::group(['as'  =>  'bookings::','prefix'   =>  '{user_id}'],function (){
            Route::get('/bookings',         ['as'   =>  'my_bookings',  'uses'  =>  'BookingsController@myBookings']);
            Route::post('edit_booking/{merchant_id}/{booking_id}',   ['as'   =>  'edit_booking',  'uses'  =>  'BookingsController@editBooking']);
            Route::match(['get','post'],'/invoices/{id}',    ['as'   =>  'invoices',     'uses'  =>  'BookingsController@invoice']);
        });
    });

    /* Instructor Module */
    Route::group(['domain'=>env('MERCHANT_URL'),'as'  =>  'instructor::','prefix' =>  'instructor','middleware' => 'instructor','namespace'=>'Instructor'],  function(){

        Route::get('{merchant_id}/dashboard',           ['as'   =>  'dashboard'     ,   'uses'      =>  'InstructorController@dashboard']);
        Route::get('{merchant_id}/profile',             ['as'   =>  'profile'       ,   'uses'      =>  'InstructorController@profile']);
        Route::post('{merchant_id}/update_profile',     ['as'   =>  'update_profile',   'uses'      =>  'InstructorController@updateProfile']);
    });

    /* Merchant Module */
    Route::group(['domain'=>env('MERCHANT_URL'),'as'=>'merchant::','middleware' => 'merchant','namespace' => 'Merchant'],function(){
        Route::get('/',                                             ['as'=>'index','uses'=>'LoginController@index']);
        Route::match(['get','post'],'/login',                       ['as'=>'login','uses'=>'LoginController@login']);
        Route::match(['login_merchant'],'/login',                   ['as'=>'login_merchant','uses'=>'LoginController@login']);
        Route::get('/logout',                                       ['as'=>'logout', 'uses' => 'DashboardController@logout']);

        // Password Reset Routes
        Route::get('password/reset',    ['as' => 'password_reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
        Route::post('password/email',   ['as' => 'password_email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
        Route::get('password/reset/{token}', ['as' => 'password_reset_token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
        Route::post('password/reset',   ['as' => 'password_reset', 'uses' => 'Auth\ResetPasswordController@reset']);

        Route::get('/{merchant_id}/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

        // rooms
        Route::get('/{merchant_id}/hotel/{hotel_id}/rooms',                 ['as' => 'all_rooms', 'uses' => 'RoomController@index']);
        Route::get('/{merchant_id}/hotel/{hotel_id}/room/create',           ['as' => 'create_room', 'uses' => 'RoomController@createRoom']);
        Route::post('/{merchant_id}/hotel/{hotel_id}/room/save',            ['as' => 'save_room', 'uses' => 'RoomController@saveRoom']);
        Route::get('/{merchant_id}/hotel/{hotel_id}/room/edit/{room_id}',   ['as' => 'edit_room', 'uses' => 'RoomController@editRoom']);
        Route::any('/{merchant_id}/hotel/{hotel_id}/room/update/{room_id}', ['as' => 'update_room', 'uses' => 'RoomController@updateRoom']);
        Route::post('/{merchant_id}/hotel/{hotel_id}/room/delete/{room_id}', ['as' => 'delete_room', 'uses' => 'RoomController@deleteRoom']);

        // room features
        Route::get('/{merchant_id}/room/features',                      ['as' => 'room_features', 'uses' => 'RoomFeaturesController@index']);
        Route::get('/{merchant_id}/room/features/create',               ['as' => 'create_room_feature', 'uses' => 'RoomFeaturesController@createRoomFeature']);
        Route::post('/{merchant_id}/room/features/save',                ['as' => 'save_room_feature', 'uses' => 'RoomFeaturesController@saveRoomFeature']);
        Route::get('/{merchant_id}/room/features/edit/{feature_id}',    ['as' => 'edit_room_feature', 'uses' => 'RoomFeaturesController@editRoomFeature']);
        Route::any('/{merchant_id}/room/features/update/{feature_id}',  ['as' => 'update_room_feature', 'uses' => 'RoomFeaturesController@updateRoomFeature']);
        Route::post('/{merchant_id}/room/features/delete/{feature_id}',  ['as' => 'delete_room_feature', 'uses' => 'RoomFeaturesController@deleteRoomFeature']);

        // room types
        Route::get('/{merchant_id}/room/types',                         ['as' => 'room_types', 'uses' => 'RoomTypeController@index']);
        Route::get('/{merchant_id}/room/types/create',                  ['as' => 'create_room_type', 'uses' => 'RoomTypeController@createRoomType']);
        Route::post('/{merchant_id}/room/types/save',                   ['as' => 'save_room_type', 'uses' => 'RoomTypeController@saveRoomType']);
        Route::get('/{merchant_id}/room/types/edit/{room_type_id}',     ['as' => 'edit_room_type', 'uses' => 'RoomTypeController@editRoomType']);
        Route::any('/{merchant_id}/room/types/update/{room_type_id}',   ['as' => 'update_room_type', 'uses' => 'RoomTypeController@updateRoomType']);
        Route::post('/{merchant_id}/room/types/delete/{room_type_id}',   ['as' => 'delete_room_type', 'uses' => 'RoomTypeController@deleteRoomType']);

        // room pricing
        Route::get('/{merchant_id}/room/tariffs',                                       ['as' => 'all_tariffs', 'uses' => 'RoomPricingController@index']);
        Route::get('/{merchant_id}/hotel/{hotel_id}/room/tariff/create',                ['as' => 'create_tariff', 'uses' => 'RoomPricingController@createTariff']);
        Route::post('/{merchant_id}/hotel/{hotel_id}/room/tariff/save',                 ['as' => 'save_tariff', 'uses' => 'RoomPricingController@saveTariff']);
        Route::get('/{merchant_id}/hotel/{hotel_id}/room/tariff/edit/{tariff_id}',      ['as' => 'edit_tariff', 'uses' => 'RoomPricingController@editTariff']);
        Route::any('/{merchant_id}/hotel/{hotel_id}/room/tariff/update/{tariff_id}',    ['as' => 'update_tariff', 'uses' => 'RoomPricingController@updateTariff']);
        Route::post('/{merchant_id}/hotel/{hotel_id}/room/tariff/delete/{tariff_id}',    ['as' => 'delete_tariff', 'uses' => 'RoomPricingController@deleteTariff']);

        // manage room bookings
        Route::get('/{merchant_id}/hotel/{hotel_id}/mark_bookings/', ['as' => 'mark_bookings', 'uses' => 'ManageBookingsController@index']);
        Route::any('/{merchant_id}/hotel/{hotel_id}/mark_bookings/{booking_id?}',   ['as' => 'save_mark_bookings', 'uses' => 'ManageBookingsController@save']);

        //instructor
        Route::get('/{merchant_id}/dive_center/{center_id}/instructor', ['as' =>  'instructor', 'uses' => 'InstructorController@index']);
        Route::get('/{merchant_id}/dive_center/{center_id}/instructor/create',                              ['as' =>  'create_instructor', 'uses'      =>     'InstructorController@createInstructor']);
        Route::post('/{merchant_id}/dive_center/{center_id}/instructor/save',                               ['as' =>  'save_instructor',   'uses'      =>     'InstructorController@saveInstructor']);
        Route::match(['get','post'],'/instructor/verify/{id}/{confirmation_code}',  ['as' =>  'verify_instructor', 'uses'      =>     'InstructorController@verifyInstructor']);
        Route::match(['get','post'],'/{merchant_id}/dive_center/{center_id}/instructor/edit/{id}',          ['as' =>  'edit_instructor',   'uses'      =>     'InstructorController@editInstructor']);
        Route::post('/{merchant_id}/dive_center/{center_id}/instructor/delete/{instructor_id}',              ['as' =>  'delete_instructor', 'uses'      =>     'InstructorController@deleteInstructor']);

        //hotel general information
        Route::get('/{merchant_id}/hotels',                     ['as' => 'hotels', 'uses' => 'HotelInformationController@index']);
        Route::get('/{merchant_id}/hotel/create',               ['as' => 'create_hotel', 'uses' => 'HotelInformationController@createHotel']);
        Route::post('/{merchant_id}/hotel/save',                ['as' => 'save_hotel', 'uses' => 'HotelInformationController@saveHotel']);
        Route::any('/{merchant_id}/hotel/edit/{hotel_id}',      ['as' => 'edit_hotel', 'uses' => 'HotelInformationController@editHotel']);
        /*Route::post('/{merchant_id}/hotel/delete/{hotel_id}/{detail_id}',    ['as' => 'delete_hotel', 'uses' => 'HotelInformationController@deleteHotel']);*/
        Route::post('/{merchant_id}/hotel/delete/{hotel_id}',    ['as' => 'delete_hotel', 'uses' => 'HotelInformationController@deleteHotel']);

        Route::group(['as'=>'hotel::', 'prefix'=>'/{merchant_id}/hotel'], function() {
            // hotel verification
            Route::any('verification/{website_id}', ['as' => 'verification', 'uses' => 'Hotel\HotelVerificationController@create']);
            Route::any('verification/edit/{detail_id}/{website_id}', ['as' => 'edit_verification', 'uses' => 'Hotel\HotelVerificationController@update']);
            Route::post('verification/delete/{detail_id}', ['as' => 'delete_verification', 'uses' => 'Hotel\HotelVerificationController@delete']);
        });

        Route::group(
            [
                'as'=>'settings::',
                'prefix'=>'/{merchant_id}/settings'
            ],
            function() {

                //Account details
                Route::match(['get','post'],'account_details',['as'  =>  'account_details',   'uses'  =>  'Settings\AccountDetailsController@saveMerchantDetails']);
                Route::match(['get','post'],'account_configuration',['as'  =>  'account_configuration','uses'  =>  'Settings\AccountConfigurationController@accountDetails']);

                // users
                Route::get('/users',                    ['as' => 'users',  'uses' => 'Settings\UserController@index']);
                Route::any('/users/add',                ['as' => 'create_user', 'uses' => 'Settings\UserController@save']);
                Route::any('/users/edit/{user_id}',     ['as' => 'edit_user', 'uses' => 'Settings\UserController@update']);
                Route::any('/users/delete/{user_id}',   ['as' => 'delete_user', 'uses' => 'Settings\UserController@delete']);
                Route::any('/users/add_user_by_id',     ['as' => 'create_user_by_id', 'uses' => 'Settings\UserController@saveUserById']);

                // Account verification
                Route::get('/verification/account', ['as' => 'account_verification', 'uses' => 'Settings\AccountVerificationController@accountVerification']);
                Route::post('/verification/account/save', ['as' => 'save_account_details', 'uses' => 'Settings\AccountVerificationController@save']);
                Route::any('/verification/account/{merchant_detail_id}/update',  ['as' => 'update_account_details', 'uses' => 'Settings\AccountVerificationController@update']);
                Route::post('/verification/{merchant_detail_id}/account/delete',  ['as' => 'delete_account_details', 'uses' => 'Settings\AccountVerificationController@delete']);

                // tax rates
                Route::get('/',               ['as'=>'tax_rates',             'uses'=>'Settings\TaxRateController@index']);
                Route::any('/create',         ['as'=>'create_tax_rate',       'uses'=>'Settings\TaxRateController@create']);
                Route::any('/edit/{tax_rate_id?}', ['as'=>'edit_tax_rate',    'uses'=>'Settings\TaxRateController@edit']);
                Route::any('/delete/{tax_rate_id}',['as'=>'delete_tax_rate',  'uses'=>'Settings\TaxRateController@delete']);
            });

        // route to send verification email when user is created by merchant
        Route::any('/user/verify/{confirmation_code}',   ['as' => 'verify_user', 'uses' => 'Settings\UserController@verifyUserAccount']);

        // pricing settings
        Route::get('/{merchant_id}/room/pricing_settings',      ['as' => 'pricing_settings', 'uses' => 'PricingSettingsController@index']);
        Route::post('/{merchant_id}/room/save_pricing_settings',['as' => 'save_pricing_settings', 'uses' => 'PricingSettingsController@savePricingSettings']);

        // shop
        Route::group(['as'=>'shop::', 'prefix'=>'/{merchant_id}/shop'], function(){

            // manage shop
            Route::get('/', ['as' => 'shops', 'uses' => 'Shop\ShopController@index']);
            Route::any('/create/{shop_id?}', ['as' => 'create_shop', 'uses' => 'Shop\ShopController@create']);
            Route::any('/edit/{shop_id?}', ['as' => 'edit_shop', 'uses' => 'Shop\ShopController@update']);
            /*Route::post('/delete/{shop_id}/{detail_id}', ['as' => 'delete_shop', 'uses' => 'Shop\ShopController@delete']);*/
            Route::post('/delete/{shop_id}', ['as' => 'delete_shop', 'uses' => 'Shop\ShopController@delete']);

            // shop verification
            Route::any('/verification/{website_id?}', ['as' => 'verification', 'uses' => 'Shop\ShopVerificationController@create']);
            Route::any('/verification/edit/{detail_id}/{website_id}', ['as' => 'edit_verification', 'uses' => 'Shop\ShopVerificationController@update']);
            Route::post('/verification/delete/{detail_id}', ['as' => 'delete_verification', 'uses' => 'Shop\ShopVerificationController@delete']);

            // courses
            Route::get('/{shop_id}/courses',                     ['as' => 'courses',  'uses' => 'Shop\CourseController@index']);
            Route::any('/{shop_id}/course/create',               ['as' => 'create_course', 'uses' => 'Shop\CourseController@save']);
            Route::any('/{shop_id}/course/edit/{course_id?}',    ['as' => 'edit_course',   'uses' => 'Shop\CourseController@update']);
            Route::post('/{shop_id}/course/delete/{course_id}',  ['as' => 'delete_course', 'uses' => 'Shop\CourseController@delete']);
            Route::post('/get_boat_instructor',  ['as' => 'boat_instructor', 'uses' => 'Shop\CourseController@getBoatsInstructors']);

            //products
            Route::get('/{shop_id?}/products', ['as' => 'products', 'uses' => 'Shop\ProductController@index']);
            Route::any('/{shop_id?}/product/create', ['as' => 'create_product', 'uses' => 'Shop\ProductController@save']);
            Route::any('/{shop_id?}/product/edit/{product_id?}', ['as' => 'edit_product', 'uses' => 'Shop\ProductController@update']);
            Route::post('/{shop_id?}/product/delete/{product_id}', ['as' => 'delete_product', 'uses' => 'Shop\ProductController@delete']);
            Route::get('/product/included_in_course',  ['as' => 'included_in_course', 'uses' => 'Shop\ProductController@isIncludedInCourse']);

            //product categories
            Route::get('/product_categories', ['as' => 'product_categories', 'uses' => 'Shop\ProductCategoriesController@index']);
            Route::post('/add_category', ['as' => 'add_category', 'uses' => 'Shop\ProductCategoriesController@addProductCategories']);
        });


        // dive center
        Route::group(['as'=>'dive_center::', 'prefix'=>'/{merchant_id}/dive_center'], function(){
            // boats
            Route::get('/{center_id}/boats',                    ['as' => 'boats', 'uses' => 'DiveCenter\BoatController@index']);
            Route::any('/{center_id}/boats/create',             ['as' => 'create_boat', 'uses' => 'DiveCenter\BoatController@create']);
            Route::any('/{center_id}/boats/edit/{boat_id?}',    ['as' => 'edit_boat', 'uses' => 'DiveCenter\BoatController@update']);
            Route::post('/{center_id}/boats/delete/{boat_id}',  ['as' => 'delete_boat', 'uses' => 'DiveCenter\BoatController@delete']);
            Route::get('/boats/update_boat_active_status',      ['as' => 'update_boat_active_status', 'uses' => 'DiveCenter\BoatController@updateBoatActiveStatus']);

            // Dive day planning
            Route::get('/{center_id?}/dive_day_planning',       ['as'   =>  'dive_day_planning',    'uses'  =>  'DiveCenter\DiveDayPlanningController@diveDayPlanning']);
            Route::post('/{center_id?}/save_dive_day_planning', ['as'   =>  'save_dive_day_planning',    'uses'  =>  'DiveCenter\DiveDayPlanningController@saveDiveDayPlanning']);

            //locations
            Route::get('/locations',                            ['as'   =>  'locations',               'uses'  =>  'DiveCenter\LocationController@locations']);
            Route::match(['get','post'],'/add_location',        ['as'   =>  'add_location',            'uses'  =>  'DiveCenter\LocationController@addLocation']);
            Route::match(['get','post'],'/edit_location/{id}',  ['as'   =>  'edit_location',           'uses'  =>  'DiveCenter\LocationController@editLocation']);
            Route::post('/update_location_status',              ['as'   =>  'update_location_status',  'uses'  =>  'DiveCenter\LocationController@updateLocationStatus']);

            //Manage Dive Center
            Route::get('/',           ['as'   =>  'dive_centers',   'uses'  =>  'DiveCenter\DiveCenterController@index']);
            Route::match(['get','post'],'/create',    ['as'   =>  'create_dive_center',   'uses'  =>  'DiveCenter\DiveCenterController@create']);
            Route::any('/edit/{dive_center_id?}', ['as' => 'edit_dive_center', 'uses' => 'DiveCenter\DiveCenterController@update']);
            /*Route::post('/delete/{dive_center_id}/{detail_id}', ['as' => 'delete_dive_center', 'uses' => 'DiveCenter\DiveCenterController@delete']);*/
            Route::post('/delete/{dive_center_id}', ['as' => 'delete_dive_center', 'uses' => 'DiveCenter\DiveCenterController@delete']);

            // dive center verification
            Route::any('/verification/{website_id?}', ['as' => 'verification', 'uses' => 'DiveCenter\DiveCenterVerificationController@create']);
            Route::any('/verification/edit/{detail_id}/{website_id}', ['as' => 'edit_verification', 'uses' => 'DiveCenter\DiveCenterVerificationController@update']);
            Route::post('/verification/delete/{detail_id}', ['as' => 'delete_verification', 'uses' => 'DiveCenter\DiveCenterVerificationController@delete']);
        });

        Route::group(['as'  =>  'bookings::',   'prefix'    =>  '{merchant_id}/bookings'],function (){
            Route::get('all_bookings',   ['as'   =>  'all_bookings',  'uses'  =>  'Bookings\BookingController@allBookings']);

            // for update edit booking request
            Route::get('course/update_booking/{booking_id}',   ['as'   =>  'confirm_course_booking',  'uses'  =>  'Bookings\BookingController@confirmCourseBooking']);
            Route::get('product/update_booking/{booking_id}',   ['as'   =>  'confirm_product_booking',  'uses'  =>  'Bookings\BookingController@confirmProductBooking']);
            Route::get('hotel/update_booking/{booking_id}/{total_price}',   ['as'   =>  'confirm_hotel_booking',  'uses'  =>  'Bookings\BookingController@confirmHotelBooking']);

            // for decline edit booking request
            Route::get('course/decline_booking/{booking_id}',   ['as'   =>  'decline_course_booking',  'uses'  =>  'Bookings\BookingController@DeclineCourseBooking']);
            Route::get('product/decline_booking/{booking_id}',   ['as'   =>  'decline_product_booking',  'uses'  =>  'Bookings\BookingController@DeclineProductBooking']);
            Route::get('hotel/decline_booking/{booking_id}',   ['as'   =>  'decline_hotel_booking',  'uses'  =>  'Bookings\BookingController@DeclineHotelBooking']);

            // for update booking
            Route::post('course/update_booking/{booking_id}',   ['as'   =>  'update_course_booking',  'uses'  =>  'Bookings\BookingController@updateCourseBooking']);
            Route::post('product/update_booking/{booking_id}',   ['as'   =>  'update_product_booking',  'uses'  =>  'Bookings\BookingController@updateProductBooking']);
            Route::post('hotel/room/update_booking/{booking_id}',   ['as'   =>  'update_hotel_room_booking',  'uses'  =>  'Bookings\BookingController@updateHotelRoomBooking']);
        });

        Route::post('course_booking_status',   ['as'   =>  'course_booking_status',  'uses'  =>  'Bookings\BookingController@updateCourseBookingStatus']);
        Route::post('product_booking_status',   ['as'   =>  'product_booking_status',  'uses'  =>  'Bookings\BookingController@updateProductBookingStatus']);
        Route::post('hotel_booking_status',   ['as'   =>  'hotel_booking_status',  'uses'  =>  'Bookings\BookingController@updateHotelBookingStatus']);

    });
});

$appRoutes  =   function(){

    Route::post('/login',    ['as'  =>'login',     'uses'   =>  'UserController@login']);
    /*    Route::get('/',          ['as'  =>'index',     'uses'   =>  'IndexController@index']);*/
    Route::any('/subscribe', ['as'  =>'subscribe', 'uses'   =>  'IndexController@subscribe']);

    Route::get('/',  ['as'=>'home','uses' =>  'IndexController@index']);

    /* TODO: they need to be managed with grouping */
    Route::get('/courses',                                                                      ['as'=>'courses','uses' =>  'CourseController@index']);
    Route::get('/dive-centers/{search?}',                                                       ['as'=>'diveCenters','uses' =>  'DiveCenterController@DiveCenters']);
    Route::get('/{center_id}/{center_name}/detail',                                             ['as'=>'dive_center_details','uses' =>  'DiveCenterController@diveCentersDetails']);
    Route::get('/{center_id}/{center_name}/courses/{course_name}/{course_id}',                  ['as'=>'course_details','uses' =>  'DiveCenterController@CourseDetails']);
    Route::match(['get','post'],'/dive-centers/courses/checkout/{course_id}',  ['as'=>'course_checkout','uses' =>  'DiveCenterController@coursesCheckout']);
    Route::post('/get_dive_site_info',    ['middleware' => 'cors','as'=> 'diveSiteById',  'uses'  =>  'DiveCenterController@getDiveSiteById']);
    Route::post('check_product_availability', ['as' => 'product_availability', 'uses' => 'DiveCenterController@checkProductAvailability']);

    // query of user to merchant
    Route::post('/user/query', ['as' => 'user_query', 'uses' => 'DiveCenterController@sendUserQuery']);

    Route::get('/terms-of-condition',                                                           ['as'=>'toc','uses' => 'IndexController@toc']);
    Route::get('/about-us',                                                                     ['as'=>'about_us','uses' => 'IndexController@aboutUs']);

    /*Route for ajax search in front end*/
    Route::get('/search_all',   ['as'   =>  'search_all',   'uses'  =>  'IndexController@searchAll']);


    /* TODO : need to manage */
    Route::group([
        'as'        =>  'hotel::',
    ], function(){
        Route::get('/hotels/{search?}',                ['as'=>'hotels',        'uses'=>'HotelController@allHotels']);
        Route::get('{hotel_id}/{hotel_name}/details',   ['as'=>'hotel_details', 'uses'=>'HotelController@showHotelDetails']);
    });

    /* dive resort and liveaboard*/
    Route::group([
        'as'        =>  'dive_resort::',
    ], function(){
        Route::get('/dive_resorts',['as'=>'dive_resorts', 'uses'=>'DiveResort\DiveResortController@DiveResorts']);
    });

    Route::group([
        'as'        =>  'liveaboard::',
    ], function(){
        Route::get('/liveaboards',['as'=>'liveaboards', 'uses'=>'Liveaboard\LiveaboardController@liveaboards']);
    });

    Route::group([
        'as'        =>  'destination::',
    ], function(){
        Route::get('/destinations/{search?}',['as'=>'destinations', 'uses'=>'Destinations\DestinationController@destinations']);
        Route::get('/destination/{destination_id}/{destination_name}/detail', ['as'=>'destination_details', 'uses'=>'Destinations\DestinationController@destinationDetails']);
        Route::get('/sub-destination/{subdestination_id}/{subdestination_name}', ['as'=>'sub_destination_details', 'uses'=>'Destinations\DestinationController@subDestinationDetails']);
    });

    Route::group(['as'  =>  'checkout::'],function (){

        Route::group(['as'  =>  'courses::'],function (){
            /*ajax for change the no of persons*/
            Route::post('/change_no_of_divers',             ['as'   =>  'change_no_of_divers',  'uses'  =>  'DiveCenterCheckoutController@changeNoOfDivers']);

            /*ajax for add to cart*/
            Route::post('/add_to_courses_cart',             ['as'   =>  'add_to_cart',  'uses'  =>  'DiveCenterCheckoutController@addTocart']);
            Route::post('/delete_course_item',  ['as'   =>  'delete_course_item',    'uses' =>  'DiveCenterCheckoutController@deleteCourseItem']);
        });

        Route::group(['as'  =>  'products::'],function (){
            Route::post('add_to_products_cart',     ['as'   =>  'add_to_products_cart', 'uses'  =>  'ProductCheckoutController@addToCart']);
            Route::post('delete_product_item', ['as'   =>  'delete_product_item',  'uses'  =>  'ProductCheckoutController@deleteProductItem']);
            Route::post('/change_no_of_products',           ['as'   =>  'change_no_of_products',  'uses'  =>  'ProductCheckoutController@changeProductQuantity']);
        });

        Route::group(['as'  =>  'hotel::'],function (){
            Route::post('add_to_cart',     ['as'   =>  'add_to_cart', 'uses'  =>  'HotelController@addToCart']);
            Route::post('delete_hotel_item', ['as'   =>  'delete_hotel_item',  'uses'  =>  'HotelController@deleteHotelItem']);
            Route::post('/change_no_of_persons', ['as'   =>  'change_no_of_persons',  'uses'  =>  'HotelController@updateNoOfPersonsForBooking']);
        });

        Route::group(['as'  =>  'cart::'],function (){
            Route::post('add_to_cart',     ['as'   =>  'add_to_cart', 'uses'  =>  'CartController@addToCart']);
        });

        Route::match(['get','post','order_review'],'/order_review',['as' =>  'order_review','uses' =>  'CartController@orderReview']);

        Route::match(['get','post'],'/cart',['as'   =>  'cart',  'uses'  =>  'CartController@cart']);

        Route::get('thank-you',    ['as'   =>  'thank_you',    'uses'  =>  'CartController@thankYou']);
    });

    // sign up
    Route::group([
        'as'        =>  'register::',
    ], function(){
        Route::post('/registration',    ['as'   =>  'registration',     'uses'=>'UserController@register']);

        //proposed routes for instructors
        Route::get('/register/instructor_account',                  ['as'   =>  'instructor_account',               'uses'      =>  'LoginController@showInstructorSignUpForm']);
        Route::post('/register/instructor_account',                 ['as'   =>  'create_instructor_account',        'uses'      =>  'LoginController@createInstructor']);
        //
        Route::get('/register',                                     ['as'   =>  'index',                            'uses'      =>  'LoginController@index']);
        Route::get('/create_merchant_account',                      ['as'   =>  'create_account',                   'uses'      =>  'LoginController@showMerchantSignUpForm']);
        Route::post('/create_merchant_account',                     ['as'   =>  'create_merchant',                  'uses'      =>  'LoginController@createMerchant']);
        Route::get('/create_merchant_account/success',              ['as'   =>  'success',                          'uses'      =>  'LoginController@showSuccess']);
        Route::get('/register/verify/{confirmation_code}',          ['as'   =>  'verify',                           'uses'      =>  'LoginController@verifyEmail']);
        Route::match(['get', 'post'], '/create_diver_account/1',    ['as'   =>  'create_diver_account_page1',       'uses'      =>  'DiverController@registerPage1']);
        Route::match(['get', 'post'], '/create_diver_account/2',    ['as'   =>  'create_diver_account_page2',       'uses'      =>  'DiverController@registerPage2']);

        //accommodation
        Route::group([
            'as'        =>   'accommodation::',
            'prefix'    =>   '/registration_form/accommodation'
        ],function(){
            Route::any('/', ['as' => 'index', 'uses' => 'AccommodationController@profileDetailsForm']);
        });

        // live board
        Route::group([
            'as'        =>   'liveboard::',
            'prefix'    =>   '/registration_form/liveboard'
        ],function(){
            Route::any('/', ['as' => 'index', 'uses' => 'LiveboardController@profileDetailsForm']);
        });

        // Diver
        Route::group([
            'as'    =>  'diver::'
        ],function(){
            //Registration of diver
            Route::match(['get', 'post'], '/register/diver/1', ['as'=>'register_page_1','uses'=>'DiverController@registerPage1']);
            Route::match(['get', 'post'], '/register/diver/2', ['as'=>'register_page_2','uses'=>'DiverController@registerPage2']);
        });
        Route::get('payment_now',function(){
            return view('payment/payment');
        });
    });

    Route::group(['as'  =>  'dynamic_content::'],function(){
        Route::get('scubaya/{slug}' ,   ['as'   =>  'dynamic_page', 'uses'  =>  'DynamicPageController@dynamicPage']);
    });
};

Route::group(['as' => 'scubaya::', 'domain' => env('DEV_URL'),'namespace'=>'Front'], $appRoutes);
Route::group(['as' => 'scubaya::', 'domain' => env('APP_URL'),'namespace'=>'Front'], $appRoutes);






