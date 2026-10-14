<?php

namespace App\DataTables;

use App\Models\Category;
use \App\Models\InternalTransfer;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class TransferProductDatatable extends DataTable
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
            ->editColumn('from_branch', function ($query){
                return $query -> f_branch -> display_name;
            })
            ->editColumn('to_branch', function ($query){
                return $query -> t_branch -> display_name;
            })

            ->editColumn('user_id', function ($query){
                return $query -> user? $query -> user -> name: '';
            })

            ->editColumn('sub_category_id', function ($query){
                $categories = Category::select('id', 'name')->with('subCategories')->get();
                return view('admin.datatableHtmlBuilderRender.internalTransfer.subCategory', compact('query', 'categories'));
//                return $query -> subCategory? $query -> subCategory -> name : '';
            })
            ->editColumn('status', function ($query){
                return view('admin.datatableHtmlBuilderRender.internalTransfer.status', compact('query'));
            })
//            ->addColumn('action', function ($query){
//                return view('admin.datatableHtmlBuilderRender.product.action', compact('query'));
//            })
//            ->editColumn('sub_category_id', function ($query){
//                return $query -> subCategory -> name;
//            })->editColumn('discount_amount', function ($query){
////                return  $query -> discount_type;
//                $discount_type = $query -> discount_type == 1 ? '('.$query -> discount.'%)': ' دولار ';
//                return $query -> discount ? $discount_type .  ' ' . $query -> discount_amount : '';
//            })

            ->rawColumns(['action', 'sub_category_id']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\InternalTransfer $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InternalTransfer $model)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');

        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $product_transfer = $model->newQuery();

        if (!empty($start_date) && !empty($end_date)) {
            $product_transfer = $model->newQuery()->whereBetween('updated_at', $date_range);
        }
        return $product_transfer;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('transferproductdatatable-table')
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
                    ->orderBy(12)
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
            Column::make('id')                          -> addClass('id')-> addClass('hide'),
            Column::make('code')                        -> title( __('trans.product code') )->addClass('code'),
            Column::make('name')                        -> title( __('trans.product name') ),
            Column::make('sub_category_id')             -> title( __('trans.sub category') ),
            Column::make('price')                       -> title( __('trans.purchasing price') ),
            Column::make('discount_amount')             -> title( __('trans.discount amount') ),
            Column::make('price_after_discount')        -> title( __('trans.price after discount') ),
            Column::make('quantity')                    -> title( __('trans.quantity') ),
//            Column::make('sub_category_id')             -> title( __('trans.category') ),
            Column::make('from_branch')                 -> title( __('trans.from_branch') ),
            Column::make('to_branch')                   -> title( __('trans.to_branch') ),
            Column::make('user_id')                   -> title( __('trans.user') ),
            Column::make('updated_at')                  -> title( __('trans.last update') )->addClass('no_print_in_datatable')
                ->addClass('date_dir_setting'),
            Column::make('status')                      -> title( __('trans.status') )->addClass('no_print_in_datatable')
//            Column::make('action')                      -> title( __('trans.action') )->addClass('no_print_in_datatable')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'TransferProduct_' . date('YmdHis');
    }
}
