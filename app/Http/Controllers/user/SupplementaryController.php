<?php namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Session;
use URL;
use PDF;
use Response;
use PHPExcel; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Hash;
use ZipArchive;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class SupplementaryController extends Controller {
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
		date_default_timezone_set("Europe/Dublin");
		require_once app_path("Http/helpers.php");
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function supplementary_manager(Request $request){
		$supple = \App\Models\supplementaryManager::where('status', 0)->get();		
		return view('user/supplementary/supplementary_manager', array('title' => 'Supplementary Manger', 'supplelist' => $supple));
	}
	public function supplementary_add(Request $request){
		$data['number'] = $request->get('number');
		$data['name'] = $request->get('name');
		$data['description'] = $request->get('description');
		$id = \App\Models\supplementaryManager::insertDetails($data);
		\App\Models\supplementaryFormula::insert(['supple_id' => $id,'name' => $data['name']]);
		return redirect('user/supplementary_note_create/'.base64_encode($id));
	}
	public function supple_number_check(Request $request){
		$number = $request->get('number');
		$validate = \App\Models\supplementaryManager::where('number', $number)->count();
		if($validate != 0 )
			$valid = false;
		else
			$valid = true;
		echo json_encode($valid);
		exit;
	}
	public function supplementary_note_create(Request $request, $id=""){
		$id = base64_decode($id);	
		$note_details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
		return view('user/supplementary/supplementary_forumula', array('title' => 'Supplementary Manger', 'id_supple_main' => $id, 'notelist' => $note_details));		
	}
	public function edit_supplementary_note(Request $request, $id=""){
		$id = base64_decode($id);	
		$note_details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
		return view('user/supplementary/edit_supplementary_forumula', array('title' => 'Supplementary Manger', 'id_supple_main' => $id, 'notelist' => $note_details));		
	}
	public function supple_value_update(Request $request){
		$id = $request->get('id');
		$data['value_1'] = $request->get('valueinput1');
		$data['value_2'] = $request->get('valueinput2');
		$data['value_3_number'] = $request->get('valueinput3');
		$data['value_3_output'] = $request->get('valueinput3');
		$data['value_3'] = $request->get('value_3_type');
		$data['value_3_combo1'] = $request->get('value_3_one');
		$data['value_3_combo2'] = $request->get('value_3_two');
		$data['value_3_formula'] = $request->get('value_3_formula');
		$data['value_4_number'] = $request->get('valueinput4');
		$data['value_4_output'] = $request->get('valueinput4');
		$data['value_4'] = $request->get('value_4_type');
		$data['value_4_combo1'] = $request->get('value_4_one');
		$data['value_4_combo2'] = $request->get('value_4_two');
		$data['value_4_formula'] = $request->get('value_4_formula');
		$data['value_5_number'] = $request->get('valueinput5');
		$data['value_5_output'] = $request->get('valueinput5');
		$data['value_5'] = $request->get('value_5_type');
		$data['value_5_combo1'] = $request->get('value_5_one');
		$data['value_5_combo2'] = $request->get('value_5_two');
		$data['value_5_formula'] = $request->get('value_5_formula');
		$data['value_6_number'] = $request->get('valueinput6');
		$data['value_6_output'] = $request->get('valueinput6');
		$data['value_6'] = $request->get('value_6_type');
		$data['value_6_combo1'] = $request->get('value_6_one');
		$data['value_6_combo2'] = $request->get('value_6_two');
		$data['value_6_formula'] = $request->get('value_6_formula');
		$data['invoice_number'] = $request->get('valueinvoice');
		$data['value_1_description'] = $request->get('valuedes1');
		$data['value_2_description'] = $request->get('valuedes2');
		$data['value_3_description'] = $request->get('valuedes3');
		$data['value_4_description'] = $request->get('valuedes4');
		$data['value_5_description'] = $request->get('valuedes5');
		$data['value_6_description'] = $request->get('valuedes6');
		$data['name'] = $request->get('valuetitle');
		\App\Models\supplementaryFormula::where('supple_id', $id)->update($data);
		$attachments = \App\Models\supplementaryFormulaAttachments::where('formula_id', $id)->get();
		if(($attachments))
		{
			foreach($attachments as $attach)
			{
				$supp_text = $attach->magic_text;
				$supp_text = str_replace("<<value1>>",$data['value_1'],$supp_text);
				$supp_text = str_replace("<<value2>>",$data['value_2'],$supp_text);
				$supp_text = str_replace("<<value3>>",$data['value_3_output'],$supp_text);
				$supp_text = str_replace("<<value4>>",$data['value_4_output'],$supp_text);
				$supp_text = str_replace("<<value5>>",$data['value_5_output'],$supp_text);
				$supp_text = str_replace("<<value6>>",$data['value_6_output'],$supp_text);
				$supp_text = str_replace("<<invoice>>",$data['invoice_number'],$supp_text);
				\App\Models\supplementaryFormulaAttachments::where('id',$attach->id)->update(['supplementary_text' => $supp_text]);
			}
		}
		\App\Models\supplementaryFormulaAttachments::where('formula_id', $id)->update($data);
	}
	public function supple_value_update_edit(Request $request){
		$id = $request->get('id');
		$checkid = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
		$data['value_1'] = $request->get('valueinput1');
		$data['value_2'] = $request->get('valueinput2');
		$data['value_3_number'] = $request->get('valueinput3');
		$data['value_3_output'] = $request->get('valueinput3');
		$data['value_3'] = $request->get('value_3_type');
		$data['value_3_combo1'] = $request->get('value_3_one');
		$data['value_3_combo2'] = $request->get('value_3_two');
		$data['value_3_formula'] = $request->get('value_3_formula');
		$data['value_4_number'] = $request->get('valueinput4');
		$data['value_4_output'] = $request->get('valueinput4');
		$data['value_4'] = $request->get('value_4_type');
		$data['value_4_combo1'] = $request->get('value_4_one');
		$data['value_4_combo2'] = $request->get('value_4_two');
		$data['value_4_formula'] = $request->get('value_4_formula');
		$data['value_5_number'] = $request->get('valueinput5');
		$data['value_5_output'] = $request->get('valueinput5');
		$data['value_5'] = $request->get('value_5_type');
		$data['value_5_combo1'] = $request->get('value_5_one');
		$data['value_5_combo2'] = $request->get('value_5_two');
		$data['value_5_formula'] = $request->get('value_5_formula');
		$data['value_6_number'] = $request->get('valueinput6');
		$data['value_6_output'] = $request->get('valueinput6');
		$data['value_6'] = $request->get('value_6_type');
		$data['value_6_combo1'] = $request->get('value_6_one');
		$data['value_6_combo2'] = $request->get('value_6_two');
		$data['value_6_formula'] = $request->get('value_6_formula');
		$data['invoice_number'] = $request->get('valueinvoice');
		$data['value_1_description'] = $request->get('valuedes1');
		$data['value_2_description'] = $request->get('valuedes2');
		$data['value_3_description'] = $request->get('valuedes3');
		$data['value_4_description'] = $request->get('valuedes4');
		$data['value_5_description'] = $request->get('valuedes5');
		$data['value_6_description'] = $request->get('valuedes6');
		$data['name'] = $request->get('valuetitle');
		\App\Models\supplementaryFormula::where('supple_id', $checkid->supple_id)->update($data);
		$attachments = \App\Models\supplementaryFormulaAttachments::where('formula_id', $checkid->formula_id)->get();
		if(($attachments))
		{
			foreach($attachments as $attach)
			{
				$supp_text = $attach->magic_text;
				$supp_text = str_replace("<<value1>>",$data['value_1'],$supp_text);
				$supp_text = str_replace("<<value2>>",$data['value_2'],$supp_text);
				$supp_text = str_replace("<<value3>>",$data['value_3_output'],$supp_text);
				$supp_text = str_replace("<<value4>>",$data['value_4_output'],$supp_text);
				$supp_text = str_replace("<<value5>>",$data['value_5_output'],$supp_text);
				$supp_text = str_replace("<<value6>>",$data['value_6_output'],$supp_text);
				$supp_text = str_replace("<<invoice>>",$data['invoice_number'],$supp_text);
				\App\Models\supplementaryFormulaAttachments::where('id',$attach->id)->update(['supplementary_text' => $supp_text]);
			}
		}
		\App\Models\supplementaryFormulaAttachments::where('formula_id', $checkid->formula_id)->update($data);
	}
	public function supple_type_update(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		$number='';
		if($type == 3){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_3' => $value, 'value_3_number' => $number]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			echo json_encode(array('type_3' => $details->value_3_number, 'combo1' => $details->value_3_combo1, 'combo2' => $details->value_3_combo2, 'formula' => $details->value_3_formula, 'output' => $details->value_3_output ));
		}
		elseif($type == 4){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_4' => $value, 'value_4_number' => $number]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			echo json_encode(array('type_4' => $details->value_4_number, 'combo1' => $details->value_4_combo1, 'combo2' => $details->value_4_combo2, 'formula' => $details->value_4_formula, 'output' => $details->value_4_output ));
		}
		elseif($type == 5){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_5' => $value, 'value_5_number' => $number]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			echo json_encode(array('type_5' => $details->value_5_number, 'combo1' => $details->value_5_combo1, 'combo2' => $details->value_5_combo2, 'formula' => $details->value_5_formula, 'output' => $details->value_5_output ));
		}
		elseif($type == 6){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_6' => $value, 'value_6_number' => $number]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			echo json_encode(array('type_6' => $details->value_6_number, 'combo1' => $details->value_6_combo1, 'combo2' => $details->value_6_combo2, 'formula' => $details->value_6_formula, 'output' => $details->value_6_output ));
		}		
	}
	public function supple_type_update_edit(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		$number='';
		if($type == 3){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_3' => $value, 'value_3_number' => $number]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			echo json_encode(array('type_3' => $details->value_3_number, 'combo1' => $details->value_3_combo1, 'combo2' => $details->value_3_combo2, 'formula' => $details->value_3_formula, 'output' => $details->value_3_output ));
		}
		elseif($type == 4){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_4' => $value, 'value_4_number' => $number]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			echo json_encode(array('type_4' => $details->value_4_number, 'combo1' => $details->value_4_combo1, 'combo2' => $details->value_4_combo2, 'formula' => $details->value_4_formula, 'output' => $details->value_4_output ));
		}
		elseif($type == 5){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_5' => $value, 'value_5_number' => $number]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			echo json_encode(array('type_5' => $details->value_5_number, 'combo1' => $details->value_5_combo1, 'combo2' => $details->value_5_combo2, 'formula' => $details->value_5_formula, 'output' => $details->value_5_output ));
		}
		elseif($type == 6){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_6' => $value, 'value_6_number' => $number]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			echo json_encode(array('type_6' => $details->value_6_number, 'combo1' => $details->value_6_combo1, 'combo2' => $details->value_6_combo2, 'formula' => $details->value_6_formula, 'output' => $details->value_6_output ));
		}		
	}
	public function supple_comboone_update(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		if($type == 3){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_3_combo1' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_3_formula == 1 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
				}								
			}
			if($details->value_3_formula == 2 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
				}							
			}
			if($details->value_3_formula == 3 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_3_formula == 4 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
				}								
			}
			if($details->value_3_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_3_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		elseif($type == 4){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_4_combo1' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();			
			if($details->value_4_formula == 1 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == 2 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == 3 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == 4 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_4_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		elseif($type == 5){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_5_combo1' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_5_formula == 1 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 2 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 3 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 4 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_5_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		if($type == 6){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_6_combo1' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_6_formula == 1 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 2 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 3 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 4 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_6_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_6_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_6_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_6_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
	}
	public function supple_comboone_update_edit(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		if($type == 3){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_3_combo1' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_3_formula == 1 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
				}								
			}
			if($details->value_3_formula == 2 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
				}							
			}
			if($details->value_3_formula == 3 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_3_formula == 4 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
				}								
			}
			if($details->value_3_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_3_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		elseif($type == 4){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_4_combo1' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();			
			if($details->value_4_formula == 1 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == 2 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == 3 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == 4 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_4_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		elseif($type == 5){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_5_combo1' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_5_formula == 1 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 2 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 3 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 4 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_5_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		if($type == 6){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_6_combo1' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_6_formula == 1 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 2 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 3 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 4 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_6_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_6_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_6_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_6_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
	}
	public function supple_formula_update(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		if($type == 3){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_3_formula' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_3_formula == 1 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
				}								
			}
			if($details->value_3_formula == 2 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
				}
			}
			if($details->value_3_formula == 3 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_3_formula == 4 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
				}							
			}			
			if($details->value_3_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_3_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		if($type == 4){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_4_formula' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();			
			if($details->value_4_formula == 1 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_4_formula == 2 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_4_formula == 3 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_4_formula == 4 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';	
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_4_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		if($type == 5){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_5_formula' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_5_formula == 1 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 2 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 3 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 4 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_5_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		elseif($type == 6){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_6_formula' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_6_formula == 1 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 2 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 3 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 4 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_6_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_6_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_6_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_6_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
	}
	public function supple_formula_update_edit(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		if($type == 3){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_3_formula' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_3_formula == 1 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
				}								
			}
			if($details->value_3_formula == 2 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
				}
			}
			if($details->value_3_formula == 3 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_3_formula == 4 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
				}							
			}			
			if($details->value_3_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_3_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_3_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		if($type == 4){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_4_formula' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();			
			if($details->value_4_formula == 1 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_4_formula == 2 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_4_formula == 3 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_4_formula == 4 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';	
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_4_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_4_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		if($type == 5){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_5_formula' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_5_formula == 1 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 2 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
			}
			if($details->value_5_formula == 3 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';						
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 4 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_5_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_5_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		elseif($type == 6){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_6_formula' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_6_formula == 1 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 2 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 3 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 4 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_6_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_6_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_6_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_6_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
	}
	public function supple_combotwo_update(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		if($type == 3){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_3_combo2' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_3_formula == 1 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
				}							
			}
			if($details->value_3_formula == 2 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
				}				
			}
			if($details->value_3_formula == 3 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
				}				
			}
			if($details->value_3_formula == 4 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
				}				
			}
			if($details->value_3_formula == '' ){
				$message = '';
				$value = '2';
				$output ='';
			}
			if($details->value_3_combo1 == ''){
				$message = '';
				$value = '2';
				$output ='';
			}
			if($details->value_3_combo2 == ''){
				$message = '';
				$value = '2';
				$output ='';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_3_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		if($type == 4){			
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_4_combo2' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();			
			if($details->value_4_formula == 1 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
			}
			if($details->value_4_formula == 2 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
			}
			if($details->value_4_formula == 3 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
			}
			if($details->value_4_formula == 4 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
			}
			if($details->value_4_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_4_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		if($type == 5){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_5_combo2' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_5_formula == 1 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 2 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 3 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 4 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_5_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_5_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_5_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		if($type == 6){
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_6_combo2' => $value]);
			$details = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
			if($details->value_6_formula == 1 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 2 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 3 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 4 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_6_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_6_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_6_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormula::where('supple_id', $id)->update(['value_6_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
	}
	public function supple_combotwo_update_edit(Request $request){
		$id = $request->get('id');
		$value = $request->get('value');
		$type = $request->get('type');
		if($type == 3){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_3_combo2' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_3_formula == 1 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
				}							
			}
			if($details->value_3_formula == 2 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';						
					}
				}				
			}
			if($details->value_3_formula == 3 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';						
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
				}				
			}
			if($details->value_3_formula == 4 ){
				if($details->value_3_combo1 == 1){
					if($details->value_3_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}					
				}
				elseif($details->value_3_combo1 == 2){
					if($details->value_3_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_3_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
				}				
			}
			if($details->value_3_formula == '' ){
				$message = '';
				$value = '2';
				$output ='';
			}
			if($details->value_3_combo1 == ''){
				$message = '';
				$value = '2';
				$output ='';
			}
			if($details->value_3_combo2 == ''){
				$message = '';
				$value = '2';
				$output ='';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_3_output' => $output]);
			echo json_encode(array('message' => $message,'output' => $output, 'value' => $value));
		}
		if($type == 4){			
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_4_combo2' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();			
			if($details->value_4_formula == 1 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
			}
			if($details->value_4_formula == 2 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
			}
			if($details->value_4_formula == 3 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message ='';						
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message ='';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
			}
			if($details->value_4_formula == 4 ){
				if($details->value_4_combo1 == 1){
					if($details->value_4_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
				if($details->value_4_combo1 == 2){
					if($details->value_4_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';						
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
				if($details->value_4_combo1 == 3){
					if($details->value_4_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message ='';
					}
					elseif($details->value_4_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message ='';
					}					
				}
			}
			if($details->value_4_formula == '' ){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo1 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			if($details->value_4_combo2 == ''){
				$message = '';
				$value = '2';
				$output = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_4_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		if($type == 5){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_5_combo2' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_5_formula == 1 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 2 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 3 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == 4 ){
				if($details->value_5_combo1 == 1){
					if($details->value_5_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 2){
					if($details->value_5_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 3){
					if($details->value_5_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_5_combo1 == 4){
					if($details->value_5_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';						
					}
					elseif($details->value_5_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_5_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}					
				}
			}
			if($details->value_5_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_5_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_5_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_5_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
		if($type == 6){
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_6_combo2' => $value]);
			$details = \App\Models\supplementaryFormulaAttachments::where('id', $id)->first();
			if($details->value_6_formula == 1 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output + $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output + $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output + $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output + $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output + $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 2 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output - $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output - $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output - $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output - $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output - $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 3 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output * $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output * $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output * $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output * $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output * $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			elseif($details->value_6_formula == 4 ){
				if($details->value_6_combo1 == 1){
					if($details->value_6_combo2 == 1){
						$output = $details->value_1 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_1 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_1 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_1 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_1 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 2){
					if($details->value_6_combo2 == 1){
						$output = $details->value_2 / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_2 / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_2 / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_2 / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_2 / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 3){
					if($details->value_6_combo2 == 1){
						$output = $details->value_3_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_3_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_3_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_3_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_3_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}					
				}
				if($details->value_6_combo1 == 4){
					if($details->value_6_combo2 == 1){
						$output = $details->value_4_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_4_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_4_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_4_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_4_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
				if($details->value_6_combo1 == 5){
					if($details->value_6_combo2 == 1){
						$output = $details->value_5_output / $details->value_1;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 2){
						$output = $details->value_5_output / $details->value_2;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 3){
						$output = $details->value_5_output / $details->value_3_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 4){
						$output = $details->value_5_output / $details->value_4_output;
						$value = '1';
						$message = '';
					}
					elseif($details->value_6_combo2 == 5){
						$output = $details->value_5_output / $details->value_5_output;
						$value = '1';
						$message = '';
					}
				}
			}
			if($details->value_6_formula == '' ){
				$output = '';
				$value = '2';
				$message = '';
			}
			if($details->value_6_combo1 == ''){
				$output = '';
				$value = '2';
				$message = '';					
			}
			if($details->value_6_combo2 == ''){
				$output = '';
				$value = '2';
				$message = '';
			}
			\App\Models\supplementaryFormulaAttachments::where('id', $id)->update(['value_6_output' => $output]);
			echo json_encode(array('message' => $message, 'output' => $output, 'value' => $value));
		}
	}
	public function supplementary_load(Request $request){
		$id = $request->get('id');
		$load = $request->get('load');
		$details = \App\Models\supplementaryFormula::where('supple_id', $load)->first();
		$data['name'] = $details->name;
		$data['value_1'] = $details->value_1;
		$data['value_1_description'] = $details->value_1_description;
		$data['value_2'] = $details->value_2;
		$data['value_2_description'] = $details->value_2_description;
		$data['value_3'] = $details->value_3;
		$data['value_3_number'] = $details->value_3_number;
		$data['value_3_combo1'] = $details->value_3_combo1;
		$data['value_3_combo2'] = $details->value_3_combo2;
		$data['value_3_formula'] = $details->value_3_formula;
		$data['value_3_output'] = $details->value_3_output;
		$data['value_3_description'] = $details->value_3_description;
		$data['value_4'] = $details->value_4;
		$data['value_4_number'] = $details->value_4_number;
		$data['value_4_combo1'] = $details->value_4_combo1;
		$data['value_4_combo2'] = $details->value_4_combo2;
		$data['value_4_formula'] = $details->value_4_formula;
		$data['value_4_output'] = $details->value_4_output;
		$data['value_4_description'] = $details->value_4_description;
		$data['value_5'] = $details->value_5;
		$data['value_5_number'] = $details->value_5_number;
		$data['value_5_combo1'] = $details->value_5_combo1;
		$data['value_5_combo2'] = $details->value_5_combo2;
		$data['value_5_formula'] = $details->value_5_formula;
		$data['value_5_output'] = $details->value_5_output;
		$data['value_5_description'] = $details->value_5_description;
		$data['value_6'] = $details->value_6;
		$data['value_6_number'] = $details->value_6_number;
		$data['value_6_combo1'] = $details->value_6_combo1;
		$data['value_6_combo2'] = $details->value_6_combo2;
		$data['value_6_formula'] = $details->value_6_formula;
		$data['value_6_output'] = $details->value_6_output;
		$data['value_6_description'] = $details->value_6_description;
		$data['invoice_number'] = $details->invoice_number;
		$data['load_id'] = $load;
		\App\Models\supplementaryFormula::where('supple_id', $id)->update($data);
		$output = \App\Models\supplementaryFormula::where('supple_id', $id)->first();
		echo json_encode(array('name'=>$output->name, 'value_1' => $output->value_1, 'value_1_description' => $output->value_1_description, 'value_2' => $output->value_2, 'value_2_description' => $output->value_2_description, 'value_3' => $output->value_3, 'value_3_number' => $output->value_3_number, 'value_3_combo1' => $output->value_3_combo1, 'value_3_combo2' => $output->value_3_combo2, 'value_3_formula' => $output->value_3_formula, 'value_3_output' => $output->value_3_output, 'value_3_description' => $output->value_3_description, 'value_4' => $output->value_4, 'value_4_number' => $output->value_4_number, 'value_4_combo1' => $output->value_4_combo1, 'value_4_combo2' => $output->value_4_combo2, 'value_4_formula' => $output->value_4_formula, 'value_4_output' => $output->value_4_output, 'value_4_description' => $output->value_4_description, 'value_5' => $output->value_5, 'value_5_number' => $output->value_5_number, 'value_5_combo1' => $output->value_5_combo1, 'value_5_combo2' => $output->value_5_combo2, 'value_5_formula' => $output->value_5_formula, 'value_5_output' => $output->value_5_output, 'value_5_description' => $output->value_5_description, 'value_6' => $output->value_6, 'value_6_number' => $output->value_6_number, 'value_6_combo1' => $output->value_6_combo1, 'value_6_combo2' => $output->value_6_combo2, 'value_6_formula' => $output->value_6_formula, 'value_6_output' => $output->value_6_output, 'value_6_description' => $output->value_6_description, 'invoice_number' => $output->invoice_number ));
	}
	public function update_fixed_text(Request $request)
	{
		$id = $request->get('id');
		$magic = $request->get('magic');
		$magic_text = $request->get('magic_text');
		$supple_text = $request->get('supple_text');
		$data['fixed_text'] = $magic;
		$data['magic_text'] = $magic_text;
		$data['supplementary_text'] = $supple_text;
		\App\Models\supplementaryFormula::where('id',$id)->update($data);
	}
	public function update_fixed_text_edit(Request $request)
	{
		$id = $request->get('id');
		$magic = $request->get('magic');
		$magic_text = $request->get('magic_text');
		$supple_text = $request->get('supple_text');
		$data['fixed_text'] = $magic;
		$data['magic_text'] = $magic_text;
		$data['supplementary_text'] = $supple_text;
		\App\Models\supplementaryFormulaAttachments::where('id',$id)->update($data);
	}
	public function save_supplementary_note(Request $request)
	{
		$selectval=\App\Models\supplementaryFormula::where('id',$request->get('formula_id'))->first();
		$count = \App\Models\supplementaryFormulaAttachments::where('formula_id',$request->get('formula_id'))->count();
		$countval = $count + 1;
		$data['supple_id'] = $request->get('supple_id');
		$data['formula_id'] = $request->get('formula_id');
		$data['name'] = $request->get('name').'_'.$countval;
		$data['value_1'] = $request->get('value_1');
		$data['value_1_description'] = $request->get('value_1_description');
		$data['value_2'] = $request->get('value_2');
		$data['value_2_description'] = $request->get('value_2_description');
		$data['value_3'] = $request->get('value_3_number');
		$data['value_3_number'] = $request->get('value_3');
		$data['value_3_combo1'] = $request->get('value_3_combo1');
		$data['value_3_combo2'] = $request->get('value_3_combo2');
		$data['value_3_formula'] = $request->get('value_3_formula');
		$data['value_3_output'] = $request->get('value_3_output');
		$data['value_3_description'] = $request->get('value_3_description');
		$data['value_4'] = $request->get('value_4_number');
		$data['value_4_number'] = $request->get('value_4');
		$data['value_4_combo1'] = $request->get('value_4_combo1');
		$data['value_4_combo2'] = $request->get('value_4_combo2');
		$data['value_4_formula'] = $request->get('value_4_formula');
		$data['value_4_output'] = $request->get('value_4_output');
		$data['value_4_description'] = $request->get('value_4_description');
		$data['value_5'] = $request->get('value_5_number');
		$data['value_5_number'] = $request->get('value_5');
		$data['value_5_combo1'] = $request->get('value_5_combo1');
		$data['value_5_combo2'] = $request->get('value_5_combo2');
		$data['value_5_formula'] = $request->get('value_5_formula');
		$data['value_5_output'] = $request->get('value_5_output');
		$data['value_5_description'] = $request->get('value_5_description');
		$data['value_6'] = $request->get('value_6_number');
		$data['value_6_number'] = $request->get('value_6');
		$data['value_6_combo1'] = $request->get('value_6_combo1');
		$data['value_6_combo2'] = $request->get('value_6_combo2');
		$data['value_6_formula'] = $request->get('value_6_formula');
		$data['value_6_output'] = $request->get('value_6_output');
		$data['value_6_description'] = $request->get('value_6_description');
		$data['invoice_number'] = $request->get('invoice_number');
		$data['fixed_text'] = $request->get('fixed_text');
		$data['magic_text'] = $selectval->magic_text;
		$data['supplementary_text'] = $request->get('supplementary_text');
		\App\Models\supplementaryFormulaAttachments::insert($data);
		$dataval['supplementary_text'] = "";
		\App\Models\supplementaryFormula::where('id',$data['formula_id'])->update($dataval);
	}
	public function update_supplementary_note(Request $request)
	{
		$id = $request->get('id');
		$data['value_1'] = $request->get('value_1');
		$data['value_1_description'] = $request->get('value_1_description');
		$data['value_2'] = $request->get('value_2');
		$data['value_2_description'] = $request->get('value_2_description');
		$data['value_3'] = $request->get('value_3_number');
		$data['value_3_number'] = $request->get('value_3');
		$data['value_3_combo1'] = $request->get('value_3_combo1');
		$data['value_3_combo2'] = $request->get('value_3_combo2');
		$data['value_3_formula'] = $request->get('value_3_formula');
		$data['value_3_output'] = $request->get('value_3_output');
		$data['value_3_description'] = $request->get('value_3_description');
		$data['value_4'] = $request->get('value_4_number');
		$data['value_4_number'] = $request->get('value_4');
		$data['value_4_combo1'] = $request->get('value_4_combo1');
		$data['value_4_combo2'] = $request->get('value_4_combo2');
		$data['value_4_formula'] = $request->get('value_4_formula');
		$data['value_4_output'] = $request->get('value_4_output');
		$data['value_4_description'] = $request->get('value_4_description');
		$data['value_5'] = $request->get('value_5_number');
		$data['value_5_number'] = $request->get('value_5');
		$data['value_5_combo1'] = $request->get('value_5_combo1');
		$data['value_5_combo2'] = $request->get('value_5_combo2');
		$data['value_5_formula'] = $request->get('value_5_formula');
		$data['value_5_output'] = $request->get('value_5_output');
		$data['value_5_description'] = $request->get('value_5_description');
		$data['value_6'] = $request->get('value_6_number');
		$data['value_6_number'] = $request->get('value_6');
		$data['value_6_combo1'] = $request->get('value_6_combo1');
		$data['value_6_combo2'] = $request->get('value_6_combo2');
		$data['value_6_formula'] = $request->get('value_6_formula');
		$data['value_6_output'] = $request->get('value_6_output');
		$data['value_6_description'] = $request->get('value_6_description');
		$data['invoice_number'] = $request->get('invoice_number');
		$data['fixed_text'] = $request->get('fixed_text');
		$data['supplementary_text'] = $request->get('supplementary_text');
		\App\Models\supplementaryFormulaAttachments::where('id',$id)->update($data);
	}
	public function delete_supplementary_note($id="")
	{
		$check_attachment =\App\Models\supplementaryFormulaAttachments::where('id',$id)->first();
		\App\Models\supplementaryFormulaAttachments::where('id',$id)->delete();
		return redirect('user/supplementary_note_create/'.base64_encode($check_attachment->supple_id))->with("message","Note Deleted Successfully");
	}
	public function download_supplementary_note(Request $request)
	{
		$id = $request->get('id');
		$get_text = \App\Models\supplementaryFormulaAttachments::where('id',$id)->first();
		$myfile = fopen("uploads/supplementary_text_file.txt", "w") or die("Unable to open file!");
		$txt = $get_text->supplementary_text;
		fwrite($myfile, $txt);
		fclose($myfile);
	}
}