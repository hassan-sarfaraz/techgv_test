<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\APIPasswordRestPostRequest;
use App\Http\Requests\APISocialLoginPostRequest;
use App\Http\Requests\APIUpdateCustomerPostRequest;
use App\Models\Web\Shipping;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Lang;
use App\Http\Controllers\Web\AlertController;
use App\Models\Web\Customer;

use Illuminate\Support\Facades\DB;
// Requests
use App\Http\Requests\APISignInPostRequest;
use App\Http\Requests\APISignUpPostRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController {

    public function signin(APISignInPostRequest $request) {
        $response = [
            'status'    => "error",
            'message'   => "Unauthorised.",
        ];
        $authUser   = null;
        $validUser  = false;

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $authUser                   = Auth::user();
            $validUser                  = true;
        }else if(!empty($request->phone) and !empty($request->password)){
            $checkUser                  = User::where('phone',$request->phone)->first();

            if($checkUser and Hash::check($request->password, $checkUser->password)) {
                if(Auth::attempt(['email' => $checkUser->email, 'password' => $request->password])){
                    $authUser           = Auth::user();
                    $validUser          = true;
                }
            }
        }


        if(Auth::check() === true and $validUser === true) {
            $success['id']              = $authUser->id;
            $success['token']           = $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['username']        = $authUser->user_name;
            $success['nicename']        = $authUser->user_name;
            $success['displayname']     = $authUser->user_name;
            $success['firstname']       = $authUser->first_name;
            $success['lastname']        = $authUser->last_name;
            $success['nickname']        = $authUser->user_name;
            $success['email']           = $authUser->email;
            $success['phone']           = $authUser->phone ?: '';
            $success['phone_verified']  = (isset($authUser->phone_verified) and !empty($authUser->phone_verified)) ? $authUser->phone_verified : 0;
            $success['email_verified']  = (isset($authUser->email_verified) and !empty($authUser->email_verified)) ? $authUser->email_verified : 0;
            $success['url']             = "";
            $success['description']     = "";
            $success['capabilities']    = "";
            $success['custom_photo']    = asset($authUser->avatar) ?: '';

            $response = [
                'status'        => 'success',
                'cookie'        => '',
                'cookie_name'   => '',
                'user'          => $success,
            ];
        }

        return response()->json($response, 200);
    }

    public function signup(APISignUpPostRequest $request) {

        $user_name  = $request->get('user_name');
        $phone      = $request->get('mobile');
        $email      = $request->get('email');
        $password   = $request->get('password');
        $first_name = $request->get('first_name');
        $last_name  = $request->get('last_name');
        $role_id    = 2;

        $input['user_name']         = $user_name;
        $input['first_name']        = $first_name;
        $input['last_name']         = $last_name;
        $input['email']             = $email;
        $input['phone']             = $phone;
        $input['role_id']           = $role_id;
        $input['password']          = Hash::make($password);

        $user                       = User::create($input);
        $success['id']              = $user->id;
        $success['token']           = $user->createToken('MyAuthApp')->plainTextToken;
        $success['username']        = $user->user_name;
        $success['nicename']        = $user->user_name;
        $success['email']           = $user->email;
        $success['phone']           = $user->phone ?: '';
        $success['phone_verified']  = (isset($user->phone_verified) and !empty($user->phone_verified)) ? $user->phone_verified : 0;
        $success['email_verified']  = (isset($user->email_verified) and !empty($user->email_verified)) ? $user->email_verified : 0;
        $success['url']             = "";
        $success['registered']      = $user->created_at;
        $success['displayname']     = $user->user_name;
        $success['firstname']       = $user->first_name;
        $success['lastname']        = $user->last_name;
        $success['nickname']        = $user->user_name;
        $success['description']     =  "";
        $success['capabilities']    =  "";
        $success['custom_photo']    =  "";

        $response = [
            'status' => 'success',
            'user'    => $success,
        ];
        return response()->json($response, 200);
    }

    public function customerDetails(Request $request){

        if(Auth::check()) {
            $authUser = Auth::user();
            $billing  = [];
            $shipping = [];

            $addresses  = DB::table('address_book')
                        ->select('address_book.*','countries.countries_name')
                        ->leftJoin('countries', 'countries.countries_id', '=' ,'address_book.entry_country_id')
                        ->where('customers_id', $authUser->id)
                        ->where('entry_type', 1)
                        ->orderBy('address_book_id', 'DESC')
                        ->get();

            $addresses2 = DB::table('address_book')
                        ->select('address_book.*','countries.countries_name')
                        ->leftJoin('countries', 'countries.countries_id', '=' ,'address_book.entry_country_id')
                        ->where('customers_id', $authUser->id)
                        ->where('entry_type', 2)
                        ->orderBy('address_book_id', 'DESC')
                        ->get();

            if($addresses != null) {
                foreach ($addresses as $index => $address) {
                    $billing[$index]['displayname'] = (!empty($address)) ? $address->entry_firstname . ' ' . $address->entry_lastname : '';
                    $billing[$index]['first_name'] = $address->entry_firstname ?? $authUser->first_name;
                    $billing[$index]['last_name'] = $address->entry_lastname ?? $authUser->last_name;
                    $billing[$index]['company'] = $address->entry_company ?? "";
                    $billing[$index]['address_1'] = $address->entry_street_address ?? "";
                    $billing[$index]['address_2'] = "";
                    $billing[$index]['city'] = $address->entry_city ?? "";
                    $billing[$index]['state'] = $address->entry_state ?? "";
                    $billing[$index]['postcode'] = $address->entry_postcode ?? "";
                    $billing[$index]['country'] = $address->countries_name ?? "";
                    $billing[$index]['email'] = $authUser->email;
                    $billing[$index]['phone'] = $address->entry_phone ?? '';
                    $billing[$index]['billing_id'] = $address->address_book_id ?? '';
                }
            }
            if($addresses2 != null) {
                foreach ($addresses2 as $index => $address2) {
                    $shipping[$index]['displayname'] = (!empty($address2)) ? $address2->entry_firstname . ' ' . $address2->entry_lastname : '';
                    $shipping[$index]['first_name'] = $address2->entry_firstname ?? $authUser->first_name;
                    $shipping[$index]['last_name'] = $address2->entry_lastname ?? $authUser->last_name;
                    $shipping[$index]['company'] = $address2->entry_company ?? "";
                    $shipping[$index]['address_1'] = $address2->entry_street_address ?? "";
                    $shipping[$index]['address_2'] = "";
                    $shipping[$index]['city'] = $address2->entry_city ?? "";
                    $shipping[$index]['state'] = $address2->entry_state ?? "";
                    $shipping[$index]['postcode'] = $address2->entry_postcode ?? "";
                    $shipping[$index]['country'] = $address2->countries_name ?? "";
                    $shipping[$index]['email'] = $authUser->email;
                    $shipping[$index]['phone'] = $address2->entry_phone ?? '';
                    $shipping[$index]['shipping_id'] = $address2->address_book_id ?? '';
                }
            }

            $success['id']                  = $authUser->id;
            $success['date_created']        = date('Y-m-d H:i:s', strtotime($authUser->created_at));
            $success['date_created_gmt']    = $authUser->created_at;
            $success['date_modified']       = date('Y-m-d H:i:s', strtotime($authUser->updated_at));
            $success['date_modified_gmt']   = $authUser->updated_at;
            $success['email']               = $authUser->email;
            $success['displayname']         = $authUser->user_name;
            $success['first_name']          = $authUser->first_name;
            $success['last_name']           = $authUser->last_name;
            $success['role']                = "customer";
            $success['user_name']           = $authUser->user_name;
            $success['phone']               = $authUser->phone;
            $success['billing']             = $billing;
            $success['shipping']            = $shipping;
            $success['is_paying_customer']  = false;
            $success['orders_count']        = 0;
            $success['total_spent']         = 0;
            $success['avatar_url']          = asset($authUser->avatar) ?: '';
            $success['meta_data']           = [];
            $success['pgs_profile_image']   = asset($authUser->avatar) ?: '';
            $success['ios_app_url']         = "";
            return response()->json($success, 200);
        }else{
            $response = [
                'status'    => "error",
                'message'   => "Unauthorised.",
            ];
            return response()->json($response, 401);
        }
    }

    public function resetPassword(APIPasswordRestPostRequest $request){

        $authUser           = Auth::user();
        $password           = $request->password;
        $input['password']  = Hash::make($password);
        $user               = User::where('id',$authUser->id)->update($input);
        $response           = [
                                'status' => 'success',
                                'message'    => 'Your password has been reset.',
                            ];
        return response()->json($response, 200);
    }

    public function forgotPassword(Request $request){
        $title = array('pageTitle' => Lang::get("website.Forgot Password"));

        $password = substr(md5(uniqid(mt_rand(), true)) , 0, 8);

		$email    		  =   $request->email;
		$postData = array();

		//check email exist
        $customerObj = new Customer;
		$existUser = $customerObj->ExistUser($email);
        // dd($existUser);
		if(count($existUser)>0){
            $customerObj->UpdateExistUser($email,$password);
			$existUser[0]->password = $password;

			$myVar = new AlertController();
			$alertSetting = $myVar->forgotPasswordAlert($existUser);

            $response = [
                'status'    => "success",
                'message'   => Lang::get("website.Password has been sent to your email address"),
            ];
            return response()->json($response, 200);
		}else{
            $response = [
                'status'    => "error",
                'message'   => Lang::get("website.Email address does not exist"),
            ];
            return response()->json($response, 400);
            
		}
    }

    public function updateCustomer(APIUpdateCustomerPostRequest $request){
        $authUser               = Auth::user();
        $newdata                = [];
        $billing                = [];
        $shipping               = [];

        if($request->has('displayname') and !empty($request->get('displayname'))) {
            $newdata['user_name']    = $request->get('displayname');
        }
        if($request->has('first_name') and !empty($request->get('first_name'))) {
            $newdata['first_name']    = $request->get('first_name');
        }
        if($request->has('last_name') and !empty($request->get('last_name'))) {
            $newdata['last_name']    = $request->get('last_name');
        }
        if($request->has('password') and !empty($request->get('password'))) {
            $newdata['password']    = Hash::make($request->get('password'));
        }
        if($request->has('email') and !empty($request->get('email')) and $authUser->email != $request->get('email')) {
            $email_exists = DB::table('users')->where('email',$request->get('email'))->count();
            if($email_exists > 0) {
                    $failed = [
                        "message" => 'The given data was invalid.',
                        "errors" => [
                            "email" => ["This email address is already taken."]
                        ]
                    ];
                return response()->json($failed, 200);
            }
            $newdata['email']               = $request->get('email');
            $newdata['email_verified']      = 0;
        }


        if($request->has('phone') and !empty($request->get('phone')) and $authUser->phone != $request->get('phone')) {
            $phone_exists = DB::table('users')->where('phone',$request->get('phone'))->count();
            if($phone_exists > 0) {
                $failed = [
                    "message" => 'The given data was invalid.',
                    "errors" => [
                        "email" => ["This phone is already taken."]
                    ]
                ];
                return response()->json($failed, 200);
            }
            $newdata['phone']               = $request->get('phone');
            $newdata['phone_verified']      = 0;
        }



        // Billing Information
        if($request->has('billing') and !empty($request->get('billing')) and is_array($request->get('billing'))) {
            $billing_data = $request->get('billing');
            $address_book_data = array(
                'entry_firstname'               => $billing_data['first_name'] ?? "",
                'entry_lastname'                => $billing_data['last_name'] ?? "",
                'entry_street_address'          => $billing_data['address_1'] ?? "",
                'entry_postcode'            	=> $billing_data['postcode'] ?? "",
                'entry_city'             		=> $billing_data['city'] ?? "",
                'entry_state'            		=> $billing_data['state'] ?? "",
                'entry_country_id'            	=> get_country_id_by_iso($billing_data['country'] ?? ""),
                'customers_id'             		=> $authUser->id,
                'user_id'             		    => $authUser->id,
                'entry_type'             		=> 1,
                'entry_phone'            		=> $billing_data['phone'] ?? "",
            );
            // Update address
            if(isset($billing_data['billing_id']) and !empty($billing_data['billing_id']) and is_numeric($billing_data['billing_id'])){
                $address_book_id = $billing_data['billing_id'];
                DB::table('address_book')->where('address_book_id','=', $address_book_id)->update($address_book_data);
            }else{
                //add address into address book
                $address_book_id = DB::table('address_book')->insertGetId($address_book_data);
            }
            $newdata['default_address_id']  = $address_book_id;
        }

        // Billing Information
        if($request->has('shipping') and !empty($request->get('shipping')) and is_array($request->get('shipping'))) {
            $shipping_data = $request->get('shipping');
            $address_book_data = array(
                'entry_firstname'               => $shipping_data['first_name'] ?? "",
                'entry_lastname'                => $shipping_data['last_name'] ?? "",
                'entry_street_address'          => $shipping_data['address_1'] ?? "",
                'entry_postcode'            	=> $shipping_data['postcode'] ?? "",
                'entry_city'             		=> $shipping_data['city'] ?? "",
                'entry_state'            		=> $shipping_data['state'] ?? "",
                'entry_country_id'            	=> get_country_id_by_iso($shipping_data['country'] ?? ""),
                'customers_id'             		=> $authUser->id,
                'user_id'             		    => $authUser->id,
                'entry_type'             		=> 2,
                'entry_phone'            		=> $shipping_data['phone'] ?? "",
            );
            // Update address
            if(isset($shipping_data['shipping_id']) and !empty($shipping_data['shipping_id']) and is_numeric($shipping_data['shipping_id'])){
                $address_book_id = $shipping_data['shipping_id'];
                DB::table('address_book')->where('address_book_id','=', $address_book_id)->update($address_book_data);
            }else{
                //add address into address book
                $address_book_id = DB::table('address_book')->insertGetId($address_book_data);
            }
            $newdata['default_address_id']  = $address_book_id;
        }

        User::where('id',$authUser->id)->update($newdata);
        $user                   = User::where('id',$authUser->id)->first();

        $addresses = DB::table('address_book')
                    ->select('address_book.*','countries.countries_name')
                    ->leftJoin('countries', 'countries.countries_id', '=' ,'address_book.entry_country_id')
                    ->where('customers_id', $authUser->id)
                    ->where('entry_type', 1)
                    ->orderBy('address_book_id', 'DESC')
                    ->get();

        $addresses2 = DB::table('address_book')
                    ->select('address_book.*','countries.countries_name')
                    ->leftJoin('countries', 'countries.countries_id', '=' ,'address_book.entry_country_id')
                    ->where('customers_id', $authUser->id)
                    ->where('entry_type', 2)
                    ->orderBy('address_book_id', 'DESC')
                    ->get();

        if($addresses != null) {
            foreach ($addresses as $index => $address) {
                $billing[$index]['displayname'] = (!empty($address)) ? $address->entry_firstname.' '.$address->entry_lastname : '';
                $billing[$index]['first_name']  = $address->entry_firstname ?? $user->first_name;
                $billing[$index]['last_name']   = $address->entry_lastname ?? $user->last_name;
                $billing[$index]['company']     = $address->entry_company ?? "";
                $billing[$index]['address_1']   = $address->entry_street_address ?? "";
                $billing[$index]['address_2']   = "";
                $billing[$index]['city']        = $address->entry_city ?? "";
                $billing[$index]['state']       = $address->entry_state ?? "";
                $billing[$index]['postcode']    = $address->entry_postcode ?? "";
                $billing[$index]['country']     = $address->countries_name ?? "";
                $billing[$index]['email']       = $user->email;
                $billing[$index]['phone']       = $address->phone ?? '';
                $billing[$index]['billing_id']  = $address->address_book_id ?? '';
            }
        }

        if($addresses2 != null) {
            foreach ($addresses2 as $index => $address) {
                $shipping[$index]['displayname'] = (!empty($address)) ? $address->entry_firstname.' '.$address->entry_lastname : '';
                $shipping[$index]['first_name']  = $address->entry_firstname ?? $user->first_name;
                $shipping[$index]['last_name']   = $address->entry_lastname ?? $user->last_name;
                $shipping[$index]['company']     = $address->entry_company ?? "";
                $shipping[$index]['address_1']   = $address->entry_street_address ?? "";
                $shipping[$index]['address_2']   = "";
                $shipping[$index]['city']        = $address->entry_city ?? "";
                $shipping[$index]['state']       = $address->entry_state ?? "";
                $shipping[$index]['postcode']    = $address->entry_postcode ?? "";
                $shipping[$index]['country']     = $address->countries_name ?? "";
                $shipping[$index]['email']       = $user->email;
                $shipping[$index]['phone']       = $address->phone ?? '';
                $shipping[$index]['shipping_id'] = $address->address_book_id ?? '';
            }
        }

        $success['status']              = "success";
        $success['message']             = "User updated successfully";
        $success['user_id']             = $user->id;
        $success['id']                  = $user->id;
        $success['date_created']        = $user->created_at;
        $success['date_created_gmt']    = $user->created_at;
        $success['date_modified']       = $user->updated_at;
        $success['date_modified_gmt']   = $user->updated_at;
        $success['email']               = $user->email;
        $success['first_name']          = $user->first_name;
        $success['last_name']           = $user->last_name;
        $success['role']                = "customer";
        $success['user_name']           = $user->user_name;
        $success['phone']               = $user->phone;
        $success['billing']             = $billing;
        $success['shipping']            = $shipping;
        $success['is_paying_customer']  = false;
        $success['orders_count']        = 0;
        $success['total_spent']         = 0;
        $success['avatar_url']          = asset($user->avatar) ?: '';
        $success['meta_data']           = [];
        $success['pgs_profile_image']   = asset($user->avatar) ?: '';
        $success['ios_app_url']         = "";

        return response()->json($success, 200);
    }

    public function SocialLogin(APISocialLoginPostRequest $request) {
        $social_id      = $request->get('social_id');
        $name           = $request->get('name');
        $email          = $request->get('email');
        $device_token   = $request->get('device_token');
        $device_type    = $request->get('device_type');
        $user_image     = $request->get('user_image');
        $phone          = $request->get('phone', '');
        $role_id        = 2;
        $password       = "social_account";
        $firstname      = "";
        $lastname       = "";
        $full_name      = explode(" ", $name);
        if(is_array($full_name) and count($full_name) > 0) {
            $firstname = $full_name[0] ?? "";
            $lastname = $full_name[1] ?? "";
        }

        // Check if this user exists or not.
        $user = User::where("email",'=',$email)->where("social_id",'=',$social_id)->first();

        if(empty($user)) {

            // Check if phone is already taken.
            if(isset($phone) and !empty($phone)) {
                if(User::where("phone",'=',$phone)->exists()) {
                    $validator = Validator::make($request->all(),[]);
                    $validator->errors()->add('phone', __('This phone number is already taken.'));
                    $errors = (new ValidationException($validator))->errors();
                    throw new HttpResponseException(
                        response()->json([
                            'message'   => Collection::make($errors)->first()[0] ?? "",
                            'status'    => 'error',
                            'errors'    => $errors
                        ], 200)
                    );
                }
            }

            $new_user       = [];
            $full_avatar    = "";
            // Add avatar using blob from API
            if(isset($user_image['data']) and !empty($user_image['data'])) {
                $image_64 = $user_image['data'];
                $imageName = date('ymdhis').'.jpg';
                Storage::disk('avatar')->put($imageName, base64_decode($image_64));
                $new_user['avatar'] = 'resources/assets/images/user_profile/'.$imageName;
                $full_avatar        = asset('resources/assets/images/user_profile/'.$imageName);
            }
            $new_user['user_name']         = $name;
            $new_user['first_name']        = $firstname;
            $new_user['last_name']         = $lastname;
            $new_user['email']             = $email;
            $new_user['role_id']           = $role_id;
            $new_user['social_id']         = $social_id;
            $new_user['password']          = Hash::make($password);
            $new_user['phone_verified']    = 0;
            $new_user['email_verified']    = 0;
            $new_user['created_at']        = date('Y-m-d H:i:s');
            $new_user['phone']             = $phone;
            $user                          = User::create($new_user);

            DB::table('devices')->insert([
                "device_id"     => $device_token,
                "user_id"       => $user->id,
                "device_type"   => $device_type,
                "status"        => 1,
                "created_at"    => date('Y-m-d H:i:s'),
            ]);

            $success['id']              = $user->id;
            $success['token']           = $user->createToken('MyAuthApp')->plainTextToken;
            $success['username']        = $user->user_name;
            $success['nicename']        = $user->user_name;
            $success['email']           = $user->email;
            $success['phone']           = $user->phone ?: '';
            $success['phone_verified']  = (isset($user->phone_verified) and !empty($user->phone_verified)) ? $user->phone_verified : 0;
            $success['email_verified']  = (isset($user->email_verified) and !empty($user->email_verified)) ? $user->email_verified : 0;
            $success['url']             = "";
            $success['registered']      = $user->created_at;
            $success['displayname']     = $user->user_name;
            $success['firstname']       = $user->first_name;
            $success['lastname']        = $user->last_name;
            $success['nickname']        = $user->user_name;
            $success['phone']           = $user->phone;
            $success['description']     =  "";
            $success['capabilities']    =  "";
            $success['custom_photo']    =  $full_avatar;
            $response = [
                'process'   => 'signup_social',
                'status'    => 'success',
                'user'      => $success,
            ];
        }else{
            $success['id']              = $user->id;
            $success['token']           = $user->createToken('MyAuthApp')->plainTextToken;
            $success['username']        = $user->user_name;
            $success['nicename']        = $user->user_name;
            $success['displayname']     = $user->user_name;
            $success['firstname']       = $user->first_name;
            $success['lastname']        = $user->last_name;
            $success['nickname']        = $user->user_name;
            $success['email']           = $user->email;
            $success['phone']           = $user->phone;
            $success['phone_verified']  = (isset($user->phone_verified) and !empty($user->phone_verified)) ? $user->phone_verified : 0;
            $success['email_verified']  = (isset($user->email_verified) and !empty($user->email_verified)) ? $user->email_verified : 0;
            $success['url']             = "";
            $success['description']     = "";
            $success['capabilities']    = "";
            $success['custom_photo']    = asset($user->avatar) ?: '';

            $response = [
                'process'       => 'signin_social',
                'status'        => 'success',
                'cookie'        => '',
                'cookie_name'   => '',
                'user'          => $success,
            ];
        }

        return response()->json($response, 200);
    }

}
