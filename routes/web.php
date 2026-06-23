<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Trash\RoleTrashController;
use App\Http\Controllers\Trash\UserTrashController;
use App\Http\Controllers\WebsiteSettingsController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Trash\ModuleTrashController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\BondhokController;
use App\Http\Controllers\Admin\KarigorController;
use App\Http\Controllers\Admin\SellController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RepairController;
use App\Http\Controllers\Admin\GoldController;
use App\Http\Controllers\Trash\PermissionTrashController;
use App\Http\Controllers\Admin\ProductPriceController;



Auth::routes();


/*
|--------------------------------------------------------------------------
| Backend
|--------------------------------------------------------------------------
*/

//Axios Call For Product
Route::get('get-products/{category_id}', [PurchaseController::class, 'getProduct']);
Route::get('get-products-shop/{category_id}', [PurchaseController::class, 'getProductShop']);

//Ajax Call For Calculation
Route::post('/calculate', [PurchaseController::class, 'calculate'])->name('calculate');
Route::post('/calculate-total', [PurchaseController::class, 'calculateTotal'])->name('calculate.total');

Route::prefix('')->group(function () {

    Route::get('/', [AdminLoginController::class, 'loginPage'])->name('admin.loginpage');
    Route::post('/', [AdminLoginController::class, 'login'])->name('admin.login');
});

