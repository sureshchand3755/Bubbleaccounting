<?php 
namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use Illuminate\Http\Request;
class ActiveClientController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('userauth');
		require_once(app_path('Http/helpers.php'));
	}
    public function add_to_active_client_list(Request $request)
    {
        $practice_code = Session::get('user_practice_code');
        $user_id = Session::get('userid');
        $client_id = $request->get('client_id');
        $data=[
            'user_id' => $user_id,
            'client_id' => $client_id,
            'practice_code' => $practice_code,
        ];
        $check = \App\Models\ActiveClientList::where($data)->count();
        if($check==0){
            \App\Models\ActiveClientList::insert($data);
            echo "<span><a href='javascript:' style='color:red;' class='active_client_manager'>".$client_id." added to active client list</a> <a href='javascript:' class='a_remove_success_msg' style='color:red;'><i style='font-size:18px' class='fa a_remove_success_msg'>&#xf00d;</i></a></span>";
        }
        else{
            echo "0";
        }        
    }
    public function remove_active_client_list(Request $request)
    {
        $id=$request->get('id');
        \App\Models\ActiveClientList::where('id',$id)->delete();
    }
    public function get_active_client_list(Request $request)
    {
        $user_id=$request->get('user_id');
        $active_client_list = \App\Models\ActiveClientList::select('active_client_list.*','cm_clients.company')
            ->join('cm_clients','cm_clients.client_id','=','active_client_list.client_id')
            ->where('active_client_list.user_id',$user_id)->get();
        $html='';
        $index=1;
        foreach($active_client_list as $cl_list){
            $html.='<tr class="active_list_tr_'.$cl_list->id.'">
                <td style="border-top:1px solid silver;">'.$index.'</td>
                <td style="border-top:1px solid silver;">'.$cl_list->client_id.'</td>
                <td style="border-top:1px solid silver;">'.$cl_list->company.'</td>
                <td style="border-top:1px solid silver;">'.date('d-M-Y H:i',strtotime($cl_list->created_date)).'</td>
                <td style="border-top:1px solid silver;"><a href="javascript:" class="remove_active_list" data-element="'.$cl_list->id.'" style="color:red;">
                <span><i class="fa fa-trash-o remove_active_list" data-element="'.$cl_list->id.'" aria-hidden="true"></i></span></a></td>
            </tr>';
            $index++;
        }
        echo $html;
    }
    public function remove_all_active_client_list(Request $request)
    {
        $user_id=$request->get('user_id');
        \App\Models\ActiveClientList::where('user_id',$user_id)->delete();
    }
    public function export_active_client_list(Request $request) {
        $user_id=$request->get('user_id');
        $active_client_list = \App\Models\ActiveClientList::select('active_client_list.*','cm_clients.company')
            ->join('cm_clients','cm_clients.client_id','=','active_client_list.client_id')
            ->where('cm_clients.practice_code', Session::get('user_practice_code'))
            ->where('active_client_list.user_id',$user_id)->get();

        $columns = array('S.No', 'Client Code','Client Name','Created Date');
        $filename = time().'active_client_list.csv';
        $file = fopen('public/papers/'.$filename, 'w');
        fputcsv($file, $columns);

        $index=1;
        foreach($active_client_list as $cl_list){
            $columns2 = array($index, $cl_list->client_id, $cl_list->company,date('d-M-Y H:i',strtotime($cl_list->created_date)));
            fputcsv($file, $columns2);
            $index++;
        }
        fclose($file);

        echo $filename;
    }
}
