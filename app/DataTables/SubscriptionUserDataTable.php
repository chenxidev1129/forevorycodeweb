<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use App\Models\ProfileSubscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionUserDataTable extends DataTable
{

 
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
        $dataTable->addColumn('check_box', function ($data) {
            return '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkBox" id="customCheck'.$data->id.'">
                        <label class="custom-control-label" for="customCheck1"></label>
                    </div>';
            })
            
            ->addColumn('status', function($data) {
                
                $class = ($data->status == 'active') ? 'active' : 'inactive'; 
                return  '<span class="'.$class.' status">'.' '.ucfirst($class);
            })            
            
            ->editColumn('start_date', function($data) {
                return getConvertedDate($data->start_date, 1);
             })

             ->editColumn('end_date', function($data) {
                if(!empty($data->end_date)){
                    return getConvertedDate($data->end_date, 1);
                }else{
                    return 'Life Time';
                }
       
             })

             ->filterColumn('profile_name', function ($query, $keyword) {
                $sql = "profile.profile_name  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            }) 

            ->filterColumn('plan_name', function ($query, $keyword) {
                $sql = "subscription_plans.plan  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            }) 

            
            ->addColumn('action', function($data) {
                
            return '<ul class="list-inline action">
                        <li class="list-inline-item"><a href="javascript:void(0);" onclick="viewDetail('.$data->id.')">View Detail</a></li>
                    </ul>';
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
        $user_id = Auth::guard('user-web')->user()->id;
        return $model->select('profile_subscriptions.*','profile.profile_name','subscription_plans.plan as plan_name')
        ->join('subscription_plans', 'subscription_plans.id', '=', 'profile_subscriptions.plan_id')
        ->join('profiles as profile', function ($join) use($user_id){
            $join->on('profile.id', '=', 'profile_subscriptions.profile_id')->where('profile.user_id', $user_id);
          })->where('profile_subscriptions.status' ,'!=', 'canceled')->orderBy('profile_subscriptions.id','desc');

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
            [
                'data'=>'check_box',
                'title'=>'<div  class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checkAll" id="customCheck2">
                    <label class="custom-control-label" for="customCheck2"></label>
                 </div>',
                'orderable'=>false,
                'searchable'=>false,
                'exportable'=>false,
                'printable'=>false,
                'width' => '100px' 
            ],
            [
                'title' =>'Profile Name',
                'data'=>'profile_name',
                'orderable'=>false,
            ],
            [
                'title' =>'Subscription Plan',
                'data'=>'plan_name',
                'orderable'=>false,
            ],
            [
                'title' =>'Start Date',
                'data'=>'start_date',
                'orderable'=>false,
            ],
            [
                'title' =>'Expiry Date',
                'data'=>'end_date',
                'orderable'=>false,
            ],
            [
                'title' =>'Status',
                'data'=>'status',
                'orderable'=>false,
            ],
            [
                'data' => 'action',
                'title' => 'Action',
                'searchable'=>false,
                'orderable'=>false,
                'exportable' => false,
            ]
        
        ];
    }
}
