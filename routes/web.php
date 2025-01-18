<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqsController as AdminFaqsController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\NewsletterMailController as AdminNewsletterMailController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Front\ContactController as FrontContactController;
use App\Http\Controllers\Front\PagesController as FrontPagesController;

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

// Route::get('/', function () {
//     return view('home');
// })->name('front.home');

Auth::routes();

// Admin Auth Route start
Route::get('/admin/login', [AdminAuthController::class, 'loginGet'])->name('admin.login.get');
Route::post('/admin/login/save', [AdminAuthController::class, 'loginSave'])->name('admin.login.save');

Route::get('/admin/password/forgot', [AdminAuthController::class, 'passwordForgotGet'])->name('admin.password.forgot.get');
Route::post('admin/password/forgot/save', [AdminAuthController::class, 'passwordForgotSave'])->name('admin.password.forgot.save');

Route::get('/admin/password/reset/{token}', [AdminAuthController::class, 'passwordResetGet'])->name('admin.password.reset.get');
Route::post('/admin/password/reset/save', [AdminAuthController::class, 'passwordResetSave'])->name('admin.password.reset.save');
// Admin Auth Route end

// Admin route start
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['is_auth', 'is_user_active', 'is_user_verified']], function () {

    // dashboard route start
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    // dashboard route end

    // profile setting Modlue start
    Route::get('/profile/setting/password', [AdminProfileController::class, 'profileSettingsPasswordIndex'])->name('admin.profile.settings.password.index');
    Route::post('/profile/setting/password/save', [AdminProfileController::class, 'profileSettingsPasswordSave'])->name('admin.profile.settings.password.save');

    Route::get('/profile/setting', [AdminProfileController::class, 'profileSettingIndex'])->name('admin.profile.setting.index');
    Route::post('/profil/esetting/save', [AdminProfileController::class, 'profileSettingSave'])->name('admin.profile.setting.save');
    // profile setting Modlue end

    // contact us msg Modlue start
    Route::get('/contact/messages', [AdminContactController::class, 'index'])->name('admin.contact.messages.index');
    Route::get('/contact/messages/view/{id}', [AdminContactController::class, 'view'])->name('admin.contact.messages.view');
    Route::post('/contact/messages/delete/{id}', [AdminContactController::class, 'delete'])->name('admin.contact.messages.delete');
    // contact us msg Modlue end

    // contact settings Modlue start
    Route::get('/contact/settings', [AdminContactController::class, 'indexContactSettings'])->name('admin.contact.settings.index');
    Route::post('/contact/settings/save', [AdminContactController::class, 'saveContactSettings'])->name('admin.contact.settings.save');
    // contact settings Modlue end

    // site settings Modlue start
    Route::get('/site/settings', [AdminSiteSettingController::class, 'index'])->name('admin.site.settings.index');
    Route::post('/site/settings/save', [AdminSiteSettingController::class, 'save'])->name('admin.site.settings.save');
    // site settings Modlue end

    // Blogs Modlue start
    Route::any('/blogs', [AdminBlogController::class, 'index'])->name('admin.blogs.index');
    Route::get('/blogs/create', [AdminBlogController::class, 'create'])->name('admin.blogs.create');
    Route::post('/blogs/save', [AdminBlogController::class, 'save'])->name('admin.blogs.save');
    Route::get('/blogs/view/{id}', [AdminBlogController::class, 'view'])->name('admin.blogs.view');
    Route::get('/blogs/edit/{id}', [AdminBlogController::class, 'edit'])->name('admin.blogs.edit');
    Route::put('/blogs/update', [AdminBlogController::class, 'update'])->name('admin.blogs.update');
    Route::post('/blogs/status/toggle', [AdminBlogController::class, 'statusToggle'])->name('admin.blogs.status.toggle');
    Route::post('/blogs/delete/{id}', [AdminBlogController::class, 'delete'])->name('admin.blogs.delete');
    // Blogs Modlue end

    // Faqs Modlue start
    Route::any('/faqs', [AdminFaqsController::class, 'index'])->name('admin.faqs.index');
    Route::get('/faqs/create', [AdminFaqsController::class, 'create'])->name('admin.faqs.create');
    Route::post('/faqs/save', [AdminFaqsController::class, 'save'])->name('admin.faqs.save');
    Route::get('/faqs/view/{id}', [AdminFaqsController::class, 'view'])->name('admin.faqs.view');
    Route::get('/faqs/edit/{id}', [AdminFaqsController::class, 'edit'])->name('admin.faqs.edit');
    Route::put('/faqs/update', [AdminFaqsController::class, 'update'])->name('admin.faqs.update');
    Route::post('/faqs/status/toggle', [AdminFaqsController::class, 'statusToggle'])->name('admin.faqs.status.toggle');
    Route::post('/faqs/delete/{id}', [AdminFaqsController::class, 'delete'])->name('admin.faqs.delete');
    // Faqs Modlue end

    // category Modlue start
    Route::any('/categorys', [AdminCategoryController::class, 'index'])->name('admin.categorys.index');
    Route::get('/categorys/create', [AdminCategoryController::class, 'create'])->name('admin.categorys.create');
    Route::post('/categorys/save', [AdminCategoryController::class, 'save'])->name('admin.categorys.save');
    Route::get('/categorys/view/{id}', [AdminCategoryController::class, 'view'])->name('admin.categorys.view');
    Route::get('/categorys/edit/{id}', [AdminCategoryController::class, 'edit'])->name('admin.categorys.edit');
    Route::put('/categorys/update', [AdminCategoryController::class, 'update'])->name('admin.categorys.update');
    Route::post('/categorys/status/toggle', [AdminCategoryController::class, 'statusToggle'])->name('admin.categorys.status.toggle');
    Route::post('/categorys/delete/{id}', [AdminCategoryController::class, 'delete'])->name('admin.categorys.delete');
    // category Modlue end

    // tags Modlue start
    Route::any('/tags', [AdminTagController::class, 'index'])->name('admin.tags.index');
    Route::get('/tags/create', [AdminTagController::class, 'create'])->name('admin.tags.create');
    Route::post('/tags/save', [AdminTagController::class, 'save'])->name('admin.tags.save');
    Route::get('/tags/view/{id}', [AdminTagController::class, 'view'])->name('admin.tags.view');
    Route::get('/tags/edit/{id}', [AdminTagController::class, 'edit'])->name('admin.tags.edit');
    Route::put('/tags/update', [AdminTagController::class, 'update'])->name('admin.tags.update');
    Route::post('/tags/status/toggle', [AdminTagController::class, 'statusToggle'])->name('admin.tags.status.toggle');
    Route::post('/tags/delete/{id}', [AdminTagController::class, 'delete'])->name('admin.tags.delete');
    // tags Modlue end

    // comments Modlue start
    Route::any('/comments', [AdminCommentController::class, 'index'])->name('admin.comments.index');
    Route::any('/comments/blog/{id}', [AdminCommentController::class, 'indexBlog'])->name('admin.comments.index.blog');
    Route::get('/comments/create', [AdminCommentController::class, 'create'])->name('admin.comments.create');
    Route::post('/comments/save', [AdminCommentController::class, 'save'])->name('admin.comments.save');
    Route::get('/comments/view/{id}', [AdminCommentController::class, 'view'])->name('admin.comments.view');
    Route::get('/comments/edit/{id}', [AdminCommentController::class, 'edit'])->name('admin.comments.edit');
    Route::put('/comments/update', [AdminCommentController::class, 'update'])->name('admin.comments.update');
    Route::post('/comments/status/toggle', [AdminCommentController::class, 'statusToggle'])->name('admin.comments.status.toggle');
    Route::post('/comments/delete/{id}', [AdminCommentController::class, 'delete'])->name('admin.comments.delete');
    // comments Modlue end

    // contact us msg Modlue start
    Route::get('/newsletters', [AdminNewsletterController::class, 'index'])->name('admin.newsletters.index');
    Route::post('/newsletters/delete/{id}', [AdminNewsletterController::class, 'delete'])->name('admin.newsletters.delete');
    Route::post('/newsletters/status/toggle', [AdminNewsletterController::class, 'statusToggle'])->name('admin.newsletters.status.toggle');
    // contact us msg Modlue end

    // newslettermails Modlue start
    Route::any('/newslettermails', [AdminNewsletterMailController::class, 'index'])->name('admin.newslettermails.index');
    Route::get('/newslettermails/create', [AdminNewsletterMailController::class, 'create'])->name('admin.newslettermails.create');
    Route::post('/newslettermails/save', [AdminNewsletterMailController::class, 'save'])->name('admin.newslettermails.save');
    Route::get('/newslettermails/view/{id}', [AdminNewsletterMailController::class, 'view'])->name('admin.newslettermails.view');
    Route::get('/newslettermails/edit/{id}', [AdminNewsletterMailController::class, 'edit'])->name('admin.newslettermails.edit');
    Route::put('/newslettermails/update', [AdminNewsletterMailController::class, 'update'])->name('admin.newslettermails.update');
    Route::post('/newslettermails/delete/{id}', [AdminNewsletterMailController::class, 'delete'])->name('admin.newslettermails.delete');
    Route::get('/newslettermails/sendmail/{id}', [AdminNewsletterMailController::class, 'sendmail'])->name('admin.newslettermails.sendmail');
    // newslettermails Modlue end

    // User Modlue start
    Route::any('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users/save', [AdminUserController::class, 'save'])->name('admin.users.save');
    Route::get('/users/view/{id}', [AdminUserController::class, 'view'])->name('admin.users.view');
    Route::get('/users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/update', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/status/toggle', [AdminUserController::class, 'statusToggle'])->name('admin.users.status.toggle');
    Route::post('/users/verify/toggle', [AdminUserController::class, 'verifyToggle'])->name('admin.users.verify.toggle');
    Route::post('/users/delete/{id}', [AdminUserController::class, 'delete'])->name('admin.users.delete');
    Route::get('/users/referrals/{id}', [AdminUserController::class, 'userReferrals'])->name('admin.users.referrals');
    // User Modlue end

});
// Admin route end


Route::group(['namespace' => 'Front'], function () {

    Route::get('/', [FrontPagesController::class, 'home'])->name('front.home');
    Route::get('/about', [FrontPagesController::class, 'about'])->name('front.about');
    Route::get('/services', [FrontPagesController::class, 'services'])->name('front.services');

    Route::get('/contact', [FrontContactController::class, 'contact'])->name('front.contact');
    Route::post('/contact/message/save', [FrontContactController::class, 'contactMessageSave'])->name('front.contact.message.save');

    Route::post('/newsletter/save', [FrontPagesController::class, 'newsletterSave'])->name('front.newsletter.save');
    Route::get('/newsletter/unsubscribe/{email}', [FrontPagesController::class, 'newsletterUnSubscribe'])->name('front.newsletter.unsubscribe');

    Route::get('/privacy_policy', [FrontPagesController::class, 'privacy_policy'])->name('front.privacy_policy');
    Route::get('/term_and_condition', [FrontPagesController::class, 'term_and_condition'])->name('front.term_and_condition');
});
