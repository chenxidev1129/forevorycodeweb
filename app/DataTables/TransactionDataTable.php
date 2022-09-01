<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use App\Models\ProfileSubscription;

class TransactionDataTable extends DataTable
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

        // $dataTable->addColumn('check_box', function ($data) {
        //     return '<div class="custom-control custom-checkbox">
        //                 <input type="checkbox" class="custom-control-input checkBox" id="customCheck'.$data->id.'">
        //                 <label class="custom-control-label w-auto" for="customCheck1"></label>
        //             </div>';
        //     })

        $dataTable->editColumn('status', function($data) {
                $class = ($data->status == 'active') ? 'active' : 'inactive';
                return '<span class="'.$class.' status">'.ucfirst($data->status).'</span>';
            })

            ->addColumn('user_name', function ($data) {
                $last_name = (!empty($data->profile->user->last_name)) ? $data->profile->user->last_name : ''; 
                return ucfirst($data->profile->user->first_name).' '.$last_name;
            })
            
            ->editColumn('id', function ($data) {
                return '<a href="javascript:void(0);" class="lightBlue-link">'.$data->id.'</a>';
            })


            ->editColumn('created_at', function ($data) {
                return getConvertedDate($data->created_at, 1);
            })

            ->editColumn('subscription_price', function ($data) {
                return "$".$data->subscription_price.'/'.$data->subscription->plan;
            })

            ->rawColumns($columns, 'action')
            ->addIndexColumn();
        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     * @param \App\ProfileSubscription 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProfileSubscription $model)
    {  
        return $model->with('profile.user','subscription:id,plan')->orderBy('id','desc');
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
                "dom" => '<"pull-left"f><"pull-right"l>tip',
                "bLengthChange"=> false,
                "sScrollXInner"=> "100%",
            ])

            ->language([
                "search"=> '',
                'searchPlaceholder' => "Search",
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
            // [
            //     'data'=>'check_box',
            //     'title'=>' 
            //     <div  class="custom-control custom-checkbox">
            //         <input type="checkbox" class="custom-control-input checkAll" id="customCheck2">
            //         <label class="custom-control-label w-auto" for="customCheck2"></label>
            //      </div>',
            //     'orderable'=>false,
            //     'searchable'=>false,
            //     'exportable'=>false,
            //     'printable'=>false, 
            //     'width' => '100px'  
            // ],
            [
                'title' =>'Invoice ID',
                'data'=>'id',
                'orderable'=>false,
            ],
            [
                'data'=>'user_name',
                'title'=>'Name',
                'searchable'=>true,
                'orderable'=>true,
            ],
            [
              
                'data'=>'profile.user.first_name',
                'orderable'=>true,
                'visible'=>false,
            ],
            [
                'data'=>'profile.user.last_name',
                'searchable'=>true,
                'orderable'=>true,
                'visible'=>false,
            ],
            [
                'title' =>'Date',
                'data'=>'created_at',
                'orderable'=>false,
            ],
            [
                'title' =>'Amount',
                'data' =>'subscription_price',
                'orderable'=>false,
            ],
            [
                'title' => 'Subscription Plan',
                'data' => 'subscription.plan',
                'searchable'=>false,
                'orderable'=>false,
                'exportable' => false,
            ],
            [
                'title' => 'Status',
                'data' => 'status',
                'searchable'=>false,
                'orderable'=>false,
                'exportable' => false,
            ]
        
        ];
    }
}