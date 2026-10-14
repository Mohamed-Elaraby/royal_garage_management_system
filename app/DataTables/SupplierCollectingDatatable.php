<?php

namespace App\DataTables;

use App\Models\SupplierCollecting;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class SupplierCollectingDatatable extends DataTable
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
            ->editColumn('branch_id', function ($query){
                return $query -> branch -> name;
            })
            ->editColumn('user_id', function ($query){
                return $query -> user -> name;
            })
            ->editColumn('supplier_id', function ($query){
                return '<a href="'.route('admin.supplierTransactions', $query -> supplier ->id).'">'.$query -> supplier -> name.'</a>';
            })
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.supplierCollecting.action', compact('query'));
            })

            ->rawColumns(['action', 'supplier_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SupplierCollecting $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SupplierCollecting $model)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');

        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $supplierCollecting = $model->newQuery();

        if (!empty($start_date) && !empty($end_date)) {
            $supplierCollecting = $model->newQuery()->whereBetween('updated_at', $date_range);
        }
        return $supplierCollecting;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('suppliercollectingdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters(array_merge($this->getBuilderParameters(),
                        [
                            'initComplete' => "function () {
                                this.api().columns().every(function () {
                                    var column = this;
                                    var input = document.createElement(\"input\");
                                    $(input).appendTo($(column.footer()).empty())
                                    .on('keyup', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });
                                });
                            }",
                        ]
                    ))
                    ->dom('Blfrtip')
                    ->scrollX(true)
                    ->scrollY(false)
                    ->searching(true)
                    ->responsive(true)
                    ->autoWidth(false)
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
                    ->orderBy(9)
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
            Column::make('DT_RowIndex')         -> title('#') ->searchable(false),
            Column::make('receipt_number')      -> title( __('trans.receipt number') ),
            Column::make('amount_paid')         -> title( __('trans.amount paid in money safe') ),
            Column::make('amount_paid_bank')    -> title( __('trans.amount paid in bank') ),
            Column::make('notes')               -> title( __('trans.notes') ),
            Column::make('collecting_date')     -> title( __('trans.collecting date') ),
            Column::make('user_id')             -> title( __('trans.editor') ),
            Column::make('branch_id')           -> title( __('trans.branch') ),
            Column::make('supplier_id')         -> title( __('trans.supplier') ),
            Column::make('updated_at')          -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
//            Column::make('action')              -> title( __('trans.action') )
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SupplierCollecting_' . date('YmdHis');
    }
}
