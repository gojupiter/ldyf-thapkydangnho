( function( $ ) {

	'use strict';


	$(window).on('load', function () {

	    var _html = '<div class="elementor-panel-footer-sub-menu-item" data-device-mode="laptop"><i class="elementor-icon eicon-laptop" aria-hidden="true"></i><span class="elementor-title">'+ LaCustomBPFE.laptop.name +'</span><span class="elementor-description">'+LaCustomBPFE.laptop.text+'</span></div>';
        _html += '<div class="elementor-panel-footer-sub-menu-item" data-device-mode="tablet"><i class="elementor-icon eicon-device-tablet landscape" aria-hidden="true"></i><span class="elementor-title">'+LaCustomBPFE.tablet.name+'</span><span class="elementor-description">'+LaCustomBPFE.tablet.text+'</span></div>',
        _html += '<div class="elementor-panel-footer-sub-menu-item" data-device-mode="width800"><i class="elementor-icon eicon-device-width800" aria-hidden="true"></i><span class="elementor-title">'+LaCustomBPFE.width800.name+'</span><span class="elementor-description">'+LaCustomBPFE.width800.text+'</span></div>';


        $('#elementor-panel-footer-responsive .elementor-panel-footer-sub-menu-wrapper .elementor-panel-footer-sub-menu-item[data-device-mode="tablet"]').replaceWith(_html);

    });

	var LaStudioElementsEditor = {

		activeSection: null,

		editedElement: null,

		init: function() {
			elementor.channels.editor.on( 'section:activated', LaStudioElementsEditor.onAnimatedBoxSectionActivated );

			window.elementor.on( 'preview:loaded', function() {
				elementor.$preview[0].contentWindow.LaStudioElementsEditor = LaStudioElementsEditor;

				LaStudioElementsEditor.onPreviewLoaded();
			});

			if(typeof window.elementorPro === "undefined"){
                elementor.hooks.addFilter('editor/style/styleText', LaStudioElementsEditor.addCustomCss);
                elementor.settings.page.model.on('change', LaStudioElementsEditor.addPageCustomCss);
                elementor.on('preview:loaded', LaStudioElementsEditor.addPageCustomCss);
			}

		},

		onAnimatedBoxSectionActivated: function( sectionName, editor ) {
			var editedElement = editor.getOption( 'editedElementView' ),
				prevEditedElement = window.LaStudioElementsEditor.editedElement;

			if ( prevEditedElement && 'lastudio-animated-box' === prevEditedElement.model.get( 'widgetType' ) ) {

				prevEditedElement.$el.find( '.lastudio-animated-box' ).removeClass( 'flipped' );
				prevEditedElement.$el.find( '.lastudio-animated-box' ).removeClass( 'flipped-stop' );

				window.LaStudioElementsEditor.editedElement = null;
			}

			if ( 'lastudio-animated-box' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.LaStudioElementsEditor.editedElement = editedElement;
			window.LaStudioElementsEditor.activeSection = sectionName;

			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( sectionName );

			if ( isBackSide ) {
				editedElement.$el.find( '.lastudio-animated-box' ).addClass( 'flipped' );
				editedElement.$el.find( '.lastudio-animated-box' ).addClass( 'flipped-stop' );
			} else {
				editedElement.$el.find( '.lastudio-animated-box' ).removeClass( 'flipped' );
				editedElement.$el.find( '.lastudio-animated-box' ).removeClass( 'flipped-stop' );
			}
		},

		onPreviewLoaded: function() {
			var elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.hooks.addAction( 'frontend/element_ready/lastudio-dropbar.default', function( $scope ){
				$scope.find( '.lastudio-dropbar-edit-link' ).on( 'click', function( event ) {
					window.open( $( this ).attr( 'href' ) );
				} );
			} );

            function makeImageAsLoaded( element ){
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
                if (element.getAttribute('data-background-image')) {
                    element.style.backgroundImage = 'url("' + element.getAttribute('data-background-image') + '")';
                }
                element.setAttribute('data-element-loaded', true);
                if($(element).hasClass('jetpack-lazy-image')){
                    $(element).addClass('jetpack-lazy-image--handled');
                }
            }

            elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
                $('.la-lazyload-image', $scope).each(function () {
                    makeImageAsLoaded(this);
                });
            } );
		},

        addPageCustomCss: function () {
            var customCSS = elementor.settings.page.model.get('custom_css');

            if (customCSS) {
                customCSS = customCSS.replace(/selector/g, '.elementor-page-' + elementor.config.document.id);
                elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
            }
        },

        addCustomCss: function (css, view) {
            var model = view.getEditModel(),
                customCSS = model.get('settings').get('custom_css');

            if (customCSS) {
                css += customCSS.replace(/selector/g, '.elementor-element.elementor-element-' + view.model.id);
            }

            return css;
        }
	};

	$( window ).on( 'elementor:init', LaStudioElementsEditor.init );

	window.LaStudioElementsEditor = LaStudioElementsEditor;

}( jQuery ) );


