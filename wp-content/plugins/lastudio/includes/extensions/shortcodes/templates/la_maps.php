<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$width = $height = $map_type = $lat = $lng = $zoom = $streetviewcontrol = $maptypecontrol = $top_margin = '';
$zoomcontrol = $zoomcontrolsize = $dragging = $marker_icon = $icon_img = $icon_img_url = $map_override = $output = '';
$map_style = $scrollwheel = $el_class = $css = '';
$default_desc = $more_markers = $shortcode_id = '';

$atts = shortcode_atts( array(
    'width' => '100%',
    'height' => '300px',
    'map_type' => 'ROADMAP',
    'lat' => '21.027764',
    'lng' => '105.834160',
    'zoom' => 12,
    'scrollwheel' => '',
    'infowindow_open' => '',
    'marker_icon' => 'default',
    'icon_img' => '',
    'icon_img_url' => '',
    'streetviewcontrol' => 'false',
    'maptypecontrol' => 'false',
    'zoomcontrol' => 'false',
    'zoomcontrolsize' => 'SMALL',
    'dragging' => 'true',
    'map_style' => '',
    'el_class' => '',
    'css' => '',
    'default_desc' => '',
    'more_markers' => '',
    'shortcode_id' => ''
), $atts );

extract( $atts );


if(empty($lat) || empty($lng)){
    return;
}

$_tmp_class = 'la-shortcode-maps';
$class_to_filter = $_tmp_class . la_shortcode_custom_css_class( $css, ' ' ) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );
$css_class = $class_to_filter;

$marker_lat = $lat;
$marker_lng = $lng;

