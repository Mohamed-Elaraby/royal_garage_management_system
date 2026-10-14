<?php

namespace App\DataTables;

use App\Models\ClientTransaction;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class ClientTransactionDatatable extends DataTable
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
                return view('admin.datatableHtmlBuilderRender.clientTransaction.action', compact('query'));
            })
            ->editColumn('debit', function ($query){
                return $query -> debit ?? '-';
            })
            ->editColumn('credit', function ($query){
                return $query -> credit ?? '-';
            })
            ->editColumn('amount_paid', function ($query){
                return $query -> amount_paid ?? '-';
            })
            ->editColumn('amount_paid_bank', function ($query){
                return $query -> amount_paid_bank ?? '-';
            })
            ->editColumn('amount_paid_add_to_client_balance', function ($query){
                return $query -> amount_paid_add_to_client_balance ?? '-';
            })
            ->editColumn('amount_due', function ($query){
                return $query -> amount_due == '' ? '-' : ($query -> amount_due == 0 ? '-' : $query -> amount_due ) ;
            })
            ->editColumn('editor', function ($query){
                return $query -> user ? $query -> user -> name : '';
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ClientTransaction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ClientTransaction $model)
    {
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');
        $client_id = request('client_id');

        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $clientTransaction = $model->newQuery() -> where('client_id', $client_id);

        if (!empty($start_date) && !empty($end_date)) {
            $clientTransaction = $model->newQuery()->whereBetween('updated_at', $date_range)-> where('client_id', $client_id);
        }
        return $clientTransaction;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('clienttransactiondatatable-table')
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
                    ->orderBy(11, 'ASC')
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
            Column::make('DT_RowIndex')                         -> title('#') ->searchable(false),
            Column::make('transaction_date')                    -> title( __('trans.transaction date') ),
            Column::make('details')                             -> title( __('trans.details') ),
            Column::make('debit')                               -> title( __('trans.debit') . ' + ' ),
            Column::make('credit')                              -> title( __('trans.credit') . ' - ' ),
            Column::make('client_balance')                    -> title( __('trans.balance') ),
            Column::make('amount_paid')                         -> title( __('trans.amount paid in money safe') )
                ->addClass('no_print_in_datatable'),
            Column::make('amount_paid_bank')                    -> title( __('trans.amount paid in bank') )
                ->addClass('no_print_in_datatable'),
            Column::make('amount_paid_add_to_client_balance')   -> title( __('trans.amount paid add to client balance') )
                ->addClass('no_print_in_datatable'),
            Column::make('amount_due')                          -> title( __('trans.amount due') )
                ->addClass('no_print_in_datatable'),
            Column::make('editor')                              -> title( __('trans.editor') )
                ->addClass('no_print_in_datatable'),
            Column::make('updated_at')                          -> title( __('trans.last update') )
                ->addClass('no_print_in_datatable')
                ->addClass('date_dir_setting'),
            Column::make('action')                              -> title( __('trans.source') )
                ->addClass('no_print_in_datatable')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ClientTransaction_' . date('YmdHis');
    }
}
