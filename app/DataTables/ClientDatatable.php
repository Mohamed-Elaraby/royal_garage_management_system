<?php

namespace App\DataTables;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClientDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.client.action', compact('query'));
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
     * @param Client $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Client $model, Request $request)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');

        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $client = $model->newQuery();

        if (!empty($start_date) && !empty($end_date)) {
            $client = $model->newQuery()->whereBetween('updated_at', $date_range);
        }

        if ($request -> month){
            $client = $model ->newQuery()->whereMonth('created_at', Carbon::now()->month);
        }
        return $client;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('clientdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters(array_merge($this->getBuilderParameters(),[]))
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
                    ->orderBy(8)
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
            Column::make('name')                    -> title( __('trans.client name') ),
            Column::make('phone')                   -> title( __('trans.phone') ),
            Column::make('vat_number')              -> title( __('trans.vat number') ),
            Column::make('employer')                -> title( __('trans.employer') ),
            Column::make('other_car')               -> title( __('trans.other car') ),
            Column::make('how_you_now_us')          -> title( __('trans.how you now us') )->addClass('no_print_in_datatable'),
            Column::make('balance')                 -> title( __('trans.balance') )
                ->addClass('balance_column_color'),
            Column::make('updated_at')              -> title( __('trans.last update') )->addClass('no_print_in_datatable')
                ->addClass('date_dir_setting'),
            Column::make('action')                  -> title( __('trans.action') )->addClass('no_print_in_datatable')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Client_' . date('YmdHis');
    }
}
