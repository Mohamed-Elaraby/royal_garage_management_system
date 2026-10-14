<?php

namespace App\DataTables;

use App\Models\Car;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class CarDatatable extends DataTable
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
                return view('admin.datatableHtmlBuilderRender.car.action', compact('query'));
            })
            ->editColumn('client_id', function ($query){
                return $query->client->name ?? '';
            })
            ->editColumn('car_type_id', function ($query){
                return $query->carType->name ?? '';
            })
            ->editColumn('car_size_id', function ($query){
                return $query->carSize->name ?? '';
            })
            ->editColumn('car_engine_id', function ($query){
                return $query->carEngine->name ?? '';
            })
            ->editColumn('car_development_code_id', function ($query){
                return $query->carDevelopmentCode->name ?? '';
            })
            ->editColumn('car_model_id', function ($query){
                return $query->carModel->name ?? '';
            })
            ->filterColumn('car_type_id', function($query, $keyword) {
                $query->whereHas('carType', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('car_size_id', function($query, $keyword) {
                $query->whereHas('carSize', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('car_engine_id', function($query, $keyword) {
                $query->whereHas('carEngine', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('car_development_code_id', function($query, $keyword) {
                $query->whereHas('carDevelopmentCode', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('car_model_id', function($query, $keyword) {
                $query->whereHas('carModel', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->filterColumn('client_id', function($query, $keyword) {
                $query->whereHas('client', function ($q) use ($keyword){
                    $q->where('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Car $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Car $model)
    {
        $car = $model->newQuery();
        if (request()->client_id){
            $car = $model ->newQuery()->where('client_id', request()->client_id);
        }
        return $car;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('cardatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters(array_merge($this->getBuilderParameters(),
                        [
                            'initComplete' => "function () {
                                    this.api().columns().every(function () {
                                        var column = this;
                                        var input = document.createElement(\"input\");
                                        $(input).appendTo($(column.footer()).empty())
                                        .on('keyup', function () {
                                            column.search($(this).val(), false, false, true).draw();
                                        });
                                    });
                                }",
                        ]
                    ))
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
            Column::make('client_id')                 -> title( __('trans.client name') ),
            Column::make('car_type_id')                 -> title( __('trans.car type') ),
            Column::make('car_size_id')                 -> title( __('trans.car size') ),
            Column::make('car_engine_id')               -> title( __('trans.engine number') ),
            Column::make('car_development_code_id')     -> title( __('trans.car development code') ),
            Column::make('car_model_id')                -> title( __('trans.model') ),
            Column::make('chassis_number')              -> title( __('trans.chassis number') ),
            Column::make('plate_number')                -> title( __('trans.plate number') ),
            Column::make('car_color')                   -> title( __('trans.car color') ),
            Column::make('updated_at')                  -> title( __('trans.last update') )
                ->addClass('date_dir_setting'),
            Column::make('action')                      -> title( __('trans.action') )

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Car_' . date('YmdHis');
    }
}
