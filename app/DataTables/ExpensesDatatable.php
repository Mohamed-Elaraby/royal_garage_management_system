<?php

namespace App\DataTables;

use App\Models\Expenses;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class ExpensesDatatable extends DataTable
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
            ->editColumn('user_id', function ($query){
                return $query -> user -> name ?? '';
            })
            ->editColumn('expenses_type_id', function ($query){
                return $query -> expensesType -> name ?? '';
            })
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.expenses.action', compact('query'));
            })
            ->filterColumn('expenses_type_id', function($query, $keyword) {
                $query->whereHas('expensesType', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('branch_id', function($query, $keyword) {
                $query->whereHas('branch', function ($q) use ($keyword){
                    $q->where('display_name', 'LIKE', "%{$keyword}%");
                });
            })

            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Expenses $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Expenses $model)
    {
        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');
        $branch_id = $this -> request() -> get('branch_id');
        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $expenses = $model->newQuery()
            -> where(function ($query){
                if (!Auth::user()->hasRole(['super_owner', 'owner', 'general-manager']))
                {
                    $query -> where('branch_id', Auth::user()->branch_id);
                }
            })
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
        return $expenses;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('expensesdatatable-table')
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
            Column::make('DT_RowIndex')         -> title('#') ->searchable(false),
            Column::make('expenses_type_id')    -> title( __('trans.expenses type') ),
            Column::make('amount')              -> title( __('trans.amount') ),
            Column::make('notes')               -> title( __('trans.notes') ),
            Column::make('expenses_date')       -> title( __('trans.expenses date') ),
            Column::make('branch_id')           -> title( __('trans.branch') ),
            Column::make('payment_method')      -> title( __('trans.payment method') ),
            Column::make('user_id')             -> title( __('trans.editor') ),
            Column::make('updated_at')          -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
            Column::make('action')              -> title( __('trans.action') )
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Expenses_' . date('YmdHis');
    }
}
