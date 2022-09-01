<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;

class SubscriptionPlanDataTable extends DataTable
{

    protected $request = '';
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable->editColumn('status', function($data) {

            return '<ul class="list-inline action">
                        <li class="list-inline-item"><a href="javascript:void(0);" onclick="loadAccountForm('.$data->id.')">Edit</a></li>
                    </ul>';
                    })

            ->addColumn('plan', function ($data) {
                return ucfirst($data->plan);
                })   

            ->addColumn('price', function ($data) {
                    return '$'.$data->price;
                    })

            ->rawColumns($columns, 'action')
            ->addIndexColumn();
        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     * @param \App\SubscriptionPlan 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SubscriptionPlan $model)
    {
        return $model->select('subscription_plans.*');
    }

    /**
     * Optional method if you want to use html builder.
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                'order' => [
                    0, // here is the column number
                    'asc'
                ],
                'bSort' => false,
                'scrollX' =>  true,
                "bInfo" => false, 
                "bLengthChange"=> false,
                "sScrollXInner"=> "100%",
                'sDom'=> 'lrtip'
            ])

            ->language([
                "paginate" => [ 
                "previous" => '<a class="page-link" href="javascript:void(0);" aria-label="Previous">
                <em class="icon-previous"></em></a>',
                
                "next" => ' <a class="page-link" href="javascript:void(0);" aria-label="Next">
                <em class="icon-next"></em></a>',
                ]
            ]);  
          
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            [
                'title' =>'Plan Name',
                'data'=>'plan',
                'orderable'=>false,
            ],
            [
                'title' =>'Days',
                'data'=>'days',
                'orderable'=>false,
            ],
            [
                'title' =>'Price',
                'data'=>'price',
                'orderable'=>false,
            ],
            [
                'data' => 'status',
                'title' => 'Action',
                'searchable'=>false,
                'orderable'=>false,
                'exportable' => false,
            ]
        
        ];
    }
}
