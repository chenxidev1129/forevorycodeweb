<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use App\Models\ProfileSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserTransectionDataTable extends DataTable
{

    protected $request = '';
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable->addColumn('check_box', function ($data) {
            
            return '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input checkBox" id="customCheck'.$data->id.'">
                        <label class="custom-control-label w-auto" for="customCheck1"></label>
                    </div>';
            })

            ->editColumn('status', function($data) {
                $class = ($data->status == 'active') ? 'active' : 'inactive';
                return '<span class="'.$class.' status">'.ucfirst($data->status).'</span>';
            })

            ->editColumn('created_at', function ($data) {
                return getConvertedDate($data->created_at, 1);
            })

            ->editColumn('created_time', function ($data) {
                return date('h:i A', strtotime($data->created_at));
            })

            ->editColumn('subscription_price', function ($data) {
                return "$".$data->subscription_price;
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
    public function query(ProfileSubscription $model, Request $request)
    {
        $userId = Auth::guard('user-web')->user()->id;
        return $model->with('subscription:id,plan','profile:id,profile_name')->whereHas('profile' , function($q) use($userId){
            $q->where('user_id', $userId);
        })->orderBy('id','desc');
       
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
                'title'=>' 
                <div  class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checkAll" id="customCheck2">
                    <label class="custom-control-label w-auto" for="customCheck2"></label>
                 </div>',
                'orderable'=>false,
                'searchable'=>false,
                'exportable'=>false,
                'printable'=>false, 
                'width' => '100px'  
            ],
            [
                'title' =>'ID',
                'data'=>'id',
                'orderable'=>false,
            ],
            [
                'title' => 'Profile Name',
                'data' => 'profile.profile_name',
                'searchable'=>true,
                'orderable'=>false,
                'exportable' => false,
            ],
            [
                'title' => 'Subscription Plan',
                'data' => 'subscription.plan',
                'searchable'=>true,
                'orderable'=>false,
                'exportable' => false,
            ],
            [
                'title' =>'Date',
                'data'=>'created_at',
                'orderable'=>false,
            ],
            [
                'title' =>'Time',
                'data'=>'created_time',
                'orderable'=>false,
                'searchable'=>false,
            ],
           
            [
                'title' =>'Amount',
                'data' =>'subscription_price',
                'orderable'=>false,
            ],
            [
                'title' => 'Status',
                'data' => 'status',
                'searchable'=>false,
                'orderable'=>false,
                'exportable' => false,
            ],
            
        ];
    }
}