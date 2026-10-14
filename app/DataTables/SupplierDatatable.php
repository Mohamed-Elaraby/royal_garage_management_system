<?php

namespace App\DataTables;

use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class SupplierDatatable extends DataTable
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
                return view('admin.datatableHtmlBuilderRender.supplier.action', compact('query'));
            })
            ->editColumn('balance', function ($query){
                if ($query -> balance > 0)
                {
                    return "<div style='color: green; '>".$query -> balance." <i class='fa fa-level-up'></i></div>";
                }elseif($query -> balance < 0){
                    return "<div style='color: red; '>".$query -> balance." <i class='fa fa-level-down'></i></div>";
                }else{
                    return $query -> balance;
                }
            })
            ->rawColumns(['action', 'balance']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Supplier $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Supplier $model, Request $request)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');

        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $supplier = $model->newQuery();

        if (!empty($start_date) && !empty($end_date)) {
            $supplier = $model->newQuery()->whereBetween('updated_at', $date_range);
        }
        return $supplier;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('supplierdatatable-table')
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
                    ->orderBy(5)
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
            Column::make('DT_RowIndex')             -> title('#') ->searchable(false),
            Column::make('name')                    -> title( __('trans.supplier name') ),
            Column::make('phone')                   -> title( __('trans.phone') ),
            Column::make('vat_number')              -> title( __('trans.vat number') ),
            Column::make('balance')                 -> title( __('trans.balance') )
                ->addClass('balance_column_color'),
            Column::make('updated_at')              -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
            Column::make('action')                  -> title( __('trans.action') )
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Supplier_' . date('YmdHis');
    }
}
