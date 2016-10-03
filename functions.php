<?php
function parse_subject($event_in_calendar) {
	$event 	  	= explode("-", $event_in_calendar['Subject']);
	$location 	= $event_in_calendar['Location']['DisplayName'];
	$date_time	= pase_outlook_date($event_in_calendar['Start']['DateTime']);
	$output   	= array();
	if (isset($event[0]) && isset($event[1])) {
		$event_type = trim($event[0]);
		$event_info = explode(",", $event[1]);
		$phone		= end($event_info);
		$output['phone'] = $phone;
		switch ($event_type) {
			case 'CR':
				$output['event_type'] = "Court Reminder";
				$output['event_template'] = court_reminder_template($event_info, $location, $date_time);
				break;
			case 'VAR':
				$output['event_type'] = "VLA Apointment Reminder";
				$output['event_template'] = vla_apoint_reminder_template($event_info, $location, $date_time);
				break;
			case 'SAR':
				$output['event_type'] = "Specialist appointment reminder";
				$output['event_template'] = specialist_appointment_reminder_template($event_info, $location, $date_time);
				break;
			case 'RSI':
				$output['event_type'] = "Reminder to supply info/docs";
				$output['event_template'] = supply_info_reminder_template($event_info, $location, $date_time);
				break;
			case 'RBB':
				$output['event_type'] = "Reminder of barrister briefed";
				$output['event_template'] = barrister_briefed_reminder_template($event_info, $location, $date_time);
				break;
			case 'CBM':
				$output['event_type'] = "Call back message";
				$output['event_template'] = call_back_message_template($event_info, $location, $date_time);
				break;			
			default:
				# code...
				$output['event_type'] = "";
				$output['event_template'] = "";
				break;
		}
	}
	return $output;
}

function pase_outlook_date($outlook_date) {
	$split_date = explode("T", $outlook_date);
	$date = $split_date[0];
	$date = date("D, d/m/Y", strtotime($split_date[0])); // date("M jS, Y", strtotime("2016-09-16")); Sep 16th, 2016
	$time = explode(".", $split_date[1]);
	$time = date("g:i a", strtotime($time[0]));
	return array('date' => $date, 'time' => $time);
}

function court_reminder_template($event_info, $location, $date_time) {
	$name	= $event_info[0];
	$phone	= $event_info[1];
	$date 	= $date_time['date'];
	$time 	= $date_time['time'];
	return "Hi, reminder to attend ". $location . " on " . $date . " at " . $time . ". Any questions call " . $name . ", Victoria Legal Aid on "  . $phone . ".";
}

function vla_apoint_reminder_template($event_info, $location, $date_time) {	
	$name	= $event_info[0];
	$phone	= $event_info[1];
	$date 	= $date_time['date'];
	$time 	= $date_time['time'];
	return "Hi, reminder to attend your appointment with Victoria Legal Aid at " . $location . " on " . $date . " at " . $time . ". To change, call " . $name . " on " . $phone . ".";
}

function specialist_appointment_reminder_template($event_info, $location, $date_time) {
	$name	= $event_info[0];
	$name_2	= $event_info[1];
	$phone	= $event_info[2];
	$date 	= $date_time['date'];
	$time 	= $date_time['time'];
	return "Reminder of your appointment with " . $name . " on " . $date . " at " . $time . " at " . $location . ". Any questions call " . $name_2 . ", Victoria Legal Aid on " . $phone . ".";
}

function supply_info_reminder_template($event_info, $location, $date_time) {
	$reason	= $event_info[0];
	$name	= $event_info[1];
	$phone	= $event_info[2];
	$date 	= $date_time['date'];
	return "Hi, reminder to " . $reason . " " . $date . ". Any questions call " . $name . ", Victoria Legal Aid on " . $phone . ".";
}

function barrister_briefed_reminder_template($event_info, $location, $date_time) {
	$name	= $event_info[0];
	$name_2	= $event_info[1];
	$phone	= $event_info[2];
	$date 	= $date_time['date'];
	$time 	= $date_time['time'];
	return "Hi, reminder to meet your barrister " . $name . " at " . $location . " Court " . $time . " " . $date . ". Any questions call " . $name_2 . ", Victoria Legal Aid on " . $phone . ".";
}

function call_back_message_template($event_info, $location, $date_time) {
	$name	= $event_info[0];
	$phone	= $event_info[1];
	$date 	= $date_time['date'];
	$time 	= $date_time['time'];
	return "Hi, I rang you on " . $date . " but there was no answer. Could you please call " . $name . ", Victoria Legal Aid on " . $phone . ".";	
}