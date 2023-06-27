<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Auth;
use Session;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Tasks;
use App\Models\AssignTask;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    function users(request $request){
        if($request->submit == "Submit"){
            $insert_array = array(
                            'privilage' => strip_tags($request->privilage),
                            'name' => strip_tags($request->name),
                            'email' => strip_tags($request->email),
                            'mobile' => strip_tags($request->mobile),
                            'password' => Hash::make(strip_tags($request->password)),
            );
            $validator = Validator::make($insert_array, [
                                        'privilage' => 'required|string',
                                        'name' => 'required|string',
                                        'email' => 'required|email|unique:users',
                                        'mobile' => 'required|regex:/^[0-9]{10}$/',
                                        'password' => 'required',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                User::insert($insert_array);
                $request->session()->flash('alert-success', 'User added successfully!');
                return redirect()->back();
            }
        }
        else if($request->submit == "Update" && $request->edit_id != ""){
            $update_array = array(
                            'privilage' => strip_tags($request->privilage),
                            'name' => strip_tags($request->name),
                            'email' => strip_tags($request->email),
                            'mobile' => strip_tags($request->mobile),
                            
            );
            $validator = Validator::make($update_array, [
                                        'privilage' => 'required|string',
                                        'name' => 'required|string',
                                        'mobile' => 'required|regex:/^[0-9]{10}$/',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                User::where('id',$request->edit_id)->update($update_array);
                $request->session()->flash('alert-success', 'User updated successfully!');
                return redirect()->back();
            }
        }
        else if($request->submit == "Delete" && $request->del_id != ""){
            User::where('id',$request->del_id)->update(['status' => 1]);
            $request->session()->flash('alert-success', 'User deleted successfully!');
            return redirect()->back();
        }
        else if($request->submit == "Block" && $request->blkunblk_id != ""){
            User::where('id',$request->blkunblk_id)->update(['block_status' => 1]);
            $request->session()->flash('alert-success', 'User blocked successfully!');
            return redirect()->back();
        }
        else if($request->submit == "Un-Block" && $request->blkunblk_id != ""){
            User::where('id',$request->blkunblk_id)->update(['block_status' => 0]);
            $request->session()->flash('alert-success', 'User un-blocked successfully!');
            return redirect()->back();
        }
        else if($request->submit == "Update Password" && $request->reset_id != ""){
            User::where('id',$request->reset_id)->update(['password' => Hash::make(strip_tags($request->password))]);
            $request->session()->flash('alert-success', 'User password resetted successfully!');
            return redirect()->back();
        }
        $data['users'] = User::select('id','name','email','created_at','privilage','block_status','mobile')
                            ->where('status',0)
                            ->get();
        return view('users',$data);
    }

    function user_edit(request $request){
        if($request->id != ""){
            $data = User::select('id','name','email','privilage','mobile','block_status')
                        ->where('id',$request->id)
                        ->first();
            echo json_encode($data);
        }
    }

    function manage_task(request $request){
        if($request->submit == "Submit"){
            $insert_array = array(
                            'title' => strip_tags($request->title),
                            'description' => strip_tags($request->description),
                            'created_by' => Auth::user()->id
            );
            $validator = Validator::make($insert_array, [
                                        'title' => 'required|string',
                                        'description' => 'required|string',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                Tasks::insert($insert_array);
                $recipientEmail = "admin@otaskit.com";
                $subject = "Task is added";
                $content = "<b>Title : <b>".strip_tags($request->title)."<br><b>Description : <b>".strip_tags($request->description);
            
                Mail::raw($content, function ($message) use ($recipientEmail, $subject) {
                    $message->to($recipientEmail)
                        ->subject($subject);
                });
                $request->session()->flash('alert-success', 'Task added successfully!');
                return redirect()->back();
            }
        }
        else if($request->submit == "Update" && $request->edit_id != ""){
            $update_array = array(
                            'title' => strip_tags($request->title),
                            'description' => strip_tags($request->description),
                            'created_by' => Auth::user()->id
            );
            $validator = Validator::make($update_array, [
                                        'title' => 'required|string',
                                        'description' => 'required|string',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                Tasks::where('id',$request->edit_id)->update($update_array);
                $request->session()->flash('alert-success', 'Task updated successfully!');
                return redirect()->back();
            }
        }
        else if($request->submit == "Delete" && $request->del_id != ""){
            Tasks::where('id',$request->del_id)->update(['status' => 1]);
            $request->session()->flash('alert-success', 'Task deleted successfully!');
            return redirect()->back();
        }
        $data['tasks'] = Tasks::select('tasks.id','tasks.title','tasks.description','tasks.created_at','users.name')
                            ->join('users','users.id','tasks.created_by')
                            ->where('tasks.status',0)
                            ->get();
        return view('tasks',$data);
    }

    function manage_task_edit(request $request){
        if($request->id != ""){
            $data = Tasks::select('id','title','description')
                        ->where('id',$request->id)
                        ->first();
            echo json_encode($data);
        }
    }

    function assign_task(request $request){
        $f_user = $f_tasks = "";
        if($request->submit == "Submit"){
            $insert_array = array(
                            'userid' => strip_tags($request->user),
                            'taskid' => strip_tags($request->tasks),
                            'created_by' => Auth::user()->id
            );
            $validator = Validator::make($insert_array, [
                                        'userid' => 'required|numeric',
                                        'taskid' => 'required|numeric',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                $recipientEmail = User::select('email')->where('id',$request->userid)->first()->email;
                $task = Tasks::select('title','description')->where('id',$request->taskid)->first();
                $subject = "Task is assigned to u";
                $content = "<b>Title : <b>".$task->title."<br><b>Description : <b>".$task->description;
            
                Mail::raw($content, function ($message) use ($recipientEmail, $subject) {
                    $message->to($recipientEmail)
                        ->subject($subject);
                });
                AssignTask::insert($insert_array);
                $request->session()->flash('alert-success', 'Task assigned successfully!');
                return redirect()->back();
            }
        }
        else if($request->submit == "Update" && $request->edit_id != ""){
            $update_array = array(
                            'userid' => strip_tags($request->user),
                            'taskid' => strip_tags($request->tasks),
                            'created_by' => Auth::user()->id
            );
            $validator = Validator::make($update_array, [
                                        'userid' => 'required|numeric',
                                        'taskid' => 'required|numeric',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{
                $check = AssignTask::select('taskstatus')->where('id',$request->edit_id)->where('taskstatus',0)->first();
                if($check != ""){
                    AssignTask::where('id',$request->edit_id)->update($update_array);
                    $request->session()->flash('alert-success', 'Task assign updated successfully!');
                    return redirect()->back();
                }
                else{
                    $request->session()->flash('alert-danger', 'Task already stated!');
                    return redirect()->back();
                }
            }
        }
        else if($request->submit == "Delete" && $request->del_id != ""){
            AssignTask::where('id',$request->del_id)->update(['status' => 1]);
            $request->session()->flash('alert-success', 'Task assign deleted successfully!');
            return redirect()->back();
        }
        else if($request->submit == "Update Status" && $request->update_id != ""){
            $exist = AssignTask::select('taskstatus','taskstatus_time')
                        ->where('id',$request->update_id)
                        ->first();
            if($exist->taskstatus == 1 && $request->status == 2){
                $currentDateTime = Carbon::now();
                $timeDifference = $currentDateTime->diffInMinutes($exist->taskstatus_time);
                if ($timeDifference >= 5) {
                    AssignTask::where('id',$request->update_id)
                        ->update([
                                'taskstatus' => $request->status,
                                'taskstatus_time' => date('Y-m-d H:i:s')
                        ]);
                    $request->session()->flash('alert-success', 'Task status updated successfully!');
                    return redirect()->back();
                }
                else{
                    $request->session()->flash('alert-danger', 'The status cannot be changed at the moment.');
                    return redirect()->back();
                }
            }
            else{
                AssignTask::where('id',$request->update_id)
                    ->update([
                            'taskstatus' => $request->status,
                            'taskstatus_time' => date('Y-m-d H:i:s')
                    ]);
                $request->session()->flash('alert-success', 'Task status updated successfully!');
                return redirect()->back();
            }
        }
        else if($request->submit == "Filter"){
            if($request->user){ 
                $f_user = $request->user; 
                Session::put('f_user',$f_user);
            }
            if($request->tasks){ 
                $f_tasks = $request->tasks; 
                Session::put('f_tasks',$f_tasks);
            }
            return redirect()->back();
        }
        else if($request->submit == "Filter Reset"){
            $f_user = $f_tasks = "";
            Session::forget('f_user','f_tasks');
            return redirect()->back();
        }
        if(Auth::user()->privilage == 0){
            $data['users'] = User::select('id','name')
                                ->where('status',0)
                                ->get();
            $data['tasks'] = Tasks::select('id','title')
                                ->where('status',0)
                                ->get();
        }
        $data['f_user'] = $data['f_tasks'] = "";
        $assigned = AssignTask::select('assigntasks.id','assigntasks.taskstatus','assigntasks.taskstatus','tasks.title','users.name','users1.name as addedby','assigntasks.created_at','tasks.description')
                                ->join('tasks','tasks.id','assigntasks.taskid')
                                ->join('users','users.id','assigntasks.userid')
                                ->join('users as users1','users1.id','assigntasks.created_by')
                                ->where('assigntasks.status',0);
        if(Session::has('f_user')){
            $data['f_user'] = Session::has('f_user');
            $assigned->where('assigntasks.userid',$data['f_user']);
        }
        if(Session::has('f_tasks')){
            $data['f_tasks'] = Session::has('f_tasks');
            $assigned->where('assigntasks.taskid',$data['f_tasks']);
        }
        if(Auth::user()->privilage != 0){
            $assigned->where('assigntasks.userid',Auth::user()->id);
        }
        $assigned = $assigned->get();
        $data['assigned'] = $assigned;
        return view('assigntask',$data);
    }

    function assign_task_edit(request $request){
        if($request->id != ""){
            $data = AssignTask::select('id','userid','taskid')
                        ->where('id',$request->id)
                        ->first();
            echo json_encode($data);
        }
    }
}
