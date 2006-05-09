<?php
class DateTime
{
	var $second;
	var $minute;
	var $hour;
	var $ms_hour;
	var $day;
	var $ms_day;
	var $week;
	var $month;
	var $ms_month;
	var $year;
	var $dayofweek;
	var $dayofyear;
	var $daysinmonth;
        var $daysinyear;
        var $dayofweek_inshort;
        var $dayofweek_inlong;
	var $month_inshort;
        var $month_inlong;
	var $ts;
	var $offset;
	var $format;
	var $tz;
	var $ts_def;
	
	function DateTime(&$timearr,$check)
	{
		if (! isset( $timearr) || count($timearr) == 0 )
                {
                        $this->setDateTime(null);
                }
                else if ( isset( $timearr['ts']))
                {
                        $this->setDateTime($time['ts']);
                }
		else
		{
			if(isset($timearr['hour']))
	                {
        	                $this->hour = $timearr['hour'];
                	}
			if(isset($timearr['min']))
        	        {
                	        $this->minute = $timearr['min'];
	                }
			if(isset($timearr['sec']))
                	{
				$this->second = $timearr['sec'];
			}
        		if(isset($timearr['day']))
	                {
         	               $this->day = $timearr['day'];
                	}
	                if(isset($timearr['week']))
        	        {
                	        $this->week = $timearr['week'];
	                }
        	        if(isset($timearr['month']))
                	{
                        	$this->month = $timearr['month'];
	                }
        	        if(isset($timearr['year']) && $timearr['year'] >= 1970)
                	{
                		$this->year = $timearr['year'];
			}
        	        else
                	{
                		return null;
                	}	
		}
		if ($check)
	        {
                        $this->getDateTime();
                }
	}

	
	function getTodayDatetimebyIndex($index)
	{
		$day_array = array();
		if($index < 0 || $index > 23)
                {
                        die("hour is invalid");
                }
                $day_array['hour'] = $index;
                $day_array['min'] = 0;
                $day_array['day'] = $this->day;
                $day_array['month'] = $this->month;
                $day_array['year'] = $this->year;
		$datetimevalue = new DateTime($day_array,true);
                return $datetimevalue;
	}
	function getThisweekDaysbyIndex($index)
        {
                $week_array = array();
                if($index < 0 || $index > 6)
                {
                        die("day is invalid");
                }
                $week_array['day'] = $this->day + ($index - $this->dayofweek);
                $week_array['month'] = $this->month;
                $week_array['year'] = $this->year;
                $datetimevalue = new DateTime($week_array,true);
                return $datetimevalue;
        }
	function getThismonthDaysbyIndex($index)
        {
                $month_array = array();
                $month_array['day'] = $index+1;
                $month_array['month'] = $this->month;
                $month_array['year'] = $this->year;
                $datetimevalue = new DateTime($month_array,true);
                return $datetimevalue;
        }
	function getHourendtime()
        {
                $date_array = array();
                $date_array['hour'] = $this->hour;
                $date_array['min'] = 59;
                $date_array['day'] = $this->day;
		$date_array['sec'] = 59;
                $date_array['month'] = $this->month;
                $date_array['year'] = $this->year;
		$datetimevalue = new DateTime($date_array,true);
                return $datetimevalue;
        }
	function getDayendtime()
        {
                $date_array = array();
                $date_array['hour'] = 23;
                $date_array['min'] = 59;
                $date_array['sec'] = 59;
                $date_array['day'] = $this->day;
                $date_array['month'] = $this->month;
                $date_array['year'] = $this->year;
		$datetimevalue = new DateTime($date_array,true);
                return $datetimevalue;
        }
	
	function get_Date()
	{
		return $this->day;
	}
	function getmonthName_inshort()
	{
		return $this->month_inshort;
	}	
	function getMonth()
        {
                return $this->month;
        }
	function getmonthName()
	{
		return $this->month_inlong;
	}
	
