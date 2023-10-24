<?php
namespace App\Http\Controllers\facility;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use DB;
use Input;
use Redirect;
use Session;
use Hash;
use Illuminate\Http\Request;
use URL;
use Illuminate\Support\Facades\Crypt;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DateTime;

class OutputToneController extends Controller
{
    public function __construct()
    {
        $this->middleware("facilityauth");
        require_once(app_path('Http/helpers.php'));
    }
    public function index(Request $request){
        $result = DB::table('ai_accrep_tone_default')->orderBy('id', 'DESC')->get();
        return view('facility/output_tones', array('title' => 'Output Tones', 'outputtonelist' => $result));
    }
    public function store(Request $request){
        $target = $request->get('target');
        $command = $request->get('command');
        $status = $request->get('status');
        DB::table('ai_accrep_tone_default')->insert(['target' => $target,'command' => $command,'status' => $status]);
        return redirect::back()->with('message','Output Tone Added Successfully');
    }
    public function edit(Request $request, $id=""){
        $id = base64_decode($id);
        $result = DB::table('ai_accrep_tone_default')->where('id', $id)->first();
        echo json_encode(array('target' => $result->target,'command' => $result->command, 'status' => $result->status, 'id' => $result->id));
    }
    public function update(Request $request){
        $target = $request->get('target');
        $command = $request->get('command');
        $status = $request->get('status');
        $id = $request->get('id');
        DB::table('ai_accrep_tone_default')->where('id', $id)->update(['target' => $target,'command' => $command,'status' => $status]);
        return redirect::back()->with('message','Output Tone Updated Successfully');
    }

    public function deactivated(Request $request, $id=""){
        $id = base64_decode($id);
        $deactive =  1;
        DB::table('ai_accrep_tone_default')->where('id', $id)->update(['status' => $deactive ]);
        return redirect::back()->with('message','Output Tone Deactived Successfully');
    }
    public function activated(Request $request, $id=""){
        $id = base64_decode($id);
        $active =  0;
        DB::table('ai_accrep_tone_default')->where('id', $id)->update(['status' => $active ]);
        return redirect::back()->with('message','Output Tone Activated Successfully');
    }
}
