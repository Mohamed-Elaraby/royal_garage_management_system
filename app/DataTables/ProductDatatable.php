<?php

namespace App\DataTables;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class ProductDatatable extends DataTable
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
                return $query -> branch -> display_name;
            })
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.product.action', compact('query'));
            })
            ->editColumn('sub_category_id', function ($query){
                return $query -> subCategory? $query -> subCategory -> name: '';
            })->editColumn('discount_amount', function ($query){
//                return  $query -> discount_type;
                $discount_type = $query -> discount_type == 1 ? '('.$query -> discount.'%)': ' دولار ';
                return $query -> discount ? $discount_type .  ' ' . $query -> discount_amount : '';
            })
            ->filterColumn('branch_id', function($query, $keyword) {
                $query->whereHas('branch', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model, Request $request)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');

        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $product = $model->newQuery();

        if (!empty($start_date) && !empty($end_date)) {
            $product = $model->newQuery()->whereBetween('updated_at', $date_range);
        }
        return $product;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('productdatatable-table')
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
                    ->orderBy(10)
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
            Column::make('code')                        -> title( __('trans.product code') ),
            Column::make('name')                        -> title( __('trans.product name') ),
            Column::make('price')                       -> title( __('trans.purchasing price') ),
            Column::make('discount_amount')             -> title( __('trans.discount amount') ),
            Column::make('price_after_discount')        -> title( __('trans.price after discount') ),
            Column::make('selling_price')               -> title( __('trans.selling price') )->addClass('no_print_in_datatable'),
            Column::make('quantity')                    -> title( __('trans.quantity') ),
            Column::make('sub_category_id')             -> title( __('trans.category') ),
            Column::make('branch_id')                   -> title( __('trans.branch') ),
            Column::make('updated_at')                  -> title( __('trans.last update') )->addClass('no_print_in_datatable')
                ->addClass('date_dir_setting'),
            Column::make('action')                      -> title( __('trans.action') )->addClass('no_print_in_datatable')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}
