<?php
require_once('modules/Calendar/Appointment.php');
require_once('modules/Calendar/Date.php');
class Calendar 
{
	var $view='day';
	var $date_time;
	var $hour_format = 'am/pm';
	var $show_events;
	var $show_tasks; 
	var $day_slice;
	var $week_slice;
	var $week_array;
	var $month_array;
	var $slices = Array();
	/* for dayview */
	var $day_start_hour=0;
	var $day_end_hour=23;
	var $sharedusers=Array();
	/*
	constructor
	*/
	function Calendar($view,$data)
	{
		$this->view = $view;
		$this->date_time = new DateTime($data,true);
		$this->constructLayout();
	}
	/*To get view Label
	*/
	function getCalendarView($view)
	{
		switch($view)
                {
			case 'day':
				return "DAY";
			case 'week':
				return "WEEK";
			case 'month':
				return "MON";
			case 'year':
				return "YEAR";
		}
	}

	/*To construct layout wrt view
	*/
	function constructLayout()
	{
		global $current_user;
		switch($this->view)
		{
			case 'day':
				$day_start_hour = $this->day_start_hour;
				$day_end_hour = $this->day_end_hour;
				//$dayview_hours = $day_end_hour - $day_start_hour;
				for($i=$day_start_hour;$i<=$day_end_hour;$i++)
				{
					$layout = new Layout('hour',$this->date_time->getTodayDatetimebyIndex($i));
					$this->day_slice[$layout->start_time->get_formatted_date().':'.$layout->start_time->hour] = $layout;
					array_push($this->slices,  $layout->start_time->get_formatted_date().":".$layout->start_time->hour);
				}
				break;
			case 'week':
				$weekview_days = 7;
				$day_start_hour = $this->day_start_hour;
                                $day_end_hour = $this->day_end_hour;
                                $dayview_hours = $day_end_hour - $day_start_hour;
				for($i=0;$i<$weekview_days;$i++)
				{
					$layout = new Layout('day',$this->date_time->getThisweekDaysbyIndex($i));
					$this->week_array[$layout->start_time->get_formatted_date()] = $layout;
					/*for($h=0;$h<$dayview_hours;$h++)
					{
                                        	$hour_list = new Layout('hour',$this->date_time->getTodayDatetimebyIndex($h));
						$this->day_slice[$layout->start_time->hour] = $layout;
						array_push($this->slices,  $layout->start_time->get_formatted_date().":".$layout->start_time->hour);
					}*/
					array_push($this->slices,  $layout->start_time->get_formatted_date());
					
				}
				break;
			case 'month':
				$monthview_days = $this->date_time->daysinmonth;
				$firstday_of_month = $this->date_time->getThismonthDaysbyIndex(0);
                                $num_of_prev_days = $firstday_of_month->dayofweek;
				for($i=0;$i<42;$i++)
                                {
					$layout = new Layout('day',$this->date_time->getThismonthDaysbyIndex($i-$num_of_prev_days));
					$this->month_array[$layout->start_time->get_formatted_date()] = $layout;
					array_push($this->slices,  $layout->start_time->get_formatted_date());
				}
				break;
			case 'year':
			case 'share':
				//include "calendar_share.php";
		}
	}

	function get_datechange_info($type)
	{
		if($type == 'next')
			$mode = 'increment';	
		if($type == 'prev')
			$mode = 'decrment';
		switch($this->view)
		{
			case 'day':
				$day = $this->date_time->get_changed_day($mode);
				break;
			case 'week':
				$day = $this->date_time->get_first_day_of_changed_week($mode);
				break;
			case 'month':
				$day = $this->date_time->get_first_day_of_changed_month($mode);
				break;
			case 'year':
				$day = $this->date_time->get_first_day_of_changed_year($mode);
				break;
			default:
				return "view is not supported";
		}
		return $day->get_date_str();
	}
	
	function add_Activities($current_user,$free_busy='')
	{
		if ( $this->view == 'week')
		{
			$end_datetime = $this->date_time->get_first_day_of_changed_week('increment');
                } elseif($this->view == 'month') {
			$end_datetime = $this->date_time->get_first_day_of_changed_month('increment');
		} else {
                        $end_datetime = $this->date_time->get_changed_day('increment');
                }
		
		$activities = Array();
		$activities = Appointment::readAppointment($current_user->id,$this->date_time,$end_datetime,$this->view);
		
		if(!empty($activities))
		{
			foreach($activities as $key=>$value)
			{
				if($this->view == 'day')
				{
					array_push($this->day_slice[$value->formatted_datetime]->activities, $value);
				}
				elseif($this->view == 'week')
				{
			
				}
				elseif($this->view == 'month')
				{
					array_push($this->month_array[$value->formatted_datetime]->activities,$value);
				}
				elseif($this->view == 'year')
				{
				}
				else
					die("view:".$this->view." is not defined");

			}
		}
		//echo '<pre>';print_r($this->month_array);echo'</pre>';
		
	}
	

}

class Layout
{
	var $view = 'day';
	var $start_time;
        var $end_time;
	var $activities = Array();
	
	function Layout($view,$time)
        {
                $this->view = $view;
                $this->start_time = $time;
                if ( $view == 'day')
                        $this->end_time = $this->start_time->getDayendtime();
                if ( $view == 'hour')
                        $this->end_time = $this->start_time->getHourendtime();
        }

	function getView()
	{
		return $this->view;
	}
}
?>
