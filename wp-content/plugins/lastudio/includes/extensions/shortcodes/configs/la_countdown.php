<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


$shortcode_params = array(
	array(
		"type" => "dropdown",
		"heading" => __("Design", 'lastudio'),
		"param_name" => "count_style",
		"value" => array(
			__("Style 01",'lastudio') => "1",
			__("Style 02",'lastudio') => "2",
			__("Style 03",'lastudio') => "3",
			__("Style 04",'lastudio') => "4",
		)
	),
	array(
		"type" => "datetimepicker",
		"heading" => __("Target Time For Countdown", 'lastudio'),
		"param_name" => "datetime",
		"description" => __("Date and time format (yyyy/mm/dd hh:mm:ss).", 'lastudio'),
	),
	array(
		"type" => "dropdown",
		"heading" => __("Countdown Timer Depends on", 'lastudio'),
		"param_name" => "time_zone",
		"value" => array(
			__("WordPress Defined Timezone",'lastudio') => "wptz",
			__("User's System Timezone",'lastudio') => "usrtz",
		),
	),
	array(
		"type" => "checkbox",
		"heading" => __("Select Time Units To Display In Countdown Timer", 'lastudio'),
		"param_name" => "countdown_opts",
		"value" => array(
			__("Years",'lastudio') => "syear",
			__("Months",'lastudio') => "smonth",
			__("Weeks",'lastudio') => "sweek",
			__("Days",'lastudio') => "sday",
			__("Hours",'lastudio') => "shr",
			__("Minutes",'lastudio') => "smin",
			__("Seconds",'lastudio') => "ssec",
		),
	),

	LaStudio_Shortcodes_Helper::fieldExtraClass(),
	LaStudio_Shortcodes_Helper::fieldElementID(array(
		'param_name' 	=> 'el_id'
	)),
	array(
		"type" => "textfield",
		"heading" => __("Day (Singular)", 'lastudio'),
		"param_name" => "string_days",
		"value" => "Day",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Days (Plural)", 'lastudio'),
		"param_name" => "string_days2",
		"value" => "Days",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Week (Singular)", 'lastudio'),
		"param_name" => "string_weeks",
		"value" => "Week",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Weeks (Plural)", 'lastudio'),
		"param_name" => "string_weeks2",
		"value" => "Weeks",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Month (Singular)", 'lastudio'),
		"param_name" => "string_months",
		"value" => "Month",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Months (Plural)", 'lastudio'),
		"param_name" => "string_months2",
		"value" => "Months",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Year (Singular)", 'lastudio'),
		"param_name" => "string_years",
		"value" => "Year",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Years (Plural)", 'lastudio'),
		"param_name" => "string_years2",
		"value" => "Years",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Hour (Singular)", 'lastudio'),
		"param_name" => "string_hours",
		"value" => "Hrs",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Hours (Plural)", 'lastudio'),
		"param_name" => "string_hours2",
		"value" => "Hrs",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Minute (Singular)", 'lastudio'),
		"param_name" => "string_minutes",
		"value" => "Mins",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Minutes (Plural)", 'lastudio'),
		"param_name" => "string_minutes2",
		"value" => "Mins",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Second (Singular)", 'lastudio'),
		"param_name" => "string_seconds",
		"value" => "Secs",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Seconds (Plural)", 'lastudio'),
		"param_name" => "string_seconds2",
		"value" => "Secs",
		'group' => __( 'Strings Translation', 'lastudio' ),
	),
);

return apply_filters(
	'LaStudio/shortcodes/configs',
	array(
		'name'			=> __('Count Down', 'lastudio'),
		'base'			=> 'la_countdown',
		'icon'          => 'la-wpb-icon la_countdown',
		'category'  	=> __('La Studio', 'lastudio'),
		'description' 	=> __('Countdown Timer','lastudio'),
		'params' 		=> $shortcode_params
	),
    'la_countdown'
);