Route::prefix('admin')->middleware('auth', 'is_admin')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminLoginController::class, 'adminLogout'])->name('admin.logout');

    //module start
    Route::get('module/trash', [ModuleTrashController::class, 'trash'])
        ->name('module.trash');
    Route::get('module/{module_slug}/restore', [ModuleTrashController::class, 'restore'])
        ->name('module.restore');
    Route::delete('module/{module_slug}/forcedelete', [ModuleTrashController::class, 'forceDelete'])
        ->name('module.forcedelete');
    Route::resource('module', ModuleController::class);
    //module end

    //permission start
    Route::get('permission/trash', [PermissionTrashController::class, 'trash'])
        ->name('permission.trash');
    Route::get('permission/{permission_slug}/restore', [PermissionTrashController::class, 'restore'])
        ->name('permission.restore');
    Route::delete('permission/{permission_slug}/forcedelete', [PermissionTrashController::class, 'forceDelete'])
        ->name('permission.forcedelete');
    Route::resource('permission', PermissionController::class);
    //permission end

    //role start
    Route::get('role/trash', [RoleTrashController::class, 'trash'])
        ->name('role.trash');
    Route::get('role/{role_slug}/restore', [RoleTrashController::class, 'restore'])
        ->name('role.restore');
    Route::delete('role/{role_slug}/forcedelete', [RoleTrashController::class, 'forceDelete'])
        ->name('role.forcedelete');
    Route::resource('role', RoleController::class);
    //role end

    //User Start
    Route::get('/users/trash', [UserTrashController::class, 'trash'])->name('users.trash');
    Route::get('/users/restore/{id}', [UserTrashController::class, 'restore'])
        ->name('users.restore');
    Route::delete('/users/forcedelete/{id}', [UserTrashController::class, 'forceDelete'])
        ->name('users.forcedelete');
    // //Ajax Call For User Active
    // Route::get('check/user/is_active/{user_id}', [UserController::class, 'checkActive'])
    // ->name('user.is_active.ajax');
    Route::resource('/users', UserController::class);
    Route::post('/users/new-user', [UserController::class, 'new_user'])->name('new.user.store');
    //User End

    //Logo start
    Route::post('/store-logo', [WebsiteSettingsController::class, 'tech_web_store_logo'])->name('store.logo');
    //Logo end

    //links start
    Route::post('/store-links', [WebsiteSettingsController::class, 'tech_web_store_links'])->name('store.links');
    //Links end

    //footer start
    Route::post('/store-footer', [WebsiteSettingsController::class, 'tech_web_store_footer'])->name('store.footer');

    //footer end

    //banner start
    Route::post('/store-main-banner', [WebsiteSettingsController::class, 'tech_web_store_main_banner'])->name('store.main.banner');
    Route::get('/edit-main-banner/{id}', [WebsiteSettingsController::class, 'tech_web_edit_main_banner'])->name('edit.main.banner');
    Route::post('/update-main-banner/{id}', [WebsiteSettingsController::class, 'tech_web_update_main_banner'])->name('update.main.banner');
    //banner end


    //general settings start
    Route::get('/general-settings', [GeneralController::class, 'tech_web_general_settings'])->name('general.settings');
    //general settings end

    //profile settings start
    Route::get('/profile-settings', [GeneralController::class, 'tech_web_profile_settings'])->name('profile.settings');
    Route::get('/profileimage-settings', [GeneralController::class, 'tech_web_profileimage_settings'])->name('profileimage.settings');
    Route::post('/store-profile', [GeneralController::class, 'tech_web_store_profile'])->name('admin.store.profile');
    Route::post('/update-profile', [GeneralController::class, 'tech_web_update_profile'])->name('admin.update.profile');
    //profile settings end

    //product category start
    Route::resource('productcategory', ProductCategoryController::class);
    //product category end

    //product start
    Route::resource('product', ProductController::class);
    //product endv

    //product price start
    Route::resource('product-price', ProductPriceController::class);
    //product price end

    //purchase start
    Route::resource('purchase', PurchaseController::class);
    Route::get('purchases/due', [PurchaseController::class, 'due'])->name('purchase.due');
    Route::get('purchases/warehouse', [PurchaseController::class, 'warehouse_index'])->name('purchase.warehouse');
    Route::get('purchases/shop', [PurchaseController::class, 'shop_index'])->name('purchase.shop');

    //purchase end

    //***Bondhok***//

    // Products
    Route::get('/bondhok/products', [BondhokController::class, 'b_product_index'])->name('bondhok.product');
    Route::get('/bondhok/products/edit/{id}', [BondhokController::class, 'b_product_edit'])->name('bondhok.product.edit');
    Route::post('/bondhok/products/store', [BondhokController::class, 'b_product_store'])->name('bondhok.product.store');
    Route::post('/bondhok/products/update', [BondhokController::class, 'b_product_update'])->name('bondhok.product.update');
    Route::post('/bondhok/products/delete', [BondhokController::class, 'b_product_delete'])->name('bondhok.product.delete');

    // Customers
    Route::get('/bondhok/customer', [BondhokController::class, 'b_customer_index'])->name('bondhok.customer');
    Route::post('/bondhok/customer/store', [BondhokController::class, 'b_customer_store'])->name('bondhok.customer.store');
    Route::post('/bondhok/customer/update', [BondhokController::class, 'b_customer_update'])->name('bondhok.customer.update');
    Route::get('/bondhok/customer/edit/{id}', [BondhokController::class, 'b_customer_edit'])->name('bondhok.customer.edit');
    Route::post('/bondhok/customer/delete', [BondhokController::class, 'b_customer_delete'])->name('bondhok.customer.delete');

    // Bondhok
    Route::get('/bondhok', [BondhokController::class, 'bondhok_index'])->name('bondhok');
    Route::post('/admin/bondhok/store', [BondhokController::class, 'bondhok_store'])->name('admin.bondhok.store');
    Route::post('/bondhok/delete', [BondhokController::class, 'bondhok_delete'])->name('bondhok.delete');
    Route::post('/bondhok/paid/update', [BondhokController::class, 'bondhok_paid'])->name('bondhok.paid.update');

    //***Bondhok   END ***//

    // Sell
    Route::get('/sells', [SellController::class, 'sell_index'])->name('sells.index');
    Route::post('/sells/store', [SellController::class, 'sell_store'])->name('sells.store');
    Route::post('/sells/update', [SellController::class, 'sell_update'])->name('sells.update');
    Route::get('/sells/edit/{id}', [SellController::class, 'sells_edit'])->name('sells.edit');


    // Stock
    Route::get('/stocks/create', [StockController::class, 'stock_create'])->name('stock.create');
    Route::post('/stocks/store', [StockController::class, 'stock_store'])->name('stock.store');
    Route::post('/stocks/update', [StockController::class, 'stock_update'])->name('stock.update');
    Route::get('/stocks/edit/{id}', [StockController::class, 'stock_edit'])->name('stock.edit');
    Route::get('/stocks', [StockController::class, 'stock_index'])->name('stock.index');
    Route::get('/shop/stocks', [StockController::class, 'shop_stock_index'])->name('shop.stock');
    Route::get('/warehouse/stocks', [StockController::class, 'warehouse_stock_index'])->name('warehouse.stock');
    Route::get('/hold/stocks', [StockController::class, 'hold_stock_index'])->name('hold.stock');
    Route::post('/stock/delete', [StockController::class, 'stock_delete'])->name('stock.delete');


    // Karigor Product
    Route::get('/karigor/stock', [KarigorController::class, 'karigor_stock'])->name('karigor.stock');
    Route::post('/karigor/stock/store', [KarigorController::class, 'karigor_stock_store'])->name('karigor.stock.store');
    Route::get('/karigor/product', [KarigorController::class, 'karigor_product'])->name('karigor.product');
    Route::post('/karigor/product/store', [KarigorController::class, 'karigor_product_store'])->name('karigor.product.store');
    Route::post('/karigor/product/status/update', [KarigorController::class, 'karigor_product_status_update'])->name('karigor.product.status.update');
    Route::get('/karigor/product/{id}', [KarigorController::class, 'karigor_product_edit'])->name('karigor.product.edit');
    Route::post('/karigor/product/update', [KarigorController::class, 'karigor_product_update'])->name('karigor.product.update');
    Route::get('/karigor', [KarigorController::class, 'karigor_index'])->name('karigor.index');
    Route::get('/karigor/{id}', [KarigorController::class, 'karigor_edit'])->name('karigor.edit');
    Route::post('/karigor/update', [KarigorController::class, 'karigor_update'])->name('karigor.update');



    //Report
    Route::get('/sells/report/index', [ReportController::class, 'sells_report_index'])->name('sells.report.index');
    Route::get('/purchases/report/index', [ReportController::class, 'purchases_report_index'])->name('purchases.report.index');
    Route::get('/bondhok/report/index', [ReportController::class, 'bondhok_report_index'])->name('bondhok.report.index');

    // Gold Convert Controller
    Route::post('/convert-to-gram', [GoldController::class, 'convertGram'])->name('convert.to.gram');
    Route::post('/convert-to-gold', [GoldController::class, 'convertGold'])->name('convert.to.gold');
    Route::post('/convert-to-both', [GoldController::class, 'convertBoth'])->name('convert.to.both');
    Route::post('/convert-number-to-bangla', [GoldController::class, 'convertNumberToBangla'])->name('convert.number.to.bangla');



    //Repair
    Route::get('/repair/index', [RepairController::class, 'repair_index'])->name('repair.index');
    Route::get('/repair/manage', [RepairController::class, 'repair_manage'])->name('repair.manage');
    Route::post('/repair/store', [RepairController::class, 'repair_store'])->name('repair.store');
    Route::post('/repair/manage/store', [RepairController::class, 'repair_manage_store'])->name('repair.manage.store');
    Route::post('/repair/karigor/store', [RepairController::class, 'repair_karigor_store'])->name('repair.karigor.store');
    Route::get('/repair/manage/{id}', [RepairController::class, 'repair_manage_edit'])->name('repair.manage.edit');

    // get karigor stock
    Route::post('/karigor/stock', [GoldController::class, 'karigor_stock'])->name('karigor.stock');
});
