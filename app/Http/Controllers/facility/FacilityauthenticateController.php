<?php namespace App\Http\Controllers\facility;

use App\Http\Controllers\Controller;
use Validator;
use Input;
use DB;
use Response;
use Mail;
use Session;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use URL;
use DateTime;
use Redirect;

class FacilityauthenticateController extends Controller
{
    public function __construct()
    {
        $this->flag = 0;
        $this->middleware("facilityredirect", ["except" => "getLogout"]);
        require_once(app_path('Http/helpers.php'));
    }
    public function login()
    {
        return view("facility/login");
    }
    public function postLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "username"      => "required",
                "password"      => "required",
                "practice_code" => "required"
            ]
        );
        if ($validator->fails()) {
            return redirect("/facility")
                ->withInput($request->all())
                ->with("login_error", $validator->errors());
        } else {
            $username = strtolower($request->get("username"));
            $password = $request->get("password");
            $practice_code = strtolower($request->get("practice_code"));
            $facility_details = \App\Models\Facility::first();
            $pin = Crypt::decrypt($facility_details->password);
            if ($password == $pin && $username == strtolower($facility_details->email) && $practice_code == strtolower($facility_details->practice_code)) {
                $details = [];
                $facility = \App\Models\Facility::where("id", $facility_details->id)->first();
                if (!empty($facility)) {
                    $details = $facility;
                }
                if (!empty($details)) {
                    $sessn = ["facility_userid" => $details->id];
                    Session::put($sessn);
                    return redirect("/facility/profile");
                } else {
                    return redirect("/facility")
                        ->withInput()
                        ->with("error", "Invalid User Credentials");
                }
            } else {
                return redirect("/facility")
                    ->withInput()
                    ->with("error", "Invalid User Credentials");
            }
        }
    }
    public function forgot_password(Request $request) {
        $facility_details = \App\Models\Facility::first();
        $email_to = $facility_details->email;

        $check_temp = DB::table('temp_details')->where('email',$email_to)->first();
        if($check_temp) {
            $FourDigitRandomNumber = mt_rand(1000,9999);
            $dataotp['otp'] = $FourDigitRandomNumber;
            $dataotp['email'] = $email_to;
            $dataotp['resend_count'] = $check_temp->resend_count + 1;

            DB::table('temp_details')->where('id',$check_temp->id)->update($dataotp);
        }
        else{
            $FourDigitRandomNumber = mt_rand(1000,9999);
            $dataotp['otp'] = $FourDigitRandomNumber;
            $dataotp['email'] = $email_to;
            $dataotp['resend_count'] = 0;

            DB::table('temp_details')->insert($dataotp);
        }

        $default_image = DB::table("email_header_images")->first();
        if($default_image) {
            if($default_image->url == "") {
                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
            }
            else{
                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
            }
        }
        else{
            $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
        }
        $data["logo"] = $image_url;
        $data['reset_link'] = URL::to('facility/reset_password?verify='.Crypt::encrypt($email_to).'&valid='.Crypt::encrypt(date('Y-m-d H:i:s')).'');
        $data['email'] = $facility_details->email;
        $data['firstname'] = $facility_details->firstname;
        $data['otp'] = $FourDigitRandomNumber;

        $contentmessage = view("facility/forgot_password_request", $data);

        $subject = 'Bubble.ie: Please Reset your Password.';

        $email = new PHPMailer();
        $email->SetFrom($email_to); //Name is optional
        $email->Subject = $subject;
        $email->Body = $contentmessage;
        $email->IsHTML(true);
        $email->AddAddress($email_to);
        $email->Send();

        return redirect("/facility")
                        ->withInput()
                        ->with("message", "Reset Link has been sent to your Email Address.  ");
    }
    public function reset_password(Request $request) {
        $valid = Crypt::decrypt($request->get('valid'));
        $verify = Crypt::decrypt($request->get('verify'));

        $current_time = date('Y-m-d H:i:s');

        $to_time = strtotime($valid);
        $from_time = strtotime($current_time);
        $diff_mins = floor(abs($to_time - $from_time) / 60);
        if($diff_mins > 10) {
            return redirect("/facility")
                        ->withInput()
                        ->with("error", "Reset Link Expired. Please Regenerate a Link using Forgot Password.");
        }
        else{
            return view("facility/reset_password");
        }
    }
    public function update_reset_password(Request $request) {
        $password = $request->get('password');
        $otp = $request->get('otp');
        $email = Crypt::decrypt($request->get('verify_email'));

        $temp_details = DB::table('temp_details')->where('email',$email)->where('otp',$otp)->first();
        if($temp_details) {
            $data['password'] = Crypt::encrypt($password);
            DB::table('facility')->where('email',$email)->update($data);

            return redirect("/facility")
                        ->withInput()
                        ->with("message", "Password Changed Successfully.");
        }
        else{
            return Redirect::back()->with("error", "OTP is Invalid");
        }
    }
}