<?php

namespace App\DataTables;

use App\Models\Technical;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class TechnicalDatatable extends DataTable
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
            ->editColumn('branch_id', function ($query){
                return $query -> branch -> name ?? '';
            })
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.technical.action', compact('query'));
            })
            ->addColumn('technical_productivity', function ($query){
                return '<a href="'.route('admin.technical_productivity', ['technical_id' => $query -> id]).'" class="btn btn-primary">'.__('trans.technical productivity').'</a>';
            })
            ->rawColumns(['action', 'technical_productivity']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Technical $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Technical $model)
    {
        $technicals = $model->newQuery();
        if (request('branch_id')) { // Get all check with [ branch id ] from request url
            $technicals = $model->newQuery() -> where('branch_id', request('branch_id'));
        }
        return $technicals;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('technicaldatatable-table')
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
                    ->orderBy(4)
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
            Column::make('name')            -> title( __('trans.technical name') ),
            Column::make('branch_id')            -> title( __('trans.branch name') ),
            Column::make('technical_productivity')            -> title( __('trans.technical productivity') ),
            Column::make('updated_at')      -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
            Column::make('action')          -> title( __('trans.action') )
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Technical_' . date('YmdHis');
    }
}
