<?php

namespace App\DataTables;

use App\Models\CarEngine;
use App\Models\CarSize;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class CarEngineDatatable extends DataTable
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
            ->editColumn('car_size_id', function ($query){
                return $query -> carSize -> name ?? '';
            })
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.carEngine.action', compact('query'));
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param CarEngine $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CarEngine $model)
    {
        $carEngine = $model->newQuery();
        if (request('car_size_id')){
            $carEngine = $model->newQuery()->where('car_size_id', request('car_size_id'));
        }

        return $carEngine;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('carenginedatatable-table')
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
            ->orderBy(3)
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
            Column::make('name')            -> title( __('trans.engine number') ),
            Column::make('car_size_id')            -> title( __('trans.car size') ),
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
        return 'CarEngine_' . date('YmdHis');
    }
}
