<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Http\Request;
use App\User;


class AccountsDataTable extends DataTable
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

        $dataTable->editColumn('action', function($data) {

            $statusValue = ($data->status=='active')?"checked":"";
            $url = route('admin/update-accout-status',[$data->id]);
            $message = ($data->status =='active') ? "Are you sure you want to Deactivate?" : "Are you sure you want to Activate?";
            $status = ($data->status=='active')?"inactive":"active";

            return '<ul class="list-inline action">
                        <li class="list-inline-item">
                            <div class="switch switch-sm d-inline-block pr-2">
                                <label>
                                <input type="checkbox"  class="check" id="categoryCustomSwitch'.$data->id.'" '.$statusValue.'  onchange="updateAccountStatusModel($(this),'."'$message'".','."'$url'".','."'$status'".');">
                                    <span class="lever"></span>
                                </label>
                            </div> 
                        </li>
                        <li class="list-inline-item"><span>|</span></li>
                        <li class="list-inline-item"><a href="javascript:void(0);" onclick="editAccount('.$data->id.')">Edit</a></li>
                    </ul>';
            }) 

            ->addColumn('full_name', function ($data) {
                return '<a href="'.route('admin/profile-details',[$data->id]).'" class="lightBlue-link">'.ucfirst($data->first_name).' '.$data->last_name.'</a>';
            })

            ->editColumn('status', function ($data) {
                $class = ($data->status == 'active') ? 'active' :  'inactive';
                return  '<span id="'.$data->id.'status" class="'.$class.' status">'.' '.ucfirst($data->status);
            }) 
            
            ->editColumn('phone_number', function ($data) {
                
                if( preg_match( '/^(\d{3})(\d{3})(\d{4})$/', $data->phone_number,  $matches ) ){
                   return $matches[1] . '-' .$matches[2] . '-' . $matches[3];
                }else{
                    return $data->phone_number;
                }
            })

            ->editColumn('created_at', function ($data) {
                return getConvertedDate($data->created_at, 1);
            })
            
            ->filterColumn('full_name', function ($query, $keyword) {
                $sql = "CONCAT(users.first_name,' ',users.last_name) like ?";
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
        return User::withCount('profile')->where(['email_verified'=> '1', 'user_type'=> 'user'])->orderBy('id', 'desc');
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
            //     'title'=>'<div  class="custom-control custom-checkbox">
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
                'title' =>'Phone No',
                'data'=>'phone_number',
                'orderable'=>false,
            ],
            [
                'title' =>'	Email Address',
                'data'=>'email',
                'orderable'=>false,
            ],
            [   
                'class' => 'description',
                'title' =>'Address',
                'data' =>'address',
                'orderable'=>false,
            ],
            [
                'title' =>'Zip Code',
                'data' =>'zip_code',
                'orderable'=>false,
            ],
            [
                'title' =>'Country',
                'data' => 'country',
                'orderable'=>false,
          
            ],
            [
                'title' =>'State',
                'data' =>'state',
                'orderable'=>false,
          
            ],
            [
                'title' =>'City',
                'data' =>'city',
                'orderable'=>false,
             
            ],
            [
                'title' =>'Profiles',
                'data' =>'profile_count',
                'orderable'=>false,
                'searchable'=>false,
            ],
            [
                'title' =>'Status',
                'data' =>'status',
                'orderable'=>false,
            ],
            [
                'title' =>'Date Joined',
                'data' =>'created_at',
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