( function( $ ) {

    $( window ).on( 'load', function() {

        draggablePanel(); // load function draggable panel

        loadFepSettings(); // load the setting FEP and action function

        $('#elementor-vertical-mode-switcher-preview-input').on('click', function(e) {
            $('#elementor-panel').toggleClass('active');
        });

        $('#elementor-mode-switcher').click(function() {
            $('#elementor-panel').css('left', '').removeClass('active');
            $('#elementor-vertical-mode-switcher-preview-input').prop('checked', false);
        });

        $('#elementor-panel-header-menu-button, #elementor-panel-header-add-button').on('click', function (e) {
            $('#elementor-panel').removeClass('active');
            $('#elementor-vertical-mode-switcher-preview-input').prop('checked', false);
        });
    });

    function draggablePanel() {
        $("#elementor-panel").draggable({
            snap: "#elementor-preview",
            opacity: 0.9,
            cancel: ".not-draggable",
            addClasses: false,
            containment: "window",
            snapMode: "inner",
            snapTolerance: 25,
            start: function () {

            },
            stop: function (event, ui) {
            	if(ui.position.left == 0){
                    $('#elementor-preview').css('left', '');
				}
				else{
                    $('#elementor-preview').css('left', '0');
				}
            }
        });
    }
    function loadFepSettings() {
        if ( !$( "#lastudio-elementor-collapse-vertical-panel" ).hasClass( "lastudio-elementor-collapse-vertical-panell-wrapper" ) ) {
            $("#elementor-panel-header #elementor-panel-header-menu-button").after('<div id="lastudio-elementor-collapse-vertical-panel" class="lastudio-elementor-collapse-vertical-panel-wrapper"><input type="checkbox" id="elementor-vertical-mode-switcher-preview-input"><i class="dlicon arrows-1_minimal-down"></i></div>');
        }
        $("#elementor-panel" ).draggable( "enable" );
        $('#elementor-panel-footer,#elementor-panel-content-wrapper').addClass('not-draggable');
    }

} )( jQuery );

