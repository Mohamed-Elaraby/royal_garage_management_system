<?php

namespace App\DataTables;

use \App\Models\MoneySafe;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class MoneySafeLogDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('user_id', function ($query){
                return $query -> user -> name ?? '';
            })
            ->editColumn('amount_paid', function ($query){
                return $query -> amount_paid . ' دولار ';
            })
//            ->editColumn('branch_id', function ($query){
//                return $query -> branch -> display_name ?? '';
//            })
            ->addColumn('process_type', function ($query){

                if ($query -> expenses_id && $query -> processType == 2)
                {
                    return __('trans.expenses') . ' [عملية محذوفة] ';
                }
                if (!empty($query -> sale_order_id))
                {
                    if ($query -> saleOrder)
                        return '<a class="btn btn-warning" href="'. route('admin.saleOrders.show', $query -> saleOrder -> id) .'">'.__('trans.sale order').'</a>';
                }
                if ($query -> client_payment_id)
                {
                    if ($query -> clientPayment -> id)
                        return '<a class="btn btn-warning" href="'. route('admin.clientPayments.show', $query -> clientPayment -> id) .'">'.__('trans.client payment').'</a>';
                }
                if ($query -> client_collecting_id)
                {
                    if ($query -> clientCollecting -> id)
                        return '<a class="btn btn-warning" href="'. route('admin.clientCollecting.show', $query -> clientCollecting -> id) .'">'.__('trans.client collecting').'</a>';
                }
                if ($query -> expenses_id)
                {
                    return __('trans.expenses');
                }
                if (!empty($query -> purchase_order_id))
                {
                    if ($query -> purchaseOrder)
                        return '<a class="btn btn-warning" href="'. route('admin.purchaseOrders.show', $query -> purchaseOrder -> id) .'">'.__('trans.purchase order').'</a>';
                }

                if ($query -> supplier_payment_id)
                {
                    if ($query -> supplierPayment)
                        return '<a class="btn btn-warning" href="'. route('admin.supplierPayments.show', $query -> supplierPayment -> id) .'">'.__('trans.supplier payment').'</a>';
                }
                if ($query -> supplier_collecting_id)
                {
                    if ($query -> supplierCollecting)
                        return '<a class="btn btn-warning" href="'. route('admin.supplierCollecting.show', $query -> supplierCollecting -> id) .'">'.__('trans.supplier collecting').'</a>';
                }
                if ($query -> sale_order_return_id)
                {
                    if ($query -> saleOrderReturn)
                        return '<a class="btn btn-warning" href="'. route('admin.saleOrderReturns.show', $query -> saleOrderReturn -> id) .'">'.__('trans.sale order return').'</a>';
                }
                if ($query -> purchase_order_return_id)
                {
                    if ($query -> purchaseOrderReturn)
                        return '<a class="btn btn-warning" href="'. route('admin.purchaseOrderReturns.show', $query -> purchaseOrderReturn -> id) .'">'.__('trans.purchase order return').'</a>';
                }
                if (!is_null($query -> processType))
                {
                    if ($query -> processType == 0)
                    {
                        $trans = 'عملية سحب';
                    }

                    if ($query -> processType == 1)
                    {
                        $trans = 'عملية ايداع';
                    }
                    return $trans;
                }
                if ($query -> advance_id)
                {
                    return '<a class="btn btn-warning" href="#">'.__('trans.advance').'</a>';
//                    return '<a class="btn btn-warning" href="'. route('admin.supplierPayments.show', $query -> advance_id) .'">'.__('trans.advance').'</a>';
                }

                if ($query -> reward_id)
                {
                    return '<a class="btn btn-warning" href="#">'.__('trans.reward').'</a>';
//                    return '<a class="btn btn-warning" href="'. route('admin.supplierPayments.show', $query -> reward_id) .'">'.__('trans.reward').'</a>';
                }

                if ($query -> discount_id)
                {
                    return '<a class="btn btn-warning" href="#">'.__('trans.discount').'</a>';
//                    return '<a class="btn btn-warning" href="'. route('admin.supplierPayments.show', $query -> discount_id) .'">'.__('trans.reward').'</a>';
                }

                if ($query -> vacation_id)
                {
                    return '<a class="btn btn-warning" href="#">'.__('trans.vacation').'</a>';
//                    return '<a class="btn btn-warning" href="'. route('admin.supplierPayments.show', $query -> vacation_id) .'">'.__('trans.reward').'</a>';
                }

                if ($query -> employee_salary_log_id)
                {
                    $salary_details = $query -> employeeSalaryLog;
                    $month = $salary_details ? Carbon::parse($salary_details->salary_month) -> month : '';
                    $year = $salary_details ? Carbon::parse($salary_details->salary_month) -> year : '';
                    $employee_id = $salary_details -> employee_id ?? '';
                    $employee_name = $salary_details ? $salary_details -> employee ->name : '';
//                    dd($month);
                    return '<a class="btn btn-warning" href="'. route('employee.salaries.salary_month_details', [$employee_id, $month, $year]) .'"> راتب '.$employee_name. ' شهر ' . $month . ' - ' . $year.'</a>';
                }
            })
            ->rawColumns(['process_type']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\MoneySafe $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(MoneySafe $model)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59']; // Date Range Used With Search => Array
        $branch_id = $this -> request() -> get('branch_id');
        return $model->newQuery()->where('branch_id', $branch_id);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('moneysafelogdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters(array_merge($this->getBuilderParameters(),[]))
                    ->dom('Blfrtip')
                    ->scrollX(true)
                    ->scrollY(false)
                    ->searching(true)
                    ->responsive(true)
                    ->autoWidth(false)
                    ->lengthMenu([[5,10,25,50,100,-1], [5,10,25,50,100,"الكل"]])
                    ->pageLength(50)
                    ->processing(true)
                    ->serverSide(true)
                    ->language(
                        [
                            "buttons" =>
                                [
                                    "export" => 'إستخراج',
                                    "print" => 'طباعة',
                                    "reset" => 'إعادة ضبط',
                                    "reload" => 'إعادة تحميل',
                                ]
                        ]
                    )
                    ->languageUrl('//cdn.datatables.net/plug-ins/1.10.22/i18n/Arabic.json')
                    ->orderBy(4)
                    ->buttons(
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')     -> title('#') ->searchable(false),
//            Column::make('branch_id')            -> title( __('trans.branch name') ),
            Column::make('amount_paid')            -> title( __('trans.amount') ),
            Column::make('process_type')            -> title( __('trans.type') ),
            Column::make('user_id')            -> title( __('trans.employee') ),
            Column::make('updated_at')      -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'MoneySafeLog_' . date('YmdHis');
    }
}
