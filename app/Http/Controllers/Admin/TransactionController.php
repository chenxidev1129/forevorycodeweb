<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\DataTables\TransactionDataTable;
use App\DataTables\ProfileTransectionDataTable;
use Illuminate\Http\Request;
class TransactionController extends Controller
{

    /**
     * @var transactionDataTable
     * @var profileTransectionDataTable
     */
    private $transactionDataTable;
    private $profileTransectionDataTable;
    
    public function __construct(TransactionDataTable $transactionDataTable, ProfileTransectionDataTable $profileTransectionDataTable)
    {

      $this->transactionDataTable = $transactionDataTable;
      $this->profileTransectionDataTable = $profileTransectionDataTable;
    }
    
    /**
     * Show transection list
     * @return view
     */
     public function index(){

        return $this->transactionDataTable->render('admin.transection.index');
     }

     
    /**
     * Function to show transection list.
     * @param Request
     * @return \Illuminate\Http\Response 
     */

    public function profileTransectionList(Request $request){
      return $this->profileTransectionDataTable->render('datatable', compact('request')); 
   }
   

}
