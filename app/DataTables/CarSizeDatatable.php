<?php

namespace App\DataTables;

use App\Models\CarSize;
use App\Models\CarType;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class CarSizeDatatable extends DataTable
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
            ->editColumn('car_type_id', function ($query){
                return $query -> carType -> name ?? '';
            })
            ->addColumn('action', function ($query){
                return view('admin.datatableHtmlBuilderRender.carSize.action', compact('query'));
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \app\models\CarSize $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CarSize $model)
    {
        $carSize = $model->newQuery();

        if (request('car_type_id')){
            $carSize = $model->newQuery()->where('car_type_id', request('car_type_id'));
        }

        return $carSize;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */

    public function html()
    {
        return $this->builder()
            ->setTableId('carsizedatatable-table')
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
            Column::make('name')            -> title( __('trans.car size') ),
            Column::make('car_type_id')            -> title( __('trans.car type') ),
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
        return 'CarSize_' . date('YmdHis');
    }
}
