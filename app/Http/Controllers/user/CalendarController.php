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
class CalendarController extends Controller {
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
	public function staff_calenders(Request $request){
		return view('user/calendar/calendar', array('title' => 'Calendar'));
	}
	public function fetchevents(Request $request) {
		$events = DB::table('events')->where('user_id',Session::get('userid'))->get();
		$response = array();
		if(is_countable($events) && count($events) > 0) {
			foreach($events as $event) {
				$company = '';
				$company_name = '';
				if($event->client_id != "") {
					$client_details = DB::table("cm_clients")->where('client_id',$event->client_id)->first();
					if($client_details) {
						$company = ' - '.$client_details->company.' ('.$event->client_id.')';
						$company_name = $client_details->company.'-'.$event->client_id;
					}
				}
				$response[] = array(
					"eventid" => $event->id,
					"title" => $event->title.$company,
					"description" => $event->description,
					"start" => $event->start_date.' '.$event->start_time,
					"end" => $event->end_date.' '.$event->end_time,
					"title_name" => $event->title,
					"client_name" => $company_name,
					"client_id" => $event->client_id,
				);
			}
		}
		echo json_encode($response);
	}
	public function calendarEvents(Request $request) {
		$type = "";
		if($request->has("type")) {
			$type = $request->get("type");
		}

		// Add New event 
		if($type == 'addEvent'){
			$title = ""; $description = ""; 
			$start_date = ""; $end_date = "";
			$start_time = ""; $end_time = "";

			if($request->has('title')) {
				$title = $request->get('title');
			}
			if($request->has('description')) {
				$description = $request->get('description');
			}
			if($request->has('start_date')) {
				$start_date = date('Y-m-d', strtotime($request->get('start_date')));
			}
			$end_date = $start_date;
			if($request->has('end_date')) {
				if($request->get('end_date') != '') {
					$end_date = date('Y-m-d', strtotime($request->get('end_date')));
				}
			}
			if($request->has('start_time')) {
				$start_time = $request->get('start_time');
			}
			$end_time = $start_time;
			if($request->has('end_time')) {
				if($request->get('end_time') != '') {
					$end_time = $request->get('end_time');
				}
			}
			if($request->has('client_id')) {
				$client_id = $request->get('client_id');
			}

			$response = array();
			$status = 0;
			if(!empty($title) && !empty($description) && !empty($start_date) && !empty($end_date) ){
				$insertedEvent = [
					'user_id'		=> Session::get('userid'),
					'title'			=> $title,
					'description'	=> $description,
					'start_date' 	=> $start_date,
					'end_date'	 	=> $end_date,
					'start_time' 	=> $start_time,
					'end_time'	 	=> $end_time,
					'client_id'		=> $client_id,
				];

				$eventid = \App\Models\Events::insertDetails($insertedEvent);
				$status = 1;

				$response['eventid'] = $eventid;
				$response['status'] = 1;
				$response['message'] = 'Event created successfully.';
			}	

			if($status == 0){
				$response['status'] = 0;
				$response['message'] = 'Event not created.';
			}
			
			echo json_encode($response);
			exit;
		}

		// Move event
		if($type == 'moveEvent'){
			$eventid = 0; 
			$start_date = ""; $end_date = "";

			if($request->has('eventid') && is_numeric($request->get('eventid'))) {
				$eventid = $request->get('eventid');
			}
			if($request->has('start_date')) {
				$start_date = $request->get('start_date');
			}
			$end_date = $start_date;
			if($request->has('end_date')) {
				if($request->get('end_date') != ""){
					$end_date = $request->get('end_date');
				}
			}
			if($request->has('start_time')) {
				$start_time = $request->get('start_time');
			}
			$end_time = $start_time;
			if($request->has('end_time')) {
				if($request->get('end_time') != ""){
					$end_time = $request->get('end_time');
				}
			}
			
			$response = array();
			$status = 0;
			if($eventid > 0 && !empty($start_date) && !empty($end_date)) {
				$getEvent = DB::table('events')->where('id',$eventid)->first();
				if($getEvent) {
					$updateRecord['start_date'] = $start_date;
					$updateRecord['end_date'] = $end_date;

					$updateRecord['start_time'] = $start_time;
					$updateRecord['end_time'] = $end_time;

					DB::table('events')->where('id',$eventid)->update($updateRecord);
					$status = 1;
					$response['status'] = 1;
					$response['message'] = 'Event date updated successfully.';
				}
			}
			if($status == 0){
				$response['status'] = 0;
				$response['message'] = 'Event date not updated.';
			}	
			echo json_encode($response);
			exit;
		}

		// Update event
		if($type == 'editEvent'){
			$eventid = 0;
			if($request->has('eventid') && is_numeric($request->get('eventid'))) {
				$eventid = $request->get('eventid');
			}
			if($request->has('title')) {
				$title = $request->get('title');
			}
			if($request->has('description')) {
				$description = $request->get('description');
			}
			if($request->has('start_date')) {
				$start_date = date('Y-m-d', strtotime($request->get('start_date')));
			}
			$end_date = $start_date;
			if($request->has('end_date')) {
				if($request->get('end_date') != ""){
					$end_date = date('Y-m-d', strtotime($request->get('end_date')));
				}
			}
			if($request->has('start_time')) {
				$start_time = $request->get('start_time');
			}
			$end_time = $start_time;
			if($request->has('end_time')) {
				if($request->get('end_time') != ""){
					$end_time = $request->get('end_time');
				}
			}
			if($request->has('client_id')) {
				$client_id = $request->get('client_id');
			}
			
			$response = array();
			if($eventid > 0 && !empty($title) && !empty($description)) {
				$getEvent = DB::table('events')->where('id',$eventid)->first();
				if($getEvent) {
					$updateRecord['user_id'] = Session::get('userid');
					$updateRecord['title'] = $title;
					$updateRecord['description'] = $description;
					$updateRecord['start_date'] = $start_date;
					$updateRecord['end_date'] = $end_date;
					$updateRecord['start_time'] = $start_time;
					$updateRecord['end_time'] = $end_time;
					$updateRecord['client_id'] = $client_id;

					DB::table('events')->where('id',$eventid)->update($updateRecord);
					$status = 1;
					$response['status'] = 1;
					$response['message'] = 'Event date updated successfully.';
				}
			}

			if($status == 0){
				$response['status'] = 0;
				$response['message'] = 'Event not updated.';
			}

			echo json_encode($response);
			exit;
		}

		// Delete Event
		if($type == 'deleteEvent'){
			$eventid = 0;
			if($request->has('eventid') && is_numeric($request->get('eventid'))) {
				$eventid = $request->get('eventid');
			}
			$response = array();
			$status = 0;
			if($eventid > 0){
				$getEvent = DB::table('events')->where('id',$eventid)->first();
				if($getEvent) {
					DB::table('events')->where('id',$eventid)->delete();

					$status = 1;
					$response['status'] = 1;
					$response['message'] = 'Event deleted successfully.';
				}
			}
			if($status == 0){
				$response['status'] = 0;
				$response['message'] = 'Event not deleted.';
			}
			echo json_encode($response);
			exit;
		}
	}
}