	function getdayofWeek()
	{
		return $this->dayofweek_inlong;
	}
	function getdayofWeek_inshort()
	{
		return $this->dayofweek_inshort;
	}
	function setDateTime($ts)
	{
		global $mod_strings;
		if (empty($ts))
                {
                        $ts = time();
                }

                $this->ts = $ts;
		$this->ts_def = $this->ts;
		/*get values from calendar settings for following variables -- by Minnie
		$this->day_start_hour
		$this->day_end_hour
		*/
                $date_string = date('i::G::H::j::d::t::w::z::L::W::n::m::Y::Z::T::s',$ts);
                list(
                $this->minute,
                $this->hour,
		$this->ms_hour,
                $this->day,
		$this->ms_day,
                $this->daysinmonth,
                $this->dayofweek,
                $this->dayofyear,
                $is_leap,
                $this->week,
                $this->month,
		$this->ms_month,
                $this->year,
                $this->offset,
		$this->tz,
		$this->second)
                 = split('::',$date_string);
		$this->dayofweek_inshort =$mod_strings['cal_weekdays_short'][$this->dayofweek];
                $this->dayofweek_inlong=$mod_strings['cal_weekdays_long'][$this->dayofweek];
                $this->month_inshort=$mod_strings['cal_month_short'][$this->month];
                $this->month_inlong=$mod_strings['cal_month_long'][$this->month];

                $this->daysinyear = 365;

                if ($is_leap == 1)
                {
                        $this->daysinyear += 1;
                }



	}
	function getDateTime()
        {
                global $mod_strings;
                $hour = 0;
                $minute = 0;
                $second = 0;
                $day = 1;
                $month = 1;
                $year = 1970;

                if ( isset($this->second))
                {
                        $second = $this->second;
                }
                if ( isset($this->minute))
                {
                        $minute = $this->minute;
                }
                if ( isset($this->hour))
                {
                        $hour = $this->hour;
                }
                if ( isset($this->day))
                {
                        $day= $this->day;
                }
                if ( isset($this->month))
                {
                        $month = $this->month;
                }
                if ( isset($this->year))
                {
                        $year = $this->year;
                }
                else
                {
                        die("year was not set");
                }
                $this->ts = mktime($hour,$minute,$second,$month,$day,$year);
                $this->setDateTime($this->ts);
	}
	function get_formatted_date()
        {
                return $this->year."-".$this->ms_month."-".$this->ms_day;
        }
        function get_formatted_time()
        {
                return $this->ms_hour.":".$this->min;
        }
	
	function get_changed_day($mode)
	{
		if($mode == 'increment')
			$day = $this->day + 1;
		else
			$day = $this->day - 1;
		$date_data = array(
                                        'day'=>$day,
                                        'month'=>$this->month,
                                        'year'=>$this->year
                                  );

                return new DateTime($date_data,true);
	}
	
	function get_first_day_of_changed_week($mode)
	{
		$first_day = $this->getThisweekDaysbyIndex(0);
		if($mode == 'increment')
                        $day = $first_day->day + 7;
                else
                        $day = $first_day->day - 7;
		$date_data = array(
                                        'day'=>$day,
                                        'month'=>$first_day->month,
                                        'year'=>$first_day->year
                                  );
		return new DateTime($date_data,true);
	}
	
	function get_first_day_of_changed_month($mode)
	{
		if($mode == 'increment')
		{
                        $month = $this->month + 1;
			$year = $this->year ;
		}
                else
		{
			if($this->month == 1)
                	{
                        	$month = 12;
	                        $year = $this->year - 1;
        	        }
                	else
                	{
                        	$month = $this->month - 1;
	                        $year = $this->year ;
        	        }
		}
		$date_data = array(
					'day'=>1,
					'month'=>$month,
					'year'=>$year
				  );

                return new DateTime($date_data,true);
	}

	function get_first_day_of_changed_year($mode)
	{
		if($mode == 'increment')
                        $year = $this->year + 1;
                else
                        $year = $this->year - 1;
                $date_data = array(
                                        'day'=>1,
                                        'month'=>1,
                                        'year'=>$year
                                  );

                return new DateTime($date_data,true);	
	}
	
	function get_date_str()
        {

                $array = Array();
                if ( isset( $this->hour))
                {
                 array_push( $array, "hour=".$this->hour);
                }
                if ( isset( $this->day))
                {
                 array_push( $array, "day=".$this->day);
                }
                if ( isset( $this->month))
                {
                 array_push( $array, "month=".$this->month);
                }
                if ( isset( $this->year))
                {
                 array_push( $array, "year=".$this->year);
                }
                return  ("&".implode('&',$array));
        }

}
?>
