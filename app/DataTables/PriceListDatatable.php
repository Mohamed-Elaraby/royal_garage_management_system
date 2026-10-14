<?php

namespace App\DataTables;

use App\Models\PriceList;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class PriceListDatatable extends DataTable
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
                return view('admin.datatableHtmlBuilderRender.priceList.action', compact('query'));
            })
            ->editColumn('branch_id', function ($query){
                return $query->branch->display_name ?? '';
            })
            ->editColumn('user_id', function ($query){
                return $query->user->name ?? '';
            })
            ->addColumn('related_products', function ($query){
                return "<a class='btn btn-warning' href='" . route('admin.priceListProducts', $query -> id) . "'>". __('trans.price list products') ."</a>";
            })
            ->addColumn('show_price_list', function ($query){
                return "<a class='btn btn-warning' href='" . route('admin.priceList.show', $query -> id) . "'>". __('trans.show') ."</a>";

            })
            ->rawColumns(['action', 'related_products', 'show_price_list']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PriceList $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PriceList $model)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');
        $branch_id = $this -> request() -> get('branch_id');
        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $priceList = $model->newQuery()
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

        return $priceList;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('pricelistdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
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
                    ->orderBy(7)
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
            Column::make('chassis_number')            -> title( __('trans.chassis number') ),
            Column::make('related_products')            -> title( __('trans.all products') ),
            Column::make('show_price_list')                -> title( __('trans.price list') ),
            Column::make('price_list_number')                -> title( __('trans.price list number') ),
            Column::make('branch_id')                   -> title( __('trans.branch') ),
            Column::make('user_id')                     -> title( __('trans.employee') ),
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
        return 'PriceList_' . date('YmdHis');
    }
}
