<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use carbon\Carbon;
class UserController extends Controller
{
   public function index(){
    return view('users.create');

   }

   public function store(Request $request){
    $request->session()->put('user_data', $request->all());

    $value = session('user_data');
    return $this->converting($value);
   }

   public function converting($value){
    $ret = '';
        $name_array=explode(' ', $value['name']);
        $last_name=$name_array[count($name_array)-1];
        $new_array=array_slice($name_array,0,count($name_array)-1);
         foreach ( $new_array as $word){
            $ret .= strtoupper($word[0]).'.';
            
         }
    $name_with_initial=$ret.$last_name;


    $reorder_address="";
    $address=$value['address'];
    $str = explode(",",$address);
    $reverse_array=array_reverse($str);
    $new_address = implode(" ,",$reverse_array);
    
   $contact_no=$value['contact_no'];
   $first_charators=substr($contact_no, 0, 3);
   $contact_no_type="";
   if($first_charators == "011"){
    $contact_no_type="Landline";
   }else{
    $contact_no_type="Mobile Number";
   }

  $rest_of_number=substr($contact_no,1);
  $plus_mark="+";
   $international_mobile_number= $plus_mark.$rest_of_number;
   


   $user_birtdate=$value['birthdate'];

   $age=\Carbon\Carbon::parse($user_birtdate)->diff(\Carbon\Carbon::now())->format('%y years');
   $age="Age"." ".$age.'.';

   $membership_cost=0;
   $membership=$value['membership'];
   $membership_value=0;
   if($membership == 'VIP'){
    $membership_cost=5000+(5000*12/100);
    $membership_value=5000;
   }
   elseif($membership == 'Gold'){
    $membership_cost=4000+(4000*12/100);
    $membership_value=4000;
   }
   else{
    $membership_cost=3000+(3000*12/100);
    $membership_value=3000;
   }

   return view("users.index", ["name_with_initial"=>$name_with_initial,'address'=>$new_address,
                                'contact_no_type'=>$contact_no_type,
                             'international_mobile_number'=>$international_mobile_number,
                           'gender'=>$value['gender'],
                            'membership_cost'=>$membership_cost,
                         'old_values'=>$value,'membership_value'=>$membership_value,
                        'age'=>$age]);



   }
}
