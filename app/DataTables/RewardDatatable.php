<?php

namespace App\DataTables;

use App\Models\Reward;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class RewardDatatable extends DataTable
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
            ->editColumn('user_id', function ($query){
                return $query -> user -> name ?? '';
            })
            ->addColumn('action', function ($query){
                return view('employee.datatableHtmlBuilderRender.reward.action', compact('query'));
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Reward $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Reward $model)
    {
        return $model->newQuery()->checkEmployeeBranch()->getDataWithEmployee();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('rewarddatatable-table')
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
            Column::make('DT_RowIndex')     -> title('#') ->searchable(false),
            Column::make('status')                  -> title( __('trans.status') ),
            Column::make('employee_id')     -> title( __('trans.employee name') ),
            Column::make('amount')          -> title( __('trans.amount') ),
            Column::make('payment_method')  -> title( __('trans.payment method') ),
            Column::make('notes')           -> title( __('trans.notes') ),
            Column::make('user_id')         -> title( __('trans.user') ),
            Column::make('updated_at')      -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
            Column::make('action')          -> title( __('trans.action') ),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Reward_' . date('YmdHis');
    }
}
