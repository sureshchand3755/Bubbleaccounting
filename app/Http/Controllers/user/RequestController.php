<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use URL;
use Session;
use Illuminate\Http\Request;
class RequestController extends Controller {
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
		require_once app_path("Http/helpers.php");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function deactiverequest(Request $request, $id=""){
		$id = base64_decode($id);
		$deactive =  1;
		\App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where('category_id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Deactive Success');
	}
	public function activerequest(Request $request, $id=""){
		$id = base64_decode($id);
		$deactive =  0;
		\App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where('category_id', $id)->update(['status' => $deactive ]);
		return redirect::back()->with('message','Active Success');		
	}
	public function deleterequest(Request $request, $id=""){
		$id = base64_decode($id);
		\App\Models\requestCategory::where('category_id', $id)->delete();
		\App\Models\requestSubCategory::where('category_id', $id)->delete();
		return redirect::back()->with('message','Delete Success');
	}
	public function requestsignature(Request $request){
		$id = base64_decode($request->get('id'));
		$value = $request->get('value');
		\App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where('category_id', $id)->update(['signature' => $value]);
	}
	public function requestadd(Request $request){
		$data['category_name'] = $request->get('category');
		$data['practice_code'] = Session::get('user_practice_code');
		$id = \App\Models\requestCategory::insertDetails($data);
		$items = $request->get('request_item');
		if(($items))
		{
			foreach($items as $item)
			{
				$dataitem['sub_category_name'] = $item;
				$dataitem['category_id'] = $id;
				$dataitem['practice_code'] = Session::get('user_practice_code');
				\App\Models\requestSubCategory::insert($dataitem);
			}
		}
		return redirect::back()->with('message','Category Added Successfully');
	}
	public function request_edit_category(Request $request)
	{
		$id = base64_decode($request->get('id'));
		$category_details = \App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where('category_id',$id)->first();
		$subcat_details = \App\Models\requestSubCategory::where('category_id',$id)->get();
		$subcate = '';
		if(($subcat_details))
		{
			foreach($subcat_details as $sub)
			{
				if($subcate == "")
				{
					$subcate = $sub->sub_category_name;
				}
				else{
					$subcate = $subcate.'||'.$sub->sub_category_name;
				}
			}
		}
		echo json_encode(array("category_name" => $category_details->category_name, 'sub_category_name' => $subcate));
	}
	public function request_edit_form(Request $request)
	{
		$id = base64_decode($request->get('category_id_edit'));
		$data['category_name'] = $request->get('category_edit');
		\App\Models\requestCategory::where('practice_code',Session::get('user_practice_code'))->where('category_id',$id)->update($data);
		$subcat_details = \App\Models\requestSubCategory::where('category_id',$id)->get();
		$items = $request->get('request_item_edit');
		if(($items))
		{
			foreach($items as $key => $item)
			{
				$dataitem['sub_category_name'] = $item;
				$dataitem['category_id'] = $id;
				$subid = $subcat_details[$key]->sub_category_id;
				\App\Models\requestSubCategory::where('sub_category_id',$subid)->update($dataitem);
			}
		}
		return redirect::back()->with('message','Category Update Success');
	}
	
}