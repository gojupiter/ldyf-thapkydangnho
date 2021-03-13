( function( $, elementor ) {

	"use strict";

	var LaStudioElements = {

		init: function() {

			var widgets = {
				'lastudio-advanced-carousel.default' : LaStudioElements.widgetCarousel,
				'lastudio-circle-progress.default' : LaStudioElements.widgetProgress,
				'lastudio-advanced-map.default' : LaStudioElements.widgetMap,
				'lastudio-countdown-timer.default' : LaStudioElements.widgetCountdown,
				'lastudio-posts.default' : LaStudioElements.widgetPosts,
				'lastudio-animated-text.default' : LaStudioElements.widgetAnimatedText,
				'lastudio-animated-box.default' : LaStudioElements.widgetAnimatedBox,
				'lastudio-images-layout.default' : LaStudioElements.widgetImagesLayout,
				'lastudio-slider.default' : LaStudioElements.widgetSlider,
				'lastudio-testimonials.default' : LaStudioElements.widgetTestimonials,
				'lastudio-image-comparison.default' : LaStudioElements.widgetImageComparison,
				'lastudio-instagram-gallery.default' : LaStudioElements.widgetInstagramGallery,
				'lastudio-scroll-navigation.default' : LaStudioElements.widgetScrollNavigation,
				'lastudio-subscribe-form.default' : LaStudioElements.widgetSubscribeForm,
				'lastudio-progress-bar.default' : LaStudioElements.widgetProgressBar,
				'lastudio-portfolio.default' : LaStudioElements.widgetPortfolio,
				'lastudio-timeline.default': LaStudioElements.widgetTimeLine,
				'lastudio-table.default': LaStudioElements.widgetTable,
				'lastudio-dropbar.default': LaStudioElements.widgetDropbar,
				'lastudio-video.default': LaStudioElements.widgetVideo,
				'lastudio-audio.default': LaStudioElements.widgetAudio,
				'lastudio-horizontal-timeline.default': LaStudioElements.widgetHorizontalTimeline,
				'mp-timetable.default': LaStudioElements.widgetTimeTable,
				'lastudio-team-member.default': LaStudioElements.widgetTeamMember,
				'lastudio-tabs.default': LaStudioElements.widgetTabs,
				'lastudio-products.default': LaStudioElements.widgetProducts
			};

			$.each( widgets, function( widget, callback ) {
				elementor.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

            elementor.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
                try {
                    LA.core.initAll($scope);
                }catch (e) {
					console.log(e);
                }
            } );

            elementor.hooks.addAction( 'frontend/element_ready/image-gallery.default', function( $scope ) {
                $scope.find('br').remove();
            } );

			elementor.hooks.addAction( 'frontend/element_ready/section', LaStudioElements.elementorSection );
			//elementor.hooks.addAction( 'frontend/element_ready/column', LaStudioElements.elementorColumn );

			elementor.hooks.addAction( 'frontend/element_ready/image-carousel.default', function ( $scope ) {

				var _id = $scope.attr('id') || false;
				if(_id && $('#' + _id + '_dots').length){
					//var _swiper = $scope.find('.swiper-container').data('swiper');
					$scope.find('.swiper-pagination').appendTo($('#' + _id + '_dots'));
					//$scope.find('.elementor-image-carousel').slick('slickSetOption', 'appendDots', $('#' + _id + '_dots'), true);
				}

            }, 500)
		},

        getCurrentDeviceMode: function(){
			var device = 'desktop',
				_w_width = window.innerWidth;

			if(_w_width > 1300){
                device = 'desktop';
			}
			else if( _w_width > 768 ){
                device = 'tablet';
			}
			else{
                device = 'mobile';
			}

			return device;

        },

        widgetProducts: function( $scope ){
            LA.core.initAll($scope);
		},

		widgetCountdown: function( $scope ) {

			var timeInterval,
				$coutdown = $scope.find( '[data-due-date]' ),
				endTime = new Date( $coutdown.data( 'due-date' ) * 1000 ),
				elements = {
					days: $coutdown.find( '[data-value="days"]' ),
					hours: $coutdown.find( '[data-value="hours"]' ),
					minutes: $coutdown.find( '[data-value="minutes"]' ),
					seconds: $coutdown.find( '[data-value="seconds"]' )
				};

			LaStudioElements.widgetCountdown.updateClock = function() {

				var timeRemaining = LaStudioElements.widgetCountdown.getTimeRemaining( endTime );

				$.each( timeRemaining.parts, function( timePart ) {

					var $element = elements[ timePart ];

					if ( $element.length ) {
						$element.html( this );
					}

				} );

				if ( timeRemaining.total <= 0 ) {
					clearInterval( timeInterval );
				}
			};

			LaStudioElements.widgetCountdown.initClock = function() {
				LaStudioElements.widgetCountdown.updateClock();
				timeInterval = setInterval( LaStudioElements.widgetCountdown.updateClock, 1000 );
			};

			LaStudioElements.widgetCountdown.splitNum = function( num ) {

				var num   = num.toString(),
					arr   = [],
					reult = '';

				if ( 1 === num.length ) {
					num = 0 + num;
				}

				arr = num.match(/\d{1}/g);

				$.each( arr, function( index, val ) {
					reult += '<span class="lastudio-countdown-timer__digit">' + val + '</span>';
				});

				return reult;
			};

			LaStudioElements.widgetCountdown.getTimeRemaining = function( endTime ) {

				var timeRemaining = endTime - new Date(),
					seconds = Math.floor( ( timeRemaining / 1000 ) % 60 ),
					minutes = Math.floor( ( timeRemaining / 1000 / 60 ) % 60 ),
					hours = Math.floor( ( timeRemaining / ( 1000 * 60 * 60 ) ) % 24 ),
					days = Math.floor( timeRemaining / ( 1000 * 60 * 60 * 24 ) );

				if ( days < 0 || hours < 0 || minutes < 0 ) {
					seconds = minutes = hours = days = 0;
				}

				return {
					total: timeRemaining,
					parts: {
						days: LaStudioElements.widgetCountdown.splitNum( days ),
						hours: LaStudioElements.widgetCountdown.splitNum( hours ),
						minutes: LaStudioElements.widgetCountdown.splitNum( minutes ),
						seconds: LaStudioElements.widgetCountdown.splitNum( seconds )
					}
				};
			};

			LaStudioElements.widgetCountdown.initClock();

		},

		widgetMap: function( $scope ) {

			var $container = $scope.find( '.lastudio-map' ),
				map,
				init,
				pins;

			if ( ! window.google || ! $container.length ) {
				return;
			}

			init = $container.data( 'init' );
			pins = $container.data( 'pins' );
			map  = new google.maps.Map( $container[0], init );

			if ( pins ) {
				$.each( pins, function( index, pin ) {

					var marker,
						infowindow,
						pinData = {
							position: pin.position,
							map: map
						};

					if ( '' !== pin.image ) {
						pinData.icon = pin.image;
					}

					marker = new google.maps.Marker( pinData );

					if ( '' !== pin.desc ) {
						infowindow = new google.maps.InfoWindow({
							content: pin.desc,
							disableAutoPan: true
						});
					}

					marker.addListener( 'click', function() {
						infowindow.setOptions({ disableAutoPan: false });
						infowindow.open( map, marker );
					});

                    google.maps.event.addListener(infowindow, 'domready', function() {
                        var iwOuter = $('.gm-style-iw');
                        iwOuter.prev().addClass('gm-style-iw-prev').parent().parent().parent().parent().addClass('gm-parent-iw');
                    });

					if ( 'visible' === pin.state && '' !== pin.desc ) {
						infowindow.open( map, marker );
					}

				});
			}

            map.addListener('tilt_changed', function() {
                if($container.find('.gm-style-pbc').length){
                    $container.find('.gm-style-pbc').next().addClass('gm-parent-iw');
				}
            });

		},

		widgetProgress: function( $scope ) {

			var $progress = $scope.find( '.circle-progress' );

			if ( ! $progress.length ) {
				return;
			}

			var $value            = $progress.find( '.circle-progress__value' ),
				$meter            = $progress.find( '.circle-progress__meter' ),
				percent           = parseInt( $value.data( 'value' ) ),
				progress          = percent / 100,
				duration          = $scope.find( '.circle-progress-wrap' ).data( 'duration' ),
				responsiveSizes   = $progress.data( 'responsive-sizes' ),
				desktopSizes      = responsiveSizes.desktop,
				tabletSizes       = responsiveSizes.tablet,
				mobileSizes       = responsiveSizes.mobile,
				currentDeviceMode = LaStudioElements.getCurrentDeviceMode(),
				prevDeviceMode    = currentDeviceMode,
				isAnimatedCircle  = false;

			if ( 'tablet' === currentDeviceMode ) {
				updateSvgSizes( tabletSizes.size, tabletSizes.viewBox, tabletSizes.center, tabletSizes.radius, tabletSizes.valStroke, tabletSizes.bgStroke, tabletSizes.circumference );
			}

			if ( 'mobile' === currentDeviceMode ) {
				updateSvgSizes( mobileSizes.size, mobileSizes.viewBox, mobileSizes.center, mobileSizes.radius, mobileSizes.valStroke, mobileSizes.bgStroke, mobileSizes.circumference );
			}

			elementorFrontend.waypoint( $scope, function() {

				// animate counter
				var $number = $scope.find( '.circle-counter__number' ),
					data = $number.data();

				var decimalDigits = data.toValue.toString().match( /\.(.*)/ );

				if ( decimalDigits ) {
					data.rounding = decimalDigits[1].length;
				}

				data.duration = duration;

				$number.numerator( data );

				// animate progress
				var circumference = parseInt( $progress.data( 'circumference' ) ),
					dashoffset    = circumference * (1 - progress);

				$value.css({
					'transitionDuration': duration + 'ms',
					'strokeDashoffset': dashoffset
				});

				isAnimatedCircle = true;

			}, {
				offset: 'bottom-in-view'
			} );

			$( window ).on( 'resize.lastudioCircleProgress orientationchange.lastudioCircleProgress', circleResizeHandler );

			function circleResizeHandler( event ) {
				currentDeviceMode = LaStudioElements.getCurrentDeviceMode();

				if ( 'desktop' === currentDeviceMode && 'desktop' !== prevDeviceMode ) {
					updateSvgSizes( desktopSizes.size, desktopSizes.viewBox, desktopSizes.center, desktopSizes.radius, desktopSizes.valStroke, desktopSizes.bgStroke, desktopSizes.circumference );
					prevDeviceMode = 'desktop';
				}

				if ( 'tablet' === currentDeviceMode && 'tablet' !== prevDeviceMode ) {
					updateSvgSizes( tabletSizes.size, tabletSizes.viewBox, tabletSizes.center, tabletSizes.radius, tabletSizes.valStroke, tabletSizes.bgStroke, tabletSizes.circumference );
					prevDeviceMode = 'tablet';
				}

				if ( 'mobile' === currentDeviceMode && 'mobile' !== prevDeviceMode ) {
					updateSvgSizes( mobileSizes.size, mobileSizes.viewBox, mobileSizes.center, mobileSizes.radius, mobileSizes.valStroke, mobileSizes.bgStroke, mobileSizes.circumference );
					prevDeviceMode = 'mobile';
				}
			}

			function updateSvgSizes( size, viewBox, center, radius, valStroke, bgStroke, circumference ) {
				var dashoffset = circumference * (1 - progress);

				$progress.attr( {
					'width': size,
					'height': size,
					'data-radius': radius,
					'data-circumference': circumference
				} );

				$progress[0].setAttribute( 'viewBox', viewBox );

				$meter.attr( {
					'cx': center,
					'cy': center,
					'r': radius,
					'stroke-width': bgStroke
				} );

				if ( isAnimatedCircle ) {
					$value.css( {
						'transitionDuration': ''
					} );
				}

				$value.attr( {
					'cx': center,
					'cy': center,
					'r': radius,
					'stroke-width': valStroke
				} );

				$value.css( {
					'strokeDasharray': circumference,
					'strokeDashoffset': isAnimatedCircle ? dashoffset : circumference
				} );
			}
		},

		widgetCarousel: function( $scope ) {

			var $carousel = $scope.find( '.lastudio-carousel' );

			if ( ! $carousel.length ) {
				return;
			}

			LaStudioElements.initCarousel( $carousel, $carousel.data( 'slider_options' ) );

		},

        widgetTeamMember: function ( $scope ) {
            var $target = $scope.find( '.lastudio-carousel' );
            if ( ! $target.length ) {
                return;
            }
            LaStudioElements.initCarousel( $target.find( '.lastudio-team-member' ), $target.data( 'slider_options' ) );
		},

		widgetPosts: function ( $scope ) {

			var $target = $scope.find( '.lastudio-carousel' );

			if ( ! $target.length ) {
				return;
			}

			LaStudioElements.initCarousel( $target.find( '.lastudio-posts' ), $target.data( 'slider_options' ) );

		},

		widgetAnimatedText: function( $scope ) {
			var $target = $scope.find( '.lastudio-animated-text' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new lastudioAnimatedText( $target, settings );
			instance.init();
		},

		widgetAnimatedBox: function( $scope ) {

			LaStudioElements.onAnimatedBoxSectionActivated( $scope );

			var $target      = $scope.find( '.lastudio-animated-box' ),
				toogleEvents = 'mouseenter mouseleave',
				scrollOffset = $( window ).scrollTop(),
				firstMouseEvent = true;

			if ( ! $target.length ) {
				return;
			}

			if ( 'ontouchend' in window || 'ontouchstart' in window ) {
				$target.on( 'touchstart', function( event ) {
					scrollOffset = $( window ).scrollTop();
				} );

				$target.on( 'touchend', function( event ) {

					if ( scrollOffset !== $( window ).scrollTop() ) {
						return false;
					}

					if ( ! $( this ).hasClass( 'flipped-stop' ) ) {
						$( this ).toggleClass( 'flipped' );
					}
				} );

			} else {
				$target.on( toogleEvents, function( event ) {

					if ( firstMouseEvent && 'mouseleave' === event.type ) {
						return;
					}

					if ( firstMouseEvent && 'mouseenter' === event.type ) {
						firstMouseEvent = false;
					}

					if ( ! $( this ).hasClass( 'flipped-stop' ) ) {
						$( this ).toggleClass( 'flipped' );
					}
				} );
			}
		},

		onAnimatedBoxSectionActivated: function( $scope ) {
			if ( ! window.elementor ) {
				return;
			}

			if ( ! window.LaStudioElementsEditor ) {
				return;
			}

			if ( ! window.LaStudioElementsEditor.activeSection ) {
				return;
			}

			var section = window.LaStudioElementsEditor.activeSection;
			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( section );

			if ( isBackSide ) {
				$scope.find( '.lastudio-animated-box' ).addClass( 'flipped' );
				$scope.find( '.lastudio-animated-box' ).addClass( 'flipped-stop' );
			} else {
				$scope.find( '.lastudio-animated-box' ).removeClass( 'flipped' );
				$scope.find( '.lastudio-animated-box' ).removeClass( 'flipped-stop' );
			}
		},

		widgetImagesLayout: function( $scope ) {
			var $target = $scope.find( '.lastudio-images-layout' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new lastudioImagesLayout( $target, settings );
			instance.init();
		},

		widgetPortfolio: function( $scope ) {
			var $target = $scope.find( '.lastudio-portfolio' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new lastudioPortfolio( $target, settings );
			instance.init();
		},

		widgetInstagramGallery: function( $scope ) {
			var $target         = $scope.find( '.lastudio-instagram-gallery__instance' ),
				instance        = null,
				defaultSettings = {},
				settings        = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );

			/*
			 * Default Settings
			 */
			defaultSettings = {
				layoutType: 'masonry',
				columns: 3,
				columnsTablet: 2,
				columnsMobile: 1
			};

			/**
			 * Checking options, settings and options merging
			 */
			$.extend( defaultSettings, settings );

			if ( 'masonry' === settings.layoutType ) {
				salvattore.init();
			}

		},

		widgetScrollNavigation: function( $scope ) {
			var $target         = $scope.find( '.lastudio-scroll-navigation' ),
				instance        = null,
				settings        = $target.data( 'settings' );

			instance = new lastudioScrollNavigation( $target, settings );
			instance.init();
		},

		widgetSubscribeForm: function( $scope ) {
			var $target               = $scope.find( '.lastudio-subscribe-form' ),
				scoreId               = $scope.data( 'id' ),
				settings              = $target.data( 'settings' ),
				lastudioSubscribeFormAjax  = null,
				subscribeFormAjaxId   = 'lastudio_subscribe_form_ajax',
				$subscribeForm        = $( '.lastudio-subscribe-form__form', $target ),
				$fields               = $( '.lastudio-subscribe-form__fields', $target ),
				$mailField            = $( '.lastudio-subscribe-form__mail-field', $target ),
				$inputData            = $mailField.data( 'instance-data' ),
				$submitButton         = $( '.lastudio-subscribe-form__submit', $target ),
				$subscribeFormMessage = $( '.lastudio-subscribe-form__message', $target ),
				timeout               = null,
				invalidMailMessage    = window.lastudioElements.messages.invalidMail || 'Please specify a valid email';

			lastudioSubscribeFormAjax = new LaStudioAjaxHandler({
				handlerId: subscribeFormAjaxId,

				successCallback: function( data ) {
					var successType   = data.type,
						message       = data.message || '',
						responceClass = 'lastudio-subscribe-form--response-' + successType;

					$submitButton.removeClass( 'loading' );

					$target.removeClass( 'lastudio-subscribe-form--response-error' );
					$target.addClass( responceClass );

					$( 'span', $subscribeFormMessage ).html( message );
					$subscribeFormMessage.css( { 'visibility': 'visible' } );

					timeout = setTimeout( function() {
						$subscribeFormMessage.css( { 'visibility': 'hidden' } );
						$target.removeClass( responceClass );
					}, 20000 );

					if ( settings['redirect'] ) {
						window.location.href = settings['redirect_url'];
					}

					$( window ).trigger( {
						type: 'lastudio-elements/subscribe',
						elementId: scoreId,
						successType: successType,
						inputData: $inputData
					} );
				}
			});

			$mailField.on( 'focus', function() {
				$mailField.removeClass( 'mail-invalid' );
			} );

			$( document ).keydown( function( event ) {

				if ( 13 === event.keyCode && $mailField.is( ':focus' ) ) {
					subscribeHandle();

					return false;
				}
			} );

			$submitButton.on( 'click', function() {
				subscribeHandle();

				return false;
			} );

			function subscribeHandle() {
				var inputValue     = $mailField.val(),
					sendData       = {
						'email': inputValue,
						'use_target_list_id': settings['use_target_list_id'] || false,
						'target_list_id': settings['target_list_id'] || '',
						'data': $inputData
					},
					serializeArray = $subscribeForm.serializeArray(),
					additionalFields = {};

				if ( LaStudioElementsTools.validateEmail( inputValue ) ) {

					$.each( serializeArray, function( key, fieldData ) {

						if ( 'email' === fieldData.name ) {
							sendData[ fieldData.name ] = fieldData.value;
						} else {
							additionalFields[ fieldData.name ] = fieldData.value;
						}
					} );

					sendData['additional'] = additionalFields;

					lastudioSubscribeFormAjax.sendData( sendData );

					$submitButton.addClass( 'loading' );
				} else {
					$mailField.addClass( 'mail-invalid' );

					$target.addClass( 'lastudio-subscribe-form--response-error' );
					$( 'span', $subscribeFormMessage ).html( invalidMailMessage );
					$subscribeFormMessage.css( { 'visibility': 'visible' } );

					timeout = setTimeout( function() {
						$target.removeClass( 'lastudio-subscribe-form--response-error' );
						$subscribeFormMessage.css( { 'visibility': 'hidden' } );
						$mailField.removeClass( 'mail-invalid' );
					}, 20000 );
				}
			}
		},

		widgetProgressBar: function( $scope ) {
			var $target      = $scope.find( '.lastudio-progress-bar' ),
				percent      = $target.data( 'percent' ),
				type         = $target.data( 'type' ),
				deltaPercent = percent * 0.01;

			elementorFrontend.waypoint( $target, function( direction ) {
				var $this       = $( this ),
					animeObject = { charged: 0 },
					$statusBar  = $( '.lastudio-progress-bar__status-bar', $this ),
					$percent    = $( '.lastudio-progress-bar__percent-value', $this ),
					animeProgress,
					animePercent;

				if ( 'type-7' == type ) {
					$statusBar.css( {
						'height': percent + '%'
					} );
				} else {
					$statusBar.css( {
						'width': percent + '%'
					} );
				}

				animePercent = anime({
					targets: animeObject,
					charged: percent,
					round: 1,
					duration: 1000,
					easing: 'easeInOutQuad',
					update: function() {
						$percent.html( animeObject.charged );
					}
				});

			} );
		},

		widgetSlider: function( $scope ) {
			var $target        = $scope.find( '.lastudio-slider' ),
				$imagesTagList = $( '.sp-image', $target ),
				instance       = null,
				defaultSettings = {
					imageScaleMode: 'cover',
					slideDistance: { size: 10, unit: 'px' },
					slideDuration: 500,
					sliderAutoplay: true,
					sliderAutoplayDelay: 2000,
					sliderAutoplayOnHover: 'pause',
					sliderFadeMode: false,
					sliderFullScreen: true,
					sliderFullscreenIcon: 'fa fa-arrows-alt',
					sliderHeight: { size: 600, unit: 'px' },
                    sliderHeightLaptop: { size: 600, unit: 'px' },
					sliderHeightTablet: { size: 400, unit: 'px' },
                    sliderHeight800: { size: 400, unit: 'px' },
                    sliderHeight640: { size: 300, unit: 'px' },
					sliderHeightMobile: { size: 300, unit: 'px' },
					sliderLoop: true,
					sliderNaviOnHover: false,
					sliderNavigation: true,
					sliderNavigationIcon: 'fa fa-angle-left',
					sliderPagination: false,
					sliderShuffle: false,
					sliderWidth: { size: 100, unit: '%' },
					thumbnailWidth: 120,
					thumbnailHeight: 80,
					thumbnails: true,
					rightToLeft: false
				},
				instanceSettings = $target.data( 'settings' ) || {},
				settings        = $.extend( {}, defaultSettings, instanceSettings );

			if ( ! $target.length ) {
				return;
			}

			$target.imagesLoaded().progress( function( instance, image ) {
				var loadedImages = null,
					progressBarWidth = null;

				if ( image.isLoaded ) {

					if ( $( image.img ).hasClass( 'sp-image' ) ) {
						$( image.img ).addClass( 'image-loaded' );
					}

					if($(image.img).hasClass('jetpack-lazy-image') && !$(image.img).hasClass('jetpack-lazy-image--handled')){
                        LaStudioElementsTools.makeImageAsLoaded(image.img);
					}

					loadedImages = $( '.image-loaded', $target );
					progressBarWidth = 100 * ( loadedImages.length / $imagesTagList.length ) + '%';

					$( '.lastudio-slider-loader', $target ).css( { width: progressBarWidth } );
				}

			} ).done( function( instance ) {

				$( '.slider-pro', $target ).addClass( 'slider-loaded' );
				$( '.lastudio-slider-loader', $target ).css( { 'display': 'none' } );
			} );

			var laptopHeight = '' !== settings['sliderHeightLaptop']['size'] ? settings['sliderHeightLaptop']['size'] + settings['sliderHeightLaptop']['unit'] : settings['sliderHeight']['size'] + settings['sliderHeight']['unit'];
			var tabletHeight = '' !== settings['sliderHeightTablet']['size'] ? settings['sliderHeightTablet']['size'] + settings['sliderHeightTablet']['unit'] : settings['sliderHeight']['size'] + settings['sliderHeight']['unit'];
            var tablet800Height = '' !== settings['sliderHeight800']['size'] ? settings['sliderHeight800']['size'] + settings['sliderHeight800']['unit'] : tabletHeight;
			var mobileHeight = '' !== settings['sliderHeightMobile']['size'] ? settings['sliderHeightMobile']['size'] + settings['sliderHeightMobile']['unit'] : settings['sliderHeight']['size'] + settings['sliderHeight']['unit'];
			var mobile640Height = '' !== settings['sliderHeight640']['size'] ? settings['sliderHeight640']['size'] + settings['sliderHeight640']['unit'] : mobileHeight;

			$( '.slider-pro', $target ).sliderPro( {
				width: settings['sliderWidth']['size'] + settings['sliderWidth']['unit'],
				height: settings['sliderHeight']['size'] + settings['sliderHeight']['unit'],
				arrows: settings['sliderNavigation'],
				fadeArrows: settings['sliderNaviOnHover'],
				buttons: settings['sliderPagination'],
				autoplay: settings['sliderAutoplay'],
				autoplayDelay: settings['sliderAutoplayDelay'],
				autoplayOnHover: settings['sliderAutoplayOnHover'],
				fullScreen: settings['sliderFullScreen'],
				shuffle: settings['sliderShuffle'],
				loop: settings['sliderLoop'],
				fade: settings['sliderFadeMode'],
				slideDistance: ( 'string' !== typeof settings['slideDistance']['size'] ) ? settings['slideDistance']['size'] : 0,
				slideAnimationDuration: +settings['slideDuration'],
				//imageScaleMode: settings['imageScaleMode'],
				imageScaleMode: 'exact',
				waitForLayers: false,
				grabCursor: false,
				thumbnailWidth: settings['thumbnailWidth'],
				thumbnailHeight: settings['thumbnailHeight'],
				rightToLeft: settings['rightToLeft'],
				init: function() {
					this.resize();

					$( '.sp-previous-arrow', $target ).append( '<i class="' + settings['sliderNavigationIcon'] + '"></i>' );
					$( '.sp-next-arrow', $target ).append( '<i class="' + settings['sliderNavigationIcon'] + '"></i>' );

					$( '.sp-full-screen-button', $target ).append( '<i class="' + settings['sliderFullscreenIcon'] + '"></i>' );
				},
				breakpoints: {
					1599: {
						height: laptopHeight
					},
					1199: {
						height: tabletHeight
					},
					799: {
						height: tablet800Height
					},
					767: {
						height: mobileHeight
					},
					639: {
						height: mobile640Height
					}
				}
			} );
		},

		widgetTestimonials: function( $scope ) {
			var $target        = $scope.find( '.lastudio-testimonials__instance' ),
				$imagesTagList = $( '.lastudio-testimonials__figure', $target ),
				instance       = null,
				settings       = $target.data( 'settings' );

			if ( ! $target.length ) {
				return;
			}

            settings.adaptiveHeight = settings['adaptiveHeight'];

            LaStudioElements.initCarousel( $target, settings );
		},

		widgetImageComparison: function( $scope ) {
			var $target              = $scope.find( '.lastudio-image-comparison__instance' ),
				instance             = null,
				imageComparisonItems = $( '.lastudio-image-comparison__container', $target ),
				settings             = $target.data( 'settings' ),
				elementId            = $scope.data( 'id' );

			if ( ! $target.length ) {
				return;
			}

			window.juxtapose.scanPage( '.lastudio-juxtapose' );

			settings.draggable = false;
			settings.infinite = false;
			//settings.adaptiveHeight = true;
			LaStudioElements.initCarousel( $target, settings );
		},

		widgetTimeTable: function( $scope ) {

			var $mptt_shortcode_wrapper = $scope.find( '.mptt-shortcode-wrapper' );

			if ( ( typeof typenow ) !== 'undefined' ) {
				if ( pagenow === typenow ) {
					switch ( typenow ) {

						case 'mp-event':
							Registry._get( 'Event' ).init();
							break;

						case 'mp-column':
							Registry._get( 'Event' ).initDatePicker();
							Registry._get( 'Event' ).columnRadioBox();
							break;

						default:
							break;
					}
				}
			}

			if ( $mptt_shortcode_wrapper.length ) {

				Registry._get( 'Event' ).initTableData();
				Registry._get( 'Event' ).filterShortcodeEvents();
				Registry._get( 'Event' ).getFilterByHash();

				$mptt_shortcode_wrapper.show();
			}

			if ( $( '.upcoming-events-widget' ).length || $mptt_shortcode_wrapper.length ) {
				Registry._get( 'Event' ).setColorSettings();
			}
		},

		elementorSection: function( $scope ) {
			var $target   = $scope,
				instance  = null,
				editMode  = Boolean( elementor.isEditMode() );

			instance = new lastudioSectionParallax( $target, true );
			instance.init();
		},

		elementorColumn: function( $scope ) {
			var $target   = $scope,
				instance  = null,
				editMode  = Boolean( elementor.isEditMode() );

			instance = new lastudioSectionParallax( $target, false );
			instance.init();
		},

		initCarousel: function( $target, options ) {

			var laptopSlides, tabletPortraitSlides, tabletSlides, mobileSlides, mobilePortraitSlides, defaultOptions, slickOptions;

            laptopSlides = parseInt(options.slidesToShow.laptop) || 1;
            tabletSlides = parseInt(options.slidesToShow.tablet) || laptopSlides;
            tabletPortraitSlides = parseInt(options.slidesToShow.tablet_portrait) || tabletSlides;
            mobileSlides = parseInt(options.slidesToShow.mobile) || tabletPortraitSlides;
            mobilePortraitSlides = parseInt(options.slidesToShow.mobile_portrait) || mobileSlides;

			options.slidesToShow = parseInt(options.slidesToShow.desktop) || 1;

			defaultOptions = {
				customPaging: function(slider, i) {
					return $( '<span />' ).text( i + 1 );
				},
				dotsClass: 'lastudio-slick-dots',
				responsive: [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: laptopSlides,
                            slidesToScroll: options.slidesToScroll
                        }
                    },
					{
						breakpoint: 1025,
						settings: {
							slidesToShow: tabletSlides,
                            slidesToScroll: tabletSlides
						}
					},
					{
						breakpoint: 800,
						settings: {
							slidesToShow: tabletPortraitSlides,
                            slidesToScroll: tabletPortraitSlides
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: mobileSlides,
                            slidesToScroll: mobileSlides
						}
					},
					{
						breakpoint: 577,
						settings: {
							slidesToShow: mobilePortraitSlides,
                            slidesToScroll: mobilePortraitSlides
						}
					}
				]
			};

			slickOptions = $.extend( {}, defaultOptions, options );

			var _autoPlay = slickOptions.autoplay || false;

			$target.slick( slickOptions );

            if( $(window).width() > 1200) {

                if ($target.closest('.lastudio-portfolio.preset-list-type-1').length > 0 || $target.closest('.lastudio-portfolio.preset-list-type-2').length > 0) {

                    $target.on('wheel', (function (e) {
                        e.preventDefault();
                        if (e.originalEvent.deltaY < 0) {
                            $(this).slick('slickNext');
                        } else {
                            $(this).slick('slickPrev');
                        }
                    }));

                }
            }

			if( $target.closest('.lastudio-portfolio').length == 0 ) {
                _autoPlay = false;
			}

            if(_autoPlay){
                var $bar = $('<div class="slick-controls-auto"><a class="slick-control-start" href="#"><i class="fa fa-play" aria-hidden="true"></i></a><a class="slick-control-stop active" href="#"><i class="fa fa-pause" aria-hidden="true"></i></a></div>');
                $bar.appendTo( $target );
                $target
					.on('click', '.slick-control-start', function (e) {
						e.preventDefault();
						$(this).removeClass('active').siblings('a').addClass('active');
                        $target.slick('slickPlay');
                	})
					.on('click', '.slick-control-stop', function (e) {
						e.preventDefault();
                        $(this).removeClass('active').siblings('a').addClass('active');
                        $target.slick('slickPause');
                	})
            }
		},

		widgetTimeLine : function ( $scope ){
			var $target = $scope.find( '.lastudio-timeline' ),
				instance = null;

			if ( ! $target.length ) {
				return;
			}

			instance = new lastudioTimeLine( $target );
			instance.init();
		},

		widgetTable: function( $scope ) {
			var $target = $scope.find( '.lastudio-table' ),
				options = {
					cssHeader: 'lastudio-table-header-sort',
					cssAsc: 'lastudio-table-header-sort--up',
					cssDesc: 'lastudio-table-header-sort--down',
					initWidgets: false
				};

			if ( ! $target.length ) {
				return;
			}

			if ( $target.hasClass( 'lastudio-table--sorting' ) ) {
				$target.tablesorter( options );
			}
		},

		widgetDropbar: function( $scope ) {
			var $dropbar       = $scope.find( '.lastudio-dropbar' ),
				$dropbar_inner = $dropbar.find( '.lastudio-dropbar__inner' ),
				$btn           = $dropbar.find( '.lastudio-dropbar__button' ),
				$content       = $dropbar.find( '.lastudio-dropbar__content' ),
				settings       = $dropbar.data( 'settings' ) || {},
				mode           = settings['mode'] || 'hover',
				hide_delay     = +settings['hide_delay'] || 0,
				activeClass    = 'lastudio-dropbar-open',
				scrollOffset,
				timer;

			if ( 'click' === mode ) {
				$btn.on( 'click.lastudioDropbar', function( event ) {
					$dropbar.toggleClass( activeClass );
				} );
			} else {
				if ( 'ontouchstart' in window || 'ontouchend' in window ) {
					$btn.on( 'touchend.lastudioDropbar', function( event ) {
						if ( $( window ).scrollTop() !== scrollOffset ) {
							return;
						}

						$dropbar.toggleClass( activeClass );
					} );
				} else {
					$dropbar_inner.on( 'mouseenter.lastudioDropbar', function( event ) {
						clearTimeout( timer );
						$dropbar.addClass( activeClass );
					} );

					$dropbar_inner.on( 'mouseleave.lastudioDropbar', function( event ) {
						timer = setTimeout( function() {
							$dropbar.removeClass( activeClass );
						}, hide_delay );
					} );
				}
			}

			$( document ).on( 'touchstart.lastudioDropbar', function( event ) {
				scrollOffset = $( window ).scrollTop();
			} );

			$( document ).on( 'click.lastudioDropbar touchend.lastudioDropbar', function( event ) {

				if ( 'touchend' === event.type && $( window ).scrollTop() !== scrollOffset ) {
					return;
				}

				if ( $( event.target ).closest( $btn ).length || $( event.target ).closest( $content ).length ) {
					return;
				}

				if ( ! $dropbar.hasClass( activeClass ) ) {
					return;
				}

				$dropbar.removeClass( activeClass );
			} );
		},

		widgetVideo: function( $scope ) {
			var $video = $scope.find( '.lastudio-video' ),
				$iframe = $scope.find( '.lastudio-video-iframe' ),
				$videoPlaer = $scope.find( '.lastudio-video-player' ),
				$mejsPlaer = $scope.find( '.lastudio-video-mejs-player' ),
				mejsPlaerControls = $mejsPlaer.data( 'controls' ) || ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen'],
				$overlay = $scope.find( '.lastudio-video__overlay' ),
				hasOverlay = $overlay.length > 0,
				settings = $video.data( 'settings' ) || {},
				autoplay = settings.autoplay || false;

			if ( $overlay[0] ) {
				$overlay.on( 'click.lastudioVideo', function( event ) {

					if ( $videoPlaer[0] ) {
						$videoPlaer[0].play();

						$overlay.remove();
						hasOverlay = false;

						return;
					}

					if ( $iframe[0] ) {
						iframeStartPlay();
					}
				} );
			}

			if ( autoplay && $iframe[0] && $overlay[0] ) {
				iframeStartPlay();
			}

			function iframeStartPlay() {
				var lazyLoad = $iframe.data( 'lazy-load' );

				if ( lazyLoad ) {
					$iframe.attr( 'src', lazyLoad );
				}

				if ( ! autoplay ) {
					$iframe[0].src = $iframe[0].src.replace( '&autoplay=0', '&autoplay=1' );
				}

				$overlay.remove();
				hasOverlay = false;
			}

			if ( $videoPlaer[0] ) {
				$videoPlaer.on( 'play.lastudioVideo', function( event ) {
					if ( hasOverlay ) {
						$overlay.remove();
						hasOverlay = false;
					}
				} );
			}

			if ( $mejsPlaer[0] ) {
				$mejsPlaer.mediaelementplayer( {
					videoVolume: 'horizontal',
					hideVolumeOnTouchDevices: false,
					enableProgressTooltip: false,
					features: mejsPlaerControls,
					success: function( media ) {
						media.addEventListener( 'timeupdate', function( event ) {
							var $currentTime = $scope.find( '.mejs-time-current' ),
								inlineStyle  = $currentTime.attr( 'style' );

							if ( inlineStyle ) {
								var scaleX = inlineStyle.match(/scaleX\([0-9.]*\)/gi)[0].replace( 'scaleX(', '' ).replace( ')', '' );

								if ( scaleX ) {
									$currentTime.css( 'width', scaleX * 100 + '%' );
								}
							}
						}, false );
					}
				} );
			}
		},

		widgetAudio: function( $scope ) {
			var $wrapper = $scope.find( '.lastudio-audio' ),
				$player  = $scope.find( '.lastudio-audio-player' ),
				settings = $wrapper.data( 'settings' );

			if ( ! $player[0] ) {
				return;
			}

			$player.mediaelementplayer( {
				features: settings['controls'] || ['playpause', 'current', 'progress', 'duration', 'volume'],
				audioVolume: settings['audioVolume'] || 'horizontal',
				startVolume: settings['startVolume'] || 0.8,
				hideVolumeOnTouchDevices: settings['hideVolumeOnTouchDevices'],
				enableProgressTooltip: false,
				success: function( media ) {
					media.addEventListener( 'timeupdate', function( event ) {
						var $currentTime = $scope.find( '.mejs-time-current' ),
							inlineStyle  = $currentTime.attr( 'style' );

						if ( inlineStyle ) {
							var scaleX = inlineStyle.match(/scaleX\([0-9.]*\)/gi)[0].replace( 'scaleX(', '' ).replace( ')', '' );

							if ( scaleX ) {
								$currentTime.css( 'width', scaleX * 100 + '%' );
							}
						}
					}, false );
				}
			} );
		},

		widgetHorizontalTimeline: function( $scope ) {
			var $timeline         = $scope.find( '.lastudio-hor-timeline' ),
				$timelineTrack    = $scope.find( '.lastudio-hor-timeline-track' ),
				$items            = $scope.find( '.lastudio-hor-timeline-item' ),
				$arrows           = $scope.find( '.lastudio-arrow' ),
				$nextArrow        = $scope.find( '.lastudio-next-arrow' ),
				$prevArrow        = $scope.find( '.lastudio-prev-arrow' ),
				columns           = $timeline.data( 'columns' ) || {},
				desktopColumns    = columns.desktop || 3,
				tabletColumns     = columns.tablet || desktopColumns,
				mobileColumns     = columns.mobile || tabletColumns,
				firstMouseEvent   = true,
				currentDeviceMode = LaStudioElements.getCurrentDeviceMode(),
				prevDeviceMode    = currentDeviceMode,
				itemsCount        = $scope.find( '.lastudio-hor-timeline-list--middle .lastudio-hor-timeline-item' ).length,
				currentTransform  = 0,
				currentPosition   = 0,
				transform = {
					desktop: 100 / desktopColumns,
					tablet:  100 / tabletColumns,
					mobile:  100 / mobileColumns
				},
				maxPosition = {
					desktop: Math.max( 0, (itemsCount - desktopColumns) ),
					tablet:  Math.max( 0, (itemsCount - tabletColumns) ),
					mobile:  Math.max( 0, (itemsCount - mobileColumns) )
				};

			if ( 'ontouchstart' in window || 'ontouchend' in window ) {
				$items.on( 'touchend.lastudioHorTimeline', function( event ) {
					var itemId = $( this ).data( 'item-id' );

					$scope.find( '.elementor-repeater-item-' + itemId ).toggleClass( 'is-hover' );
				} );
			} else {
				$items.on( 'mouseenter.lastudioHorTimeline mouseleave.lastudioHorTimeline', function( event ) {

					if ( firstMouseEvent && 'mouseleave' === event.type ) {
						return;
					}

					if ( firstMouseEvent && 'mouseenter' === event.type ) {
						firstMouseEvent = false;
					}

					var itemId = $( this ).data( 'item-id' );

					$scope.find( '.elementor-repeater-item-' + itemId ).toggleClass( 'is-hover' );
				} );
			}

			// Set Line Position
			setLinePosition();
			$( window ).on( 'resize.lastudioHorTimeline orientationchange.lastudioHorTimeline', setLinePosition );

			function setLinePosition() {
				var $line             = $scope.find( '.lastudio-hor-timeline__line' ),
					$firstPoint       = $scope.find( '.lastudio-hor-timeline-item__point-content:first' ),
					$lastPoint        = $scope.find( '.lastudio-hor-timeline-item__point-content:last' ),
					firstPointLeftPos = $firstPoint.position().left + parseInt( $firstPoint.css( 'marginLeft' ) ),
					lastPointLeftPos  = $lastPoint.position().left + parseInt( $lastPoint.css( 'marginLeft' ) ),
					pointWidth        = $firstPoint.outerWidth();

				$line.css( {
					'left': firstPointLeftPos + pointWidth/2,
					'width': lastPointLeftPos - firstPointLeftPos
				} );

				// var $progressLine   = $scope.find( '.lastudio-hor-timeline__line-progress' ),
				// 	$lastActiveItem = $scope.find( '.lastudio-hor-timeline-list--middle .lastudio-hor-timeline-item.is-active:last' );
				//
				// if ( $lastActiveItem[0] ) {
				// 	var $lastActiveItemPointWrap = $lastActiveItem.find( '.lastudio-hor-timeline-item__point' ),
				// 		progressLineWidth        = $lastActiveItemPointWrap.position().left + $lastActiveItemPointWrap.outerWidth() - firstPointLeftPos - pointWidth / 2;
				//
				// 	$progressLine.css( {
				// 		'width': progressLineWidth
				// 	} );
				// }
			}

			// Arrows Navigation Type
			if ( $nextArrow[0] && maxPosition[ currentDeviceMode ] === 0 ) {
				$nextArrow.addClass( 'lastudio-arrow-disabled' );
			}

			if ( $arrows[0] ) {
				$arrows.on( 'click.lastudioHorTimeline', function( event ){
					var $this = $( this ),
						direction = $this.hasClass( 'lastudio-next-arrow' ) ? 'next' : 'prev',
						currentDeviceMode = LaStudioElements.getCurrentDeviceMode();

					if ( 'next' === direction && currentPosition < maxPosition[ currentDeviceMode ] ) {
						currentTransform -= transform[ currentDeviceMode ];
						currentPosition += 1;
					}

					if ( 'prev' === direction && currentPosition > 0 ) {
						currentTransform += transform[ currentDeviceMode ];
						currentPosition -= 1;
					}

					if ( currentPosition > 0 ) {
						$prevArrow.removeClass( 'lastudio-arrow-disabled' );
					} else {
						$prevArrow.addClass( 'lastudio-arrow-disabled' );
					}

					if ( currentPosition === maxPosition[ currentDeviceMode ] ) {
						$nextArrow.addClass( 'lastudio-arrow-disabled' );
					} else {
						$nextArrow.removeClass( 'lastudio-arrow-disabled' );
					}

					if ( currentPosition === 0 ) {
						currentTransform = 0;
					}

					$timelineTrack.css({
						'transform': 'translateX(' + currentTransform + '%)'
					});

				} );
			}

			setArrowPosition();
			$( window ).on( 'resize.lastudioHorTimeline orientationchange.lastudioHorTimeline', setArrowPosition );
			$( window ).on( 'resize.lastudioHorTimeline orientationchange.lastudioHorTimeline', timelineSliderResizeHandler );

			function setArrowPosition() {
				if ( ! $arrows[0] ) {
					return;
				}

				var $middleList = $scope.find( '.lastudio-hor-timeline-list--middle' ),
					middleListTopPosition = $middleList.position().top,
					middleListHeight = $middleList.outerHeight();

				$arrows.css({
					'top': middleListTopPosition + middleListHeight/2
				});
			}

			function timelineSliderResizeHandler( event ) {
				if ( ! $timeline.hasClass( 'lastudio-hor-timeline--arrows-nav' ) ) {
					return;
				}

				var currentDeviceMode = LaStudioElements.getCurrentDeviceMode(),
					resetSlider = function() {
						$prevArrow.addClass( 'lastudio-arrow-disabled' );

						if ( $nextArrow.hasClass( 'lastudio-arrow-disabled' ) ) {
							$nextArrow.removeClass( 'lastudio-arrow-disabled' );
						}

						if ( maxPosition[ currentDeviceMode ] === 0 ) {
							$nextArrow.addClass( 'lastudio-arrow-disabled' );
						}

						currentTransform = 0;
						currentPosition = 0;

						$timelineTrack.css({
							'transform': 'translateX(0%)'
						});
					};

				switch ( currentDeviceMode ) {
					case 'desktop':
						if ( 'desktop' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'desktop';
						}
						break;

					case 'tablet':
						if ( 'tablet' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'tablet';
						}
						break;

					case 'mobile':
						if ( 'mobile' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'mobile';
						}
						break;
				}
			}
		},

		widgetTabs: function( $scope ) {
			var $target         = $( '.lastudio-tabs', $scope ).first(),
				$controlWrapper = $( '.lastudio-tabs__control-wrapper', $target ).first(),
				$contentWrapper = $( '.lastudio-tabs__content-wrapper', $target ).first(),
				$controlList    = $( '> .lastudio-tabs__control', $controlWrapper ),
				$contentList    = $( '> .lastudio-tabs__content', $contentWrapper ),
				settings        = $target.data( 'settings' ) || {},
				toogleEvents    = 'mouseenter mouseleave',
				scrollOffset,
				autoSwitchInterval = null,
				curentHash      = window.location.hash || false,
				tabsArray       = curentHash ? curentHash.replace( '#', '' ).split( '&' ) : false;

			if ( 'click' === settings['event'] ) {
				addClickEvent();
			} else {
				addMouseEvent();
			}

			if ( settings['autoSwitch'] ) {

				var startIndex        = settings['activeIndex'],
					currentIndex      = startIndex,
					controlListLength = $controlList.length;

				autoSwitchInterval = setInterval( function() {

					if ( currentIndex < controlListLength - 1 ) {
						currentIndex++;
					} else {
						currentIndex = 0;
					}

					switchTab( currentIndex );

				}, +settings['autoSwitchDelay'] );
			}

			$( window ).on( 'resize.lastudioTabs orientationchange.lastudioTabs', function() {
				$contentWrapper.css( { 'height': 'auto' } );
			} );

			function addClickEvent() {
				$controlList.on( 'click.lastudioTabs', function() {
					var $this = $( this ),
						tabId = +$this.data( 'tab' ) - 1;

					clearInterval( autoSwitchInterval );
					switchTab( tabId );
				});
			}

			function addMouseEvent() {
				if ( 'ontouchend' in window || 'ontouchstart' in window ) {
					$controlList.on( 'touchstart', function( event ) {
						scrollOffset = $( window ).scrollTop();
					} );

					$controlList.on( 'touchend', function( event ) {
						var $this = $( this ),
							tabId = +$this.data( 'tab' ) - 1;

						if ( scrollOffset !== $( window ).scrollTop() ) {
							return false;
						}

						clearInterval( autoSwitchInterval );
						switchTab( tabId );
					} );

				} else {
					$controlList.on( 'mouseenter', function( event ) {
						var $this = $( this ),
							tabId = +$this.data( 'tab' ) - 1;

						clearInterval( autoSwitchInterval );
						switchTab( tabId );
					} );
				}
			}

			function switchTab( curentIndex ) {
				var $activeControl      = $controlList.eq( curentIndex ),
					$activeContent      = $contentList.eq( curentIndex ),
					activeContentHeight = 'auto',
					timer;

				$contentWrapper.css( { 'height': $contentWrapper.outerHeight( true ) } );

				$controlList.removeClass( 'active-tab' );
				$activeControl.addClass( 'active-tab' );

				$contentList.removeClass( 'active-content' );
				activeContentHeight = $activeContent.outerHeight( true );
				activeContentHeight += parseInt( $contentWrapper.css( 'border-top-width' ) ) + parseInt( $contentWrapper.css( 'border-bottom-width' ) );
				$activeContent.addClass( 'active-content' );

				$contentWrapper.css( { 'height': activeContentHeight } );

				if ( timer ) {
					clearTimeout( timer );
				}

				timer = setTimeout( function() {
					$contentWrapper.css( { 'height': 'auto' } );
				}, 500 );
			}

			// Hash Watch Handler
			if ( tabsArray ) {

				$controlList.each( function( index ) {
					var $this    = $( this ),
						id       = $this.attr( 'id' ),
						tabIndex = index;

					tabsArray.forEach( function( itemHash, i ) {
						if ( itemHash === id ) {
							switchTab( tabIndex );
						}
					} );

				} );
			}

		},// tabsInit end
	};

	$( window ).on( 'elementor/frontend/init', LaStudioElements.init );

	var LaStudioElementsTools = {
		debounce: function( threshold, callback ) {
			var timeout;

			return function debounced( $event ) {
				function delayed() {
					callback.call( this, $event );
					timeout = null;
				}

				if ( timeout ) {
					clearTimeout( timeout );
				}

				timeout = setTimeout( delayed, threshold );
			};
		},

		getObjectNextKey: function( object, key ) {
			var keys      = Object.keys( object ),
				idIndex   = keys.indexOf( key ),
				nextIndex = idIndex += 1;

			if( nextIndex >= keys.length ) {
				//we're at the end, there is no next
				return false;
			}

			var nextKey = keys[ nextIndex ];

			return nextKey;
		},

		getObjectPrevKey: function( object, key ) {
			var keys      = Object.keys( object ),
				idIndex   = keys.indexOf( key ),
				prevIndex = idIndex -= 1;

			if ( 0 > idIndex ) {
				//we're at the end, there is no next
				return false;
			}

			var prevKey = keys[ prevIndex ];

			return prevKey;
		},

		getObjectFirstKey: function( object ) {
			return Object.keys( object )[0];
		},

		getObjectLastKey: function( object ) {
			return Object.keys( object )[ Object.keys( object ).length - 1 ];
		},

		validateEmail: function( email ) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			return re.test( email );
		},

        makeImageAsLoaded: function( element ){
            var base_src = element.getAttribute('data-src') || element.getAttribute('data-lazy') || element.getAttribute('data-lazy-src') || element.getAttribute('data-lazy-original'),
                base_srcset = element.getAttribute('data-src') || element.getAttribute('data-lazy-srcset'),
                base_sizes = element.getAttribute('data-sizes') || element.getAttribute('data-lazy-sizes');

            if(element.getAttribute('datanolazy') == 'true'){
                base_src = base_srcset = base_sizes = '';
            }

            if (base_src) {
                element.src = base_src;
            }
            if (base_srcset) {
                element.srcset = base_srcset;
            }
            if (base_sizes) {
                element.sizes = base_sizes;
            }
            element.setAttribute('data-element-loaded', true);
            if($(element).hasClass('jetpack-lazy-image')){
                $(element).addClass('jetpack-lazy-image--handled');
			}
        }
	}

	/**
	 * LaStudio animated text Class
	 *
	 * @return {void}
	 */
	window.lastudioAnimatedText = function( $selector, settings ) {
		var self                   = this,
			$instance              = $selector,
			$animatedTextContainer = $( '.lastudio-animated-text__animated-text', $instance ),
			$animatedTextList      = $( '.lastudio-animated-text__animated-text-item', $animatedTextContainer ),
			timeOut                = null,
			defaultSettings        = {
				effect: 'fx1',
				delay: 3000
			},
			settings               =  $.extend( defaultSettings, settings || {} ),
			currentIndex           = 0,
			animationDelay         = settings.delay;

		/**
		 * Avaliable Effects
		 */
		self.avaliableEffects = {
			'fx1' : {
				in: {
					duration: 1000,
					delay: function( el, index ) { return 75 + index * 100; },
					easing: 'easeOutElastic',
					elasticity: 650,
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: ['100%','0%']
				},
				out: {
					duration: 300,
					delay: function(el, index) { return index*40; },
					easing: 'easeInOutExpo',
					opacity: 0,
					translateY: '-100%'
				}
			},
			'fx2' : {
				in: {
					duration: 800,
					delay: function( el, index) { return index * 50; },
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: function(el, index) {
						return index%2 === 0 ? ['-80%', '0%'] : ['80%', '0%'];
					}
				},
				out: {
					duration: 300,
					delay: function( el, index ) { return index * 20; },
					easing: 'easeOutExpo',
					opacity: 0,
					translateY: function( el, index ) {
						return index%2 === 0 ? '80%' : '-80%';
					}
				}
			},
			'fx3' : {
				in: {
					duration: 700,
					delay: function(el, index) {
						return ( el.parentNode.children.length - index - 1 ) * 80;
					},
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: function(el, index) {
						return index%2 === 0 ? [ '-80%', '0%' ] : [ '80%', '0%' ];
					},
					rotateZ: [90,0]
				},
				out: {
					duration: 300,
					delay: function(el, index) { return (el.parentNode.children.length-index-1) * 50; },
					easing: 'easeOutExpo',
					opacity: 0,
					translateY: function(el, index) {
						return index%2 === 0 ? '80%' : '-80%';
					},
					rotateZ: function(el, index) {
						return index%2 === 0 ? -25 : 25;
					}
				}
			},
			'fx4' : {
				in: {
					duration: 700,
					delay: function( el, index ) { return 550 + index * 50; },
					easing: 'easeOutQuint',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: [ '-150%','0%' ],
					rotateY: [ 180, 0 ]
				},
				out: {
					duration: 200,
					delay: function( el, index ) { return index * 30; },
					easing: 'easeInQuint',
					opacity: {
						value: 0,
						easing: 'linear',
					},
					translateY: '100%',
					rotateY: -180
				}
			},
			'fx5' : {
				in: {
					duration: 250,
					delay: function( el, index ) { return 200 + index * 25; },
					easing: 'easeOutCubic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: ['-50%','0%']
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 25; },
					easing: 'easeOutCubic',
					opacity: 0,
					translateY: '50%'
				}
			},
			'fx6' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutSine',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateY: [ -90, 0 ]
				},
				out: {
					duration: 200,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutSine',
					opacity: 0,
					rotateY: 45
				}
			},
			'fx7' : {
				in: {
					duration: 1000,
					delay: function( el, index ) { return 100 + index * 30; },
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateZ: function( el, index ) {
						return [ anime.random( 20, 40 ), 0 ];
					}
				},
				out: {
					duration: 300,
					opacity: {
						value: [ 1, 0 ],
						easing: 'easeOutExpo',
					}
				}
			},
			'fx8' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return 200 + index * 20; },
					easing: 'easeOutExpo',
					opacity: 1,
					rotateY: [ -90, 0 ],
					translateY: [ '50%','0%' ]
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 20; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateY: 90
				}
			},
			'fx9' : {
				in: {
					duration: 400,
					delay: function(el, index) { return 200+index*30; },
					easing: 'easeOutExpo',
					opacity: 1,
					rotateX: [90,0]
				},
				out: {
					duration: 250,
					delay: function(el, index) { return index*30; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateX: -90
				}
			},
			'fx10' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return 100 + index * 50; },
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateX: [ 110, 0 ]
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateX: -110
				}
			},
			'fx11' : {
				in: {
					duration: function( el, index ) { return anime.random( 800, 1000 ); },
					delay: function( el, index ) { return anime.random( 100, 300 ); },
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: [ '-150%','0%' ],
					rotateZ: function( el, index ) { return [ anime.random( -50, 50 ), 0 ]; }
				},
				out: {
					duration: function( el, index ) { return anime.random( 200, 300 ); },
					delay: function( el, index ) { return anime.random( 0, 80 ); },
					easing: 'easeInQuart',
					opacity: 0,
					translateY: '50%',
					rotateZ: function( el, index ) { return anime.random( -50, 50 ); }
				}
			},
			'fx12' : {
				in: {
					elasticity: false,
					duration: 1,
					delay: function( el, index ) {
						var delay = index * 100 + anime.random( 50, 100 );

						return delay;
					},
					width: [ 0, function( el, i ) { return $( el ).width(); } ]
				},
				out: {
					duration: 1,
					delay: function( el, index ) { return ( el.parentNode.children.length - index - 1 ) * 20; },
					easing: 'linear',
					width: {
						value: 0
					}
				}
			}
		};

		self.textChange = function() {
			var currentDelay = animationDelay,
				$prevText    = $animatedTextList.eq( currentIndex ),
				$nextText;

			if ( currentIndex < $animatedTextList.length - 1 ) {
				currentIndex++;
			} else {
				currentIndex = 0;
			}

			$nextText = $animatedTextList.eq( currentIndex );

			self.hideText( $prevText, settings.effect, null, function( anime ) {
				$prevText.toggleClass( 'visible' );

				var currentDelay = animationDelay;

				if ( timeOut ) {
					clearTimeout( timeOut );
				}

				self.showText(
					$nextText,
					settings.effect,
					function() {
						$nextText.toggleClass( 'active' );
						$prevText.toggleClass( 'active' );

						$nextText.toggleClass( 'visible' );
					},
					function() {
						timeOut = setTimeout( function() {
							self.textChange();
						}, currentDelay );
					}
				);

			} );
		};

		self.showText = function( $selector, effect, beginCallback, completeCallback ) {
			var targets = [];

			$( 'span', $selector ).each( function() {
				$( this ).css( {
					'width': 'auto',
					'opacity': 1,
					'WebkitTransform': '',
					'transform': ''
				});
				targets.push( this );
			});

			self.animateText( targets, 'in', effect, beginCallback, completeCallback );
		};

		self.hideText = function( $selector, effect, beginCallback, completeCallback ) {
			var targets = [];

			$( 'span', $selector ).each( function() {
				targets.push(this);
			});

			self.animateText( targets, 'out', effect, beginCallback, completeCallback );
		};

		self.animateText = function( targets, direction, effect, beginCallback, completeCallback ) {
			var effectSettings   = self.avaliableEffects[ effect ] || {},
				animationOptions = effectSettings[ direction ],
				animeInstance = null;

			animationOptions.targets = targets;

			animationOptions.begin = beginCallback;
			animationOptions.complete = completeCallback;

			animeInstance = anime( animationOptions );
		};

		self.init = function() {
			var $text = $animatedTextList.eq( currentIndex );

			self.showText(
				$text,
				settings.effect,
				null,
				function() {
					var currentDelay = animationDelay;

					if ( timeOut ) {
						clearTimeout( timeOut );
					}

					timeOut = setTimeout( function() {
						self.textChange();
					}, currentDelay );

				}
			);
		};
	}

	/**
	 * LaStudio Images Layout Class
	 *
	 * @return {void}
	 */
	window.lastudioImagesLayout = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$instanceList   = $( '.lastudio-images-layout__list', $instance ),
			$itemsList      = $( '.lastudio-images-layout__item', $instance ),
			defaultSettings = {},
			settings        = settings || {};

		/*
		 * Default Settings
		 */
		defaultSettings = {
			layoutType: 'masonry',
			columns: 3,
			columnsTablet: 2,
			columnsMobile: 1,
			justifyHeight: 300
		}

		/**
		 * Checking options, settings and options merging
		 */
		$.extend( defaultSettings, settings );

		/**
		 * Layout build
		 */
		self.layoutBuild = function() {
			switch ( settings['layoutType'] ) {
				case 'masonry':
					salvattore.init();
				break;
				case 'justify':
					$itemsList.each( function() {
						var $this          = $( this ),
							$imageInstance = $( '.lastudio-images-layout__image-instance', $this),
							imageWidth     = $imageInstance.data( 'width' ),
							imageHeight    = $imageInstance.data( 'height' ),
							imageRatio     = +imageWidth / +imageHeight,
							flexValue      = imageRatio * 100,
							newWidth       = +settings['justifyHeight'] * imageRatio,
							newHeight      = 'auto';

						$this.css( {
							'flex-grow': flexValue,
							'flex-basis': newWidth
						} );
					} );
				break;
			}

			$( '.lastudio-images-layout__image', $itemsList ).imagesLoaded().progress( function( instance, image ) {
				var $image      = $( image.img ),
					$parentItem = $image.closest( '.lastudio-images-layout__item' ),
					$loader     = $( '.lastudio-images-layout__image-loader', $parentItem );

                LaStudioElementsTools.makeImageAsLoaded(image.img);

				$parentItem.addClass( 'image-loaded' );

				$loader.fadeTo( 500, 0, function() {
					$( this ).remove();
				} );

			});
		}

		/**
		 * Init
		 */
		self.init = function() {
			self.layoutBuild();
		}
	}

	/**
	 * LaStudio Scroll Navigation Class
	 *
	 * @return {void}
	 */
	window.lastudioScrollNavigation = function( $selector, settings ) {
		var self            = this,
			$window         = $( window ),
			$document       = $( document ),
			$instance       = $selector,
			$htmlBody       = $( 'html, body' ),
			$itemsList      = $( '.lastudio-scroll-navigation__item', $instance ),
			sectionList     = [],
			defaultSettings = {
				speed: 500,
				blockSpeed: 500,
				offset: 200,
				sectionSwitch: false
			},
			settings        = $.extend( {}, defaultSettings, settings ),
			sections        = {},
			currentSection  = null,
			isScrolling     = false,
			isSwipe         = false,
			hash            = window.location.hash.slice(1),
			timeout         = null,
			timeStamp       = 0,
			platform        = navigator.platform;

		$.extend( $.easing, {
			easeInOutCirc: function (x, t, b, c, d) {
				if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
				return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
			}
		});

		/**
		 * [init description]
		 * @return {[type]} [description]
		 */
		self.init = function() {
			self.setSectionsData();

			// Add Events
			$itemsList.on( 'click.lastudioScrollNavigation', self.onAnchorChange );

			$window.on( 'scroll.lastudioScrollNavigation', self.onScroll );
			$window.on( 'resize.lastudioScrollNavigation orientationchange.lastudioScrollNavigation', LaStudioElementsTools.debounce( 50, self.onResize ) );
			$window.on( 'load', function() { self.setSectionsData(); } );

			$document.keydown( function( event ) {

				if ( 38 == event.keyCode ) {
					self.directionSwitch( event, 'up' );
				}

				if ( 40 == event.keyCode ) {
					self.directionSwitch( event, 'down' );
				}
			} );

			if ( settings.sectionSwitch ) {

				if ( 'onwheel' in window ) {
					// onwheel check handler
				}

				$document.on( 'mousewheel.lastudioScrollNavigation DOMMouseScroll.lastudioScrollNavigation', self.onWheel );

				/*if ( self.mobileAndTabletcheck() ) {
					var touchstartY = 0,
						touchendY   = 0;

					$document.on( 'touchstart', function( event ) {
						var originalEvent = event.originalEvent;

						isSwipe = true;

						touchstartY = originalEvent.changedTouches[0].screenY;

					} );

					$document.on( 'touchend', function( event ) {
						var originalEvent = event.originalEvent;

						isSwipe = false;

						touchendY = originalEvent.changedTouches[0].screenY;

						if ( touchendY < touchstartY ) {
							self.directionSwitch( event, 'down' );
						}

						if ( touchendY > touchstartY ) {
							self.directionSwitch( event, 'up' );
						}
					} );

				}*/
			}

			if ( hash && sections.hasOwnProperty( hash ) ) {
				$itemsList.addClass( 'invert' );
			}

			for ( var section in sections ) {
				var $section = sections[section].selector;

				elementorFrontend.waypoint( $section, function( direction ) {
					var $this = $( this ),
						sectionId = $this.attr( 'id' );

						if ( 'down' === direction && ! isScrolling && ! isSwipe ) {
							window.history.pushState( null, null, '#' + sectionId );
							currentSection = sectionId;
							$itemsList.removeClass( 'active' );
							$( '[data-anchor=' + sectionId + ']', $instance ).addClass( 'active' );

							$itemsList.removeClass( 'invert' );

							if ( sections[sectionId].invert ) {
								$itemsList.addClass( 'invert' );
							}
						}
				}, {
					offset: '95%',
					triggerOnce: false
				} );

				elementorFrontend.waypoint( $section, function( direction ) {
					var $this = $( this ),
						sectionId = $this.attr( 'id' );

						if ( 'up' === direction && ! isScrolling && ! isSwipe ) {
							window.history.pushState( null, null, '#' + sectionId );
							currentSection = sectionId;
							$itemsList.removeClass( 'active' );
							$( '[data-anchor=' + sectionId + ']', $instance ).addClass( 'active' );

							$itemsList.removeClass( 'invert' );

							if ( sections[sectionId].invert ) {
								$itemsList.addClass( 'invert' );
							}
						}
				}, {
					offset: '0%',
					triggerOnce: false
				} );
			}
		};

		/**
		 * [onAnchorChange description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onAnchorChange = function( event ) {
			var $this     = $( this ),
				sectionId = $this.data('anchor'),
				offset    = null;

			if ( ! sections.hasOwnProperty( sectionId ) ) {
				return false;
			}

			offset = sections[sectionId].offset - settings.offset;

			if ( ! isScrolling ) {
				isScrolling = true;
				window.history.pushState( null, null, '#' + sectionId );
				currentSection = sectionId;

				$itemsList.removeClass( 'active' );
				$this.addClass( 'active' );

				$itemsList.removeClass( 'invert' );

				if ( sections[sectionId].invert ) {
					$itemsList.addClass( 'invert' );
				}

				$htmlBody.stop().clearQueue().animate( { 'scrollTop': offset }, settings.speed, 'easeInOutCirc', function() {
					isScrolling = false;
				} );
			}
		};

		/**
		 * [directionSwitch description]
		 * @param  {[type]} event     [description]
		 * @param  {[type]} direction [description]
		 * @return {[type]}           [description]
		 */
		self.directionSwitch = function( event, direction ) {
			var direction = direction || 'up',
				sectionId,
				nextItem = $( '[data-anchor=' + currentSection + ']', $instance ).next(),
				prevItem = $( '[data-anchor=' + currentSection + ']', $instance ).prev();

			//event.preventDefault();

			if ( isScrolling ) {
				return false;
			}

			if ( 'up' === direction ) {
				if ( prevItem[0] ) {
					prevItem.trigger( 'click.lastudioScrollNavigation' );
				}
			}

			if ( 'down' === direction ) {
				if ( nextItem[0] ) {
					nextItem.trigger( 'click.lastudioScrollNavigation' );
				}
			}
		};

		/**
		 * [onScroll description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onScroll = function( event ) {
			/* On Scroll Event */
			if ( isScrolling || isSwipe ) {
				event.preventDefault();
			}
		};

		/**
		 * [onWheel description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onWheel = function( event ) {

			if ( isScrolling || isSwipe ) {
				event.preventDefault();
				return false;
			}

			var $target         = $( event.target ),
				$section        = $target.closest( '.elementor-top-section' ),
				sectionId       = $section.attr( 'id' ),
				offset          = 0,
				newSectionId    = false,
				prevSectionId   = false,
				nextSectionId   = false,
				delta           = event.originalEvent.wheelDelta || -event.originalEvent.detail,
				direction       = ( 0 < delta ) ? 'up' : 'down',
				windowScrollTop = $window.scrollTop();

			if ( self.beforeCheck() ) {
				sectionId = LaStudioElementsTools.getObjectFirstKey( sections );
			}

			if ( self.afterCheck() ) {
				sectionId = LaStudioElementsTools.getObjectLastKey( sections );
			}

			if ( sectionId && sections.hasOwnProperty( sectionId ) ) {

				prevSectionId = LaStudioElementsTools.getObjectPrevKey( sections, sectionId );
				nextSectionId = LaStudioElementsTools.getObjectNextKey( sections, sectionId );

				if ( 'up' === direction ) {
					if ( ! nextSectionId && sections[sectionId].offset < windowScrollTop ) {
						newSectionId = sectionId;
					} else {
						newSectionId = prevSectionId;
					}
				}

				if ( 'down' === direction ) {
					if ( ! prevSectionId && sections[sectionId].offset > windowScrollTop + 5 ) {
						newSectionId = sectionId;
					} else {
						newSectionId = nextSectionId;
					}
				}

				if ( newSectionId ) {

					if ( event.timeStamp - timeStamp > 10 && 'MacIntel' == platform ) {
						timeStamp = event.timeStamp;
						event.preventDefault();
						return false;
					}

					event.preventDefault();

					offset = sections[newSectionId].offset - settings.offset;
					window.history.pushState( null, null, '#' + newSectionId );
					currentSection = newSectionId;

					$itemsList.removeClass( 'active' );
					$( '[data-anchor=' + newSectionId + ']', $instance ).addClass( 'active' );

					$itemsList.removeClass( 'invert' );

					if ( sections[newSectionId].invert ) {
						$itemsList.addClass( 'invert' );
					}

					isScrolling = true;
					self.scrollStop();
					$htmlBody.animate( { 'scrollTop': offset }, settings.blockSpeed, 'easeInOutCirc', function() {
						isScrolling = false;
					} );
				}
			}

		};

		/**
		 * [setSectionsData description]
		 */
		self.setSectionsData = function() {
			$itemsList.each( function() {
				var $this         = $( this ),
					sectionId     = $this.data('anchor'),
					sectionInvert = 'yes' === $this.data('invert') ? true : false,
					$section      = $( '#' + sectionId );

				$section.addClass( 'lastudio-scroll-navigation-section' );
				$section.attr( { 'touch-action': 'none'} );

				if ( $section[0] ) {
					sections[ sectionId ] = {
						selector: $section,
						offset: Math.round( $section.offset().top ),
						height: $section.outerHeight(),
						invert: sectionInvert
					};
				}
			} );
		};


		/**
		 * [beforeCheck description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.beforeCheck = function( event ) {
			var windowScrollTop = $window.scrollTop(),
				firstSectionId = LaStudioElementsTools.getObjectFirstKey( sections ),
				offset = sections[ firstSectionId ].offset,
				topBorder = windowScrollTop + $window.outerHeight();

			if ( topBorder > offset ) {
				return false;
			}

			return true;
		};

		/**
		 * [afterCheck description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.afterCheck = function( event ) {
			var windowScrollTop = $window.scrollTop(),
				lastSectionId = LaStudioElementsTools.getObjectLastKey( sections ),
				offset = sections[ lastSectionId ].offset,
				bottomBorder = sections[ lastSectionId ].offset + sections[ lastSectionId ].height;

			if ( windowScrollTop < bottomBorder ) {
				return false;
			}

			return true;
		};

		/**
		 * [onResize description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onResize = function( event ) {
			self.setSectionsData();
		};

		/**
		 * [scrollStop description]
		 * @return {[type]} [description]
		 */
		self.scrollStop = function() {
			$htmlBody.stop( true );
		};

		/**
		 * Mobile and tablet check funcion.
		 *
		 * @return {boolean} Mobile Status
		 */
		self.mobileAndTabletcheck = function() {
			var check = false;

			(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);

			return check;
		};

	}

	/**
	 * lastudioSectionParallax Class
	 *
	 * @return {void}
	 */
	window.lastudioSectionParallax = function( $target, _is_section ) {
		var self             = this,
			is_Section		 = _is_section,
			sectionId        = $target.data('id'),
			settings         = false,
			editMode         = Boolean( elementor.isEditMode() ),
			$window          = $( window ),
			$body            = $( 'body' ),
			scrollLayoutList = [],
			mouseLayoutList  = [],
			winScrollTop     = $window.scrollTop(),
			winHeight        = $window.height(),
			requesScroll     = null,
			requestMouse     = null,
			tiltx            = 0,
			tilty            = 0,
			isSafari         = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/),
			platform         = navigator.platform;

		/**
		 * Init
		 */

		var sectionParentId = $target.closest('.elementor-section').data('id');

		self.init = function() {

			if ( ! editMode ) {
				settings = lastudioElements[ 'lastudioParallaxSections' ][ sectionId ] || false;
			}
			else {
				settings = self.generateEditorSettings( sectionId, is_Section);
			}

			if ( ! settings ) {
				return false;
			}

            $target.addClass( 'lastudio-parallax-section' );

			self.generateLayouts();

			//$window.on( 'scroll.lastudioSectionParallax resize.lastudioSectionParallax', LaStudioElementsTools.debounce( 5, self.scrollHandler ) );

			if ( 0 !== scrollLayoutList.length ) {
				$window.on( 'scroll.lastudioSectionParallax resize.lastudioSectionParallax', self.scrollHandler );
			}

			if ( 0 !== mouseLayoutList.length ) {
				$target.on( 'mousemove.lastudioSectionParallax resize.lastudioSectionParallax', self.mouseMoveHandler );
				$target.on( 'mouseleave.lastudioSectionParallax', self.mouseLeaveHandler );
			}

			self.scrollUpdate();
		};

		self.generateEditorSettings = function( sectionId, _lookingSection ) {
			var editorElements      = null,
				sectionsData        = {},
				sectionData         = {},
				sectionParallaxData = {},
				settings            = [];

			if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
				return false;
			}

			editorElements = window.elementor.elements;

			if ( ! editorElements.models ) {
				return false;
			}

			if(_lookingSection){
                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                } );
			}
			else{

				var sectionParentData = {};

                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionParentId == obj.id ) {
                        sectionParentData = obj.attributes.elements;
                    }
                } );

                if ( ! sectionParentData.hasOwnProperty( 'models' ) || 0 === Object.keys( sectionParentData ).length ) {
                    return false;
                }

                $.each( sectionParentData.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                } );

			}

			if ( ! sectionData.hasOwnProperty( 'lastudio_parallax_layout_list' ) || 0 === Object.keys( sectionData ).length ) {
				return false;
			}

			sectionParallaxData = sectionData[ 'lastudio_parallax_layout_list' ].models;

			$.each( sectionParallaxData, function( index, obj ) {
				settings.push( obj.attributes );
			} );

			if ( 0 !== settings.length ) {
				return settings;
			}

			return false;
		};

		self.generateLayouts = function() {

			$( '>.lastudio-parallax-section__layout', $target ).remove();

			$.each( settings, function( index, layout ) {

				var imageData   = layout['lastudio_parallax_layout_image'],
					speed       = layout['lastudio_parallax_layout_speed']['size'] || 50,
					zIndex      = layout['lastudio_parallax_layout_z_index'],
					customCSSClass      = layout['lastudio_parallax_layout_css_class'] || '',
					bgSize      = layout['lastudio_parallax_layout_bg_size'] || 'auto',
					animProp    = layout['lastudio_parallax_layout_animation_prop'] || 'bgposition',
					bgX         = layout['lastudio_parallax_layout_bg_x'],
					bgY         = layout['lastudio_parallax_layout_bg_y'],
					type        = layout['lastudio_parallax_layout_type'] || 'none',
					device      = layout['lastudio_parallax_layout_on'] || [ 'desktop', 'tablet' ],
					$layout     = null,
					layoutData  = {},
					safariClass = isSafari ? ' is-safari' : '',
					macClass    = 'MacIntel' == platform ? ' is-mac' : '';

				if ( '' == imageData['url'] ) {
					return false;
				}

				$layout = $( '<div class="lastudio-parallax-section__layout lastudio-parallax-section__' + type +'-layout' + macClass + ' ' + customCSSClass + '"><div class="lastudio-parallax-section__image"></div></div>' )
					.prependTo( $target )
					.css({
						'z-index': zIndex
					});

				$( '> .lastudio-parallax-section__image', $layout ).css({
					'background-image': 'url(' + imageData['url'] + ')',
					'background-size': bgSize,
					'background-position-x': bgX + '%',
					'background-position-y': bgY + '%'
				});

				layoutData = {
					selector: $layout,
					image: imageData['url'],
					size: bgSize,
					prop: animProp,
					type: type,
					device: device,
					xPos: bgX,
					yPos: bgY,
					speed: 2 * ( speed / 100 )
				};

				if ( 'none' !== type ) {
					if ( 'scroll' === type || 'zoom' === type ) {
						scrollLayoutList.push( layoutData );
					}

					if ( 'mouse' === type ) {
						mouseLayoutList.push( layoutData );
					}
				}

			});

		};

		self.scrollHandler = function( event ) {
			winScrollTop = $window.scrollTop();
			winHeight    = $window.height();

			self.scrollUpdate();
		};

		self.scrollUpdate = function() {

			$.each( scrollLayoutList, function( index, layout ) {

				var $this      = layout.selector,
					$image     = $( '.lastudio-parallax-section__image', $this ),
					speed      = layout.speed,
					offsetTop  = $this.offset().top,
					thisHeight = $this.outerHeight(),
					prop       = layout.prop,
					type       = layout.type,
					posY       = ( winScrollTop - offsetTop + winHeight ) / thisHeight * 100,
					device     = LaStudioElements.getCurrentDeviceMode();

				if( device == ''){
                    device = $(document.body).attr('data-elementor-device-mode');
				}

				if ( -1 == layout.device.indexOf( device ) ) {
					$image.css( {
						'transform': 'translateY(0)',
						'background-position-y': layout.yPos + '%'
					} );

					return false;
				}

				if ( winScrollTop < offsetTop - winHeight ) posY = 0;
				if ( winScrollTop > offsetTop + thisHeight) posY = 200;

				posY = parseFloat( speed * posY ).toFixed(1);

				switch( type ) {
					case 'scroll':
						if ( 'bgposition' === layout.prop ) {
							$image.css( {
								'background-position-y': 'calc(' + layout.yPos + '% + ' + posY + 'px)'
							} );
						} else {
							$image.css( {
								'transform': 'translateY(' + posY + 'px)'
							} );
						}
						break;
					case 'zoom':
						var deltaScale = ( winScrollTop - offsetTop + winHeight ) / winHeight,
							scale      = deltaScale * speed;

						scale = scale + 1;

						$image.css( {
							'transform': 'scale(' + scale + ')'
						} );
						break;
				}

			} );

			//requesScroll = requestAnimationFrame( self.scrollUpdate );
			//requestAnimationFrame( self.scrollUpdate );
		};

		self.mouseMoveHandler = function( event ) {
			var windowWidth  = $window.width(),
				windowHeight = $window.height(),
				cx           = Math.ceil( windowWidth / 2 ),
				cy           = Math.ceil( windowHeight / 2 ),
				dx           = event.clientX - cx,
				dy           = event.clientY - cy;

			tiltx = -1 * ( dx / cx );
			tilty = -1 * ( dy / cy);

			self.mouseMoveUpdate();
		};

		self.mouseLeaveHandler = function( event ) {

			$.each( mouseLayoutList, function( index, layout ) {
				var $this  = layout.selector,
					$image = $( '.lastudio-parallax-section__image', $this );

				switch( layout.prop ) {
					case 'transform3d':
						TweenMax.to(
							$image[0],
							1.2, {
								x: 0,
								y: 0,
								z: 0,
								rotationX: 0,
								rotationY: 0,
								ease:Power2.easeOut
							}
						);
					break;
				}

			} );
		};

		self.mouseMoveUpdate = function() {
			$.each( mouseLayoutList, function( index, layout ) {
				var $this   = layout.selector,
					$image  = $( '.lastudio-parallax-section__image', $this ),
					speed   = layout.speed,
					prop    = layout.prop,
					posX    = parseFloat( tiltx * 125 * speed ).toFixed(1),
					posY    = parseFloat( tilty * 125 * speed ).toFixed(1),
					posZ    = layout.zIndex * 50,
					rotateX = parseFloat( tiltx * 25 * speed ).toFixed(1),
					rotateY = parseFloat( tilty * 25 * speed ).toFixed(1),
					device  = LaStudioElements.getCurrentDeviceMode();

                if( device == ''){
                    device = $(document.body).attr('data-elementor-device-mode');
                }

				if ( -1 == layout.device.indexOf( device ) ) {
					$image.css( {
						'transform': 'translateX(0) translateY(0)',
						'background-position-x': layout.xPos + '%',
						'background-position-y': layout.yPos + '%'
					} );

					return false;
				}

				switch( prop ) {
					case 'bgposition':
						TweenMax.to(
							$image[0],
							1, {
								backgroundPositionX: 'calc(' + layout.xPos + '% + ' + posX + 'px)',
								backgroundPositionY: 'calc(' + layout.yPos + '% + ' + posY + 'px)',
								ease:Power2.easeOut
							}
						);
					break;

					case 'transform':
						TweenMax.to(
							$image[0],
							1, {
								x: posX,
								y: posY,
								ease:Power2.easeOut
							}
						);
					break;

					case 'transform3d':
						TweenMax.to(
							$image[0],
							2, {
								x: posX,
								y: posY,
								z: posZ,
								rotationX: rotateY,
								rotationY: -rotateX,
								ease:Power2.easeOut
							}
						);
					break;
				}

			} );
		};

	}

	/**
	 * LaStudio Portfolio Class
	 *
	 * @return {void}
	 */
	window.lastudioPortfolio = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$instanceList   = $( '.lastudio-portfolio__list', $instance ),
			$itemsList      = $( '.lastudio-portfolio__item', $instance ),
			$filterList     = $( '.lastudio-portfolio__filter-item', $instance ),
			$moreWrapper    = $( '.lastudio-portfolio__view-more', $instance ),
			$moreButton     = $( '.lastudio-portfolio__view-more-button', $instance ),
			itemsData       = {},
			filterData      = {},
			activeSlug      = [],
			defaultSettings = {
				layoutType: 'masonry',
				columns: 3,
				columnsTablet: 2,
				columnsMobile: 1,
				perPage: 6
			},
			masonryOptions = {
				itemSelector: '.lastudio-portfolio__item',
                percentPosition: true,
                gutter: 0
            },
			settings        = $.extend( defaultSettings, settings ),
			$masonryInstance,
			page            = 1;

		/**
		 * Init
		 */
		self.init = function() {
			self.layoutBuild();
		}

        self.get_item_column = function (w_w, item_w) {
            return Math.round(w_w / item_w);
        }

        self.calc_item_sizes = function(){
            var ww = $(window).width(),
                _base_w = $instance.data('item-width'),
                _base_h = $instance.data('item-height'),
                _container_width_base = ( false !== !!$instance.data('container-width') ? $instance.data('container-width') : $instanceList.width()),
                _container_width = $instanceList.width();

            var portfolionumber = self.get_item_column(_container_width_base, _base_w);


            if( ww < 1700){
                portfolionumber = settings.columnsLaptop;
            }
            if( ww < 1200){
                portfolionumber = settings.columnsTablet;
            }
            if( ww < 992){
                portfolionumber = settings.columnsTabletPortrait;
            }
            if( ww < 768){
                portfolionumber = settings.columnsMobile;
            }
            if( ww < 576){
                portfolionumber = settings.columnsMobilePortrait;
            }

            var itemwidth = Math.floor(_container_width / portfolionumber),
                margin = parseInt($instance.data('item_margin') || 0),
                dimension = parseFloat( _base_w / _base_h );

            $('> .grid-sizer', $instanceList).css('width', itemwidth);

            $itemsList.each(function (idx) {

                var thiswidth = parseFloat( $(this).data('width') || 1 ),
                    thisheight = parseFloat( $(this).data('height') || 1),
                    _css = {};

                if (isNaN(thiswidth)) thiswidth = 1;
                if (isNaN(thisheight)) thisheight = 1;

                if( thiswidth > portfolionumber ) {
                	thiswidth = portfolionumber;
				}

                if( ww < 992){
                    thiswidth = thisheight = 1;
                }
                _css.width = Math.floor((itemwidth * thiswidth) - (margin / 2));
                _css.height = Math.floor((itemwidth / dimension) * thisheight);

                if( ww < 992){
                    _css.height = 'auto';
                }

                $(this).css(_css);

            });

		};

		/**
		 * Layout build
		 */
		self.layoutBuild = function() {

			self.generateData();

			if ( 'justify' == settings['layoutType'] ) {
				masonryOptions['columnWidth'] = '.grid-sizer';
			}

			if($instance.hasClass('advancedMasonry')){
				$instanceList.prepend('<div class="grid-sizer"></div>');
                masonryOptions['columnWidth'] = '.grid-sizer';
                self.calc_item_sizes();
			}

            if($instance.find('.lastudio-carousel').length == 0) {
                $masonryInstance = $instanceList.masonry(masonryOptions);
            }
            else{
                LaStudioElements.initCarousel( $instanceList, $instance.find('.lastudio-carousel').data( 'slider_options' ) );
			}

            $( '.lastudio-portfolio__image', $itemsList ).imagesLoaded().progress( function( instance, image ) {
                var $image      = $( image.img ),
                    $parentItem = $image.closest( '.lastudio-portfolio__item' ),
                    $loader     = $( '.lastudio-portfolio__image-loader', $parentItem );

                LaStudioElementsTools.makeImageAsLoaded(image.img);

                $loader.remove();
                $parentItem.addClass( 'item-loaded' );

                if($instance.find('.lastudio-carousel').length == 0) {
                    $masonryInstance.masonry('layout');
                }
            } );


            if($instance.hasClass('advancedMasonry')) {
                $(window).on('resize', function (e) {
                    self.calc_item_sizes();
                });
            }

			$filterList.on( 'click.lastudioPortfolio', self.filterHandler );
			$moreButton.on( 'click.lastudioPortfolio', self.moreButtonHandler );

			self.render();
			self.checkMoreButton();
		};

		self.generateData = function() {
			if ( $filterList[0] ) {
				$filterList.each( function( index ) {
					var $this = $( this ),
						slug  = $this.data('slug');

					filterData[ slug ] = false;

					if ( 'all' == slug ) {
						filterData[ slug ] = true;
					}
				} );
			}
			else {
				filterData['all'] = true;
			}

			$itemsList.each( function( index ) {
				var $this = $( this ),
					slug  = $this.data('slug');

				itemsData[ index ] = {
					selector: $this,
					slug: slug,
					visible: $this.hasClass( 'visible-status' ) ? true : false,
					more: $this.hasClass( 'hidden-status' ) ? true : false
				};
			} );
		};

		self.filterHandler = function( event ) {
			var $this = $( this ),
				slug  = $this.data( 'slug' );

			$filterList.removeClass( 'active' );
			$this.addClass( 'active' );

			for ( var slugName in filterData ) {
				filterData[ slugName ] = false;

				if ( slugName == slug ) {
					filterData[ slugName ] = true;
				}
			}

			$.each( itemsData, function( index, obj ) {
				var visible = false;

				if ( self.isItemVisible( obj.slug ) && ! obj['more'] ) {
					visible = true;
				}

				obj.visible = visible;
			} );

			self.render();
			self.checkMoreButton();
		}

		/**
		 * [moreButtonHandler description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.moreButtonHandler = function( event ) {
			var $this   = $( this ),
				counter = 1;

			$.each( itemsData, function( index, obj ) {

				if ( self.isItemVisible( obj.slug ) && obj.more && counter <= settings.perPage ) {
					obj.more = false;
					obj.visible = true;

					counter++;
				}
			} );

			self.render();
			self.checkMoreButton();
		}

		/**
		 * [checkmoreButton description]
		 * @return {[type]} [description]
		 */
		self.checkMoreButton = function() {
			var check = false;

			$.each( itemsData, function( index, obj ) {

				if ( self.isItemVisible( obj.slug ) && obj.more ) {
					check = true;
				}
			} );

			if ( check ) {
				$moreWrapper.removeClass( 'hidden-status' );
			} else {
				$moreWrapper.addClass( 'hidden-status' );
			}
		}

		/**
		 * [anyFilterEnabled description]
		 * @return {Boolean} [description]
		 */
		self.isItemVisible = function( slugs ) {
			var slugList = Object.keys(slugs).map(function(e) {
                return slugs[e]
            });
			for ( var slug in filterData ) {
				var checked = filterData[ slug ];

				if ( checked && -1 !== slugList.indexOf( slug ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * [anyFilterEnabled description]
		 * @return {Boolean} [description]
		 */
		self.anyFilterEnabled = function() {

			for ( var slug in filterData ) {
				if ( filterData[ slug ] ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		self.render = function() {
			var hideAnimation,
				showAnimation;

			$itemsList.removeClass( 'visible-status' ).removeClass( 'hidden-status' );

			$.each( itemsData, function( index, itemData ) {
				var selector = $( '.lastudio-portfolio__inner', itemData.selector );

				if ( itemData.visible ) {
					itemData.selector.addClass( 'visible-status' );

					showAnimation = anime( {
						targets: selector[0],
						opacity: {
							value: 1,
							duration: 400
						},
						scale: {
							value: 1,
							duration: 500,
							easing: 'easeOutExpo'
						},
						delay: 50,
						elasticity: false
					} );
				} else {
					itemData.selector.addClass( 'hidden-status' );
					hideAnimation = anime( {
						targets: selector[0],
						opacity: 0,
						scale: 0,
						duration: 500,
						elasticity: false
					} );
				}
			} );

            if($instance.find('.lastudio-carousel').length == 0) {
                $masonryInstance.masonry('layout');
            }
		}
	}

	/**
	 * LaStudio Timeline Class
	 *
	 * @return {void}
	 */
	window.lastudioTimeLine = function ( $element ){
		var $viewport		= $(window),
			self			= this,
			$line 			= $element.find( '.lastudio-timeline__line' ),
			$progress		= $line.find( '.lastudio-timeline__line-progress' ),
			$cards			= $element.find( '.lastudio-timeline-item' ),
			$points 		= $element.find('.timeline-item__point'),

			currentScrollTop 		= $viewport.scrollTop(),
			lastScrollTop 			= -1,
			currentWindowHeight 	= $(window).height(),
			currentViewportHeight 	= $viewport.outerHeight(),
			lastWindowHeight 		= -1,
			requestAnimationId 		= null,
			flag 					= false;

		self.onScroll = function (){
			currentScrollTop = $viewport.scrollTop();

			self.updateFrame();
			self.animateCards();
		};

		self.onResize = function() {
			currentScrollTop = $viewport.scrollTop();
			currentWindowHeight = $viewport.height();

			self.updateFrame();
		};

		self.updateWindow = function() {
			flag = false;

			$line.css({
				'top' 		: $cards.first().find( $points ).offset().top - $cards.first().offset().top,
				'bottom'	: ( $element.offset().top + $element.outerHeight() ) - $cards.last().find( $points ).offset().top
			});

			if ( ( lastScrollTop !== currentScrollTop ) ) {
				lastScrollTop 		= currentScrollTop;
				lastWindowHeight = currentWindowHeight;

				self.updateProgress();
			}
		};

		self.updateProgress = function() {
			var progressFinishPosition = $cards.last().find( $points ).offset().top,
				progressHeight = ( currentScrollTop - $progress.offset().top ) + ( currentViewportHeight / 2 );

			if ( progressFinishPosition <= ( currentScrollTop + currentViewportHeight / 2 ) ) {
				progressHeight = progressFinishPosition - $progress.offset().top;
			}

			$progress.css({
				'height' : progressHeight + 'px'
			});

			$cards.each( function() {
				if ( $(this).find( $points ).offset().top < ( currentScrollTop + currentViewportHeight * 0.5 ) ) {
					$(this).addClass('is--active');
				} else {
					$(this).removeClass('is--active');
				}
			});
		};

		self.updateFrame = function() {
			if ( ! flag ) {
				requestAnimationId = requestAnimationFrame( self.updateWindow );
			}
			flag = true;
		};

		self.animateCards = function() {
			$cards.each( function() {
				if( $(this).offset().top <= currentScrollTop + currentViewportHeight * 0.9 && $(this).hasClass('lastudio-timeline-item--animated') ) {
					$(this).addClass('is--show');
				}
			});
		};

		self.init = function(){
			$(document).ready(self.onScroll);
			$(window).on('scroll.lastudioTimeline', self.onScroll);
			$(window).on('resize.lastudioTimeline orientationchange.lastudioTimeline', LaStudioElementsTools.debounce( 50, self.onResize ));
		};
	}


    /**
	 * Ajax Handlers
     */

    window.LaStudioAjaxHandler = function ( options ) {
        /**
         * General default settings
         *
         * @type {Object}
         */
        var self     = this,
            settings = {
                'handlerId': '',
                'cache': false,
                'processData': true,
                'url': '',
                'async': false,
                'beforeSendCallback': function() {},
                'errorCallback': function() {},
                'successCallback': function() {},
                'completeCallback': function() {}
            };

        /**
         * Checking options, settings and options merging
         *
         */
        if ( options ) {
            $.extend( settings, options );
        }

        /**
         * Check if handlerId ready to using
         *
         */
        if ( ! window[ settings.handlerId ] ) {
            if ( window.console ) {
                window.console.warn( 'Handler id not found' );
            }
            return false;
        }

        /**
         * Set handler settings from localized global variable
         *
         * @type {Object}
         */
        self.handlerSettings = window[ settings.handlerId ] || {};

        /**
         * Ajax request instance
         *
         * @type {Object}
         */
        self.ajaxRequest = null;

        /**
         * Ajax processing state
         *
         * @type {Boolean}
         */
        self.ajaxProcessing = false;

        /**
         * Set ajax request data
         *
         * @type {Object}
         */
        self.data = {
            'action': self.handlerSettings.action,
            'nonce': self.handlerSettings.nonce
        };

        /**
         * Check ajax url is empty
         */
        if ( '' === settings.url ) {

            // Check public request
            if ( 'false' === self.handlerSettings.is_public ) {
                settings.url = window.lastudioElements.ajaxurl;
            }
            else{
                settings.url = window.lastudioElements.ajaxurl;
			}
        }

        /**
         * Init ajax request
         *
         * @return {Void}
         */
        self.send = function() {
            if ( self.ajaxProcessing ) {
                window.LaStudioHandlerUtils.noticeCreate( 'error-notice', self.handlerSettings.sys_messages.wait_processing, self.handlerSettings.is_public );
            }
            self.ajaxProcessing = true;

            self.ajaxRequest = jQuery.ajax( {
                type: self.handlerSettings.type,
                url: settings.url,
                data: self.data,
                cache: settings.cache,
                dataType: self.handlerSettings.data_type,
                processData: settings.processData,
                beforeSend: function( jqXHR, ajaxSettings ) {
                    if ( null !== self.ajaxRequest && ! settings.async ) {
                        self.ajaxRequest.abort();
                    }

                    if ( settings.beforeSendCallback && 'function' === typeof( settings.beforeSendCallback ) ) {
                        settings.beforeSendCallback( jqXHR, ajaxSettings );
                    }
                },
                error: function( jqXHR, textStatus, errorThrown ) {
                    $( document ).trigger( {
                        type: 'lastudio-ajax-handler-error',
                        jqXHR: jqXHR,
                        textStatus: textStatus,
                        errorThrown: errorThrown
                    } );

                    if ( settings.errorCallback && 'function' === typeof( settings.errorCallback ) ) {
                        settings.errorCallback( jqXHR, textStatus, errorThrown );
                    }
                },
                success: function( data, textStatus, jqXHR ) {
                    self.ajaxProcessing = false;

                    $( document ).trigger( {
                        type: 'lastudio-ajax-handler-success',
                        response: data,
                        jqXHR: jqXHR,
                        textStatus: textStatus
                    } );

                    if ( settings.successCallback && 'function' === typeof( settings.successCallback ) ) {
                        settings.successCallback( data, textStatus, jqXHR );
                    }

                    window.LaStudioHandlerUtils.noticeCreate( data.type, data.message, self.handlerSettings.is_public );
                },
                complete: function( jqXHR, textStatus ) {
                    $( document ).trigger( {
                        type: 'lastudio-ajax-handler-complete',
                        jqXHR: jqXHR,
                        textStatus: textStatus
                    } );

                    if ( settings.completeCallback && 'function' === typeof( settings.completeCallback ) ) {
                        settings.completeCallback( jqXHR, textStatus );
                    }
                }

            } );
        };

        /**
         * Send data ajax request
         *
         * @param  {Object} data User data
         * @return {Void}
         */
        self.sendData = function( data ) {
            var sendData = data || {};
            self.data = {
                'action': self.handlerSettings.action,
                'nonce': self.handlerSettings.nonce,
                'data': sendData
            };

            self.send();
        };

        /**
         * Send form serialized data
         * @param  {String} formId Form selector
         * @return {Void}
         */
        self.sendFormData = function( formId ) {
            var form = $( formId ),
                data;

            data = window.LaStudioHandlerUtils.serializeObject( form );

            self.sendData( data );
        };
    }

    window.LaStudioHandlerUtils = {
        /**
         * Rendering notice message
         *
         * @param  {String} type    Message type
         * @param  {String} message Message content
         * @return {Void}
         */
        noticeCreate: function( type, message, isPublicPage ) {
            var notice,
                rightDelta = 0,
                timeoutId,
                isPublic = isPublicPage || false;

            if ( ! message || 'true' === isPublic ) {
                return false;
            }

            notice = $( '<div class="lastudio-handler-notice ' + type + '"><span class="dashicons"></span><div class="inner">' + message + '</div></div>' );

            $( 'body' ).prepend( notice );
            reposition();
            rightDelta = -1 * ( notice.outerWidth( true ) + 10 );
            notice.css( { 'right': rightDelta } );

            timeoutId = setTimeout( function() {
                notice.css( { 'right': 10 } ).addClass( 'show-state' );
            }, 100 );
            timeoutId = setTimeout( function() {
                rightDelta = -1 * ( notice.outerWidth( true ) + 10 );
                notice.css( { right: rightDelta } ).removeClass( 'show-state' );
            }, 4000 );
            timeoutId = setTimeout( function() {
                notice.remove();
                clearTimeout( timeoutId );
            }, 4500 );

            function reposition() {
                var topDelta = 100;

                $( '.lastudio-handler-notice' ).each( function() {
                    $( this ).css( { top: topDelta } );
                    topDelta += $( this ).outerHeight( true );
                } );
            }
        },

        /**
         * Serialize form into
         *
         * @return {Object}
         */
        serializeObject: function( form ) {

            var self = this,
                json = {},
                pushCounters = {},
                patterns = {
                    'validate': /^[a-zA-Z][a-zA-Z0-9_-]*(?:\[(?:\d*|[a-zA-Z0-9_-]+)\])*$/,
                    'key':      /[a-zA-Z0-9_-]+|(?=\[\])/g,
                    'push':     /^$/,
                    'fixed':    /^\d+$/,
                    'named':    /^[a-zA-Z0-9_-]+$/
                };

            this.build = function( base, key, value ) {
                base[ key ] = value;

                return base;
            };

            this.push_counter = function( key ) {
                if ( undefined === pushCounters[ key ] ) {
                    pushCounters[ key ] = 0;
                }

                return pushCounters[ key ]++;
            };

            $.each( form.serializeArray(), function() {
                var k, keys, merge, reverseKey;

                // Skip invalid keys
                if ( ! patterns.validate.test( this.name ) ) {
                    return;
                }

                keys = this.name.match( patterns.key );
                merge = this.value;
                reverseKey = this.name;

                while ( undefined !== ( k = keys.pop() ) ) {

                    // Adjust reverseKey
                    reverseKey = reverseKey.replace( new RegExp( '\\[' + k + '\\]$' ), '' );

                    // Push
                    if ( k.match( patterns.push ) ) {
                        merge = self.build( [], self.push_counter( reverseKey ), merge );
                    } else if ( k.match( patterns.fixed ) ) {
                        merge = self.build( [], k, merge );
                    } else if ( k.match( patterns.named ) ) {
                        merge = self.build( {}, k, merge );
                    }
                }

                json = $.extend( true, json, merge );
            });

            return json;
        }
	}

}( jQuery, window.elementorFrontend ) );