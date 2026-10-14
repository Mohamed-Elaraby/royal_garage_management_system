<?php


use App\Models\Check;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleElement;
use App\Models\SaleOrderProducts;

Route::get('test', function () {
    return view('ws_test');
});



Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

    // Authentication Routes

    Auth::routes(
        [
            'register' => false, // Registration Routes...
            'reset' => false,   // Password Reset Routes...
            'verify' => false, // Email Verification Routes...
        ]
    );

    // Dashboard Route
    Route::group(['middleware' => ['auth'], 'namespace' => 'Admin'], function () {
        Route::get('/', 'DashboardController@index') -> name('dashboard');
    });

    Route::prefix('admin')->middleware('auth')->name('admin.')->namespace('Admin')->group(function (){
        // Collection Routes


        Route::get('images/{check_id}/{type}', 'CheckController@images')->name('check.images');
        Route::get('createDeviceReport/{check_id}/{check_number}', 'CheckController@create_device_report')->name('check.createDeviceReport');
        Route::post('storeDeviceReport', 'CheckController@store_device_report')->name('check.storeDeviceReport');
        Route::post('signature', 'CheckController@signature')->name('check.signature');
        Route::get('client_signature/{check_id}/{check_number}', 'CheckController@client_signature')->name('check.clientSignature');
        Route::post('editCheckStatus', 'CheckController@onlyEditCheckStatus')->name('check.onlyEditCheckStatus');
        Route::get('receipt/{check_id}', 'CheckController@receipt')->name('check.receipt');
        Route::get('receipt_print', 'CheckController@receipt_print')->name('check.receipt_print');
        Route::get('receipt_download', 'CheckController@receipt_download_without_last_page')->name('check.receipt_download');
        Route::post('getCarSizesByAjax', 'CarController@getCarSizesByAjax')->name('car.getCarSizesByAjax');
        Route::post('getCarDevCodeAndEnginesByAjax', 'CarController@getCarDevCodeAndEnginesByAjax')->name('car.getCarDevCodeAndEnginesByAjax');
        Route::get('managementNotes/{check_id}', 'CheckController@managementNotes')->name('check.managementNotes');
        Route::post('managementNotesUpdate/{check_id}', 'CheckController@managementNotesUpdate')->name('check.managementNotesUpdate');
        Route::get('selectBranch', 'CheckController@selectBranch')->name('selectBranch');
        Route::get('generate_pdf/{check_id}', 'CheckController@generate_pdf')->name('check.generate_pdf');
        Route::get('transfer_car_ownership_view/{car_id}', 'CarController@transfer_car_ownership_view')->name('car.transfer_car_ownership_view');
        Route::get('select2-autocomplete-ajax', 'CarController@dataAjax')->name('car.dataAjax');
        Route::post('transfer_car_ownership/{car_id}', 'CarController@transfer_car_ownership')->name('car.transfer_car_ownership');


//        Route::get('create_sale_order_returns/{sale_order_id}', 'SaleOrderReturnController@create')-> name('sale_order_returns.create');
//        Route::post('store_sale_order_returns', 'SaleOrderReturnController@store')-> name('sale_order_returns.store');
//        Route::get('sale_order_returns/invoice/{id}', 'SaleOrderReturnController@show')-> name('sale_order_returns.show');
//        Route::get('create_purchase_order_returns/{purchase_order_id}', 'PurchaseOrderReturnController@create')-> name('purchase_order_returns.create');
//        Route::post('store_purchase_order_returns', 'PurchaseOrderReturnController@store')-> name('purchase_order_returns.store');

        Route::get('get_all_products_in_branch', 'SaleOrderController@get_all_products_in_branch')->name('get_all_products_in_branch');
        Route::get('get_all_products_in_branch_edit', 'SaleOrderController@get_all_products_in_branch_edit')->name('get_all_products_in_branch_edit');

        Route::get('clientTransactions/{client_id}', 'ClientController@clientTransactions')->name('clientTransactions');
        Route::get('supplierTransactions/{supplier_id}', 'SupplierController@supplierTransactions')->name('supplierTransactions');

        Route::get('saleOrderProducts/{sale_order_id}', 'SaleOrderController@saleOrderProducts')->name('saleOrderProducts');
        Route::get('purchaseOrderProducts/{purchase_order_id}', 'PurchaseOrderController@purchaseOrderProducts')->name('purchaseOrderProducts');


        Route::get('saleOrderReturnProducts/{sale_order_return_id}', 'SaleOrderReturnController@saleOrderReturnProducts')->name('saleOrderReturnProducts');
        Route::get('purchaseOrderReturnProducts/{purchase_order_return_id}', 'PurchaseOrderReturnController@purchaseOrderReturnProducts')->name('purchaseOrderReturnProducts');

//        Route::get('openSaleOrderProducts/{open_sale_order_id}', 'OpenSaleOrderController@openSaleOrderProducts')->name('openSaleOrderProducts');
//        Route::get('openPurchaseOrderProducts/{purchase_order_id}', 'OpenPurchaseOrderController@openPurchaseOrderProducts')->name('openPurchaseOrderProducts');

        Route::get('moneySafe/{branch_id}', 'MoneySafeController@index')->name('moneySafe.index');
        Route::get('moneySafeOperations/{branch_id}', 'MoneySafeController@operations')->name('moneySafe.operations');
        Route::post('moneySafeOperationStore', 'MoneySafeController@store')->name('moneySafe.store');
        Route::get('check_amount_from_moneySafe_and_bank', 'PurchaseOrderController@check_amount_from_moneySafe_and_bank')->name('purchaseOrder.check_amount_from_moneySafe_and_bank');
        Route::get('money_safe_log', 'MoneySafeController@money_safe_log')->name('money_safe_log');

        Route::get('bank/{branch_id}', 'BankController@index')->name('bank.index');
        Route::get('bankOperations/{branch_id}', 'BankController@operations')->name('bank.operations');
        Route::post('bankOperationStore', 'BankController@store')->name('bank.store');
        Route::get('bank_log', 'BankController@bank_log')->name('bank_log');


        Route::get('purchaseOrder/tax_invoice', 'PurchaseOrderController@tax_invoice')->name('purchaseOrder.tax_invoice');
        Route::get('tax_invoice', 'SaleOrderController@tax_invoice')->name('tax_invoice');
        Route::get('simplified_tax_invoice', 'SaleOrderController@simplified_tax_invoice')->name('simplified_tax_invoice');
        Route::get('getClientBalance', 'SaleOrderController@getClientBalance')->name('getClientBalance');
        Route::get('search_product_code', 'PurchaseOrderController@search_product_code')->name('search_product_code');
        Route::get('reports', 'ReportsController@index')->name('reports');
        Route::get('getDataSearch', 'ReportsController@getDataSearch')->name('getDataSearch');
        Route::get('technical_productivity', 'TechnicalController@technical_productivity')->name('technical_productivity');
        Route::get('get_technical_productivity_by_ajax', 'TechnicalController@get_technical_productivity_by_ajax')->name('get_technical_productivity_by_ajax');
        Route::get('get_all_clients_by_ajax', 'ClientController@get_all_clients_by_ajax')->name('get_all_clients_by_ajax');
        Route::get('priceListProducts/{price_list_id}', 'PriceListController@priceListProducts')->name('priceListProducts');
        Route::get('download_price_list', 'PriceListController@download_price_list')->name('download_price_list');
        Route::get('download_work_order', 'PriceListController@download_work_order')->name('download_work_order');

        Route::get('load_roles_list', 'RolesManagerController@load_roles_list')->name('rolesManager.load_roles_list');
        Route::get('getRolePermissions', 'RolesManagerController@getRolePermissions')->name('rolesManager.getRolePermissions');
        Route::get('syncPermissions', 'RolesManagerController@syncPermissions')->name('rolesManager.syncPermissions');
        Route::get('sync_select_all_permissions', 'RolesManagerController@sync_select_all_permissions')->name('rolesManager.sync_select_all_permissions');
        Route::get('sync_group_permissions', 'RolesManagerController@sync_group_permissions')->name('rolesManager.sync_group_permissions');
        Route::get('delete_role', 'RolesManagerController@delete_role')->name('rolesManager.delete_role');

        Route::get('load_elements', 'RoleElementController@load_elements')->name('roleElement.load_elements');
        Route::get('delete_element', 'RoleElementController@delete_element')->name('roleElement.delete_element');

        Route::get('internalTransfer', 'InternalTransferController@index')->name('product.transfer.index');
        Route::get('transfer_product/{product_id}', 'InternalTransferController@transfer_product')->name('product.transfer');
        Route::post('transfer_product_store', 'InternalTransferController@transfer_product_store')->name('product.transfer.store');
        Route::get('change_product_status', 'InternalTransferController@change_product_status')->name('product.transfer.status.change');
//        Route::get('statement_selectBranch', 'StatementController@selectBranch')->name('statement.selectBranch');
        Route::get('statement_print', 'StatementController@statement_print')->name('statement.print');
        Route::get('get_tables_of_statement', 'StatementController@get_tables_of_statement')->name('statement.table');


        Route::resources([
            'check'                     => 'CheckController',
            'users'                     => 'UserController',
            'clients'                   => 'ClientController',
            'checkStatus'               => 'CheckStatusController',
            'branches'                  => 'BranchController',
            'technicals'                => 'TechnicalController',
            'engineers'                 => 'EngineerController',
            'jobTitle'                  => 'JobTitleController',
            'cars'                      => 'CarController',
            'carType'                   => 'CarTypeController',
            'carSize'                   => 'CarSizeController',
            'carModel'                  => 'CarModelController',
            'carEngine'                 => 'CarEngineController',
            'carDevelopmentCode'        => 'CarDevelopmentCodeController',
            'suppliers'                 => 'SupplierController',
            'categories'                => 'CategoryController',
            'subCategories'             => 'SubCategoryController',
            'products'                  => 'ProductController',
            'productCodes'              => 'ProductCodeController',
            'purchaseOrders'            => 'PurchaseOrderController',
//            'openPurchaseOrders'        => 'OpenPurchaseOrderController',
            'supplierPayments'          => 'SupplierPaymentController',
            'supplierCollecting'        => 'SupplierCollectingController',
            'clientPayments'            => 'ClientPaymentController',
            'clientCollecting'          => 'ClientCollectingController',
            'saleOrders'                => 'SaleOrderController',
//            'openSaleOrders'            => 'OpenSaleOrderController',
            'expenses'                  => 'ExpensesController',
            'expensesTypes'             => 'ExpensesTypeController',
            'saleOrderReturns'          => 'SaleOrderReturnController',
            'purchaseOrderReturns'      => 'PurchaseOrderReturnController',
            'priceList'                 => 'PriceListController',
            'rolesManager'              => 'RolesManagerController',
            'roleElements'              => 'RoleElementController',
            'statement'                 => 'StatementController',
        ]); // end of resources
    }); // enf of group
}); //end of localization group
