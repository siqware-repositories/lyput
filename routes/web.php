<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
/*home*/
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware'=>['web','is_super_admin_admin_user']],function (){
    Route::get('/product', 'ProductController@index')->name('product.index');
    Route::post('/product-list', 'ProductController@list')->name('product.list');
    Route::post('/product-detail-list/{id}', 'ProductController@detail_list')->name('product.detail.list');
    Route::post('/product-search-from-stock/{id}', 'ProductController@search_from_stock')->name('product.search.from.stock');
});
Route::group(['middleware' => ['web', 'is_super_admin_admin']], function () {
    /*home [/]*/
    Route::get('/', function () {
        return view('index');
    });
    /*media*/
    Route::get('/media', function () {
        return view('media');
    })->name('media');

    /*User Controller*/
    Route::resource('/user', 'UserController');
    Route::post('/user-list', 'UserController@list')->name('user.list');
    /*Product Controller*/
    Route::get('/product/{id}/edit', 'ProductController@edit')->name('product.edit');
    Route::delete('/product/{id}', 'ProductController@destroy')->name('product.destroy');
    Route::post('/product', 'ProductController@store')->name('product.store');
    Route::put('/product/{id}', 'ProductController@update')->name('product.update');
    Route::post('/product-update', 'ProductController@updateProduct')->name('product._update');
    Route::get('/product/create', 'ProductController@create')->name('product.create');
    Route::get('/product-stock-create', 'ProductController@stock_create')->name('stock.create');
    Route::post('/product-stock-store', 'ProductController@stock_store')->name('stock.store');
    Route::get('/product-create-group', 'ProductController@create_group')->name('product.create.group');
    Route::post('/product-store-group', 'ProductController@store_group')->name('product.store.group');
    Route::get('/product-check-out-stock', 'ProductController@check_out_stock')->name('product.check.out.stock');
    /*Invoice Controller*/
    Route::resource('/invoice', 'InvoiceController');
    Route::post('/invoice-stock-list', 'InvoiceController@list')->name('invoice.stock.list');
    Route::post('/invoice-selling-report', 'InvoiceController@selling_report')->name('invoice.selling.report');
    Route::post('/invoice-store-group', 'InvoiceController@store_group')->name('invoice.store.group');
    Route::post('/invoice-stock-list-group', 'InvoiceController@list_group')->name('invoice.stock.list.group');
    Route::post('/invoice-stock-item/{id}', 'InvoiceController@item')->name('invoice.stock.item');
    Route::post('/invoice-stock-item-playlist/{id}', 'InvoiceController@item_playlist')->name('invoice.stock.item.playlist');
    /*Account Type Controller*/
    Route::resource('/account-type', 'AccountTypeController');
    Route::post('/account-type-search', 'AccountTypeController@search_select2')->name('account.type.search');
    /*Account Controller*/
    Route::resource('/account', 'AccountController');
    Route::get('/account-deposit-create', 'AccountController@deposit_create')->name('deposit.create');
    Route::get('/account-return-create', 'AccountController@return_create')->name('account.return.create');
    Route::post('/account-return-store', 'AccountController@ap_return_store')->name('account.return.store');
    Route::post('/account-deposit-store', 'AccountController@deposit_store')->name('deposit.store');
    Route::post('/account-list', 'AccountController@list')->name('account.list');
    Route::get('/account-show-history', 'AccountController@show_history')->name('account.show.history');
    Route::post('/account-show-history-data/{id}', 'AccountController@show_history_data')->name('account.show.history.data');
    Route::post('/account-dashboard-data', 'AccountController@dashboard_data')->name('account.dashboard.data');
    /*Employee Controller*/
    Route::resource('/employee', 'EmployeeController');
    Route::post('/employee-list', 'EmployeeController@list')->name('employee.list');
    Route::post('/employee-salary-list/{id}', 'EmployeeController@salary_history_list')->name('employee.salary.list');
    Route::get('/employee-salary-create', 'EmployeeController@salary_create')->name('employee.salary.create');
    Route::post('/employee-salary-store', 'EmployeeController@salary_store')->name('employee.salary.store');
    Route::post('/employee-salary-history', 'EmployeeController@salary_history')->name('employee.salary.history');
    /*Budget Controller*/
    Route::resource('/budget', 'BudgetController');
    Route::get('/budget-income-create', 'BudgetController@create')->name('income.create');
    Route::delete('/budget-income-destroy/{id}', 'BudgetController@incomeDestroy')->name('income.destroy');
    Route::delete('/budget-expense-destroy/{id}', 'BudgetController@expenseDestroy')->name('expense.destroy');
    Route::get('/budget-expense-create', 'BudgetController@expense_create')->name('expense.create');
    Route::post('/budget-income-store', 'BudgetController@store')->name('income.store');
    Route::post('/budget-expense-store', 'BudgetController@expense_store')->name('expense.store');
    Route::post('/budget-expense-list', 'BudgetController@expense_list')->name('expense.list');
    Route::post('/budget-income-list', 'BudgetController@income_list')->name('income.list');
    /*APAC Controller*/
    Route::resource('/ap-ar', 'APARController');
    Route::get('/ap-ar-ap-create', 'APARController@ap_create')->name('ap.create');
    Route::get('/ap-ar-ar-create', 'APARController@ar_create')->name('ar.create');
    Route::post('/ap-ar-ap-store', 'APARController@ap_store')->name('ap.store');
    Route::post('/ap-ar-ar-store', 'APARController@ar_store')->name('ar.store');

    Route::post('/ap-ar-ap-list', 'APARController@ap_list')->name('ap.list');
    Route::post('/ap-ar-ar-list', 'APARController@ar_list')->name('ar.list');

    Route::get('/ap-ar-ap-invoice-show/{id}', 'APARController@ap_invoice_show')->name('ap.invoice.show');
    Route::get('/ap-ar-ar-invoice-show/{id}', 'APARController@ar_invoice_show')->name('ar.invoice.show');

    Route::delete('/ap-ar-ap-destroy/{id}', 'APARController@ap_destroy')->name('ap.destroy');
    Route::delete('/ap-ar-ar-destroy/{id}', 'APARController@ar_destroy')->name('ar.destroy');

    Route::get('/ap-ar-ap-return-create/{id}', 'APARController@ap_return_create')->name('ap.return.create');
    Route::get('/ap-ar-ar-return-create/{id}', 'APARController@ar_return_create')->name('ar.return.create');

    Route::post('/ap-ar-ap-return-store/{id}', 'APARController@ap_return_store')->name('ap.return.store');
    Route::post('/ap-ar-ar-return-store/{id}', 'APARController@ar_return_store')->name('ar.return.store');
    /*Playlist Controller*/
    Route::resource('/playlist', 'PlaylistController');
    Route::post('/playlist-search-stock', 'PlaylistController@search_stock')->name('search.stock');
    Route::post('/playlist-search-stock-product', 'PlaylistController@search_stock_product')->name('search.stock.product');
    Route::post('/playlist-item-create/{id}', 'PlaylistController@item_create')->name('playlist.item.create');
    /*Playlist Controller*/
    Route::post('/bundle-search', 'BundleController@search_playlist')->name('bundle.search');
    /*ExcelImport Class*/
    Route::resource('/excel-import', 'ExcelImportController');
    /*Report Controller*/
    Route::resource('/report','ReportController');
    Route::post('/report-stock-invoice','ReportController@stock_inv')->name('report.stock.invoice');
    Route::post('/report-invoice','ReportController@report_inv')->name('report.invoice');
    Route::get('/report-stock-invoice-show/{id}','ReportController@stock_invoice_show')->name('report.stock.invoice.show');
    Route::get('/report-invoice-show/{id}','ReportController@report_invoice_show')->name('report.invoice.show');
});

