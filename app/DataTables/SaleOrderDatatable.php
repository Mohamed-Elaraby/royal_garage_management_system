<?php

namespace App\DataTables;

use App\Models\SaleOrder;
use Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class SaleOrderDatatable extends DataTable
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
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.saleOrder.action', compact('query'));
            })
            ->editColumn('branch_id', function ($query){
                return $query->branch->display_name ?? '';
            })
            ->editColumn('client_id', function ($query){
                return '<a href="'.route('admin.clientTransactions', $query -> client ->id).'">'.$query -> client -> name.'</a>';
            })
            ->editColumn('user_id', function ($query){
                return $query->user->name ?? '';
            })
            ->addColumn('related_products', function ($query){
                return "<a class='btn btn-warning' href='" . route('admin.saleOrderProducts', $query -> id) . "'>". __('trans.related products') ."</a>";
            })
            ->addColumn('show_invoice', function ($query){
                if (request('status') == 'close')
                {
                    return "<a class='btn btn-warning' href='" . route('admin.saleOrders.show', $query -> id) . "'>". __('trans.show invoice') ."</a>";
                }else {
                    return '<span style="color: #00a65a; text-align: center">فاتورة مفتوحة</span>';
                }
            })
            ->addColumn('return_invoices', function ($query){
                if (!Auth::user()->hasRole(['general_observer']))
                    return "<a class='btn btn-primary' href='" . route('admin.saleOrderReturns.index', ['sale_order_id' => $query -> id]) . "'>". __('trans.show return invoices') ."</a>";
            })
            ->addColumn('check', function ($query){
                $check = $query -> check ?? '';
                if ($check)
                {
                    return "<a href='".route('admin.check.show', $check-> id )."'>".$check -> check_number."</a>";
                }
            })
            ->rawColumns(['action', 'related_products', 'show_invoice', 'client_id', 'check', 'return_invoices']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SaleOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SaleOrder $model)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');
        $branch_id = $this -> request() -> get('branch_id');
        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $saleOrder = $model->newQuery() -> checkUserRole() -> checkOrderStatus()
            ->where(function ($query)use ($start_date, $end_date, $date_range ){
                if ($start_date && $end_date)
                {
                    $query-> whereBetween('created_at' ,$date_range);
                }
            })
            ->where(function ($query)use ($branch_id){
                if ($branch_id)
                {
                    $query-> where('branch_id' ,$branch_id);
                }
            });

        return $saleOrder;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('saleorderdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Blfrtip')
                    ->scrollX(true)
                    ->scrollY(false)
                    ->searching(true)
                    ->responsive(true)
                    ->autoWidth(true)
                    ->lengthMenu([[5,10,25,50,100,-1], [5,10,25,50,100,"الكل"]])
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
                    ->orderBy(13)
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
            Column::make('DT_RowIndex')                 -> title('#') ->searchable(false),
            Column::make('related_products')            -> title( __('trans.all products') ),
            Column::make('show_invoice')                -> title( __('trans.full invoice') ),
            Column::make('return_invoices')             -> title( __('trans.returns invoices') ),
            Column::make('check')                       -> title( __('trans.check') ),
            Column::make('invoice_number')              -> title( __('trans.invoice number') ),
            Column::make('invoice_date')                -> title( __('trans.invoice date') ),
            Column::make('total_amount_due')            -> title( __('trans.total amount due') ),
            Column::make('amount_paid')                 -> title( __('trans.amount paid in money safe') ),
            Column::make('amount_paid_bank')                 -> title( __('trans.amount paid in bank') ),
            Column::make('amount_due')                  -> title( __('trans.amount due') ),
            Column::make('branch_id')                   -> title( __('trans.branch') ),
            Column::make('client_id')                   -> title( __('trans.client') ),
//            Column::make('notes')                             -> title( __('trans.notes') ),
            Column::make('updated_at')                  -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
            Column::make('action')                            -> title( __('trans.action') )
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SaleOrder_' . date('YmdHis');
    }
}
