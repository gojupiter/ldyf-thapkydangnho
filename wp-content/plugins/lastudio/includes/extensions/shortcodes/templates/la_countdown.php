<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$count_style = $datetime = $time_zone = $countdown_opts = $tick_col = $tick_size = $tick_line_height = $tick_style = $tick_sep_col = $tick_sep_size = $tick_sep_line_height = '';
$tick_sep_style = $br_color = $br_style = $br_size = $timer_bg_color = $br_radius = $br_time_space = $el_class = '';
$string_days = $string_weeks = $string_months = $string_years = $string_hours = $string_minutes = $string_seconds = '';
$string_days2 = $string_weeks2 = $string_months2 = $string_years2 = $string_hours2 = $string_minutes2 = $string_seconds2 = '';
$css_countdown = $el_id = '';
extract(shortcode_atts( array(
    'count_style'=>'1',
    'datetime'=>'',
    'time_zone'=>'wptz',
    'countdown_opts'=>'',
    'tick_col'=>'',
    'tick_size'=>'',
    'tick_line_height'=>'',
    'tick_style'=>'',
    'tick_sep_col'=>'',
    'tick_sep_size'=>'',
    'tick_sep_line_height'=> '',
    'tick_sep_style'=>'',
    'br_color'=>'',
    'br_style'=>'',
    'br_size'=>'',
    'timer_bg_color'=>'',
    'br_radius'=>'',
    'br_time_space'=>'0',
    'el_class'=>'',
    'string_days' => 'Day',
    'string_days2' => 'Days',
    'string_weeks' => 'Week',
    'string_weeks2' => 'Weeks',
    'string_months' => 'Month',
    'string_months2' => 'Months',
    'string_years' => 'Year',
    'string_years2' => 'Years',
    'string_hours' => 'Hrs',
    'string_hours2' => 'Hrs',
    'string_minutes' => 'Mins',
    'string_minutes2' => 'Mins',
    'string_seconds' => 'Secs',
    'string_seconds2' => 'Secs',
    'css_countdown' => '',
    'el_id' => '',
), $atts ));
$el_class = LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$count_frmt = $labels = $countdown_design_style = '';
$labels = $string_years2 .','.$string_months2.','.$string_weeks2.','.$string_days2.','.$string_hours2.','.$string_minutes2.','.$string_seconds2;
$labels2 = $string_years .','.$string_months.','.$string_weeks.','.$string_days.','.$string_hours.','.$string_minutes.','.$string_seconds;
$countdown_opt = explode(",",$countdown_opts);
if(is_array($countdown_opt)){
    foreach($countdown_opt as $opt){
        if($opt == "syear") $count_frmt .= 'Y';
        if($opt == "smonth") $count_frmt .= 'O';
        if($opt == "sweek") $count_frmt .= 'W';
        if($opt == "sday") $count_frmt .= 'D';
        if($opt == "shr") $count_frmt .= 'H';
        if($opt == "smin") $count_frmt .= 'M';
        if($opt == "ssec") $count_frmt .= 'S';
    }
}
if (is_numeric($tick_size)) {
    $tick_size = 'desktop:'.$tick_size.'px;';
}
$countdown_design_style = la_shortcode_custom_css_class( $css_countdown, ' ' );
$countdown_design_style = esc_attr( $countdown_design_style );



$data_attr = '';
if($count_frmt=='') $count_frmt = 'DHMS';
if($br_size =='' || $br_color == '' || $br_style ==''){
    if($timer_bg_color==''){
        $el_class.=' elm-countdown-no-border';
    }
}
else{
    $data_attr .=  'data-br-color="'.$br_color.'" data-br-style="'.$br_style.'" data-br-size="'.$br_size.'" ';
}
// Responsive param

if(is_numeric($tick_sep_size)) 	{ 	$tick_sep_size = 'desktop:'.$tick_sep_size.'px;';		}
if(is_numeric($tick_sep_line_height)) 	{ 	$tick_sep_line_height = 'desktop:'.$tick_sep_line_height.'px;';		}

$count_down_id =  !empty($el_id) ? esc_attr($el_id) : uniqid('la_countdown_');
$count_down_sep_args = array(
    'target'		=>	'#'.$count_down_id.' .countdown-period',
    'media_sizes' 	=> array(
        'font-size' 	=> $tick_sep_size,
        'line-height' 	=> $tick_sep_line_height,
    ),
);


$data_attr .= ' data-tick-style="'.$tick_style.'" ';
$data_attr .= ' data-tick-p-style="'.$tick_sep_style.'" ';
$data_attr .= ' data-bg-color="'.$timer_bg_color.'" data-br-radius="'.$br_radius.'" data-padd="'.$br_time_space.'" ';
$output = '<div data-la_component="CountDownTimer" class="js-el elm-countdown '.$countdown_design_style.' '.$el_class.' elm-countdown-style-'.$count_style.'" >';


if(is_numeric($tick_size)) 	{ 	$tick_size = 'desktop:'.$tick_size.'px;';		}
if(is_numeric($tick_line_height)) 	{ 	$tick_line_height = 'desktop:'.$tick_line_height.'px;';		}

$count_down_args = array(
    'target'		=>	'#'.$count_down_id.' .countdown-amount',
    'media_sizes' 	=> array(
        'font-size' 	=> $tick_size,
        'line-height' 	=> $tick_line_height,
    ),
);

if($datetime!=''){
    $output .='<div id="'.$count_down_id.'"  class="elm-countdown-div elm-countdown-dateandtime '.$time_zone.'" data-labels="'.$labels.'" data-labels2="'.$labels2.'"  data-terminal-date="'.$datetime.'" data-countformat="'.$count_frmt.'" data-time-zone="'.get_option('gmt_offset').'" data-time-now="'.str_replace('-', '/', current_time('mysql')).'"  data-tick-col="'.$tick_col.'" data-tick-p-col="'.$tick_sep_col.'" '.$data_attr.'>'.$datetime.'</div>';
}
$output .='</div>';

echo $output;