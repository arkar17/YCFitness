<?php

use App\Models\Feedback;
use App\Models\TrainingCenter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AccDeleteController;
use App\Http\Controllers\Admin\MealController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\SocialmediaController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\Admin\WorkoutController;
use App\Http\Controllers\Admin\BanWordsController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\MealPlanController;
use App\Http\Controllers\Auth\PassResetController;
use App\Http\Controllers\Admin\FreeVideosController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ShopMemberController;
use App\Http\Controllers\User\UserWorkoutController;
use App\Http\Controllers\Admin\BankinginfoController;
use App\Http\Controllers\Admin\ShopRequestController;
use App\Http\Controllers\Customer\ChattingController;
use App\Http\Controllers\Admin\ChatWithAdminController;
use App\Http\Controllers\Admin\TrainingGroupController;
use App\Http\Controllers\Admin\TrainingCenterController;
use App\Http\Controllers\Trainer\TrainerGroupController;
use App\Http\Controllers\Customer\CustomerLoginController;
use App\Http\Controllers\Customer\RegisterPaymentController;
use App\Http\Controllers\Customer\CustomerRegisterController;
use App\Http\Controllers\Admin\RequestAcceptDeclineController;
use App\Http\Controllers\Trainer\TrainerManagementConntroller;
use App\Http\Controllers\Customer\CustomerManagementController;
use App\Http\Controllers\Customer\Customer_TrainingCenterController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

