<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class AccessAccountsDataTable extends DataTable
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

            $statusValue = ($data->status=='active')?"checked":"";
            $url = route('admin/update-access-accout-status',[$data->id]);
            $message = ($data->status =='active') ? "Are you sure you want to Deactivate?" : "Are you sure you want to Activate?";
            $status = ($data->status=='active')?"inactive":"active";
            return '<ul class="list-inline action">
                        <li class="list-inline-item">
                            <div class="switch switch-sm d-inline-block pr-2">
                                <label>
                                <input type="checkbox"  class="check" id="categoryCustomSwitch'.$data->id.'" '.$statusValue.'  onchange="updateStatus($(this),'."'$message'".','."'$url'".','."'$status'".');">
                                    <span class="lever"></span>
                                </label>
                            </div> 
                        </li>
                        <li class="list-inline-item"><span>|</span></li>
                        <li class="list-inline-item"><a href="javascript:void(0);" onclick="loadAccountForm('.$data->id.')">Edit</a></li>
                    </ul>';
                    })

           ->addColumn('user_type', function ($data) {
                return ucfirst($data->user_type);
                })   

            ->addColumn('full_name', function ($data) {
                return ucfirst($data->first_name).' '.$data->last_name;
                })

            ->addColumn('security_level', function ($data) {
                    if($data->user_type == 'administrator'){
                        $class = 'active';
                        $accessType = "Full Access";  
                    }else{
                        $class = 'pending';
                        $accessType = "Accounts Only";  
                    } 
                    return  '<span class="'.$class.' status">'.' '.ucfirst($accessType);
                }) 

            ->filterColumn('full_name', function ($query, $keyword) {
                    $sql = "users.first_name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                }) 

            ->rawColumns($columns, 'action')
            ->addIndexColumn();
        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     * @param \App\User 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $user_id = Auth::guard('admin-web')->user()->id;
        return $model->select('users.*')->whereIn('user_type',['administrator','support'])->whereNotIn('id', [$user_id])->orderBy('id','desc');
       
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
                'title' =>'Name',
                'data'=>'full_name',
                'orderable'=>false,
            ],
            [
                'title' =>'Role',
                'data'=>'user_type',
                'orderable'=>false,
            ],
            [
                'title' =>'Email',
                'data'=>'email',
                'orderable'=>false,
            ],
            [
                'data' =>'security_level',
                'title' =>'Security Level',
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