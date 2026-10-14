<?php

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function(){

    Route::prefix('employee')->middleware('auth')->name('employee.')->namespace('Employee')->group(function (){
        // Collection Routes

        Route::get('scheduling_details', 'ScheduledAdvanceController@getSchedulingDetailsByAjax')->name('scheduling_details');
        Route::get('edit_status_advance', 'ScheduledAdvanceController@edit_status_advance')->name('edit_status_advance');
        Route::get('salaryDetails/{employee_id}', 'SalaryController@salaryDetails')->name('salaries.details');
        Route::get('salaryReceiptView/{employee_id}', 'SalaryController@salaryReceipt')->name('salaries.receipt.view');
        Route::get('salaryReceipt/{employee_id}', 'SalaryController@salaryReceipt')->name('salaries.receipt');
        Route::post('register_to_employee_log/{employee_id}', 'SalaryController@registerToEmployeeLog')->name('salaries.registerToEmployeeLog');
        Route::get('salary_month_details/{employee_id}/{month}/{year}', 'SalaryController@salary_month_details')->name('salaries.salary_month_details');


        Route::get('employee_signature/{employee_id}/{month}/{year}/{employee_salary_log_id}', 'SalaryController@employee_signature')->name('salaries.employeeSignature');
        Route::post('signature', 'SalaryController@signature')->name('salaries.signature');
        Route::get('salary_month_details_print', 'SalaryController@salary_month_details_print')->name('salary_month_details_print');
//
        Route::get('advance_signature/{advance_id}', 'AdvanceController@advance_signature')->name('advance.signature');
        Route::post('advanceSignature', 'AdvanceController@signature')->name('advance.employee_signature');
        Route::get('advanceReceiptView/{advance_id}', 'AdvanceController@advanceReceipt')->name('advance.receipt.view');
        Route::get('advance_receipt_print', 'AdvanceController@advance_receipt_print')->name('advance_receipt_print');

        Route::get('reward_signature/{reward_id}', 'RewardsController@reward_signature')->name('reward.signature');
        Route::post('rewardSignature', 'RewardsController@signature')->name('reward.employee_signature');
        Route::get('rewardReceiptView/{reward_id}', 'RewardsController@rewardReceipt')->name('reward.receipt.view');
        Route::get('reward_receipt_print', 'RewardsController@reward_receipt_print')->name('reward_receipt_print');

        Route::get('discount_signature/{discount_id}', 'DiscountController@discount_signature')->name('discount.signature');
        Route::post('discountSignature', 'DiscountController@signature')->name('discount.employee_signature');
        Route::get('discountReceiptView/{discount_id}', 'DiscountController@discountReceipt')->name('discount.receipt.view');
        Route::get('discount_receipt_print', 'DiscountController@discount_receipt_print')->name('discount_receipt_print');



        Route::resources([
            'nationalities'             => 'NationalityController',
            'salaries'                  => 'SalaryController',
//            'salaryDetails'             => 'SalaryDetailsController',
            'employees'                 => 'EmployeeController',
            'advances'                  => 'AdvanceController',
            'scheduledAdvances'         => 'ScheduledAdvanceController',
            'rewards'                   => 'RewardsController',
            'vacations'                 => 'VacationController',
            'discounts'                 => 'DiscountController',

        ]); // end of resources
    }); // enf of employee group
}); //end of localization group
