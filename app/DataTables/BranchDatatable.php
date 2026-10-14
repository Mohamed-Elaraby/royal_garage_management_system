<?php

namespace App\DataTables;

use App\Models\Branch;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

class BranchDatatable extends DataTable
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
                return view('admin.datatableHtmlBuilderRender.branch.action', compact('query'));
            })
            ->editColumn('status', function ($query){
                if ($query->getOriginal('status') == 1) {
                    return '<span class="bg-green">'.$query->status.'</span>';
                }else{
                    return '<span class="bg-red">'.$query->status.'</span>';
                }
            })
            ->editColumn('name', function ($query){
                return "<a href='".route('dashboard', ['branch_id' => $query->id])."'>".$query -> name."</a>";
            })
            ->rawColumns(['action', 'status', 'name']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Branch $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Branch $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('branchdatatable-table')
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
            ->orderBy(5)
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
            Column::make('name')            -> title( __('trans.branch name') ),
            Column::make('display_name')    -> title( __('trans.display name') ),
            Column::make('phone')           -> title( __('trans.phone') ),
            Column::make('vat_number')      -> title( __('trans.vat number') ),
            Column::make('status')          -> title( __('trans.status') ),
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
        return 'Branch_' . date('YmdHis');
    }
}
