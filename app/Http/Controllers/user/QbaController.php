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
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
class QbaController extends Controller {
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
		require_once(app_path('Http/helpers.php'));
	}
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function qba(Request $request){
		$datapost['table_content'] = '';
		$datapost['header_row'] = 0;
		$datapost['secondary_row'] = 0;
		$datapost['source_titles'] = '';
		$datapost['allocations'] = '';
		\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->update($datapost);
		return view('user/qba/qba', array('title' => 'QBA'));
	}
	public function qba_upload_file(Request $request)
	{
		$upload_dir = 'uploads/qba_file';
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		$upload_dir = $upload_dir.'/'.time();
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir);
		}
		if (!empty($_FILES)) {
			$tmpFile = $_FILES['file']['tmp_name'];
			$fname = str_replace("#","",$_FILES['file']['name']);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("#","",$fname);
			$fname = str_replace("&","",$fname);
			$fname = str_replace("&","",$fname);
			$fname = str_replace("&","",$fname);
			$fname = str_replace("&","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$fname = str_replace("%","",$fname);
			$filename = $upload_dir.'/'.$fname;
			$output = '<table class="table tableqba">';
		 	if(move_uploaded_file($tmpFile,$filename))
		 	{
		 		$filepath = $filename;
				$chunk_size = 100000;
				$csv_data = array_map('str_getcsv', file($filepath));
				$chunked_data = array_chunk($csv_data, $chunk_size);
				$serializedata = json_encode($chunked_data[0]);
				$dataval['table_content'] = $serializedata;
				$dataval['header_row'] = 0;
				$dataval['secondary_row'] = 0;
				$dataval['source_titles'] = '';
				$dataval['allocations'] = '';
				$get_det =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
				if(($get_det)){
					\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->update($dataval);
				}
				$contentdata = array_slice($chunked_data[0], 0, 5);
				$column_count = 0;
				if(($contentdata)){
					foreach($contentdata as $data){
						$output.='<tr>';
						$countdata = count($data);
						if($countdata > $column_count){
							$column_count = $countdata;
						}
						if(($data)){
							for($i = 0; $i <= $countdata - 1; $i++){
								if(isset($data[$i])) { $datai = $data[$i]; }
								else { $datai = ''; }
								$output.='<td style="width:200px">'.$datai.'</td>';
							}
						}
						$output.='</tr>';
					}
				}
				else{
					$output.='<tr>
						<td colspan="4">No Data Found.</td>
					</tr>';
				}
		 	}
		 	$output.='</table>';
		 	echo json_encode(array('output' => $output,'column_count' => $column_count));
		}
	}
	public function update_qba_settings(Request $request){
		$header_row = $request->get('header_row');
		$secondary_row = $request->get('secondary_row');
		$column_count = $request->get('column_count');
		$get_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
		$table_content = json_decode($get_details->table_content);
		$header_color = 'color:#f00';
		$secondary_color = 'color:#f00';
		if($header_row != "1"){
			$header_row_array[0] = array();
			for($i=1;$i<=$column_count;$i++){
				array_push($header_row_array[0],'column_'.$i);
			}
			$contents_array = array_merge($header_row_array,$table_content);
		}
		else{
			$header_color = 'color:green';
			$contents_array = $table_content;
		}
		$full_array = array();
		if($secondary_row == "1"){
			$secondary_color = 'color:green';
			if(($contents_array)){
				foreach($contents_array as $key => $content){
					if($key == 0){
						$contentarr = array('Line ID');
					}
					else{
						$contentarr = array($key);
					}
					foreach($content as $datacont){
						array_push($contentarr,$datacont);
					}
					array_push($full_array,$contentarr);
				}
			}
		}
		else{
			if(($contents_array)){
				foreach($contents_array as $key => $content){
					if($key == 0){
						$contentarr = array('Line ID');
					}
					else{
						$contentarr = array($key);
					}
					foreach($content as $datacont){
						array_push($contentarr,$datacont);
					}
					array_push($full_array,$contentarr);
				}
			}
			//$full_array = $contents_array;
		}
		$datapost['table_content'] = json_encode($full_array);
		$datapost['header_row'] = $header_row;
		$datapost['secondary_row'] = $secondary_row;
		$datapost['source_titles'] = '';
		$datapost['allocations'] = '';
		\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->update($datapost);
		$header_output = '<label style="font-size:18px;'.$header_color.';width:100%">* Source file has a header row</label>
                      <label style="font-size:18px;'.$secondary_color.';width:100%">* Second line of Source file is blank</label>';
		$output = '
        <div class="col-md-12 padding_00">
          <label style="margin-top:11px;font-size:18px">Source File Content:</label>
          <a href="javascript:" class="common_black_button process_source_file" style="float:right">Process Source File</a>
        </div>
        <div class="col-md-12 qba_imported_sub_div" style="height:500px;max-height: 500px;overflow-y: scroll;">
        <table class="table tableqba tableqba_main">';
        if(($full_array)){
          foreach($full_array as $keyc => $datacontent){
            $output.='<tr>';
            $countdata = count($datacontent);
            if(($datacontent)){
              for($i = 0; $i <= $countdata - 1; $i++){
                if(isset($datacontent[$i])) { $datai = $datacontent[$i]; }
                else { $datai = ''; }
                if($keyc == 0){
                  if(isset($allocations[$i])){
                    $datav = $datai.' / '.$allocations[$i];
                    $datav_color = 'color:green';
                  }else{
                    $datav = $datai;
                    $datav_color = 'color:#000';
                  }
                  $output.='<td><a href="javascript:" title="'.$datav.'" style="'.$datav_color.'">'.$datai.'</a></td>';
                }
                else{
                  $output.='<td>'.$datai.'</td>';
                }
              }
            }
            $output.='</tr>';
          }
        }
        else{
          $output.='<tr>
            <td colspan="4">No Data Found.</td>
          </tr>';
        }
        $output.='</table></div>';
		echo json_encode(array("output" => $output, "header_output" => $header_output));
	}
	public function qba_data_alloations(Request $request){
		$get_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
		$table_content = json_decode($get_details->table_content);
		$contentdata = array_slice($table_content, 0, 5);
		$output = '<h4 style="font-weight:600">Sample of Loaded Data:</h4>
		<div class="sample_loaded_div">
		<table class="table sample_loaded_table tableqba">';
		$headers = array();
		if(count($contentdata) > 0){
			foreach($contentdata as $key => $data){
				$output.='<tr>';
				$countdata = count($data);
				if(count($data) > 0){
					for($i = 0; $i <= $countdata - 1; $i++){
						if(isset($data[$i])) { $datai = $data[$i]; }
						else { $datai = ''; }
						if($key == 0){
							array_push($headers,$datai);
						}
						$output.='<td style="width:200px">'.$datai.'</td>';
					}
				}
				$output.='</tr>';
			}
		}
		else{
			$output.='<tr>
				<td colspan="4">No Data Found.</td>
			</tr>';
		}
		$output.='</table>
		</div>';
		$allocations = json_decode($get_details->allocations);

		$output.='
		<h4 style="font-weight:600;margin-top: 34px;">Linking Source File Headers to System Headers:</h4>
		<div class="col-md-6 padding_00">
			<table class="table data_allocations_table tableqba">
				<tbody>
				<tr>
					<td>Source Titles</td>
					<td>Data Link</td>
				</tr>';
				if(count($headers) > 0){
					foreach($headers as $keyhead => $header){
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Date') { $optionselected1 = 'selected'; }
						else { $optionselected1 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Debit/Withdrawal Value') { $optionselected2 = 'selected'; }
						else { $optionselected2 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Credit/Lodgement Value') { $optionselected3 = 'selected'; }
						else { $optionselected3 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Value Debit is Minus') { $optionselected4 = 'selected'; }
						else { $optionselected4 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Value Debit is in Brackets') { $optionselected5 = 'selected'; }
						else { $optionselected5 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Debit/Credit Identifier') { $optionselected6 = 'selected'; }
						else { $optionselected6 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Debit/Credit Value') { $optionselected7 = 'selected'; }
						else { $optionselected7 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Balance') { $optionselected8 = 'selected'; }
						else { $optionselected8 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 1') { $optionselected9 = 'selected'; }
						else { $optionselected9 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 2') { $optionselected10 = 'selected'; }
						else { $optionselected10 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 3') { $optionselected11 = 'selected'; }
						else { $optionselected11 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 4') { $optionselected12 = 'selected'; }
						else { $optionselected12 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 5') { $optionselected13 = 'selected'; }
						else { $optionselected13 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 6') { $optionselected14 = 'selected'; }
						else { $optionselected14 = ''; }
						$output.='<tr>
							<td>'.$header.'</td>
							<td>
								<select name="data_link_header" class="form-control data_link_header" id="data_link_header">
									<option value=""></option>
									<option value="Date" '.$optionselected1.'>Date</option>
									<option value="Debit/Withdrawal Value" '.$optionselected2.'>Debit/Withdrawal Value</option>
									<option value="Credit/Lodgement Value" '.$optionselected3.'>Credit/Lodgement Value</option>
									<option value="Value Debit is Minus" '.$optionselected4.'>Value Debit is Minus</option>
									<option value="Value Debit is in Brackets" '.$optionselected5.'>Value Debit is in Brackets</option>
									<option value="Debit/Credit Identifier" '.$optionselected6.'>Debit/Credit Identifier</option>
									<option value="Debit/Credit Value" '.$optionselected7.'>Debit/Credit Value</option>
									<option value="Balance" '.$optionselected8.'>Balance</option>
									<option value="Descript Narrative 1" '.$optionselected9.'>Descript Narrative 1</option>
									<option value="Descript Narrative 2" '.$optionselected10.'>Descript Narrative 2</option>
									<option value="Descript Narrative 3" '.$optionselected11.'>Descript Narrative 3</option>
									<option value="Descript Narrative 4" '.$optionselected12.'>Descript Narrative 4</option>
									<option value="Descript Narrative 5" '.$optionselected13.'>Descript Narrative 5</option>
									<option value="Descript Narrative 6" '.$optionselected14.'>Descript Narrative 6</option>
								</select>
							</td>
						</tr>';
					}
				}
			$output.='</tbody>
			</table>
		</div>
		<div class="col-md-6" style="font-size:16px;text-align: justify;line-height: 28px;">
			<p><strong>Date:</strong> this is the Transaction date as per the bank transaction file, you can select any field that has a date format.</p>
			<p><strong>Debits/Withdrawal Value:</strong> this is the Debit (or withdrawal value) it is a numeric field</p>
			<p><strong>Credit/Lodgement Value:</strong> this is the Credit (or lodgement value) it is a numeric field</p>
			<p><strong>Value Debit is Minus:</strong> this is your Debit (or withdrawal value) but it is shows as a negative figure such as “-52.04”</p>
			<p><strong>Value Debit is in Brackets:</strong> this is your Debit (or withdrawal value) but it is shows as a figure in brackets such as “(52.04)”</p>
			<p><strong>Debit/Credit Identifier:</strong> this is were the debit and credit values are stored in the “Debit/Credit Value” field and there is a text/symbol to identify the Debit Value</p>
			<p><strong>Debit/Credit Value:</strong> this is were the debit and credit values are stored in the “Debit/Credit Value” field and there is a text/symbol to identify the Debit Value</p>
			<p><strong>Balance:</strong> this is the bank Balance as per the extracted file and is a numeric format file</p>
			<p><strong>Debit Narrative:</strong> this is the text describing the transaction on the bank file</p>
			<p>&nbsp;</p>
			<p>** your bank extract file might have different fields but they can be ignored you only need to link the relevant fields. Date, Description and Debit and Credit Value is all that is required</p>
		</div>';
		echo $output;
	}
	public function qba_data_validations(Request $request){
		$get_details =\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->first();
		$table_content = json_decode($get_details->table_content);
		$contentdata = array_slice($table_content, 0, 5);
		$allocations = json_decode($get_details->allocations);
		$headers = array();
		if(count($contentdata) > 0){
			foreach($contentdata as $key => $data){
				$countdata = count($data);
				if(count($data) > 0){
					for($i = 0; $i <= $countdata - 1; $i++){
						if(isset($data[$i])) { $datai = $data[$i]; }
						else { $datai = ''; }
						if($key == 0){
							array_push($headers,$datai);
						}
					}
				}
			}
		}
		$default_date_valid = 'No Date Specified';
		$default_date_color = 'color:#f00';
		$default_debit_valid = 'Link Not Valid';
		$default_debit_color = 'color:#f00';
		$default_credit_valid = 'Link Not Valid';
		$default_credit_color = 'color:#f00';
		if(count($allocations) > 0){
			if(in_array("Date", $allocations)){
				$default_date_valid = 'Valid';
				$default_date_color = 'color:green';
			}
			else{
				$default_date_valid = 'No Date Specified';
				$default_date_color = 'color:#f00';
			}
			if((in_array("Debit/Withdrawal Value", $allocations)) || (in_array("Value Debit is Minus", $allocations)) || (in_array("Value Debit is in Brackets", $allocations))){
				$default_debit_valid = 'Valid Link';
				$default_debit_color = 'color:green';
			}
			else{
				$default_debit_valid = 'Link Not Valid';
				$default_debit_color = 'color:#f00';
			}
			if((in_array("Credit/Lodgement Value", $allocations))) {
				$default_credit_valid = 'Valid Link';
				$default_credit_color = 'color:green';
			}
			else{
				$default_credit_valid = 'Link Not Valid';
				$default_credit_color = 'color:#f00';
			}
		}
		$output='<div class="col-md-12 padding_00">
			<table class="table data_allocations_table tableqba">
				<tbody>
				<tr>
					<td>Source File Header Names</td>
					<td>Data Link column Names</td>
					<td>Validation Status</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">Date</td>
					<td>
						<select name="default_data_link_header" class="form-control default_data_link_header" id="default_data_link_header">
							<option value=""></option>
							<option value="Date">Date</option>
							<option value="Debit/Withdrawal Value">Debit/Withdrawal Value</option>
							<option value="Credit/Lodgement Value">Credit/Lodgement Value</option>
							<option value="Value Debit is Minus">Value Debit is Minus</option>
							<option value="Value Debit is in Brackets">Value Debit is in Brackets</option>
							<option value="Debit/Credit Identifier">Debit/Credit Identifier</option>
							<option value="Debit/Credit Value">Debit/Credit Value</option>
							<option value="Balance">Balance</option>
							<option value="Descript Narrative 1">Descript Narrative 1</option>
							<option value="Descript Narrative 2">Descript Narrative 2</option>
							<option value="Descript Narrative 3">Descript Narrative 3</option>
							<option value="Descript Narrative 4">Descript Narrative 4</option>
							<option value="Descript Narrative 5">Descript Narrative 5</option>
							<option value="Descript Narrative 6">Descript Narrative 6</option>
						</select>
					</td>
					<td style="vertical-align:middle;">
						<spam class="date_static_text" style="'.$default_date_color.'">'.$default_date_valid.'</spam>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">Debit Value</td>
					<td>
						<select name="default_debit_link_header" class="form-control default_debit_link_header" id="default_debit_link_header">
							<option value=""></option>
							<option value="Date">Date</option>
							<option value="Debit/Withdrawal Value">Debit/Withdrawal Value</option>
							<option value="Credit/Lodgement Value">Credit/Lodgement Value</option>
							<option value="Value Debit is Minus">Value Debit is Minus</option>
							<option value="Value Debit is in Brackets">Value Debit is in Brackets</option>
							<option value="Debit/Credit Identifier">Debit/Credit Identifier</option>
							<option value="Debit/Credit Value">Debit/Credit Value</option>
							<option value="Balance">Balance</option>
							<option value="Descript Narrative 1">Descript Narrative 1</option>
							<option value="Descript Narrative 2">Descript Narrative 2</option>
							<option value="Descript Narrative 3">Descript Narrative 3</option>
							<option value="Descript Narrative 4">Descript Narrative 4</option>
							<option value="Descript Narrative 5">Descript Narrative 5</option>
							<option value="Descript Narrative 6">Descript Narrative 6</option>
						</select>
					</td>
					<td style="vertical-align:middle;">
						<spam class="debit_static_text" style="'.$default_debit_color.'">'.$default_debit_valid.'</spam>
						<spam class="debit_dynamic_text" style="display:none"></spam>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:middle">Credit Value</td>
					<td>
						<select name="default_credit_link_header" class="form-control default_credit_link_header" id="default_credit_link_header">
							<option value=""></option>
							<option value="Date">Date</option>
							<option value="Debit/Withdrawal Value">Debit/Withdrawal Value</option>
							<option value="Credit/Lodgement Value">Credit/Lodgement Value</option>
							<option value="Value Debit is Minus">Value Debit is Minus</option>
							<option value="Value Debit is in Brackets">Value Debit is in Brackets</option>
							<option value="Debit/Credit Identifier">Debit/Credit Identifier</option>
							<option value="Debit/Credit Value">Debit/Credit Value</option>
							<option value="Balance">Balance</option>
							<option value="Descript Narrative 1">Descript Narrative 1</option>
							<option value="Descript Narrative 2">Descript Narrative 2</option>
							<option value="Descript Narrative 3">Descript Narrative 3</option>
							<option value="Descript Narrative 4">Descript Narrative 4</option>
							<option value="Descript Narrative 5">Descript Narrative 5</option>
							<option value="Descript Narrative 6">Descript Narrative 6</option>
						</select>
					</td>
					<td style="vertical-align:middle;">
						<spam class="credit_static_text" style="'.$default_credit_color.'">'.$default_credit_valid.'</spam>
						<spam class="credit_dynamic_text" style="display:none"></spam>
					</td>
				</tr>';
				if(count($headers) > 0){
					foreach($headers as $keyhead => $header){
						$color_options = '';
						$valid_options = '';
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Date') { $optionselected1 = 'selected'; }
						else { $optionselected1 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Debit/Withdrawal Value') { $optionselected2 = 'selected'; }
						else { $optionselected2 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Credit/Lodgement Value') { $optionselected3 = 'selected'; }
						else { $optionselected3 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Value Debit is Minus') { $optionselected4 = 'selected'; }
						else { $optionselected4 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Value Debit is in Brackets') { $optionselected5 = 'selected'; }
						else { $optionselected5 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Debit/Credit Identifier') { $optionselected6 = 'selected'; }
						else { $optionselected6 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Debit/Credit Value') { $optionselected7 = 'selected'; }
						else { $optionselected7 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Balance') { $optionselected8 = 'selected'; }
						else { $optionselected8 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 1') { $optionselected9 = 'selected'; $color_options = 'color:green'; $valid_options = 'Valid';}
						else { $optionselected9 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 2') { $optionselected10 = 'selected'; $color_options = 'color:green'; $valid_options = 'Valid';}
						else { $optionselected10 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 3') { $optionselected11 = 'selected'; $color_options = 'color:green'; $valid_options = 'Valid';}
						else { $optionselected11 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 4') { $optionselected12 = 'selected'; $color_options = 'color:green'; $valid_options = 'Valid';}
						else { $optionselected12 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 5') { $optionselected13 = 'selected'; $color_options = 'color:green'; $valid_options = 'Valid';}
						else { $optionselected13 = ''; }
						if(isset($allocations[$keyhead]) && $allocations[$keyhead] == 'Descript Narrative 6') { $optionselected14 = 'selected'; $color_options = 'color:green'; $valid_options = 'Valid';}
						else { $optionselected14 = ''; }
						$output.='<tr>
							<td style="vertical-align:middle">'.$header.'</td>
							<td>
								<select name="data_allocations_link_header" class="form-control data_allocations_link_header" id="data_allocations_link_header">
									<option value=""></option>
									<option value="Date" '.$optionselected1.'>Date</option>
									<option value="Debit/Withdrawal Value" '.$optionselected2.'>Debit/Withdrawal Value</option>
									<option value="Credit/Lodgement Value" '.$optionselected3.'>Credit/Lodgement Value</option>
									<option value="Value Debit is Minus" '.$optionselected4.'>Value Debit is Minus</option>
									<option value="Value Debit is in Brackets" '.$optionselected5.'>Value Debit is in Brackets</option>
									<option value="Debit/Credit Identifier" '.$optionselected6.'>Debit/Credit Identifier</option>
									<option value="Debit/Credit Value" '.$optionselected7.'>Debit/Credit Value</option>
									<option value="Balance" '.$optionselected8.'>Balance</option>
									<option value="Descript Narrative 1" '.$optionselected9.'>Descript Narrative 1</option>
									<option value="Descript Narrative 2" '.$optionselected10.'>Descript Narrative 2</option>
									<option value="Descript Narrative 3" '.$optionselected11.'>Descript Narrative 3</option>
									<option value="Descript Narrative 4" '.$optionselected12.'>Descript Narrative 4</option>
									<option value="Descript Narrative 5" '.$optionselected13.'>Descript Narrative 5</option>
									<option value="Descript Narrative 6" '.$optionselected14.'>Descript Narrative 6</option>
								</select>
							</td>
							<td style="vertical-align:middle;'.$color_options.'">
								'.$valid_options.'
							</td>
						</tr>';
					}
				}
			$output.='</tbody>
			</table>
		</div>';
		echo $output;
	}
	public function save_qba_data_links(Request $request){
		$links = json_decode(stripslashes($request->get('links')));
		if(count($links) > 0)
		{
			$data['allocations'] = json_encode($links);
			\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->update($data);
		}
	}
	public function empty_qba_allocations(Request $request){
		$datapost['table_content'] = '';
		$datapost['header_row'] = 0;
		$datapost['secondary_row'] = 0;
		$datapost['source_titles'] = '';
		$datapost['allocations'] = '';
		\App\Models\User::where('practice',Session::get('user_practice_code'))->where('user_id',Session::get('userid'))->update($datapost);
	}
}