<?php

namespace App\Http\Controllers;  
use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request){
    	if($request->isMethod('post')){
    		$data = $request->input();
    		if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password'],'admin'=>'1'])){
    			//echo "Success";die;
    			/*Session::put('adminSession',$data['email']);  adminSession variable that assigning email*/
    			return redirect('/admin/dashboard');// route given here 

    		}else{
    			//echo "Failed";die;
    			return redirect('/admin')->with('flash_message_error','Invalid User or Password');
    		}
    	}
    	return view('admin.admin_login');
    }

    public function dashboard(){
    	/* if (Session('adminSession')) {
    	 	//perform all dashboard tasks
    	 }else{
    	 	return redirect('admin')->with('flash_message_error','Please login to access'); 
    	 }*/


    	return view('admin.dashboard');
    }


    public function settings(){
    	return view('admin.settings');
    }


    public function chkpassword(Request $request){
        $data = $request->all(); //Coming through get url that send from ajax code 
        $current_password = $data['current_pwd'];
        $check_password = User::where(['admin'=>'1'])->first();
        if(Hash::check($current_password,$check_password->password)){
            echo "true";die;
        }else{
            echo "false";die; 
        }        
    }

    public function updatepassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>";print_r($data);die;
            $current_password = $data['current_pwd'];
            $check_password = User::where(['email'=>Auth::user()->email])->first();
            if(Hash::check($current_password,$check_password->password)){
                $password = bcrypt($data['new_pwd']);
                User::where('id','1')->update(['password'=>$password]);
                return redirect('/admin/settings')->with('flash_message_success','Password updated Successfully');
            }else{
                return redirect('/admin/settings')->with('flash_message_error','Incorrect Current Password');
            }
        }
    }
    public function logout(){
    	Session::flush();
    	return redirect('/admin')->with('flash_message_success','Logged out Successfully');
    }

}