Route::group(['middleware' => 'prevent-back-history'], function () {
    Route::get('/locale/change', [HomeController::class, 'lang'])->name('langChange');
    Route::get('/customerlogin', [CustomerLoginController::class, 'login'])->name('customerlogin');
    Route::get('customer/checkPhone', [CustomerRegisterController::class, 'checkPhone'])->name('checkPhone');
    Route::get('customer/checkemail', [CustomerRegisterController::class, 'checkemail'])->name('checkEmail');

    Route::get('customer/checkemail', [CustomerRegisterController::class, 'checkemail'])->name('checkPhone');

    //Route::get('/customer/signup', [App\Http\Controllers\HomeController::class, 'customersignup'])->name('home');

    Route::post('/data/save', [HomeController::class, 'store'])->name('data.save');
    Route::post('customer/customerCreate', [CustomerRegisterController::class, 'CustomerData'])->name('customerCreate');

    // NCK
    Route::get('/customer_payment_active_staus/{id}', [RegisterPaymentController::class, 'changeStatusAndType'])->name('customer_upgrade');
    
    

    Route::post('/member/upgraded-history/', [HomeController::class, 'memberUpgradedHistory'])->name('member-upgraded-history');
    Route::post('/member/upgraded-history-monthly/', [HomeController::class, 'memberUpgradedHistory_monthly'])->name('member-upgraded-history-monthly');

    // Route::get('');
    //NCK

    Route::get('/customer_payment', [RegisterPaymentController::class, 'payment'])->name('customer_payment');
    // Route::get('test_payment', [RegisterPaymentController::class, 'test'])->name('test_payment');
    Route::post('ewallet_store', [RegisterPaymentController::class, 'ewallet_store'])->name('ewallet_store');
    Route::post('bank_payment_store', [RegisterPaymentController::class, 'bank_payment_store'])->name('bank_payment_store');

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Auth::routes();
    Route::middleware(['auth','activity'])->group(function () {

        //Social Media
        // Route::get('/socialmedia_profile/{id}', [SocialmediaController::class, 'socialmedia_profile'])->name('socialmedia.profile');

        Route::get('/free-video', [ShopController::class, 'freevideo'])->name('free-videos');

        // Route::get('/downloadImage/{url}', [ShopController::class, 'downloadImage'])->name('download.image');
        Route::get('/download', [ShopController::class, 'download'])->name('download-image');


        Route::post('/feedback/store', [FeedbackController::class, 'feedback_send'])->name('feedback.store');
        Route::get('/account_delete', [AccDeleteController::class, 'acc_delete'])->name('acc_delete');
        Route::post('/account_del', [AccDeleteController::class, 'acc_del'])->name('acc_del');
        Route::get('/shop', [ShopController::class, 'index'])->name('shop');
        Route::post('/shop/rating',[ShopController::class, 'shop_rating'])->name('shop_rating');
        Route::post('/shop/list', [ShopController::class, 'shop_list'])->name('shop.list');
        Route::get('/shop/request', [ShopController::class, 'shoprequest'])->name('shoprequest');
        Route::get('/shop/payment', [ShopController::class, 'payment'])->name('shoppayment');
        Route::post('/shop/post/store', [ShopController::class, 'shoppost_store'])->name('shoppost.store');
        Route::get('/shop/{id}/post', [ShopController::class, 'shoppost'])->name('shoppost');
        Route::post('/shop/edit/{id}', [ShopController::class, 'shoppost_edit'])->name('shoppost.edit');
        Route::post('/shop/update', [ShopController::class, 'shoppost_update'])->name('shoppost.update');
        Route::post('/shop/delete/{id}', [ShopController::class, 'shoppost_destroy'])->name('shoppost.destroy');
        Route::get('/shop/post/save/', [ShopController::class, 'shoppost_save'])->name('shoppost.save');

        Route::get('/socialmedia', [SocialmediaController::class, 'index'])->name('socialmedia');
        Route::post('/socialmedia', [SocialmediaController::class, 'post_store'])->name('post.store');

        Route::get('/socialmedia/post/save/', [SocialmediaController::class, 'post_save'])->name('socialmedia.post.save');

        Route::post('/socialmedia/report', [SocialmediaController::class, 'post_report'])->name('socialmedia.report');

        Route::post('/socialmedia/delete/{id}', [SocialmediaController::class, 'post_destroy'])->name('post.destroy');
        Route::post('/socialmedia/edit/{id}', [SocialmediaController::class, 'post_edit'])->name('post.edit');
        Route::post('/socialmedia/update', [SocialmediaController::class, 'post_update'])->name('post.update');
        Route::post('/profile/photo/delete', [SocialmediaController::class, 'profile_photo_delete'])->name('profile.photo.delete');

        Route::get('/socialmedia/message/seeall', [SocialmediaController::class, 'see_all_message'])->name('message.seeall');
        Route::get('/socialmedia/message/chat/{id}', [SocialmediaController::class, 'chat_message'])->name('message.chat');
        Route::get('/socialmedia/message/chat_admin', [SocialmediaController::class, 'chat_message_admin'])->name('message.chat.admin');
        

        //new chat
        Route::post('message/chat/{user}',[ChattingController::class,'chatting']);
        Route::post('message/chat_admin', [
            ChattingController::class, 'chatting_admin'
        ]);
        Route::post('message/chat_admin/admin_side', [ChattingController::class, 'chatting_admin_side']);

        Route::post('message/read-unread', [ChattingController::class, 'read_unread'])->name('read.unread');

        Route::get('/socialmedia/message/deletechat', [SocialmediaController::class, 'delete_allchat_message'])->name('message.all.delete');
        Route::get('/socialmedia/message/viewmedia/{id}', [SocialmediaController::class, 'viewmedia_message'])->name('message.viewmedia');
        Route::post('/socialmedia/message/hide', [SocialmediaController::class, 'hide_message'])->name('message.hide');
        Route::post('/socialmedia/message/delete', [SocialmediaController::class, 'delete_message'])->name('message.delete');
        Route::post('/agora/call-user',  [VideoController::class, 'callUser'])->name('socialmedia.videocall');
        // ------
        Route::post('/agora/call-gp-user', [VideoController::class, 'callGpuser'])->name('socialmedia.gpcall');
        Route::post('/agora/call-gpaudio-user', [VideoController::class, 'callGpAudioUser'])->name('socialmedia.gpaudiocall');
        Route::post('/agora/decline-call-user', [VideoController::class, 'declineCallUser'])->name('decline.user');

        //-------
        Route::post('/agora/call-audio-user',  [VideoController::class, 'callAudioUser'])->name('socialmedia.videocall');

        Route::post('/agora/token',  [VideoController::class, 'token']);

        Route::post('/socialmedia/group/create', [SocialmediaController::class, 'group_create'])->name('socialmedia.group.create');
        Route::get('/socialmedia/group/{id}', [SocialmediaController::class, 'group'])->name('socialmedia.group');
        Route::post('/socialmedia/group/{id}/addmember', [SocialmediaController::class, 'addmember'])->name('socialmedia.group.addmember');
        Route::get('/socialmedia/group/detail/{id}', [SocialmediaController::class, 'group_detail'])->name('socialmedia.group.detail');
        Route::post('/socialmedia/group/member/kick', [SocialmediaController::class, 'group_member_kick'])->name('socialmedia.group.memberkick');
        Route::get('/socialmedia/group/viewmedia/{id}', [SocialmediaController::class, 'group_viewmedia'])->name('socialmedia.group.viewmedia');
        Route::get('/socialmedia/group/leave/{gp_id}/{id}', [SocialmediaController::class, 'group_leave'])->name('socialmedia.group.leave');
        Route::get('/socialmedia/group/delete/{id}', [SocialmediaController::class, 'group_delete'])->name('socialmedia.group.delete');

        Route::get('/socialmedia/latest_messages', [SocialmediaController::class, 'latest_messages'])->name('socialmedia.latest_messages');

        // Route::get('/testing', [SocialmediaController::class, 'index'])->name('testing');
        // Route::post('/testing/store', [SocialmediaController::class, 'post_store'])->name('testing.store');

        Route::get('customer/personal_infos', [CustomerRegisterController::class, 'personal_info'])->name('customer-personal_infos');

        Route::get('/profile', [Customer_TrainingCenterController::class, 'profile'])->name('customer-profile');
        Route::get('/profile/likes/{post_id}', [Customer_TrainingCenterController::class, 'profile_post_likes'])->name('profile.likes.view');

        Route::get('/likes/comment/{id}', [Customer_TrainingCenterController::class, 'comment_likes'])->name('likes.comment');

        Route::post('customer/profile/update', [Customer_TrainingCenterController::class, 'profile_update'])->name('customer-profile.update');
        Route::post('customer/profile/name/update', [Customer_TrainingCenterController::class, 'profile_update_name'])->name('customer-profile-name.update');

        Route::post('customer/profile/bio/update', [Customer_TrainingCenterController::class, 'profile_update_bio'])->name('customer-profile-bio.update');
        Route::post('customer/profile/cover/update', [Customer_TrainingCenterController::class, 'profile_update_cover'])->name('customer-profile-cover.update');

        Route::post('customer/profile/image/update', [Customer_TrainingCenterController::class, 'profile_update_profile_img'])->name('customer-profile-img.update');

        Route::get('customer/profile/saved_post', [Customer_TrainingCenterController::class, 'saved_post'])
        ->name('saved.post');
        Route::get('customer/profile/shop_saved_post', [Customer_TrainingCenterController::class, 'shop_saved_post'])
        ->name('shop.saved.post');

        Route::get('customer/profile/posts/', [Customer_TrainingCenterController::class, 'all_post'])->name('all.post');

        Route::get('customer/profile/shop_posts/', [Customer_TrainingCenterController::class, 'shop_all_post'])
        ->name('all.shop.post');

        Route::post('customer/profile/shop_posts/id', [Customer_TrainingCenterController::class, 'shop_all_post_id'])
        ->name('all.shop.post.id');

        Route::get('customer/profile/year/{year}', [Customer_TrainingCenterController::class, 'year_filter'])->name('customer-profile.year');
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('social_media');

        //Social Media

        Route::get('/socialmedia/profile/{id}', [SocialmediaController::class, 'profile'])->name('socialmedia.profile');

        Route::get('/socialmedia/likes/{post_id}', [SocialmediaController::class, 'social_media_likes'])->name('social_media_likes');

        Route::get('/comment/likes/{id}', [SocialmediaController::class, 'comment_likes'])->name('comment_likes');

        Route::post('/socialmedia_profile', [SocialmediaController::class, 'social_media_profile'])->name('social_media_profile');

        Route::get('/socialmedia/profile/photos/{id}', [SocialmediaController::class, 'socialmedia_profile_photos'])->name('socialmedia_profile_photos');

        Route::post('search_users', [SocialmediaController::class, 'showUser'])->name('search_users');

        Route::get('/friendsList/{id}', [SocialmediaController::class, 'friendsList'])->name('friendsList');
        Route::post('friend/search/{id}', [SocialmediaController::class, 'friList'])
            ->name('friend_search');

        Route::get('/addUser/{id}', [SocialmediaController::class, 'addUser'])->name('addUser');

        Route::get('/unfriend/{id}', [SocialmediaController::class, 'unfriend'])->name('unfriend');

        Route::post('/socialmedia/user/react/', [SocialmediaController::class, 'user_react_post'])->name('user.react.post');
        Route::post('/socialmedia/user/comment/', [SocialmediaController::class, 'user_react_comment'])->name('user.react.comment');
        Route::get('/socialmedia/user/view/', [SocialmediaController::class, 'user_view_post'])->name('user.view.post');
        Route::get('/socialmedia/user/view1/', [SocialmediaController::class, 'user_view_post1'])->name('user.view.post1');

        Route::get('/cancelRequest/{id}', [SocialmediaController::class, 'cancelRequest'])->name('cancelRequest');
        Route::get('/declineRequest/{id}', [SocialmediaController::class, 'declineRequest'])->name('declineRequest');
        Route::get('/confirmRequest/{id}', [SocialmediaController::class, 'confirmRequest'])->name('confirmRequest');
        Route::get('/block/{id}', [SocialmediaController::class, 'blockUser'])->name('block');
        Route::post('/blockList', [SocialmediaController::class, 'block_list'])->name('blockList');
        Route::get('/unblock/{id}', [SocialmediaController::class, 'unblockUser'])->name('unblock');

        Route::get('/notification_center', [SocialmediaController::class, 'notification_center'])->name('notification_center');

        Route::get('/viewFriendRequestNoti/{id}/{noti_id}', [SocialmediaController::class, 'viewFriendRequestNoti'])->name('viewFriendRequestNoti');

        Route::post('post/comment/list/{id}', [SocialmediaController::class, 'comment_list'])->name('comment_list');

        Route::post('/post/store', [SocialmediaController::class, 'post_store'])->name('post.store');

        Route::post('/users_for_mention', [SocialmediaController::class, 'users_for_mention'])->name('users.mention');

        Route::get('/post/comment/{id}', [SocialmediaController::class, 'post_comment'])->name('post.comment');

        Route::post('/post/comment/store', [SocialmediaController::class, 'post_comment_store'])->name('post.comment.store');
        Route::post('/post/comment/delete/{id}', [SocialmediaController::class, 'comment_delete'])->name('post.comment.delete');
        Route::get('/post/comment/edit/{id}', [SocialmediaController::class, 'comment_edit'])->name('post.comment.edit');
        Route::post('post/comment/update', [SocialmediaController::class, 'comment_update'])->name('post.comment.update');
    });
    Route::get('customer/register', [App\Http\Controllers\HomeController::class, 'customer_register'])->name('customer_register');
    //Route::post('customer/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('customer_register');
    Route::post('customer/register', [App\Http\Controllers\Customer\CustomerRegisterController::class, 'register'])->name('customer_register');
    Route::get('getOPT', [App\Http\Controllers\Customer\CustomerRegisterController::class, 'getOPT'])->name('getOPT');
    Route::post('customer/updateinfo/', [App\Http\Controllers\Customer\CustomerRegisterController::class, 'updateinfo'])->name('updateinfo');

    Route::get('/user/workout/start', [UserWorkoutController::class, 'getstart'])->name('userworkout.getstart');

    Route::get('password_reset_view', [PassResetController::class, 'passResetView'])->name('password_reset_view');
    Route::get('checkPhoneGetOTP', [PassResetController::class, 'checkPhoneGetOTP'])->name('checkPhoneGetOTP');

    Route::post('password_reset', [PassResetController::class, 'password_reset'])->name('password_reset');



    Route::middleware(['role:Trainer'])->group(function () {
        Route::get('/trainers', [TrainerManagementConntroller::class, 'index'])->name('trainers');
        // Route::get('/test', [TrainerManagementConntroller::class, 'index'])->name('test');
        // Route::get('/test')
        Route::post('/trainer/group/create', [TrainerGroupController::class, 'store'])->name('trainer.group.create');
        Route::post('trainer/view_member/search/{id}', [TrainerManagementConntroller::class, 'showMember'])->name('trainer/member/search');
        Route::get('/trainer/view_member/{id}', [TrainerManagementConntroller::class, 'view_member'])->name('trainer/view_member');
        Route::get('/trainer/add_member/{id}', [TrainerManagementConntroller::class, 'add_member'])->name('trainer/add_member');
        Route::get('/trainer/view_media/{id}', [TrainerManagementConntroller::class, 'view_media'])->name('trainer/view_media');
        Route::get('/addMember/{id}/{group_id}', [TrainerManagementConntroller::class, 'addMember'])->name('addMember');
        Route::get('trainer/group/show/{id}', [TrainerGroupController::class, 'chat_show']);
        // Route::post('trainer/send/{id}', [TrainerManagementConntroller::class, 'send'])->name('trainer-send-message');
        Route::get('trainer/group/delete', [TrainerManagementConntroller::class, 'destroy'])->name('group.delete');
        Route::get('trainer/group/member/kick/{id}', [TrainerManagementConntroller::class, 'kick'])->name('member.kick');
    });

    // Admin Site

    Route::prefix('admin')->group(function () {

        Route::middleware(['role:System_Admin|King|Queen|Admin'])->group(function () {
            // Route::middleware('auth')->group(function () {

            //Route::get('/', [AdminController::class, 'index'])->name('admin-home');
            Route::get('/profile', [AdminController::class, 'adminProfile'])->name('admin-profile');
            Route::get('/profile/edit', [AdminController::class, 'editAdminProfile'])->name('admin-edit');
            Route::put('/profile/{id}/update', [AdminController::class, 'updateAdminProfile'])->name('admin-update');

            // Ban Words
            Route::get('/banwords', [BanWordsController::class, 'index'])->name('banwords.index');
            Route::get('/banwords/edit/{id}', [BanWordsController::class, 'edit'])->name('banwords.edit');
            Route::post('/banwords/update/{id}', [BanWordsController::class, 'update'])->name('banwords.update');
            Route::get('/banwords/destroy/{id}', [BanWordsController::class, 'destroy'])->name('banwords.destroy');
            Route::get('/banwords/create/', [BanWordsController::class, 'create'])->name('banwords.create');
            Route::post('/banwords/store', [BanWordsController::class, 'store'])->name('banwords.store');
            Route::get('admin/banword/datatable/ssd', [BanWordsController::class, 'ssd']);

            // all users
            Route::resource('user', UserController::class);
            Route::get('admin/user/datatable/ssd', [UserController::class, 'ssd']);

            Route::get('/requestlist', [HomeController::class, 'requestlist'])->name('requestlist');

            //Workout
            Route::get('/workout/index', [WorkoutController::class, 'index'])->name('workoutindex');
            Route::get('create/workout', [WorkoutController::class, 'workoutindex'])->name('workout');
            Route::get('/workout', [WorkoutController::class, 'workoutview'])->name('workoutview');
            Route::get('/workout/delete/{id}', [WorkoutController::class, 'workoutdelete'])->name('workoutdelete');
            Route::get('/workout/edit/{id}', [WorkoutController::class, 'workoutedit'])->name('workoutedit');
            Route::post('/workout/update/{id}', [WorkoutController::class, 'workoutupdate'])->name('workoutupdate');
            Route::post('/workout/create', [WorkoutController::class, 'createworkout'])->name('createworkout');
            Route::get('/videoview', [WorkoutController::class, 'getVideo'])->name('getvideo');

            //Trainer
            Route::resource('trainer', TrainerController::class);
            Route::get('admin/trainer/datatable/ssd', [TrainerController::class, 'ssd']);

            // Permission
            Route::resource('permission', PermissionController::class);
            Route::get('admin/permission/datatable/ssd', [PermissionController::class, 'ssd']);

            // Role
            Route::resource('role', RoleController::class);
            Route::get('admin/role/datatable/ssd', [RoleController::class, 'ssd']);


            //Shop members
            // Route::resource('shop-member', ShopMemberController::class);
            // Route::get('admin/shop-member/datatable/ssd', [ShopMemberController::class, 'ssd']);
            Route::resource('shop-member', ShopMemberController::class);
            Route::get('admin/shop-member/datatable/ssd', [ShopMemberController::class, 'ssd']);

            //Report
            Route::get('admin/report/datatable/ssd', [ReportController::class, 'ssd']);
            Route::get('admin/action-report/datatable/ssd', [ReportController::class, 'action_ssd']);
            Route::delete('admin/report/{$id}', [ReportController::class, 'destroy'])->name('report.destroy');
            Route::get('admin/report/{id}/view', [ReportController::class, 'view_post'])->name('admin.view.report');
            Route::get('admin/report/accept/{report_id}', [ReportController::class, 'accept_report'])->name('admin.accept.report');
            Route::get('admin/report/decline/{report_id}', [ReportController::class, 'decline_report'])->name('admin.decline.report');

            // Meal Plan
            Route::resource('mealplan', MealPlanController::class);
            Route::get('admin/getmealplan', [MealPlanController::class, 'getmealplan'])->name('getmealplan');
            Route::get('admin/mealplan/{id}/delete', [MealPlanController::class, 'destroy'])->name('mealplan.delete');


            // Meal
            Route::resource('meal', MealController::class);
            Route::get('admin/getmeal', [MealController::class, 'getMeal'])->name('getmeal');
            Route::get('/meal/{id}/delete', [MealController::class, 'destroy'])->name('meal.delete');

            // Member
            Route::resource('member', MemberController::class);
            Route::get('/member/{id}/delete', [MemberController::class, 'destroy'])->name('member.delete');
            Route::get('admin/member/datatable/ssd', [MemberController::class, 'ssd']);

            Route::get('user_member', [MemberController::class, 'user_member_show'])->name('member.user_member');
            Route::get('user_member/edit/{id}', [MemberController::class, 'user_member_edit'])->name('member.user_member.edit');
            Route::post('user_member/update/{id}', [MemberController::class, 'user_member_update'])->name('member.user_member.update');

            Route::get('admin/user_member/datatable/ssd', [MemberController::class, 'user_member_ssd'])->name('admin/user_member/datatable/ssd');
            Route::get('admin/user_member/datatable_decline/ssd', [MemberController::class, 'user_member_decline_ssd'])->name('admin/user_member/datatable_decline/ssd');
            Route::get('user_member/destroy/{id}', [MemberController::class, 'user_member_destroy'])->name('user_member.destroy');
            Route::get('user_member/ban/{id}', [MemberController::class, 'user_member_ban'])->name('user_member.ban');

            //BankingInfo
            Route::resource('bankinginfo', BankinginfoController::class);
            Route::get('admin/bankinginfo/datatable/ssd', [BankinginfoController::class, 'ssd']);

            //payment
            Route::get('/payment/{id}', [PaymentController::class, 'detail'])->name('payment.detail');
            Route::get('/transaction/bank/{id}', [PaymentController::class, 'transactionBankDetail'])->name('transactionbank.detail');
            Route::get('/transaction/ewallet/{id}', [PaymentController::class, 'transactionWalletDetail'])->name('transactionwallet.detail');
            Route::get('payment/bank/transction', [PaymentController::class, 'bankPaymentTransction'])->name('banktransaction');
            Route::get('payment/ewallet/transction', [PaymentController::class, 'EPaymentTransction'])->name('wallettransaction');
            Route::get('/payment', [PaymentController::class, 'transctionView'])->name('payment.transction');

            //Request
            Route::resource('request', RequestController::class);
            Route::get('request/member/datatable/ssd', [RequestController::class, 'ssd']);
            Route::get('request/member/accept/{id}', [RequestAcceptDeclineController::class, 'accept'])->name('requestaccept');
            Route::get('request/member/decline/{id}', [RequestAcceptDeclineController::class, 'decline'])->name('requestdecline');

            //training center
            Route::resource('traininggroup', TrainingGroupController::class);
            Route::get('traininggroup/{traininggroup}/ssd', [TrainingGroupController::class, 'ssd']);
            Route::get('/trainingcenter/index', [TrainingCenterController::class, 'index'])->name('trainingcenter.index');
            Route::get('/trainingcenter/entergroup', [TrainingCenterController::class, 'entergroup'])->name('trainingcenter.entergroup');
            Route::get('/trainingcenter/chat/{id}', [TrainingCenterController::class, 'chat_message'])->name('chat_message');
            Route::get('/trainingcenter/chat/viewmedia/{id}', [TrainingCenterController::class, 'view_media'])->name('trainingcenter.view_media');
            Route::get('/trainingcenter/chat/viewmember/{id}', [TrainingCenterController::class, 'view_member'])->name('trainingcenter.view_member');
            Route::post('trainingcenter/show_member/search/{id}', [TrainingCenterController::class, 'show_member'])->name('show_member');
            Route::get('/trainingcenter/add_member/{id}/{gp_id}', [TrainingCenterController::class, 'add_member'])->name('add_member');
            Route::get('/trainingcenter/kick_member/{id}', [TrainingCenterController::class, 'kick_member'])->name('kick_member');
            Route::get('/trainingcenter/delete_gp', [TrainingCenterController::class, 'delete_gp'])->name('delete_gp');

            //report
            Route::resource('report', ReportController::class);

            //shop request
            Route::get('shop/request', [ShopRequestController::class, 'index'])->name('admin.shop_request');
            Route::get('shop/request/accept/{id}', [ShopRequestController::class, 'request_accept'])->name('admin.shop_request.accept');
            Route::get('shop/request/decline/{id}', [ShopRequestController::class, 'request_decline'])->name('admin.shop_request.decline');
            Route::get('request/shop/datatable/ssd', [ShopRequestController::class, 'ssd']);


            
            Route::resource('free_video', FreeVideosController::class);
            Route::get('getVideos', [FreeVideosController::class, 'getVideos'])->name('getVideos');
            Route::get('admin/video/{id}/delete', [FreeVideosController::class, 'destroy'])->name('video.delete');


            //feedback
            Route::resource('feedback', FeedbackController::class);
            Route::get('getfeedbcak', [FeedbackController::class, 'getFeedback'])->name('admin.getFeedback');


        });
    }); //admin prefix

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('chat_with_admin', [ChatWithAdminController::class, 'user_list'])->name('admin.chat_with_admin');
        Route::get('chat_with_admin/{id}', [ChatWithAdminController::class, 'user_list_one'])->name('admin.chat_with_admin_messages');
        Route::get('chat_with_viewmedia/{id}', [ChatWithAdminController::class, 'viewmedia_message'])->name('admin.chat.viewmedia');

    });

    Route::middleware(['role:Free'])->group(function () {
        Route::get('/free', [TrainerManagementConntroller::class, 'free'])->name('free');
    });
    Route::middleware(['role:Platinum|Diamond|Gym Member'])->group(function () {

        Route::get('customer/today', [Customer_TrainingCenterController::class, 'todaywater'])->name('today');
        Route::get('customer/lastsevenDay/{date}', [Customer_TrainingCenterController::class, 'lastsevenDay'])->name('last7day');
        Route::get('/customer/workout/lastsevenDay/', [Customer_TrainingCenterController::class, 'workout_sevenday'])->name('workout_sevenday');
        Route::get('/customer/workout/filter/{from}/{to}', [Customer_TrainingCenterController::class, 'workout_filter'])->name('workout_filter');

        Route::get('customer/training_center/workout/workout_complete/{t_sum}/{cal_sum?}/{count_video?}/{group_id?}', [Customer_TrainingCenterController::class, 'workout_complete'])->name('workout_complete');
        Route::get('customer/training_center/workout/workout_complete_gym/{t_sum}/{cal_sum?}/{count_video?}/{group_id?}', [Customer_TrainingCenterController::class, 'workout_complete_gym'])->name('workout_complete.gym');
        Route::post('customer/training_center/workout/workout_complete/store/', [Customer_TrainingCenterController::class, 'workout_complete_store'])->name('workout_complete.store');

        Route::get('customer/meal/sevendays/{date}', [Customer_TrainingCenterController::class, 'meal_sevendays'])->name('meal_sevendays');

        Route::get('customer/training_center', [Customer_TrainingCenterController::class, 'index'])->name('training_center.index');
        Route::get('customer/training_center/member_plan', [Customer_TrainingCenterController::class, 'member_plan'])->name('training_center.member_plan');

        Route::get('customer/training_center/meal', [Customer_TrainingCenterController::class, 'meal'])->name('training_center.meal');
        Route::get('customer/training_center/workout_plan', [Customer_TrainingCenterController::class, 'workout_plan'])->name('training_center.workout_plan');
        Route::get('customer/training_center/water', [Customer_TrainingCenterController::class, 'water'])->name('training_center.water');
        Route::post('customer/training_center/water', [Customer_TrainingCenterController::class, 'water_track'])->name('training_center.water.store');
        Route::get('customer/training_center/workout/home', [Customer_TrainingCenterController::class, 'workout_home'])->name('training_center.workout.home');
        Route::get('customer/training_center/workout/gym', [Customer_TrainingCenterController::class, 'workout_gym'])->name('training_center.workout.gym');

        Route::post('customer/training_center/breakfast', [Customer_TrainingCenterController::class, 'showbreakfast'])->name('customer/training_center/breakfast');
        Route::post('customer/training_center/lunch', [Customer_TrainingCenterController::class, 'showlunch'])->name('customer/training_center/lunch');
        Route::post('customer/training_center/snack', [Customer_TrainingCenterController::class, 'showsnack'])->name('customer/training_center/snack');
        Route::post('customer/training_center/dinner', [Customer_TrainingCenterController::class, 'showdinner'])->name('customer/training_center/dinner');
        Route::post('customer/training_center/foodList', [Customer_TrainingCenterController::class, 'foodList'])->name('customer/training_center/foodList');
    });

    // Route::middleware(['role:Platinum|Diamond|Gym Member'])->group(function () {


    //     Route::get('customer/training_center/workout_complete/{t_sum}/{cal_sum?}/{count_video?}/{group_id?}',[Customer_TrainingCenterController::class,'workout_complete'])->name('workout_complete');
    //     Route::get('customer/training_center',[Customer_TrainingCenterController::class,'index'])->name('training_center.index');
    //     Route::post('customer/training_center/workout_complete/store/',[Customer_TrainingCenterController::class,'workout_complete_store'])->name('workout_complete.store');

    //     Route::get('customer/training_center/meal',[Customer_TrainingCenterController::class,'meal'])->name('training_center.meal');
    //     Route::get('customer/training_center/workout_plan',[Customer_TrainingCenterController::class,'workout_plan'])->name('training_center.workout_plan');
    //     Route::get('customer/training_center/water',[Customer_TrainingCenterController::class,'water'])->name('training_center.water');


    //     Route::post('customer/training_center/breakfast',[Customer_TrainingCenterController::class,'showbreakfast'])->name('customer/training_center/breakfast');
    //     Route::post('customer/training_center/lunch',[Customer_TrainingCenterController::class,'showlunch'])->name('customer/training_center/lunch');
    //     Route::post('customer/training_center/snack',[Customer_TrainingCenterController::class,'showsnack'])->name('customer/training_center/snack');
    //     Route::post('customer/training_center/dinner',[Customer_TrainingCenterController::class,'showdinner'])->name('customer/training_center/dinner');
    //     Route::post('customer/training_center/foodList',[Customer_TrainingCenterController::class,'foodList'])->name('customer/training_center/foodList');

    // });

    Route::middleware(['role:Free'])->group(function () {
        Route::get('/free', [TrainerManagementConntroller::class, 'free'])->name('free');
    });

    Route::middleware(['role:Platinum'])->group(function () {
        Route::get('/platinum', [TrainerManagementConntroller::class, 'platinum'])->name('platinum');
    });
    Route::middleware(['role:Gold'])->group(function () {
        Route::get('/gold', [TrainerManagementConntroller::class, 'gold'])->name('gold');
    });
    Route::middleware(['role:Diamond'])->group(function () {
        Route::get('/diamond', [TrainerManagementConntroller::class, 'diamond'])->name('diamond');
    });
    Route::middleware(['role:Ruby'])->group(function () {
        Route::get('/ruby', [TrainerManagementConntroller::class, 'ruby'])->name('ruby');
    });
    Route::middleware(['role:Ruby Premium'])->group(function () {
        Route::get('/ruby_premium', [TrainerManagementConntroller::class, 'ruby_premium'])->name('ruby_premium');
    });
    Route::middleware(['role:Gold'])->group(function () {
        Route::get('/gold', [TrainerManagementConntroller::class, 'gold'])->name('gold');
    });
    Route::middleware(['role:Gym Member'])->group(function () {
        Route::get('/gym_member', [TrainerManagementConntroller::class, 'gym_member'])->name('gym_member');
    });

    Route::middleware(['role:Gold|Ruby|Ruby Premium'])->group(function () {
        Route::get('customer/groups', [CustomerManagementController::class, 'showgroup'])->name('groups');
        Route::get('customer', [CustomerManagementController::class, 'showchat'])->name('group');
        Route::get('customer/view_media', [CustomerManagementController::class, 'view_media'])->name('view_media');
        Route::get('customer/trainingcenter/memberplan', [Customer_TrainingCenterController::class, 'member_plan'])->name('trainingcenter.member_plan');
    });
});