$icon_url = plugin_dir_url( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . 'public/images/icon-marker.png';
if($marker_icon == "default"){
    $icon_url = '';
}
else{
    if(!empty($icon_img_url)){
        $icon_url = $icon_img_url;
    }
    if(!empty($icon_img)){
        if($_icon_img = wp_get_attachment_image_url($icon_img,'full')){
            $icon_url = $_icon_img;
        }
    }
}

$id = !empty($shortcode_id) ? $shortcode_id : uniqid('la_maps_');
$tmp_id = 'wrap_' . $id;
$map_type = strtoupper($map_type);
$map_width = (substr($width, -1)!="%" && substr($width, -2)!="px" ? $width . "px" : $width);
$map_height = (substr($height, -1)!="%" && substr($height, -2)!="px" ? $height . "px" : $height);

$decode_markers = urldecode($more_markers);
$more_markers = json_decode($decode_markers,true);
if($decode_markers == '[{}]'){
    $more_markers = array();
}

$marker_stores = array();
$marker_stores[] = array(
    'lat'   => $lat,
    'lng'   => $lng,
    'desc'  => $default_desc,
    'icon'  => $icon_url
);

if(!empty($more_markers)){
    foreach($more_markers as $store){
        if(!empty($store['lat']) && !empty($store['lng'])){
            $tmp = array();
            $tmp['lat'] = $store['lat'];
            $tmp['lng'] = $store['lng'];
            $tmp['desc'] = isset($store['desc']) ? $store['desc'] : '';
            if(!empty($store['custom_icon'])){
                if($_icon_img = wp_get_attachment_image_url($store['custom_icon'],'full')){
                    $tmp['icon'] = $_icon_img;
                }
                else{
                    $tmp['icon'] = $icon_url;
                }
            }
            else{
                $tmp['icon'] = $icon_url;
            }
            $marker_stores[] = $tmp;
        }
    }
}

$output .= '<div id="'.esc_attr($tmp_id).'" class="'.esc_attr($css_class).'" style="min-height:'.esc_attr($map_height).'">';
$output .= '<div id="'.esc_attr($id).'" class="la-maps-inner" style="'.esc_attr( implode(';', array(
        "width:{$map_width}",
        "height:{$map_height}"
    ))).'">';
$output .= '</div>';
$output .= '</div>';

echo $output;


$url = 'https://maps.googleapis.com/maps/api/js';
$key = apply_filters('LaStudio/core/google_map_api', '');
if(!empty($key)){
    $url = add_query_arg('key',$key, $url);
}

?><script type='text/javascript'>
    (function($) {
        'use strict';
        $(window).load(function(){
            var marker_stores = <?php echo json_encode($marker_stores) ?>,
                preview_infowindow = false,
                infowindow_open_first = <?php echo $infowindow_open == 'yes' ? 'true' : 'false'; ?>,

            initMap = function(){
                var map, center_position, is_draggable, mapOptions, StyledMapType, styleMapValue;
                is_draggable = $(document).width() > 641 ? true : <?php echo $dragging;?>;
                center_position = new google.maps.LatLng(<?php echo esc_attr($lat)?>,<?php echo esc_attr($lng)?>);
                mapOptions = {
                    zoom: <?php echo esc_attr($zoom)?>,
                    center: center_position,
                    scaleControl: true,
                    streetViewControl: <?php echo esc_attr($streetviewcontrol)?>,
                    mapTypeControl: <?php echo esc_attr($maptypecontrol)?>,
                    zoomControl: <?php echo $zoomcontrol;?>,
                    scrollwheel: <?php echo $scrollwheel == 'disable' ? 'false' : 'true';?>,
                    draggable: is_draggable,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.<?php echo esc_attr($zoomcontrolsize);?>
                    },
                    mapTypeControlOptions:{
                        mapTypeIds: [google.maps.MapTypeId.<?php echo esc_attr($map_type);?>, 'map_style']
                    }
                };
                <?php if(!empty($map_style)): ?>
                styleMapValue = JSON.parse(decodeURIComponent(atob('<?php echo strip_tags($map_style); ?>')));
                <?php else: ?>
                styleMapValue = [
                    {
                        "featureType":"administrative",
                        "elementType":"geometry",
                        "stylers":[
                            {
                                "saturation":"2"
                            },
                            {
                                "visibility":"simplified"
                            }
                        ]
                    },
                    {
                        "featureType":"administrative",
                        "elementType":"labels",
                        "stylers":[
                            {
                                "saturation":"-28"
                            },
                            {
                                "lightness":"-10"
                            },
                            {
                                "visibility":"on"
                            }
                        ]
                    },
                    {
                        "featureType":"administrative",
                        "elementType":"labels.text.fill",
                        "stylers":[
                            {
                                "color":"#444444"
                            }
                        ]
                    },
                    {
                        "featureType":"landscape",
                        "elementType":"all",
                        "stylers":[
                            {
                                "color":"#f2f2f2"
                            }
                        ]
                    },
                    {
                        "featureType":"landscape",
                        "elementType":"geometry.fill",
                        "stylers":[
                            {
                                "saturation":"-1"
                            },
                            {
                                "lightness":"-12"
                            }
                        ]
                    },
                    {
                        "featureType":"landscape.natural",
                        "elementType":"labels.text",
                        "stylers":[
                            {
                                "lightness":"-31"
                            }
                        ]
                    },
                    {
                        "featureType":"landscape.natural",
                        "elementType":"labels.text.fill",
                        "stylers":[
                            {
                                "lightness":"-74"
                            }
                        ]
                    },
                    {
                        "featureType":"landscape.natural",
                        "elementType":"labels.text.stroke",
                        "stylers":[
                            {
                                "lightness":"65"
                            }
                        ]
                    },
                    {
                        "featureType":"landscape.natural.landcover",
                        "elementType":"geometry",
                        "stylers":[
                            {
                                "lightness":"-15"
                            }
                        ]
                    },
                    {
                        "featureType":"landscape.natural.landcover",
                        "elementType":"geometry.fill",
                        "stylers":[
                            {
                                "lightness":"0"
                            }
                        ]
                    },
                    {
                        "featureType":"poi",
                        "elementType":"all",
                        "stylers":[
                            {
                                "visibility":"off"
                            }
                        ]
                    },
                    {
                        "featureType":"road",
                        "elementType":"all",
                        "stylers":[
                            {
                                "saturation":-100
                            },
                            {
                                "lightness":45
                            }
                        ]
                    },
                    {
                        "featureType":"road",
                        "elementType":"geometry",
                        "stylers":[
                            {
                                "visibility":"on"
                            },
                            {
                                "saturation":"0"
                            },
                            {
                                "lightness":"-9"
                            }
                        ]
                    },
                    {
                        "featureType":"road",
                        "elementType":"geometry.stroke",
                        "stylers":[
                            {
                                "lightness":"-14"
                            }
                        ]
                    },
                    {
                        "featureType":"road",
                        "elementType":"labels",
                        "stylers":[
                            {
                                "lightness":"-35"
                            },
                            {
                                "gamma":"1"
                            },
                            {
                                "weight":"1.39"
                            }
                        ]
                    },
                    {
                        "featureType":"road",
                        "elementType":"labels.text.fill",
                        "stylers":[
                            {
                                "lightness":"-19"
                            }
                        ]
                    },
                    {
                        "featureType":"road",
                        "elementType":"labels.text.stroke",
                        "stylers":[
                            {
                                "lightness":"46"
                            }
                        ]
                    },
                    {
                        "featureType":"road.highway",
                        "elementType":"all",
                        "stylers":[
                            {
                                "visibility":"simplified"
                            }
                        ]
                    },
                    {
                        "featureType":"road.highway",
                        "elementType":"labels.icon",
                        "stylers":[
                            {
                                "lightness":"-13"
                            },
                            {
                                "weight":"1.23"
                            },
                            {
                                "invert_lightness":true
                            },
                            {
                                "visibility":"simplified"
                            },
                            {
                                "hue":"#ff0000"
                            }
                        ]
                    },
                    {
                        "featureType":"road.arterial",
                        "elementType":"labels.icon",
                        "stylers":[
                            {
                                "visibility":"off"
                            }
                        ]
                    },
                    {
                        "featureType":"transit",
                        "elementType":"all",
                        "stylers":[
                            {
                                "visibility":"off"
                            }
                        ]
                    },
                    {
                        "featureType":"water",
                        "elementType":"all",
                        "stylers":[
                            {
                                "color":"#adadad"
                            },
                            {
                                "visibility":"on"
                            }
                        ]
                    }
                ];
                <?php endif; ?>
                StyledMapType = new google.maps.StyledMapType(styleMapValue,{name: "LA-Studio Styled Map"});
                map = new google.maps.Map(document.getElementById('<?php echo esc_attr($id)?>'),mapOptions);
                map.mapTypes.set('map_style', StyledMapType);
                map.setMapTypeId('map_style');

                for (var i = 0; i < marker_stores.length; i++) {
                    var marker_position = new google.maps.LatLng(marker_stores[i].lat,marker_stores[i].lng);
                    var marker = new google.maps.Marker({
                        position: marker_position,
                        animation:  google.maps.Animation.DROP,
                        map: map,
                        icon: marker_stores[i].icon,
                        zIndex: i+1
                    });
                    if(marker_stores[i].desc != null){
                        var marker_desc = decodeURIComponent(atob(marker_stores[i].desc));
                        if(marker_desc != ''){
                            attachSecretMessage(marker, marker_desc);
                            if(infowindow_open_first && i == 0){
                                google.maps.event.trigger(marker, 'click');
                            }
                        }
                    }
                }

                google.maps.event.trigger(map, 'resize');
                $(window).resize(function(){
                    google.maps.event.trigger(map, 'resize');
                    if(map){
                        map.setCenter(center_position);
                    }
                });
            }

            function attachSecretMessage(marker, secretMessage) {
                var infowindow = new google.maps.InfoWindow({
                    content: '<div class="map_info_text">' + secretMessage + '</div>'
                });
                marker.addListener('click', function() {
                    if(preview_infowindow){
                        preview_infowindow.close();
                    }
                    preview_infowindow = infowindow;
                    infowindow.open(marker.get('map'), marker);
                });
            }

            if("undefined" != typeof google && "undefined" != typeof google.maps){
                initMap();
            }
            else{
                try{
                    LA.core.loadDependencies(['<?php echo $url; ?>'], function(){
                        initMap();
                    })
                }catch (ex){

                }
            }
        });

    })(jQuery);
</script>