<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DarkModeController;

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

Route::get('dark-mode-switcher', [DarkModeController::class, 'switch'])->name('dark-mode-switcher');

Route::middleware('loggedin')->group(function() {
    Route::get('login', [AuthController::class, 'loginView'])->name('login-view');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('register', [AuthController::class, 'registerView'])->name('register-view');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth')->group(function() {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [PageController::class, 'dashboardOverview1'])->name('dashboard-overview-1');
    Route::get('user/daily/rewards', [HomeController::class,'daily'])->name('userDailyBonus');
    Route::get('dashboard-overview-2-page', [PageController::class, 'dashboardOverview2'])->name('dashboard-overview-2');
    Route::get('inbox-page', [PageController::class, 'inbox'])->name('inbox');
    Route::get('file-manager-page', [PageController::class, 'fileManager'])->name('file-manager');
    Route::get('point-of-sale-page', [PageController::class, 'pointOfSale'])->name('point-of-sale');
    Route::get('chat-page', [PageController::class, 'chat'])->name('chat');
    Route::get('post-page', [PageController::class, 'post'])->name('post');
    Route::get('calendar-page', [PageController::class, 'calendar'])->name('calendar');
    Route::get('crud-data-list-page', [PageController::class, 'crudDataList'])->name('crud-data-list');
    Route::get('crud-form-page', [PageController::class, 'crudForm'])->name('crud-form');
    Route::get('users-layout-1-page', [PageController::class, 'usersLayout1'])->name('users-layout-1');
    Route::get('users-layout-2-page', [PageController::class, 'usersLayout2'])->name('users-layout-2');
    Route::get('users-layout-3-page', [PageController::class, 'usersLayout3'])->name('users-layout-3');
    Route::get('profile-overview-1-page', [PageController::class, 'profileOverview1'])->name('profile-overview-1');
    Route::get('profile-overview-2-page', [PageController::class, 'profileOverview2'])->name('profile-overview-2');
    Route::get('profile-overview-3-page', [PageController::class, 'profileOverview3'])->name('profile-overview-3');
    Route::get('wizard-layout-1-page', [PageController::class, 'wizardLayout1'])->name('wizard-layout-1');
    Route::get('wizard-layout-2-page', [PageController::class, 'wizardLayout2'])->name('wizard-layout-2');
    Route::get('wizard-layout-3-page', [PageController::class, 'wizardLayout3'])->name('wizard-layout-3');
    Route::get('blog-layout-1-page', [PageController::class, 'blogLayout1'])->name('blog-layout-1');
    Route::get('blog-layout-2-page', [PageController::class, 'blogLayout2'])->name('blog-layout-2');
    Route::get('blog-layout-3-page', [PageController::class, 'blogLayout3'])->name('blog-layout-3');
    Route::get('pricing-layout-1-page', [PageController::class, 'pricingLayout1'])->name('pricing-layout-1');
    Route::get('pricing-layout-2-page', [PageController::class, 'pricingLayout2'])->name('pricing-layout-2');
    Route::get('invoice-layout-1-page', [PageController::class, 'invoiceLayout1'])->name('invoice-layout-1');
    Route::get('invoice-layout-2-page', [PageController::class, 'invoiceLayout2'])->name('invoice-layout-2');
    Route::get('faq-layout-1-page', [PageController::class, 'faqLayout1'])->name('faq-layout-1');
    Route::get('faq-layout-2-page', [PageController::class, 'faqLayout2'])->name('faq-layout-2');
    Route::get('faq-layout-3-page', [PageController::class, 'faqLayout3'])->name('faq-layout-3');
    Route::get('login-page', [PageController::class, 'login'])->name('login');
    Route::get('register-page', [PageController::class, 'register'])->name('register');
    Route::get('error-page-page', [PageController::class, 'errorPage'])->name('error-page');
    Route::get('update-profile-page', [PageController::class, 'updateProfile'])->name('update-profile');
    Route::get('change-password-page', [PageController::class, 'changePassword'])->name('change-password');
    Route::get('regular-table-page', [PageController::class, 'regularTable'])->name('regular-table');
    Route::get('tabulator-page', [PageController::class, 'tabulator'])->name('tabulator');
    Route::get('modal-page', [PageController::class, 'modal'])->name('modal');
    Route::get('slide-over-page', [PageController::class, 'slideOver'])->name('slide-over');
    Route::get('notification-page', [PageController::class, 'notification'])->name('notification');
    Route::get('accordion-page', [PageController::class, 'accordion'])->name('accordion');
    Route::get('button-page', [PageController::class, 'button'])->name('button');
    Route::get('alert-page', [PageController::class, 'alert'])->name('alert');
    Route::get('progress-bar-page', [PageController::class, 'progressBar'])->name('progress-bar');
    Route::get('tooltip-page', [PageController::class, 'tooltip'])->name('tooltip');
    Route::get('dropdown-page', [PageController::class, 'dropdown'])->name('dropdown');
    Route::get('typography-page', [PageController::class, 'typography'])->name('typography');
    Route::get('icon-page', [PageController::class, 'icon'])->name('icon');
    Route::get('loading-icon-page', [PageController::class, 'loadingIcon'])->name('loading-icon');
    Route::get('regular-form-page', [PageController::class, 'regularForm'])->name('regular-form');
    Route::get('datepicker-page', [PageController::class, 'datepicker'])->name('datepicker');
    Route::get('tail-select-page', [PageController::class, 'tailSelect'])->name('tail-select');
    Route::get('file-upload-page', [PageController::class, 'fileUpload'])->name('file-upload');
    Route::get('wysiwyg-editor-page', [PageController::class, 'wysiwygEditor'])->name('wysiwyg-editor');
    Route::get('validation-page', [PageController::class, 'validation'])->name('validation');
    Route::get('chart-page', [PageController::class, 'chart'])->name('chart');
    Route::get('slider-page', [PageController::class, 'slider'])->name('slider');
    Route::get('image-zoom-page', [PageController::class, 'imageZoom'])->name('image-zoom');
});



