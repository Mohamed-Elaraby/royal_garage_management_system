<?php

use App\Models\RoleElement;
use Illuminate\Database\Seeder;

class RolesManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $elements = [
            'users'                ,
            'check'                ,
            'jobTitle'             ,
            'clients'              ,
            'checkStatus'          ,
            'branches'             ,
            'technicals'           ,
            'engineers'            ,
            'cars'                 ,
            'carType'              ,
            'carSize'              ,
            'carModel'             ,
            'carEngine'            ,
            'carDevelopmentCode'   ,
            'managementNotes'      ,
            'suppliers'            ,
            'products'             ,
            'categories'           ,
            'subCategories'        ,
            'productCodes'         ,
            'purchaseOrders'       ,
            'supplierTransactions' ,
            'supplierPayments'     ,
            'supplierCollecting'   ,
            'clientPayments'       ,
            'clientCollecting'     ,
            'clientTransactions'   ,
            'saleOrders'           ,
            'moneySafe'            ,
            'bank'                 ,
            'expenses'             ,
            'expensesTypes'        ,
            'reports'              ,
            'openSaleOrders'       ,
            'openPurchaseOrders'   ,
            'saleOrderReturns'     ,
            'purchaseOrderReturns' ,
            'priceList'            ,
            'rolesManager'         ,
            'nationalities'        ,
            'salaries'             ,
            'employees'            ,
            'advances'             ,
            'scheduledAdvances'    ,
            'rewards'              ,
            'vacations'            ,
        ];
        foreach ($elements as $element)
        {
            RoleElement::create([
                'name' => $element
            ]);
        }
    }
}
