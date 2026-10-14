<?php

namespace App\DataTables;

use App\Models\ScheduledAdvance;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class ScheduledAdvanceDatatable extends DataTable
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
                return view('admin.datatableHtmlBuilderRender.advance.action', compact('query'));
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ScheduledAdvance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ScheduledAdvance $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('scheduledadvancedatatable-table')
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
                    ->orderBy(6)
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
            Column::make('advance_id')             -> title( __('trans.advance') ),
            Column::make('amount')                  -> title( __('trans.amount') ),
            Column::make('notes')               -> title( __('trans.notes') ),
            Column::make('status')                    -> title( __('trans.type') ),
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
        return 'ScheduledAdvance_' . date('YmdHis');
    }
}