/*
|--------------------------------------------------------------------------
| Web Routes For Admin
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['admin', 'ban']], function (){

    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('adminIndex');

//    Route::get('admin/mail/inbox', 'AdminEmailController@index')->name('adminEmail');
//    Route::get('admin/mail/view/{id}', 'AdminEmailController@show')->name('adminEmail.show');
//    Route::get('admin/email/compose', 'AdminEmailController@create')->name('adminEmail.create');
//    Route::get('admin/message/compose', 'AdminEmailController@message')->name('adminMessage.create');
//    Route::post('admin/message/send', 'AdminEmailController@store')->name('adminMessage.send');
//    Route::post('admin/send/mail', 'AdminEmailController@send')->name('adminEmail.send');
//
//
//    Route::get('admin/users', ['uses' => 'AdminUsersController@index','as' => 'admin.users.index']);
//    Route::get('admin/unverified/users', ['uses' => 'AdminUsersController@unverified','as' => 'admin.users.unverified']);
//    Route::get('admin/verified/users', ['uses' => 'AdminUsersController@verified','as' => 'admin.users.verified']);
//    Route::get('admin/banned/users', ['uses' => 'AdminUsersController@banned','as' => 'admin.users.banned']);
//    Route::get('admin/user/create', ['uses' => 'AdminUsersController@create','as' => 'admin.user.create']);
//    Route::get('admin/user/edit/{id}', ['uses' => 'AdminUsersController@edit','as' => 'admin.user.edit']);
//    Route::get('admin/user/show/{id}', ['uses' => 'AdminUsersController@show','as' => 'admin.user.show']);
//
//    Route::get('admin/ver/users', ['uses' => 'AdminUsersController@ver','as' => 'admin.users.ver']);
//
//
//    Route::get('user/investments', 'UserInterestController@index')->name('userInvestments');
//
//    Route::get('admin/user/investment', 'AdminUsersController@investment')->name('admin.user.invest');
//    Route::get('admin/user/interest/show/{id}', 'AdminUsersController@interest')->name('admin.user.interest');
//    Route::get('admin/user/cashlinks/show/{id}', 'AdminUsersController@cashLinks')->name('admin.user.ptc');
//    Route::get('admin/user/cashvideos/show/{id}', 'AdminUsersController@cashVideos')->name('admin.user.ppv');
//    Route::post('admin/user/suspend/{id}', 'AdminUsersController@suspend')->name('admin.user.ban');
//    Route::get('admin/user/active/{id}', 'AdminUsersController@unSuspend')->name('admin.users.active');
//    Route::get('admin/user/investment/details/{id}', 'AdminUsersController@details')->name('admin.user.investDetails');
//    Route::get('admin/user/linkshare/show/{id}', 'AdminUsersController@LinkShare')->name('admin.user.share');
//    Route::get('admin/user/transfer/show/{id}', 'AdminUsersController@transfer')->name('admin.user.transfer');
//    Route::get('admin/user/deposit/show/{id}', 'AdminUsersController@deposit')->name('admin.user.deposit');
//    Route::get('admin/user/withdraw/show/{id}', 'AdminUsersController@withdraw')->name('admin.user.withdraw');
//
//    Route::post('admin/user/suspends/{id}', 'AdminUsersController@ver')->name('admin.user.ver');
//
//
//    Route::get('admin/user/referral/show/{id}', ['uses' => 'AdminUsersController@referral','as' => 'admin.user.referShow']);
//    Route::get('admin/user/delete/{id}', ['uses' => 'AdminUsersController@destroy','as' => 'admin.user.delete']);
//    Route::post('admin/user/update/{id}', ['uses' => 'AdminUsersController@update','as' => 'admin.user.update']);
//    Route::post('admin/user/create/store', ['uses' => 'AdminUsersController@store','as' => 'admin.user.store']);
//    Route::get('admin/user/create/admin/{id}', ['uses' => 'AdminUsersController@admin','as' => 'admin.create.admin']);
//    Route::get('admin/user/remove/admin/{id}', ['uses' => 'AdminUsersController@adminRemove','as' => 'admin.remove.admin']);
//    Route::resource('admin/posts', 'AdminPostsController',['names'=>[
//
//        'index'=>'admin.posts.index',
//        'create'=>'admin.post.create',
//        'store'=>'admin.posts.store',
//        'edit'=>'admin.posts.edit'
//
//    ]]);
//
//    Route::get('admin/posts/delete/{id}', ['uses' => 'AdminPostsController@destroy','as' => 'admin.posts.delete']);
//    Route::post('admin/posts/update/{id}', ['uses' => 'AdminPostsController@update', 'as' => 'admin.posts.update']);
//    Route::get('admin/trash/posts', ['uses' => 'AdminPostsController@trashed', 'as' => 'admin.posts.tIndex']);
//    Route::get('admin/kill/post/{id}', ['uses' => 'AdminPostsController@kill', 'as' => 'admin.post.kill']);
//    Route::get('admin/restore/post/{id}', ['uses' => 'AdminPostsController@restore', 'as' => 'admin.post.restore']);
//    Route::resource('admin/categories', 'AdminCategoriesController',['names'=>[
//
//        'index'=>'admin.category.index',
//        'create'=>'admin.category.create',
//        'store'=>'admin.category.store',
//        'edit'=>'admin.category.edit'
//    ]]);
//
//    Route::post('admin/categories/update/{id}', ['uses' => 'AdminCategoriesController@update', 'as' => 'admin.category.update']);
//    Route::get('admin/categories/delete/{id}', ['uses' => 'AdminCategoriesController@destroy', 'as' => 'admin.category.delete']);
//
//    Route::get('admin/tags', ['uses' => 'AdminTagsController@index', 'as' => 'admin.tags.index']);
//    Route::get('admin/tag/edit/{id}', ['uses' => 'AdminTagsController@edit', 'as' => 'admin.tag.edit']);
//    Route::post('admin/tag/update/{id}', ['uses' => 'AdminTagsController@update', 'as' => 'admin.tag.update']);
//    Route::post('admin/tag/store', ['uses' => 'AdminTagsController@store', 'as' => 'admin.tag.store']);
//    Route::get('admin/tag/delete/{id}', ['uses' => 'AdminTagsController@destroy', 'as' => 'admin.tag.destroy']);
//
//
//    Route::get('admin/website/pages', ['uses' => 'AdminPagesController@index', 'as' => 'adminPages']);
//    Route::get('admin/website/page/edit/{id}', ['uses' => 'AdminPagesController@edit', 'as' => 'adminPage.edit']);
//    Route::post('admin/website/page/update/{id}', ['uses' => 'AdminPagesController@update', 'as' => 'adminPage.update']);
//    Route::get('admin/website/page/publish/{id}', ['uses' => 'AdminPagesController@publish', 'as' => 'adminPage.Publish']);
//    Route::get('admin/website/page/unpublish/{id}', ['uses' => 'AdminPagesController@unPublish', 'as' => 'adminPage.unPublish']);
//
//    Route::get('admin/memberships', ['uses' => 'AdminMembershipController@index', 'as' => 'admin.memberships.index']);
//    Route::get('admin/membership/create', ['uses' => 'AdminMembershipController@create', 'as' => 'admin.membership.create']);
//    Route::get('admin/membership/edit/{id}', ['uses' => 'AdminMembershipController@edit', 'as' => 'admin.membership.edit']);
//    Route::get('admin/membership/delete/{id}', ['uses' => 'AdminMembershipController@destroy', 'as' => 'admin.membership.delete']);
//    Route::post('admin/membership/store', ['uses' => 'AdminMembershipController@store', 'as' => 'admin.membership.store']);
//    Route::post('admin/membership/update/{id}', ['uses' => 'AdminMembershipController@update', 'as' => 'admin.membership.update']);
//
//
//    Route::get('admin/ptc', ['uses' => 'AdminPTCController@index', 'as' => 'admin.ptcs.index']);
//    Route::get('admin/ptc/create', ['uses' => 'AdminPTCController@create', 'as' => 'admin.ptc.create']);
//    Route::post('admin/ptc/create', ['uses' => 'AdminPTCController@store', 'as' => 'admin.ptc.store']);
//    Route::get('admin/ptc/delete/{id}', ['uses' => 'AdminPTCController@destroy', 'as' => 'admin.ptc.delete']);
//    Route::get('admin/ptc/edit/{id}', ['uses' => 'AdminPTCController@edit', 'as' => 'admin.ptc.edit']);
//    Route::post('admin/ptc/update/{id}', ['uses' => 'AdminPTCController@update', 'as' => 'admin.ptc.update']);
//    Route::get('admin/ptc/preview/{id}', ['uses' => 'AdminPTCController@preview', 'as' => 'admin.ptc.preview']);
//
//    Route::get('admin/link/share', ['uses' => 'AdminLinkController@index', 'as' => 'admin.link.index']);
//    Route::get('admin/link/share/create', ['uses' => 'AdminLinkController@create', 'as' => 'admin.link.create']);
//    Route::post('admin/link/share/create', ['uses' => 'AdminLinkController@store', 'as' => 'admin.link.store']);
//    Route::get('admin/link/share/delete/{id}', ['uses' => 'AdminLinkController@destroy', 'as' => 'admin.link.delete']);
//    Route::get('admin/link/share/edit/{id}', ['uses' => 'AdminLinkController@edit', 'as' => 'admin.link.edit']);
//    Route::post('admin/link/share/update/{id}', ['uses' => 'AdminLinkController@update', 'as' => 'admin.link.update']);
//
//    Route::get('admin/ppv', ['uses' => 'AdminPPVController@index', 'as' => 'admin.ppvs.index']);
//    Route::get('admin/ppv/create', ['uses' => 'AdminPPVController@create', 'as' => 'admin.ppv.create']);
//    Route::post('admin/ppv/create', ['uses' => 'AdminPPVController@store', 'as' => 'admin.ppv.store']);
//    Route::get('admin/ppv/delete/{id}', ['uses' => 'AdminPPVController@destroy', 'as' => 'admin.ppv.delete']);
//    Route::get('admin/ppv/edit/{id}', ['uses' => 'AdminPPVController@edit', 'as' => 'admin.ppv.edit']);
//    Route::post('admin/ppv/update/{id}', ['uses' => 'AdminPPVController@update', 'as' => 'admin.ppv.update']);
//
//    Route::get('admin/advert/plan', ['uses' => 'AdminAdvertPlanController@index', 'as' => 'admin.advert.planIndex']);
//    Route::post('admin/advert/plan/store', ['uses' => 'AdminAdvertPlanController@store', 'as' => 'admin.advert.planStore']);
//    Route::get('admin/advert/plan/edit/{id}', ['uses' => 'AdminAdvertPlanController@edit', 'as' => 'admin.advert.planEdit']);
//    Route::post('admin/advert/plan/update/{id}', ['uses' => 'AdminAdvertPlanController@update', 'as' => 'admin.advert.planUpdate']);
//    Route::get('admin/advert/plan/destroy/{id}', ['uses' => 'AdminAdvertPlanController@destroy', 'as' => 'admin.advert.planDestroy']);
//    Route::get('admin/user/advert/request', ['uses' => 'AdminAdvertPlanController@request', 'as' => 'admin.user.advert']);
//    Route::get('admin/user/advert/request/approve/{id}', ['uses' => 'AdminAdvertPlanController@approve', 'as' => 'admin.user.advertAp']);
//    Route::get('admin/user/adverts', ['uses' => 'AdminAdvertPlanController@allAds', 'as' => 'admin.user.advertAll']);
//    Route::get('admin/user/advert/pause/{id}', ['uses' => 'AdminAdvertPlanController@pause', 'as' => 'admin.user.advertPR']);
//    Route::get('admin/user/advert/edit/{id}', ['uses' => 'AdminAdvertPlanController@orderEdit', 'as' => 'admin.user.advertEdit']);
//    Route::post('admin/user/advert/submit/edit/{id}', ['uses' => 'AdminAdvertPlanController@orderEditsubmit', 'as' => 'admin.user.advertEditSubmit']);
//
//
//    Route::get('admin/gateways', ['uses' => 'AdminGatewaysController@index', 'as' => 'admin.gateways.index']);
//    Route::get('admin/gateway/edit/{id}', ['uses' => 'AdminGatewaysController@edit', 'as' => 'admin.gateway.edit']);
//    Route::get('admin/gateway/delete/{id}', ['uses' => 'AdminGatewaysController@destroy', 'as' => 'admin.gateway.delete']);
//    Route::post('admin/gateway/update/{id}', ['uses' => 'AdminGatewaysController@update', 'as' => 'admin.gateway.update']);
//
//
//    Route::get('admin/local/gateways', ['uses' => 'AdminGatewaysController@localIndex', 'as' => 'admin.gateways.local']);
//    Route::get('admin/gateway/gateway/edit/{id}', ['uses' => 'AdminGatewaysController@localEdit', 'as' => 'admin.local.edit']);
//    Route::get('admin/local/gateway/delete/{id}', ['uses' => 'AdminGatewaysController@localDestroy', 'as' => 'admin.local.delete']);
//    Route::post('admin/local/gateway/update/{id}', ['uses' => 'AdminGatewaysController@localUpdate', 'as' => 'admin.local.update']);
//    Route::post('admin/local/gateway/create', ['uses' => 'AdminGatewaysController@localStore', 'as' => 'admin.local.store']);
//    Route::get('admin/new/gateway', ['uses' => 'AdminGatewaysController@localCreate', 'as' => 'admin.local.create']);
//
//
//
//
//    Route::get('admin/transfer/log', 'AdminController@fundlog')->name('adminFundlog');
//
//
//    Route::get('admin/kyc/identity', 'AdminKYCController@kyc')->name('adminKyc');
//    Route::get('admin/kyc/address', 'AdminKYCController@kyc2')->name('adminKyc2');
//    Route::get('admin/kyc/show/data/{id}', 'AdminKYCController@show')->name('adminKycShow');
//    Route::get('admin/kyc2/show/data/{id}', 'AdminKYCController@show2')->name('adminKyc2Show');
//    Route::get('admin/kyc/identity/verify/accept/{id}', 'AdminKYCController@KycAccept')->name('adminKycAccept');
//    Route::get('admin/kyc/identity/verify/reject/{id}', 'AdminKYCController@KycReject')->name('adminKycReject');
//    Route::get('admin/kyc/address/verify/accept/{id}', 'AdminKYCController@Kyc2Accept')->name('adminKyc2Accept');
//    Route::get('admin/kyc/address/verify/reject/{id}', 'AdminKYCController@Kyc2Reject')->name('adminKyc2Reject');
//
//    Route::get('admin/user/reviews', 'AdminController@review')->name('adminReview');
//    Route::get('admin/user/review/publish/{id}', 'AdminController@reviewPublish')->name('adminReview.accept');
//    Route::get('admin/user/review/un-publish/{id}', 'AdminController@reviewUnPublish')->name('adminReview.reject');
//
//
//    Route::get('admin/users/deposit', ['uses' => 'AdminController@userDeposits', 'as' => 'admin.users.deposit']);
//    Route::get('admin/users/deposit/local', ['uses' => 'AdminController@localDeposits', 'as' => 'admin.deposit.local']);
//    Route::get('admin/users/deposit/local/update/{id}', ['uses' => 'AdminController@localDepositsUpdate', 'as' => 'admin.deposit.update']);
//    Route::get('admin/users/deposit/local/fraud/{id}', ['uses' => 'AdminController@localDepositsFraud', 'as' => 'admin.deposit.fraud']);
//
//    Route::get('admin/users/withdraws', ['uses' => 'AdminController@userWithdraws', 'as' => 'admin.users.withdraws']);
//    Route::get('admin/users/withdraws/request', ['uses' => 'AdminController@userWithdrawsRequest', 'as' => 'admin.withdraws.request']);
//    Route::get('admin/users/withdraw/update/{id}', ['uses' => 'AdminController@payment', 'as' => 'admin.withdraw.update']);
//    Route::get('admin/users/withdraw/fraud/{id}', ['uses' => 'AdminController@withdrawFraud', 'as' => 'admin.withdraw.fraud']);
//
//    Route::get('admin/giftcards/view', 'AdminUsersController@Card')->name('admin.users.card');
//    Route::get('admin/giftcards/approve', 'AdminUsersController@Card2')->name('admin.users.card2');
//    Route::get('admin/giftcards/sale', 'AdminUsersController@Card3')->name('admin.users.card3');
//    Route::get('admin/giftcards/buy', 'AdminUsersController@Card4')->name('admin.users.card4');
//    Route::get('admin/giftcards/update/{id}', ['uses' => 'AdminController@cardApprove', 'as' => 'admin.card.update']);
//    Route::get('admin/giftcards/fraud/{id}', ['uses' => 'AdminController@cardFraud', 'as' => 'admin.card.fraud']);
//    Route::get('admin/giftcards/update2/{id}', ['uses' => 'AdminController@cardApprove2', 'as' => 'admin.card2.update']);
//    Route::get('admin/giftcards/fraud2/{id}', ['uses' => 'AdminController@cardFraud2', 'as' => 'admin.card2.fraud']);
//
//
//    Route::get('admin/crypto/view', 'AdminUsersController@Crypto')->name('admin.users.crypto');
//    Route::get('admin/crypto/approve', 'AdminUsersController@Crypto2')->name('admin.users.crypto2');
//    Route::get('admin/crypto/update2/{id}', ['uses' => 'AdminController@cryptoApprove2', 'as' => 'admin.crypto2.update']);
//    Route::get('admin/crypto/sale', 'AdminUsersController@Crypto3')->name('admin.users.crypto3');
//    Route::get('admin/crypto/buy', 'AdminUsersController@Crypto4')->name('admin.users.crypto4');
//    Route::get('admin/crypto/update2/{id}', ['uses' => 'AdminController@cryptoApprove2', 'as' => 'admin.crypto2.update']);
//    Route::get('admin/crypto/fraud2/{id}', ['uses' => 'AdminController@cryptoFraud2', 'as' => 'admin.crypto2.fraud']);
//
//
//
//    Route::get('admin/invest/style', 'AdminStyleController@index')->name('adminStyle');
//    Route::post('admin/invest/style', 'AdminStyleController@store')->name('adminStyle.post');
//    Route::get('admin/invest/style/edit/{id}', 'AdminStyleController@edit')->name('adminStyle.edit');
//    Route::post('admin/invest/style/update/{id}', 'AdminStyleController@update')->name('adminStyle.update');
//    Route::get('admin/invest/style/delete/{id}', 'AdminStyleController@destroy')->name('adminStyle.delete');
//
//    Route::get('admin/invest/plan', 'AdminInvestController@index')->name('adminInvest');
//    Route::get('admin/invest/plan/create', 'AdminInvestController@create')->name('adminInvest.create');
//    Route::post('admin/invest/plan', 'AdminInvestController@store')->name('adminInvest.post');
//    Route::get('admin/invest/plan/edit/{id}', 'AdminInvestController@edit')->name('adminInvest.edit');
//    Route::post('admin/invest/plan/update/{id}', 'AdminInvestController@update')->name('adminInvest.update');
//    Route::get('admin/invest/plan/delete/{id}', 'AdminInvestController@destroy')->name('adminInvest.delete');
//    Route::get('admin/users/invest', 'AdminInvestController@index2')->name('adminusersinvest');
//
//
//
//    Route::get('admin/loan/plan', 'AdminloanController@index')->name('adminloan');
//    Route::get('admin/users/loan', 'AdminloanController@usersloan2')->name('adminactiveloan');
//    Route::get('admin/loan/users', 'AdminloanController@usersloan')->name('adminusersloan');
//    Route::get('admin/loan/approve/{id}', 'AdminloanController@approveloan')->name('adminloanapprove');
//    Route::get('admin/loan/disburse', 'AdminloanController@disburseloan')->name('adminloandisburse');
//    Route::post('admin/loan/disburse2/{id}', 'AdminloanController@disburseloan2')->name('adminloandisburse2');
//    Route::get('admin/loan/reject/{id}', 'AdminloanController@rejectloan')->name('adminloanreject');
//    Route::get('admin/loan/plan/create', 'AdminloanController@create')->name('adminloan.create');
//    Route::post('admin/loan/store', 'AdminloanController@store')->name('adminloan.post');
//    Route::get('admin/loan/plan/edit/{id}', 'AdminloanController@edit')->name('adminloan.edit');
//    Route::post('admin/loan/plan/update/{id}', 'AdminloanController@update')->name('adminloan.update');
//    Route::get('admin/loan/plan/delete/{id}', 'AdminloanController@destroy')->name('adminloan.delete');
//
//
//
//
//    Route::get('admin/coin/plan', 'AdminInvestController@coinindex')->name('adminCoin');
//    Route::get('admin/share/unit/create', 'AdminInvestController@coincreate')->name('adminCoin2');
//    Route::get('admin/share/unit/sales', 'AdminInvestController@coinsale')->name('adminCoin3');
//    Route::get('admin/share/unit/sale', 'AdminInvestController@coinsale2')->name('adminCoin4');
//    Route::post('admin/coin/store', 'AdminInvestController@coinstore')->name('adminCoin.post');
//    Route::get('admin/coin/edit/{id}', 'AdminInvestController@coinedit')->name('adminCoin.edit');
//    Route::post('admin/coin/update/{id}', 'AdminInvestController@coinupdate')->name('adminCoin.update');
//    Route::get('admin/coin/delete/{id}', 'AdminInvestController@coindestroy')->name('adminCoin.delete');
//
//
//    Route::get('admin/faqs/index', 'AdminFAQController@index')->name('adminFAQ');
//    Route::get('admin/faq/edit/{id}', 'AdminFAQController@edit')->name('adminFAQEdit');
//    Route::post('admin/faq/update/{id}', 'AdminFAQController@update')->name('adminFAQUpdate');
//    Route::post('admin/faq/create', 'AdminFAQController@store')->name('adminFAQStore');
//    Route::get('admin/faq/delete/{id}', 'AdminFAQController@destroy')->name('adminFAQDestroy');
//
//    Route::get('admin/user/supports', 'AdminSupportController@open')->name('adminSupports.open');
//    Route::get('admin/user/supports/close', 'AdminSupportController@index')->name('adminSupports.index');
//    Route::get('admin/user/support/ticket/view/{ticket}', 'AdminSupportController@show')->name('adminSupport.view');
//    Route::post('admin/user/support/create/{ticket}', 'AdminSupportController@store')->name('adminSupport.post');
//
//
//    Route::get('admin/system/settings', 'SettingsController@index')->name('websiteSettings');
//    Route::get('admin/feature/settings', 'SettingsController@menu')->name('menuSettings');
//    Route::get('admin/user/settings', 'SettingsController@user')->name('userSettings');
//    Route::get('admin/earning/settings', 'SettingsController@earnings')->name('earningSettings');
//    Route::post('admin/website/settings/general/update/{id}', 'SettingsController@generalSettings')->name('generalSettings');
//    Route::post('admin/website/settings/features/update/{id}', 'SettingsController@featuresSettings')->name('featuresSettings');
//    Route::post('admin/website/settings/users/update/{id}', 'SettingsController@usersSettings')->name('usersSettings');
//    Route::post('admin/website/settings/earnings/update/{id}', 'SettingsController@earningsSettings')->name('earningsSettings');
//
});