(function( $ ) {

    'use strict';

    var LaStudioTabsEditor = {

        modal: false,

        init: function() {
            window.elementor.on( 'preview:loaded', LaStudioTabsEditor.onPreviewLoaded );
        },

        onPreviewLoaded: function() {
            var $previewContents = window.elementor.$previewContents,
                elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

            elementorFrontend.hooks.addAction( 'frontend/element_ready/lastudio-tabs.default', function( $scope ){
                $scope.find( '.lastudio-tabs__edit-cover' ).on( 'click', LaStudioTabsEditor.showTemplatesModal );
                $scope.find( '.lastudio-tabs-new-template-link' ).on( 'click', function( event ) {
                    window.location.href = $( this ).attr( 'href' );
                } );
            } );

            elementorFrontend.hooks.addAction( 'frontend/element_ready/lastudio-accordion.default', function( $scope ){
                $scope.find( '.lastudio-toggle__edit-cover' ).on( 'click', LaStudioTabsEditor.showTemplatesModal );
                $scope.find( '.lastudio-toogle-new-template-link' ).on( 'click', function( event ) {
                    window.location.href = $( this ).attr( 'href' );
                } );
            } );

            elementorFrontend.hooks.addAction( 'frontend/element_ready/lastudio-switcher.default', function( $scope ){
                $scope.find( '.lastudio-switcher__edit-cover' ).on( 'click', LaStudioTabsEditor.showTemplatesModal );
                $scope.find( '.lastudio-switcher-new-template-link' ).on( 'click', function( event ) {
                    window.location.href = $( this ).attr( 'href' );
                } );
            } );

            LaStudioTabsEditor.getModal().on( 'hide', function() {
                window.elementor.reloadPreview();
            });
        },

        showTemplatesModal: function() {
            var editLink = $( this ).data( 'template-edit-link' );

            LaStudioTabsEditor.showModal( editLink );
        },

        showModal: function( link ) {
            var $iframe,
                $loader;

            LaStudioTabsEditor.getModal().show();

            $( '#lastudio-tabs-template-edit-modal .dialog-message').html( '<iframe src="' + link + '" id="lastudio-tabs-edit-frame" width="100%" height="100%"></iframe>' );
            $( '#lastudio-tabs-template-edit-modal .dialog-message').append( '<div id="lastudio-tabs-loading"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-boxes"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div></div><div class="elementor-loading-title">Loading</div></div></div>' );

            $iframe = $( '#lastudio-tabs-edit-frame');
            $loader = $( '#lastudio-tabs-loading');

            $iframe.on( 'load', function() {
                $loader.fadeOut( 300 );
            } );
        },

        getModal: function() {

            if ( ! LaStudioTabsEditor.modal ) {
                this.modal = elementor.dialogsManager.createWidget( 'lightbox', {
                    id: 'lastudio-tabs-template-edit-modal',
                    closeButton: true,
                    hide: {
                        onBackgroundClick: false
                    }
                } );
            }

            return LaStudioTabsEditor.modal;
        }

    };

    $( window ).on( 'elementor:init', LaStudioTabsEditor.init );

})( jQuery );

(function( $ ) {

    'use strict';


    var LaStudioQueryControl = {

        modal: false,

        init: function() {
            window.elementor.on( 'preview:loaded', LaStudioQueryControl.onPreviewLoaded );
        },

        onPreviewLoaded: function() {
            window.elementor.addControlView('lastudio_query', window.elementor.modules.controls.Select2.extend({

                cache: null,

                isTitlesReceived: false,

                getSelect2Placeholder: function getSelect2Placeholder() {
                    return {
                        id: '',
                        text: elementor.translate('All')
                    };
                },

                getSelect2DefaultOptions: function getSelect2DefaultOptions() {
                    var self = this;

                    return jQuery.extend(elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
                        ajax: {
                            transport: function transport(params, success, failure) {
                                var data = {
                                    q: params.data.q,
                                    filter_type: self.model.get('filter_type'),
                                    object_type: self.model.get('object_type'),
                                    include_type: self.model.get('include_type')
                                };

                                console.log(data);

                                return elementor.ajax.addRequest('lastudio_panel_posts_control_filter_autocomplete', {
                                    data: data,
                                    success: success,
                                    error: failure
                                });
                            },
                            data: function data(params) {
                                return {
                                    q: params.term,
                                    page: params.page
                                };
                            },
                            cache: true
                        },
                        escapeMarkup: function escapeMarkup(markup) {
                            return markup;
                        },
                        minimumInputLength: 1
                    });
                },

                getValueTitles: function getValueTitles() {
                    var self = this,
                        ids = this.getControlValue(),
                        filterType = this.model.get('filter_type');

                    if (!ids || !filterType) {
                        return;
                    }

                    if (!_.isArray(ids)) {
                        ids = [ids];
                    }

                    elementorCommon.ajax.loadObjects({
                        action: 'lastudio_query_control_value_titles',
                        ids: ids,
                        data: {
                            filter_type: filterType,
                            object_type: self.model.get('object_type'),
                            unique_id: '' + self.cid + filterType
                        },
                        before: function before() {
                            self.addControlSpinner();
                        },
                        success: function success(data) {
                            self.isTitlesReceived = true;

                            self.model.set('options', data);

                            self.render();
                        }
                    });
                },

                addControlSpinner: function addControlSpinner() {
                    this.ui.select.prop('disabled', true);
                    this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>');
                },

                onReady: function onReady() {
                    // Safari takes it's time to get the original select width
                    setTimeout(elementor.modules.controls.Select2.prototype.onReady.bind(this));

                    if (!this.isTitlesReceived) {
                        this.getValueTitles();
                    }
                }
            }));
        }

    };

    $( window ).on( 'elementor:init', LaStudioQueryControl.init );

})( jQuery );