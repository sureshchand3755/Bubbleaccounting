<?php

	function number_format_invoice($value)
	{
	    if(is_numeric($value)){
	        $expvalue = explode(".",$value);
	    	if(count($expvalue) > 1)
	    	{
	    		$value = number_format($value,2,".","");
	    		$explode = explode(".",$value);
	    	}
	    	else{
	    		$explode = explode(".",$value);
	    	}
	    	if(count($explode) > 1)
	    	{
	    		$after_decimal = substr($explode[1], 0, 2);
	    		if($after_decimal < 10)
	    		{
	    			$checkval = substr($after_decimal, 0, 1);
	    			$checkval2 = substr($after_decimal, 1, 2);
	    			if($checkval == 0 && $checkval2 < 10)
	    			{
	    				$after_decimal = $after_decimal;
	    			}
	    			else{
	    				$after_decimal = $after_decimal.'0';
	    			}
	    		}
	    	}
	    	else{
	    		$after_decimal = '00';
	    	}
	    	$first = add_commas((int)$explode[0]);
	    	$check_minus = substr($explode[0],0,1);
	    	if($check_minus == "-" && $first == "0")
	    	{
	    		if($after_decimal == '00')
	    		{
	    			$first = $first;
	    		}
	    		else{
	    			$first = '-'.$first;
	    		}
	    	}
	    	return $first.'.'.$after_decimal;   
	    }
	    else{
	        return '';
	    }
	}
	function number_format_invoice_empty($value)
	{
		if($value == "")
		{
			return '';
		}
		else{
		    if(is_numeric($value)){
	    		$expvalue = explode(".",$value);
	    		if(count($expvalue) > 1)
	    		{
	    			$value = number_format($value,2,".","");
	    			$explode = explode(".",$value);
	    		}
	    		else{
	    			$explode = explode(".",$value);
	    		}
	    		if(count($explode) > 1)
	    		{
	    			$after_decimal = substr($explode[1], 0, 2);
	    			if($after_decimal < 10)
	    			{
	    				$checkval = substr($after_decimal, 0, 1);
	    				$checkval2 = substr($after_decimal, 1, 2);
	    				if($checkval == 0 && $checkval2 < 10)
	    				{
	    					$after_decimal = $after_decimal;
	    				}
	    				else{
	    					$after_decimal = $after_decimal.'0';
	    				}
	    			}
	    		}
	    		else{
	    			$after_decimal = '00';
	    		}
	    		$first = add_commas((int)$explode[0]);
	    		$check_minus = substr($explode[0],0,1);
	    		if($check_minus == "-" && $first == "0")
	    		{
	    			if($after_decimal == '00')
	    			{
	    				$first = $first;
	    			}
	    			else{
	    				$first = '-'.$first;
	    			}
	    		}
	    		return $first.'.'.$after_decimal;
		    }
		    else{
		        return '';
		    }
		}
	}
	function number_format_invoice_without_decimal($value)
	{
		if(is_numeric($value)){
			$expvalue = explode(".",$value);
			if(count($expvalue) > 1)
			{
				$value = number_format($value,2,".","");
				$explode = explode(".",$value);
			}
			else{
				$explode = explode(".",$value);
			}
			if(count($explode) > 1)
			{
				$after_decimal = substr($explode[1], 0, 2);
				if($after_decimal < 10)
				{
					$checkval = substr($after_decimal, 0, 1);
					$checkval2 = substr($after_decimal, 1, 2);
					if($checkval == 0 && $checkval2 < 10)
					{
						$after_decimal = $after_decimal;
					}
					else{
						$after_decimal = $after_decimal.'0';
					}
				}
			}
			else{
				$after_decimal = '';
			}
			$first = add_commas((int)$explode[0]);
			$check_minus = substr($explode[0],0,1);
			if($check_minus == "-" && $first == "0")
			{
				if($after_decimal == '')
				{
					$first = $first;
				}
				else{
					$first = '-'.$first;
				}
			}

			if($after_decimal == "")
			{
				return $first.'.00';
			}
			else{
				return $first.'.'.$after_decimal;
			}
		} else{
			return '';
		}
	}
	function add_commas($number)
	{
		return number_format($number);
	}
	function number_format_invoice_without_comma($value)
	{
		if($value == "")
		{
			return '';
		}
		else{
			$expvalue = explode(".",$value);
			if(count($expvalue) > 1)
			{
				$value = number_format($value,2,".","");
				$explode = explode(".",$value);
			}
			else{
				$explode = explode(".",$value);
			}
			if(count($explode) > 1)
			{
				$after_decimal = substr($explode[1], 0, 2);
				if($after_decimal < 10)
				{
					$checkval = substr($after_decimal, 0, 1);
					$checkval2 = substr($after_decimal, 1, 2);
					if($checkval == 0 && $checkval2 < 10)
					{
						$after_decimal = $after_decimal;
					}
					else{
						$after_decimal = $after_decimal.'0';
					}
				}
			}
			else{
				$after_decimal = '00';
			}
			$first = (int)$explode[0];
			$check_minus = substr($explode[0],0,1);
			if($check_minus == "-" && $first == "0")
			{
				if($after_decimal == '00')
				{
					$first = $first;
				}
				else{
					$first = '-'.$first;
				}
			}
			return $first.'.'.$after_decimal;
		}
	}
	function getDirContents($dir,$missing_files,&$results = array(),&$filess = array())
	{
		$files = explode("||",$missing_files);
		$ffs = opendir($dir);
		print_r($ffs);
		exit;
	    foreach($ffs as $ff)
	    {
	        $ff = str_replace("'","''",$ff);
	        if($ff!='.' && $ff!='..')
	        {
	            if(is_dir($dir.'/'.$ff))
	            {
	                $file = $dir.'/'.$ff;
	                $file = str_replace('//', '/', $file);
	                getDirContents($file,$missing_files,$results,$filess);
	            }
	            else{
	            	if(in_array(strtolower($ff), $files))
	            	{
	            		$results[] = $dir.'/'.$ff;
	            		$filess[] = strtolower($ff);
	            	}
	            }
	        }
	    }
	    return json_encode(array("urls" => $results,"files" => $filess));
	}
	function time_task_review_all_helper()
	{
		$task_ids = \App\Models\timeTask::where('practice_code',Session::get('user_practice_code'))->where('task_type', 2)->get();
		$array = array();
		if(($task_ids)){
			foreach ($task_ids as $key => $singletask) {
				$taskid = $singletask->id;
				$client_ids = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where("client_id","!=","")->get();

				$update_id ='';	
				$commo = '';

				if(($client_ids)){
					foreach ($client_ids as $key => $clienid) {
						if($commo == ''){
							$commo = $clienid->client_id;
						}
						else{
							$commo =  $commo.','. $clienid->client_id;
						}
					}
				}
				$data['clients'] = $commo;
				\App\Models\timeTask::where('id', $taskid)->update($data);
			}
		}
	}
	function user_rating($id= '',$specifics=0){
		$rating = 0;
		if($id != ""){
			$details = \App\Models\taskmanager::where('id',$id)->first();
			if(($details)){
				$rating = $details->user_rating;
			}
		}

		if($specifics == 1){
			$cls_5 = 'taskmanagerstar_spec_'.$id.'_5';
			$cls_4 = 'taskmanagerstar_spec_'.$id.'_4';
			$cls_3 = 'taskmanagerstar_spec_'.$id.'_3';
			$cls_2 = 'taskmanagerstar_spec_'.$id.'_2';
			$cls_1 = 'taskmanagerstar_spec_'.$id.'_1';
		}
		else{
			$cls_5 = 'taskmanagerstar_'.$id.'_5';
			$cls_4 = 'taskmanagerstar_'.$id.'_4';
			$cls_3 = 'taskmanagerstar_'.$id.'_3';
			$cls_2 = 'taskmanagerstar_'.$id.'_2';
			$cls_1 = 'taskmanagerstar_'.$id.'_1';
		}

		$output = '<div class="taskmanager_rate">
		    <input type="radio" id="'.$cls_5.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_red '; if($rating == 5) { $output.='checked_input'; } $output.='" value="5"'; if($rating == 5) { $output.=' checked'; } $output.=' data-element="'.$id.'" />
		    <label for="'.$cls_5.'" title="High">5 stars</label>
		    <input type="radio" id="'.$cls_4.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_orange '; if($rating >= 4) { $output.='checked_input'; } $output.='" value="4"'; if($rating >= 4) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
		    <label for="'.$cls_4.'" title="High">4 stars</label>
		    <input type="radio" id="'.$cls_3.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_yellow '; if($rating >= 3) { $output.='checked_input'; } $output.='" value="3"'; if($rating >= 3) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
		    <label for="'.$cls_3.'" title="Medium">3 stars</label>
		    <input type="radio" id="'.$cls_2.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_chartreuse '; if($rating >= 2) { $output.='checked_input'; } $output.='" value="2"'; if($rating >= 2) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
		    <label for="'.$cls_2.'" title="Low">2 stars</label>
		    <input type="radio" id="'.$cls_1.'" name="taskmanager_rate_input" class="taskmanager_rate_input taskmanager_star_green '; if($rating >= 1) { $output.='checked_input'; } $output.='" value="1"'; if($rating >= 1) { $output.=' checked'; } $output.=' data-element="'.$id.'"/>
		    <label for="'.$cls_1.'" title="Low">1 star</label>
		    <input type="hidden" name="hidden_star_rating_taskmanager" id="hidden_star_rating_taskmanager" class="hidden_star_rating_taskmanager" value="'.$rating.'">
		</div>
		';
		
		return $output;
	}
	function ExtractTextFromPdf ($pdfdata) {
	    if (strlen ($pdfdata) < 1000 && file_exists ($pdfdata)) $pdfdata = file_get_contents ($pdfdata); //get the data from file
	    if (!trim ($pdfdata)) echo "Error: there is no PDF data or file to process.";
	    $result = ''; //this will store the results
	    //Find all the streams in FlateDecode format (not sure what this is), and then loop through each of them
	    if (preg_match_all ('/\s*stream(.+)endstream/Uis', $pdfdata, $m)) foreach ($m[1] as $chunk) {

	    	// $chunk = preg_replace("/[^a-zA-Z0-9., ]/", "", mb_convert_encoding($chunk,'UTF-8'));
	        try {
	        	$chunk = gzuncompress_crc32 (ltrim ($chunk)); //uncompress the data using the PHP gzuncompress function
	        } catch (MyGzException $e) {
			    $chunk = $e;
			}
	        //If there are [] in the data, then extract all stuff within (), or just extract () from the data directly
	        $a = preg_match_all ('/\[([^\]]+)\]/', $chunk, $m2) ? $m2[1] : array ($chunk); //get all the stuff within []
	        
	        foreach ($a as $subchunk) if (preg_match_all ('/\(([^\)]+)\)/', $subchunk, $m3)) $result .= join (' ', $m3[1]); //within ()
	    }
	    else echo "Error: there is no FlateDecode text in this PDF file that I can process.";
	    return $result; //return what was found
	}
	function gzuncompress_crc32($data) {
	     $f = tempnam('/tmp', 'gz_fix');
	     file_put_contents($f, "\x1f\x8b\x08\x00\x00\x00\x00\x00" . $data);
	     return file_get_contents('compress.zlib://' . $f);
	}
	function dateformat_string($array_values) {
		$output_string = '';
	    if(!empty($array_values)){
			foreach($array_values as $arr)
			{
				$output_string.=date('d M Y @ H:i', strtotime($arr)).'<br/>';
			}
		}
		
		return $output_string;
	}
	function get_vat_review_submissions($color_status,$color_text,$text_three,$month,$client_id,$checked,$check_box_color,$text_one,$remove_two,$text_two,$t1,$refresh_file,$t2,$attachment_div,$t1_value,$t2_value,$approve_status,$comments)
		{
			if($t1_value != "" || $t2_value != "")
	        {
	        	$t3_value = (int)$t1_value - (int)$t2_value;
	        }
	        else{
	        	$t3_value = '';
	        }
			$outputval = '<p style="text-align:center"><label class="import_icon '.$color_status.'">'.$color_text.'</label></p>
							<p>Import FIle ID: '.$text_three.' <input type="checkbox" class="check_records_received" id="check_records_received_'.$client_id.'_'.$month.'" data-month="'.$month.'" data-client="'.$client_id.'" '.$checked.'><label for="check_records_received_'.$client_id.'_'.$month.'" class="records_receive_label '.$check_box_color.' '.$checked.'">Records Received</label></p>
							<p>Period: &nbsp;&nbsp;<a href="javascript:" class="period_change" style="float:right;font-weight:800;margin-left: 10px;" data-month="'.$month.'" data-client="'.$client_id.'">...</a> <spam class="period_import" style="float:right">'.$text_one.'</spam></p>
							<p><label>Submitted:</label> <label class="approve_label">Approval:</label></p>
							<p><input type="text" class="submitted_import" data-client="'.$client_id.'" data-element="'.$month.'" style="float:right" value="'.$text_two.'">'.$remove_two.'</p>
							<div style="margin-top:10px"><div style="width: 50%;float: left;">T1: <spam class="t1_spam">'.$t1.'</spam> '.$refresh_file.'</div> <div class="approve_t1_div">T1: <input type="text" class="approve_t1_textbox" id="approve_t1_textbox" value="'.$t1_value.'" oninput="keypressonlynumber(this)" data-month="'.$month.'" data-client="'.$client_id.'"></div></div>
							<div style="margin-top: 41px;"><div style="width: 50%;float: left;">T2: <spam class="t2_spam">'.$t2.'</spam> </div> <div class="approve_t2_div">T2: <input type="text" class="approve_t2_textbox" id="approve_t2_textbox" value="'.$t2_value.'" oninput="keypressonlynumber(this)" data-month="'.$month.'" data-client="'.$client_id.'"></div></div>
							<div style="margin-top: 41px;"><div style="width: 50%;float: left;">&nbsp;</div> <div class="approve_t3_div">T3: <input type="text" class="approve_t3_textbox" id="approve_t3_textbox" value="'.$t3_value.'" oninput="keypressonlynumber(this)" data-month="'.$month.'" data-client="'.$client_id.'" disabled></div></div>
							<p style="margin-top: 100px;">Submission: <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-element="'.$month.'" data-client="'.$client_id.'" style="margin-top:10px;" aria-hidden="true" title="Add a PDF File"></a>';
								if($approve_status == 0 || $approve_status == '0' || $approve_status == ''){
									$outputval.='<a href="javascript:" class="common_black_button approve_t_button" data-value="1" style="background:#f00;color:#fff" data-month="'.$month.'" data-client="'.$client_id.'"> Approve</a>';
								}
								else{
									$outputval.='<a href="javascript:" class="common_black_button approve_t_button" data-value="0" style="background:green;color:#fff" data-month="'.$month.'" data-client="'.$client_id.'"> Approved </a>';
								}
							$outputval.='</p> 
							<p ><div class="attachment_div">'.$attachment_div.'</div></p>
							<p><label>Comments:</label><textarea name="comments_approval" class="form-control comments_approval comments_approval_'.$month.'_'.$client_id.'" data-month="'.$month.'" data-client="'.$client_id.'" style="height:70px">'.$comments.'</textarea></p>';
			return $outputval;
		}
	function get_taskmanager_layouts($task_id,$userid){
		$tasks = $user_tasks = DB::select("SELECT * FROM `taskmanager` WHERE `id` = '".$task_id."' AND (`allocated_to` = '".$userid."' OR `author` = '".$userid."' OR `allocated_to` = '0')");
		$userlist =\App\Models\User::where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();

		$open_tasks_output = '';
		$layout = '';
		if(($tasks)){
			foreach($tasks as $task){
			 	if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	          	else{ $disabled = ''; $disabled_icon = ''; }

	          	$taskfiles = \App\Models\taskmanagerFiles::where('task_id',$task->id)->get();
	          	$tasknotepad = \App\Models\taskmanagerNotepad::where('task_id',$task->id)->get();
	          	$taskinfiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
	        	$taskyearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
	            $two_bill_icon = '';
	            if($task->two_bill == "1")
	            {
	              $two_bill_icon = '<img src="'.URL::to('public/assets/images/2bill.png').'" class="2bill_image" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
	            }
				if($task->client_id == "")
				{
					$title_lable = 'Task Name:';
					$task_details = \App\Models\timetask::where('id', $task->task_type)->first();
					if(($task_details))
					{
					  $title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
					  $title_layout = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>';
					}
					else{
					  $title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
					  $title_layout = '<spam class="task_name_'.$task->id.'"></spam>';
					}
				}
				else{
					$title_lable = 'Client:';
					$client_details = \App\Models\CMClients::where('practice_code',Session::get('user_practice_code'))->where('client_id', $task->client_id)->first();
					if(($client_details))
					{
					  $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
					  $title_layout = $client_details->company.' ('.$task->client_id.')';
					}
					else{
					  $title = ''.$two_bill_icon;
					  $title_layout = '';
					}
				}
	          	$author =\App\Models\User::where('user_id',$task->author)->first();
	          	$task_specifics_val = strip_tags($task->task_specifics);

	          	if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	          	else{ $subject = $task->subject; }

	          	if($task->allocated_to == 0) { $allocated_to = 'Open Task'; }
	          	else{ $allocated =\App\Models\User::where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }

				if(Session::has('taskmanager_user')) {
					if($userid == $task->author) {
						if($userid == $task->allocated_to) {
						  $author_cls = 'author_tr allocated_tr'; $hidden_author_cls = 'hidden_author_tr hidden_allocated_tr'; 
						}
						else{
						  $author_cls = 'author_tr'; $hidden_author_cls = 'hidden_author_tr'; 
						}
					}
	                else { 
	                	$author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr'; 
	              	}
	          	}
				else{
					$author_cls = '';
					$hidden_author_cls = '';
				}

	            if($task->auto_close == 1)
	            {
	              	$close_task = 'auto_close_task_complete';
	            }
	            else{
	              	$close_task = '';
	            }
	          	$open_tasks_output.='<tr class="tasks_tr '.$author_cls.'" id="task_tr_'.$task->id.'">
	                <td style="vertical-align: baseline;background: #E1E1E1;width:35%;padding:0px">';
	                  	$statusi = 0;
						if(Session::has('taskmanager_user'))
						{
							if($userid == $task->author) { 
							  if($task->author_spec_status == "1")
							  {
							    $open_tasks_output.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
							    $statusi++;
							  }
							}
							else{
							  if($task->allocated_spec_status == "1")
							  {
							    $open_tasks_output.='<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
							    $statusi++;
							  }
							}
						}
						if($statusi == 0)
						{
							$open_tasks_output.='<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
						}
	                   	$open_tasks_output.='<table class="table">
	                        <tr>
								<td style="width:25%;background: #E1E1E1;font-weight:700;text-decoration: underline;">'.$title_lable.'</td>
								<td style="width:75%;background: #E1E1E1">'.$title.''; 
		                            if($task->client_id != ""){
		                              $open_tasks_output.='<img class="active_client_list_tm1" data-iden="'.$task->client_id.'" src="'.URL::to('public/assets/images/active_client_list.png').'" style="width:24px; cursor:pointer;" title="Add to active client list" />';
		                            }
									if($task->recurring_task > 0)
									{
									$open_tasks_output.='<img src="'.URL::to('public/assets/images/recurring.png').'" class="recure_image" style="width:30px;" title="This is a Recurring Task">';
									}
	                          	$open_tasks_output.='</td>
	                        </tr>
	                        <tr>
	                          <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Subject:</td>
	                          <td style="background: #E1E1E1">'.$subject.'</td>
	                        </tr>
	                        <tr>';
	                            $date1=date_create(date('Y-m-d'));
	                            $date2=date_create($task->due_date);
	                            $diff=date_diff($date1,$date2);
	                            $diffdays = $diff->format("%R%a");

	                            if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                            elseif($diffdays < 0) { $due_color = '#f00'; }
	                            elseif($diffdays > 7) { $due_color = '#000'; }
	                            elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                            else{ $due_color = '#000'; }
	                          	$open_tasks_output.='<td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Due Date:</td>
								<td style="background: #E1E1E1" class="'.$disabled_icon.'">
									<spam style="color:'.$due_color.' !important;font-weight:800" id="due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
									<a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-value="'.date('d-M-Y', strtotime($task->due_date)).'" data-duedate="'.$task->due_date.'" data-color="'.$due_color.'" class="fa fa-edit edit_due_date edit_due_date_'.$task->id.' '.$disabled.'" style="font-weight:800"></a>
								</td>
	                        </tr>
	                      	<tr>
	                            <td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Date Created:</td>
	                            <td style="background: #E1E1E1">
	                              <spam>'.date('d-M-Y', strtotime($task->creation_date)).'</spam>
	                        	</td>
	                      	</tr>
	                        <tr>
								<td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task Specifics:</td>
								<td style="background: #E1E1E1"><a href="javascript:" class="link_to_task_specifics" data-element="'.$task->id.'" data-clientid="'.$task->client_id.'">'.substr($task_specifics_val,0,30).'...</a></td>
	                        </tr>
	                        <tr>
								<td style="background: #E1E1E1;font-weight:700;text-decoration: underline;">Task files:
								</td>
								<td style="background: #E1E1E1">';
									$fileoutput = '';
									if(($taskfiles))
									{
									  foreach($taskfiles as $file)
									  {
									    if($file->status == 0)
									    {
									      $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
									    }
									  }
									}
									if(($tasknotepad))
									{
									  foreach($tasknotepad as $note)
									  {
									    if($note->status == 0)
									    {
									      $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
									    }
									  }
									}
									if(($taskinfiles))
									{
									  $i=1;
									  foreach($taskinfiles as $infile)
									  {
									    if($infile->status == 0)
									    {
									      if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
									      $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
									      $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
									      $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>

									      <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
									      </p>';
									      $i++;
									    }
									  }
									}
									$open_tasks_output.=$fileoutput;
									$open_tasks_output.='
								</td>
	                        </tr>
	                    </table>
	                </td>
	                <td style="vertical-align: baseline;background: #E8E8E8;width:30%">
	                  	<table class="table">
	                        <tr>
								<td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
								<td style="width:75%">'.$author->lastname.' '.$author->firstname.'';
								if($task->avoid_email == 0) {
								$open_tasks_output.='<a href="javascript:" class="fa fa-envelope avoid_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
								}
								else{
								$open_tasks_output.='<a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="'.$task->id.'" title="Avoid Emails for this task"></a>';
								}
								$open_tasks_output.='</td>
	                        </tr>
	                        <tr>
								<td style=";font-weight:700;text-decoration: underline;">Allocated to:</td>
								<td id="allocated_to_name_'.$task->id.'">'.$allocated_to.'</td>
	                        </tr>
	                        <tr>
								<td colspan="2">
									<spam style="font-weight:700;text-decoration: underline;">Allocations: </spam> &nbsp;
									<a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.' '.$close_task.'" title="Allocate User" style="font-weight:800"></a>
									&nbsp;
									<a href="javascript:" data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="fa fa-history show_task_allocation_history show_task_allocation_history_'.$task->id.'" title="Allocation History" style="font-weight:800"></a>
									&nbsp;
									<a href="javascript:" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" title="Request Update" style="font-weight:800">
									<img src="'.URL::to('public/assets/images/request.png').'" data-element="'.$task->id.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="request_update request_update_'.$task->id.'" style="width:16px;">
									</a>
								</td>
	                        </tr>
	                        <tr>
	                          	<td colspan="2" id="allocation_history_div_'.$task->id.'" class="'.$disabled_icon.'">';
	                            	$allocations = \App\Models\taskmanagerSpecifics::where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
		                            $output = '';
		                            if(($allocations))
		                            {
		                              foreach($allocations as $allocate)
		                              {
		                                $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$disabled.' '.$close_task.'" title="Allocate User">';
		                                  $fromuser =\App\Models\User::where('user_id',$allocate->from_user)->first();
		                                  $touser =\App\Models\User::where('user_id',$allocate->to_user)->first();
		                                  $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
		                                $output.='</p>';
		                              }
		                            }
		                            $open_tasks_output.=$output;
	                          	$open_tasks_output.='</td>
	                        </tr>
	                  	</table>
	                </td>
	                <td style="vertical-align: baseline;background: #F0F0F0;width:20%">
	                  	<table class="table">
	                        <tr>
	                          	<td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
	                          	<td></td>
	                        </tr>
	                    	<tr>
	                      		<td class="'.$disabled_icon.'">
	                        		<a href="javascript:" class="fa fa-plus faplus_progress '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Insert Files"></a>
	                        		<a href="javascript:" class="fa fa-edit fanotepad_progress '.$disabled.'" style="padding:5px;background: #dfdfdf;" title="Create Notes"></a>
	                      			<a href="javascript:" class="fa fa-download faprogress_download_all '.$disabled.'" data-element="'.$task->id.'" style="padding:5px;background: #dfdfdf;" title="Download All Progress FIles"></a>';
		                            if($task->client_id != "")
		                            {
		                              $open_tasks_output.='<a href="javascript:" class="infiles_link_progress '.$disabled.'" data-element="'.$task->id.'">Infiles</a>';
		                            }
	                        		$open_tasks_output.='<input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_'.$task->id.'" value="'.$task->client_id.'">
	                        		<input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_'.$task->id.'" value="">
	                       			<div class="notepad_div_progress_notes" style="z-index:99999999999999 !important; min-height: 250px; height: auto; position:absolute; padding: 10px;">
	                        			<div class="row">
	                          				<div class="col-lg-12">
	                            				<div class="form-group" style="margin-bottom: 5px;">
													<label style="font-weight: normal;">Select User</label>
													<select class="form-control notepad_user">
														<option value="">Select User</option>';
														$selected = '';                 
														if(($userlist)){
														  	foreach ($userlist as $user) {
															    if(Session::has('taskmanager_user'))
															    {
															      if($user->user_id == $userid) { $selected = 'selected'; }
															      else{ $selected = ''; }
															    }
															  	$open_tasks_output.='<option value="'.$user->user_id .'" '.$selected.'>'.$user->lastname.'&nbsp;'.$user->firstname.'</option>';
														  	}
														}
													$open_tasks_output.='</select>
	                              					<spam class="error_notepad_user" style="color:#f00;"></spam>
	                            				</div>
	                          				</div>
											<div class="col-lg-12">
												<textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents" style="height: 110px !important"></textarea>
												<spam class="error_files_notepad" style="color:#f00;"></spam>
												<input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="'.$task->id.'">
												<input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
											</div>
	                        			</div>
	                      			</div>
	                      		</td>
	                  			<td></td>
	                    	</tr>
	                    	<tr>
	                      		<td colspan="2" class="'.$disabled_icon.'">';
	                            	$fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
			                            if(($taskfiles))
			                            {
			                              foreach($taskfiles as $file)
			                              {
			                                if($file->status == 1)
			                                {
			                                  $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
			                                }
			                              }
			                            }
	                            	$fileoutput.='</div>';
	                        		$fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
			                            if(($tasknotepad))
			                            {
			                              foreach($tasknotepad as $note)
			                              {
			                                if($note->status == 1)
			                                {
			                                  $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
			                                }
			                              }
			                            }
	                        		$fileoutput.='</div>';
	                        		$fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
			                            if(($taskinfiles))
			                            {
			                              $i=1;
			                                foreach($taskinfiles as $infile)
			                                {
			                                  if($infile->status == 1)
			                                  {
			                                    if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
			                                    $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
			                                    $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
			                                    $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>

			                                    <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
			                                    </p>';
			                                    $i++;
			                                  }
			                                }
			                            }
	                        		$fileoutput.='</div>';
	                        		$open_tasks_output.=$fileoutput;
	                      		$open_tasks_output.='</td>
	                    	</tr>
	                  	</table>
	                </td>
	                <td style="vertical-align: baseline;background: #F8F8F8;width:15%">
	                  	<table class="table" style="margin-bottom: 105px;">
	                    	<tr>
	                      		<td style="background:#F8F8F8" class="'.$disabled_icon.'">
	                          		<spam style="font-weight:700;text-decoration: underline;font-size: 16px;">Task ID:</spam> <spam style="font-size: 16px;">'.$task->taskid.'</spam>
	                          		<a href="javascript:" class="fa fa-files-o copy_task" data-element="'.$task->id.'" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800;float: right"></a>
	                            	<a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;float: right">
	                              	</a> 
	                          		<a href="'.URL::to('user/view_taskmanager_task/'.$task->id).'" target="_blank" class="fa fa-expand view_full_task" data-element="'.$task->id.'" 
	                            		title="View Task" style="padding:5px;font-size:20px;font-weight: 800;float: right">
	                          		</a> 
	                        	</td>
	                    	</tr>
	                        <tr>
	                          	<td style="background:#F8F8F8">
	                              	<spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
	                              	<a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="bottom" data-popover-content="#a1_'.$task->id.'" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>
	                          		<div class="hidden" id="a1_'.$task->id.'">
		                                <div class="popover-heading">
		                                  Set Progress Percentage
		                                </div>
		                                <div class="popover-body">
		                                  <input type="number" class="form-control input-sm progress_value" id="progress_value_'.$task->id.'" value="" style="width:60%;float:left">
		                                  <a href="javascript:" class="common_black_button set_progress" data-element="'.$task->id.'" style="font-size: 11px;line-height: 29px;">Set</a>
		                                </div>
	                          		</div>
									<div class="progress progress_'.$task->id.'" style="width:60%;margin-bottom:5px">
										<div class="progress-bar" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$task->progress.'%">
										  '.$task->progress.'%
										</div>
									</div>
	                        	</td>
	                        </tr>
	                    	<tr>
	                      		<td class="last_td_display" style="background:#F8F8F8">';
									if($task->status == 1)
									{
										$open_tasks_output.='<a href="javascript:" class="common_black_button mark_as_incomplete" data-element="'.$task->id.'" style="font-size:12px">Completed</a>';
									}
									elseif($task->status == 2)
									{
										if(Session::has('taskmanager_user'))
										{
										  $allocated_person = $userid;
										  if($task->author == $allocated_person)
										  {
										    $complete_button = 'mark_as_complete_author';
										  }
										  else{
										    $complete_button = 'mark_as_complete';
										  }
										}
										else{
										  $complete_button = 'mark_as_complete';
										}
										$open_tasks_output.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
										<a href="javascript:" class="common_black_button activate_task_button" data-element="'.$task->id.'" style="font-size:12px">Activate</a>';
									}
	                      			else{
		                                if(Session::has('taskmanager_user'))
		                                {
		                                  $allocated_person = $userid;
		                                  if($task->author == $allocated_person)
		                                  {
		                                    $complete_button = 'mark_as_complete_author';
		                                  }
		                                  else{
		                                    $complete_button = 'mark_as_complete';
		                                  }
		                                }
		                                else{
		                                  $complete_button = 'mark_as_complete';
		                                }
	                        			$open_tasks_output.='<a href="javascript:" class="common_black_button '.$complete_button.' '.$close_task.'" data-element="'.$task->id.'" style="font-size:12px">Mark Complete</a>
	                        			<a href="javascript:" class="common_black_button park_task_button" data-element="'.$task->id.'" style="font-size:12px">Park Task</a>';
	                      			}
	                        		$open_tasks_output.='<a href="javascript:" class="fa fa-files-o integrity_check_for_task common_black_button" data-element="'.$task->id.'" title="Integrity Check"></a>
	                  			</td>
	                    	</tr>
	                    	<tr>
								<td style="background:#F8F8F8">
									<spam style="font-weight:700;text-decoration: underline;">Completion Files:</spam><br/>
									<a href="javascript:" class="fa fa-plus faplus_completion '.$disabled.'" data-element="'.$task->id.'" style="padding:5px"></a>
									<a href="javascript:" class="fa fa-edit fanotepad_completion '.$disabled.'" style="padding:5px;"></a>';
									if($task->client_id != "")
									{
										$open_tasks_output.='<a href="javascript:" class="infiles_link_completion '.$disabled.'" data-element="'.$task->id.'">Infiles</a>
										<a href="javascript:" class="yearend_link_completion '.$disabled.'" data-element="'.$task->id.'">Yearend</a>';
									}
									$get_infiles = \App\Models\taskmanagerInfiles::where('task_id',$task->id)->get();
									$get_yearend = \App\Models\taskmanagerYearend::where('task_id',$task->id)->get();
	                      			$idsval = '';
									$idsval_yearend = '';
									if(($get_infiles))
									{
										foreach($get_infiles as $set_infile)
										{
										  if($idsval == "")
										  {
										    $idsval = $set_infile->infile_id;
										  }
										  else{
										    $idsval = $idsval.','.$set_infile->infile_id;
										  }
										}
									}
									if(($get_yearend))
									{
										foreach($get_yearend as $set_yearend)
										{
										  if($idsval_yearend == "")
										  {
										    $idsval_yearend = $set_yearend->setting_id;
										  }
										  else{
										    $idsval_yearend = $idsval_yearend.','.$set_yearend->setting_id;
										  }
										}
									}
	                      
									$open_tasks_output.='<input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_'.$task->id.'" value="'.$task->client_id.'">
									<input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_'.$task->id.'" value="'.$idsval.'">
									<input type="hidden" name="hidden_yearend_completion_id" id="hidden_yearend_completion_id_'.$task->id.'" value="'.$idsval_yearend.'">
									<div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
										<textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
										<input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="'.$task->id.'">
										<input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
										<spam class="error_files_notepad"></spam>
									</div>';
	                      			$fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
										if(($taskfiles))
										{
											foreach($taskfiles as $file)
											{
												if($file->status == 2)
												{
													$fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
												}
											}
										}
	                  				$fileoutput.='</div>';
	                      			$fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
										if(($tasknotepad))
										{
											foreach($tasknotepad as $note)
											{
											  if($note->status == 2)
											  {
											    $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
											  }
											}
										}
	                     			$fileoutput.='</div>';
	                     		 	$fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
										if(($taskinfiles))
										{
			                                $i=1;
											foreach($taskinfiles as $infile)
											{
												if($infile->status == 2)
												{
												  if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
												  $file = \App\Models\inFile::where('id',$infile->infile_id)->first();
												  $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
												  $fileoutput.='<p class="link_infile_p"><a href="'.$ele.'" target="_blank" class="link_infile" data-element="'.$ele.'">'.$i.' '.date('d-M-Y', strtotime($file->data_received)).' '.$file->description.'</a>

												  <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
												  </p>';
												  $i++;
												}
											}
	                      				}
	                      			$fileoutput.='</div>';
	                      			$fileoutput.='<div id="add_yearend_attachments_completion_div_'.$task->id.'">';
	                      				if(($taskyearend))
	                      				{
	                        				$i=1;
	                          				foreach($taskyearend as $yearend)
	                          				{
	                            				if($yearend->status == 2)
	                            				{
													if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
													$file = \App\Models\YearSetting::where('id',$yearend->setting_id)->first();
													$get_client_id = \App\Models\taskmanager::where('id',$task->id)->first();
													$year_client_id = $get_client_id->client_id;
													$yearend_id =\App\Models\YearClientwhere('client_id',$year_client_id)->orderBy('id','desc')->first();

													$ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
													$fileoutput.='<p class="link_yearend_p"><a href="'.$ele.'" target="_blank">'.$i.'</a>
													<a href="'.$ele.'" target="_blank">'.$file->document.'</a>
													<a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$yearend->id.'').'" class="fa fa-trash delete_attachments"></a>
													</p>';
													$i++;
	                            				}
	                          				}
	                      				}
	                      			$fileoutput.='</div>';
	                      			$open_tasks_output.=$fileoutput;
	                      		$open_tasks_output.='</td>
	                    	</tr>
	                  		<tr>
	                    		<td style="background:#F8F8F8">
	                    		</td>
	                  		</tr>
	                  	</table>
	                </td>
	          	</tr>
	          	<tr class="empty_tr" style="background: #fff;height:30px">
	            	<td style="padding:0px;background: #fff;">
	            	</td>
	            	<td colspan="3" style="background: #fff;height:30px"></td>
	          	</tr>';
	          	$layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
	            	<td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
	            		<table style="width:100%">
	                		<tr>';
	                    		$statusi = 0;
			                  	if(Session::has('taskmanager_user'))
			                  	{
				                    if($userid == $task->author) { 
				                      if($task->author_spec_status == "1")
				                      {
				                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
				                        $redlight_value = 1;
				                        $statusi++;
				                      }
				                    }
				                    else{
				                      if($task->allocated_spec_status == "1")
				                      {
				                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
				                        $redlight_value = 1;
				                        $statusi++;
				                      }
				                    }
			                  	}
			                  	if($statusi == 0)
			                  	{
			                    	$redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
			                    	$redlight_value = 0;
			                  	}
	                			$layout.= '<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
	                				<spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
	                				'.$redlight_indication_layout.'
	                			</td>
	                			<td style="width:10%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
	                			<td style="width:30%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title_layout.'';
	                              	if($task->recurring_task > 0) {
	                                  $layout.= '<img src="'.URL::to('public/assets/images/recurring.png').'" style="width:30px;" title="This is a Recurring Task">';
	                                }
	                			$layout.= '</td>
	                            <td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="2bill_sort_val">
	                            '.$two_bill_icon.'
	                            </td>
	                			<td style="width:40%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
	                		</tr>
	                	</table>
	            	</td>
	                <td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
	                	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:60%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.' / <spam class="allocated_sort_val">'.$allocated_to.'</spam></td>
	                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800">'.user_rating($task->id).'</td>
	                    	</tr>
	                	</table>
	                </td>
	                <td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
	                	<table style="width:100%">
	                    	<tr>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
	                    			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
	                    			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
	                    		</td>
	                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
	                    		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
	                    		'.date('d-M-Y', strtotime($task->creation_date)).'
	                    		</td>
	                    	</tr>
	                    </table>
	                </td>
	                <td style="background: #fff;padding:0px; border-bottom:1px solid #ddd">
	                	<table style="width:100%">
	                    	<tr>';
	                    $project_time = '-';
	                    if($task->project_hours != ''){
	                      $project_time = $task->project_hours.':'.$task->project_mins;
	                    }
	                    $layout.='<td style="width:25%;padding:10px; font-size:14px; font-weight:800;">'.$project_time.'</td>
	                    		<td class="layout_progress_'.$task->id.'" style="width:30%;padding:10px; font-size:14px; font-weight:800;"><spam class="progress_sort_val" style="display:none">'.$task->progress.'</spam>'.$task->progress.'%</td>
	                    	</tr>
	                    </table>
	                </td>
	          	</tr>';
			}
		}
		$datavaloutput['open_tasks_output'] = $open_tasks_output;
	  	$datavaloutput['layout'] = $layout;

	  	return $datavaloutput;
	}
	function vat_notifications_form(){
		if(Session::has('alreadyinsertedrows')) {
		    $taxnumber_compared = Session::get('alreadyinsertedrows'); 
		} else {
		    $taxnumber_compared = [];
		}

		$without_emailed =\App\Models\vatClients::where('pemail','')->where('semail','')->where('status',0)->get();
		$disabled =\App\Models\vatClients::where('status',1)->get();
		$with_emailed =\App\Models\vatClients::where('status',0)->where('self_manage','!=','')->where('status',0)->whereRaw('(pemail != "" OR semail != "")')->get();


		$active_clients = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
	      <div class="page_title">
	        Clients that we Can Notify <br/>
	        <input type="checkbox" name="select_all" id="select_all" value="1"> <label for="select_all">Select All</label><br/>
	        <input type="checkbox" name="always_nill" id="always_nill" value="1"> <label for="select_all">Send Email to Always Nil Clients</label>
	      </div>
	    </div>
	    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">
	        <a href="javascript:" class="common_black_button pdf_button" id="pdf_with_email" style="float: right;margin-top: 10px;">Download PDF</a>
	    </div>
	    <div class="select_button" style="height:400px;max-height: 400px;overflow-y: scroll;">
	      <table class="table" id="vat_active_clients_expand">
	        <thead>
	          <tr style="background: #fff;">
	              <th width="5%">#</th>
	              <th>OK to Send</th>
	              <th>Client Name</th>
	              <th>Tax Regn./Trader No</th>
	              <th>Email</th>
	              <th>Secondary Email</th> 
	              <th>Last Email Sent</th> 
	          </tr>
	        </thead>
	        <tbody>';
	              $i=1;
	              if(($with_emailed)){              
	                foreach($with_emailed as $with){    
	                if(in_array($with->taxnumber,$taxnumber_compared)) {                             
	                  $check_compare =\App\Models\vatClientsCompare::where('taxnumber',$with->taxnumber)->get();
	                  if(($check_compare)) {
	                    foreach($check_compare as $key => $compare)
	                    {
	                    	if($with->always_nil == "yes") { $always_nil_check = 'always_nil_check'; } else { $always_nil_check = '';  }
	                    	$display_none = '';
	                    	if($key != 0) { $display_none ='display:none'; }
	                    	if($with->last_email_sent == "0000-00-00 00:00:00") { $dateval = '-'; } 
	                    	else {
	                    		$dateval = date('d F Y', strtotime($with->last_email_sent));
	                    	}

	                      $active_clients.='<tr style="background:#f5f5f5;'.$display_none.'">
	                          <td width="5%">'.$i.'</td>
	                          <td>
	                          		<input type="checkbox" class="select_functioning '.$always_nil_check.' functioningall functioning_'.$with->client_id.'" name="select_functioning" id="'.$with->client_id.'" value="1" style="opacity:1"> 
	                              	<input type="hidden" class="hidden_pemail" id="'.$with->client_id.'" value="'.$with->pemail.'">
	                              	<input type="hidden" class="hidden_semail" id="'.$with->client_id.'" value="'.$with->semail.'">
	                              	<input type="hidden" class="hidden_salutation" id="'.$with->client_id.'" value="'.$with->salutation.'">
	                              	<input type="hidden" class="hidden_self" id="'.$with->client_id.'" value="'.$with->self_manage.'">

	                              	<input type="hidden" class="hidden_period" id="'.$with->client_id.'" value="'.$compare->period.'">
	                              	<input type="hidden" class="hidden_duedate" id="'.$with->client_id.'" value="'.$compare->due_date.'">
	                          </td>
	                          <td>'.$with->name.'</td>
	                          <td>'.$with->taxnumber.'</td>
	                          <td>'.$with->pemail.'</td>
	                          <td>'.$with->semail.'</td>
	                          <td>'.$dateval.'</td>
	                      </tr>';
	                    }                        
	                  }   
	                  $i++;             
	                }
	              }
	            }
	            if($i == 1)
	            {
	              $active_clients.='<tr style="background:#f5f5f5;"><td style="color:#000 !important">Empty</td>
	              <td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	            }
	        $active_clients.='</tbody>            
	      </table>
	    </div>
	    <input type="button" name="with_email_submit" id="with_email_submit" class="common_black_button" value="Send VAT Notifications" style="width:20%; margin-top:20px !important;float: left;">';

	    $without_email = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
	      <div class="page_title">
	        Clients Without Email Address:
	      </div>
	    </div>
	    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">
	        <a href="javascript:" class="common_black_button pdf_button" id="pdf_without_email" style="float: right;margin-top: 10px;">Download PDF</a>
	    </div>
	    <div class="select_button" style="height:400px;max-height: 400px;overflow-y: scroll;">
	      <table class="table" id="vat_without_email_expand">
	        <thead>
	          <tr style="background: #fff;">
	              <th width="5%">#</th>
	              <th>Client Name</th>
	              <th>Tax Regn./Trader No</th>

	          </tr>
	        </thead>
	        <tbody>';
	            $i=1;
	            if(($without_emailed)){              
	                foreach($without_emailed as $without){       
		                if(in_array($without->taxnumber,$taxnumber_compared)) {
							$without_email.='<tr style="background:#f5f5f5;">
							  <td width="5%">'.$i.'</td>
							  <td>'.$without->name.'</td>
							  <td>'.$without->taxnumber.'</td>
							</tr>';
		             	 	$i++;      
		              	}                        
	              	}              
	            }
	            if($i == 1)
	            {
	              $without_email.='<tr style="background:#f5f5f5;"><td style="color:#000 !important">Empty</td><td></td><td></td></tr>';
	            }
	        $without_email.='</tbody>            
	      </table>
	    </div>';

	    $deactivated_clients = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
	      <div class="page_title">
	        Disabled Clients
	      </div>
	    </div>
	    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">
	        <a href="javascript:" class="common_black_button pdf_button" id="pdf_disabled" style="float: right;margin-top: 10px;">Download PDF</a>
	    </div>
	    <div class="select_button" style="height:400px;max-height: 400px;overflow-y: scroll;">
	      <table class="table" id="vat_deactivated_clients_expand">
	        <thead>
	          	<tr style="background: #fff;">
	              <th width="5%">#</th>
	              <th>Client Name</th>
	              <th>Tax Regn./Trader No</th>
	          	</tr>
	        </thead>
	        <tbody>';
	            $i=1;
	            if(($disabled)){              
	                foreach($disabled as $disable){    
	                	if(in_array($disable->taxnumber,$taxnumber_compared)) {  
				          	$deactivated_clients.='<tr style="background:#f5f5f5;">
				              <td width="5%">'.$i.'</td>
				              <td>'.$disable->name.'</td>
				              <td>'.$disable->taxnumber.'</td>
				          	</tr>';
	              			$i++;  
	              		}                            
	              	}              
	            }
	            if($i == 1)
	            {
	              $deactivated_clients.='<tr style="background:#f5f5f5;"><td style="color:#000 !important">Empty</td><td></td><td></td></tr>';
	            }
	        $deactivated_clients.='</tbody>            
	      </table>
	    </div>';

	    $dataval['status'] = '0';
	    $dataval['message'] = 'Items Listed Successfully';
	    $dataval['active_clients'] = $active_clients;
	    $dataval['without_email'] = $without_email;
	    $dataval['deactivated_clients'] = $deactivated_clients;

	    return $dataval;
	}
?>