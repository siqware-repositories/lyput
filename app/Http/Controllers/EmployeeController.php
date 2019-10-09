<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountDetail;
use App\Employee;
use App\Invoice;
use App\InvoiceDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /*list*/
    public function list()
    {
        $stock_product = Employee::where('status',1)->get();
        return DataTables::of($stock_product)
            ->addColumn('action',function ($action){
                return '<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" id="employee-edit" data-id="'.$action->id.'" data-toggle="modal" data-target="#modal_action" class="dropdown-item text-success"><i class="icon-database-edit2"></i> កែប្រែ</a>
												<a href="#" id="employee-destroy" data-id="'.$action->id.'" class="dropdown-item text-warning"><i class="icon-database-remove"></i> លុប</a>
											</div>
										</div>';
            })
            ->make(true);
    }
    /*index*/
    public function index(){
        $sender= Account::whereIn('type',['assets'])->get();
        $receiver= Account::whereIn('type',['income'])->get();
        return view('employee.index',compact(['sender','receiver']));
    }
    /*create*/
    public function create(){
        return view('employee.create');
    }
    /*create*/
    public function salary_create(){
        $employee = Employee::where('status',1)->get();
        $sender = Account::whereIn('type',['income','owner-equity'])->get();
        $receiver= Account::whereIn('type',['expense'])->get();
        return view('employee.salary-create',compact(['employee','sender','receiver']));
    }
    /*store*/
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
            'gender'=>'required',
            'age'=>'required',
            'address'=>'required',
            'tel'=>'required'
        ]);
        if ($validator->passes()) {
            $newEmployee = Employee::create($input);
            if ($newEmployee){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*edit*/
    public function edit($id){
        $employee = Employee::findOrFail($id);
        return view('employee.edit',compact('employee'));
    }
    /*store*/
    public function update(Request $request,$id){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
            'gender'=>'required',
            'age'=>'required',
            'address'=>'required',
            'tel'=>'required'
        ]);
        if ($validator->passes()) {
            $employee = Employee::findorFail($id);
            $updateEmployee = $employee->update($input);
            if ($updateEmployee){
                return response()->json(['success'=>'Updated records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*destroy*/
    public function destroy($id){
        $employee = Employee::findOrFail($id);
        $employee->status = 0;
        $employee->save();
        if ($employee){
            return response()->json(['success'=>'deleted records.']);
        }else{
            return response()->json(['error'=>'cannot delete records.']);
        }
    }
    /*salary_store*/
    public function salary_store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender'=>'required',
            'date'=>'required',
            'receiver'=>'required',
            'employee.*.*'=>'required'
        ]);
        if ($validator->passes()) {
            /*invoice*/
            $totalQty = 0;
            $totalSalary = 0;
            foreach ($input['employee'] as $value){
                $totalSalary+=$value['salary'];
            }
            $_time = Carbon::now();
            $date_time = $input['date'].' '.$_time->toTimeString();
            $invoice = new Invoice();
            $invoice->amount = $totalSalary;
            $invoice->qty = $totalQty;
            $invoice->created_at = $date_time;
            $invoice->save();

            /*account*/
            //sender
            $sender = Account::findOrFail($input['sender']);
            $sender->balance -= $totalSalary;
            $sender->save();
            //sender detail
            AccountDetail::insert([
                'account_id'=>$sender->id,
                'invoice_id'=>$invoice->id,
                'desc'=>'ដកសម្រាប់ប្រាក់ខែបុគ្គលិក',
                'memo'=>'ដកសម្រាប់ប្រាក់ខែបុគ្គលិក',
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'balance'=>$totalSalary,
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $totalSalary;
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>$invoice->id,
                'desc'=>'ប្រាក់ខែបុគ្គលិក',
                'memo'=>'ប្រាក់ខែបុគ្គលិក',
                'is_receiver'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'balance'=>$totalSalary,
            ]);
            /*end account*/

            $invoice_data = [];
            foreach ($input['employee'] as $value){
                $invoice_data[]=[
                    'invoice_id'=>$invoice->id,
                    'stock_detail_id'=>$value['id'],
                    'amount'=>$value['salary'],
                    'qty'=>0,
                    'is_salary'=>true,
                    'created_at'=>$date_time,
                    'updated_at'=>Carbon::now(),
                ];
            }
            $invoice_detail = InvoiceDetail::insert($invoice_data);
            if ($invoice_detail){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*salary history*/
    public function salary_history(){
        $employee = Employee::all();
        return view('employee.salary-history',compact('employee'));
    }
    /*salary history*/
    public function salary_history_list($id){
        $salary_history = InvoiceDetail::where('is_salary',1)
            ->where('stock_detail_id',$id)
            ->orderBy('created_at','desc')
            ->get();
        return DataTables::of($salary_history)
            ->addColumn('name',function ($name){
                return $name->employee->name;
            })
            ->addColumn('tel',function ($tel){
                return $tel->employee->tel;
            })
            ->make(true);
    }
}
