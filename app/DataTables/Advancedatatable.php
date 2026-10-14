<?php

namespace App\DataTables;

use App\Models\Advance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class Advancedatatable extends DataTable
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
            ->editColumn('employee_id', function ($query){
                return $query -> employee -> name ?? '';
            })
            ->editColumn('number_of_schedule', function ($query){
                    if ($query -> number_of_schedule)
                return '<div data-toggle="modal" data-target="#scheduled_advances_model" data-id="'.$query ->id.'" class="scheduled_advances_button btn btn-info">'.$query -> number_of_schedule.'</div>';
            })
            ->editColumn('user_id', function ($query){
                return $query -> user -> name ?? '';
            })
            ->filterColumn('employee_id', function($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })

            ->filterColumn('user_id', function($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
//            ->filterColumn('status', function($query, $keyword) {
//                $query -> where();
//            })
            ->addColumn('action', function ($query){
                return view('employee.datatableHtmlBuilderRender.advance.action', compact('query'));
            })
            ->rawColumns(['action', 'number_of_schedule']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Advance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Advance $model, Request $request)
    {

        // Search Parameters
        $start_date = $this -> request() -> get('start_date');
        $end_date = $this -> request() -> get('end_date');

        // Date Range Used With Search => Array
        $date_range = [$start_date.' 00:00:00', $end_date.' 23:59:59'];

        $advance = $model->newQuery()->checkEmployeeBranch()->getDataWithEmployee();

        if (!empty($start_date) && !empty($end_date)) {
            $advance = $model->newQuery()->whereBetween('updated_at', $date_range)->checkEmployeeBranch()->getDataWithEmployee();
        }

        if ($request -> month){
            $advance = $model ->newQuery()->whereMonth('created_at', Carbon::now()->month)->checkEmployeeBranch()->getDataWithEmployee();
        }
        return $advance;
//        return $model->newQuery()->checkEmployeeBranch()->getDataWithEmployee();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('advancedatatable-table')
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
                    ->orderBy(11)
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
            Column::make('status')                  -> title( __('trans.status') ),
            Column::make('employee_id')             -> title( __('trans.employee') ),
            Column::make('amount')                  -> title( __('trans.amount') ),
            Column::make('payment_method')                  -> title( __('trans.payment method') ),
            Column::make('type')                    -> title( __('trans.type') ),
            Column::make('number_of_schedule')      -> title( __('trans.number of schedule') ),
            Column::make('refunds')                 -> title( __('trans.refunds') ),
            Column::make('remaining_payments')      -> title( __('trans.remaining payments') ),
            Column::make('user_id')                 -> title( __('trans.user') ),
            Column::make('notes')                   -> title( __('trans.notes') ),
            Column::make('updated_at')              -> title( __('trans.last update') )
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
        return 'Advance_' . date('YmdHis');
    }
}
