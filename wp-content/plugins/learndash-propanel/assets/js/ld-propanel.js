var learnDashProPanel = jQuery( function ( $ ) {

	var widgetElements = $( 'div[id^="learndash-propanel"]' );
	var widgetObjects = {};
	var currentFilters = {
		type:  null,
		id: null,
		courseStatus: null,
		search: null,
	};
	var selectedUserIds = [];
	var allUserIds = [];
	var proPanelTable;
	var containerType;

	initialize();

	/**
	 * Initialize ProPanel
	 */
	function initialize() {
		$( document ).on( 'proPanel.templateLoaded', function ( event, template ) {
			if ( template == 'reporting' ) {
				propanelToggles();
				loadSelect2s();
				requireEmailFields();
				$( document ).on( 'proPanel.setSelectedUsers', maybeHideEmailBox );
				$( document ).on( 'proPanel.setSelectedUsers', updateSelectedCount );
				$( '#learndash-propanel-reporting' ).on( 'click tap', '#propanel-send-email', emailUsers );

				$( document ).on( 'click', 'button.filter', filterReporting );
				$( document ).on( 'click', 'button.reset', resetReporting );
				$( document ).on( 'click', 'button.download-reporting', downloadReporting );
			}

			if ( template == 'course-reporting' ) {
				loadReportingTable();
				setSelectedUsers();

				// change selected users when checkbox is checked or searched
				proPanelTable.on( 'change', 'tbody tr :checkbox', setSelectedUsers );
				proPanelTable.on( 'filterEnd', setSelectedUsers );
			}

			if ( template == 'user-reporting' ) {
				loadReportingTable();
				setSelectedUsers();
			}

			if ( template == 'activity' ) {
				$( document ).on( 'proPanel.filterChanged', loadActivity );
			}

			if ( ( template == 'activity_rows' ) || ( template == 'activity' ) ) {
				$( '.activity-item.pagination > a' ).on( 'click', processActivityPagination );
				$( document ).on( 'click', 'button.download-activity', downloadActivity );
				
			}

			if ( template == 'progress-chart' ) {
				$( document ).on( 'proPanel.filterChanged', getProgessChartsData );
			}

			if ( template == 'trends' ) {
				trendsBarChart();
			}
		});

		loadWidgets();
		setContainerType();
	}

	/**
	 * Initialize all widgets
	 */
	function loadWidgets() {
		$.each( widgetElements, function () {
			
			if (( jQuery('#dashboard-widgets').length ) || ( jQuery('body.dashboard_page_propanel-reporting').length ))  {
				var widget_id = $( this ).attr( 'id' ).replace( 'learndash-propanel-', '' );
				
				widgetObjects[ widget_id ] = $( this );
				$( this ).addClass('learndash-propanel-' + widget_id);
			
				loadTemplate( widgetObjects[ widget_id ].find( '.inside' ), widget_id );
			} else {
				var widget_id = $( this ).attr( 'id' ).replace( 'learndash-propanel-', '' );
				
				var template_args = {};
				
				var widget_substr = widget_id.substr(0, 18);
				if (widget_substr == 'activity-shortcode' ) {
					
					widgetObjects[ widget_id ] = $( this );
					$( this ).addClass('learndash-propanel-' + widget_id);
					
					var filters = $( this ).data('filters');
					if (( typeof filters !== 'undefined' ) && ( filters != '')) {
						currentFilters = filters;
					
						if (( typeof filters['per_page'] !== 'undefined' ) && ( filters['per_page'] != '')) {
							template_args.per_page = filters['per_page'];
						}
					}
					
					loadTemplate( widgetObjects[ widget_id ], 'activity', template_args );
				}
			}
		} );
	}

	function setContainerType () {
		var container = $( '#learndash-propanel-reporting' );

		if ( container.hasClass( 'single-view' ) ) {
			containerType = 'full';
		} else {
			containerType = 'widget';
		}
	}

	/**
	 * Load a template via AJAX
	 *
	 * If data comes along with the response that other areas of propanel need to use, add it
	 * Add/remove a spinner while loading
	 *
	 * @param element
	 * @param template
	 * @param args
	 */
	function loadTemplate( element, template, args ) {
		showSpinner( element );

		// For Activity and Activity_rows we want to pass the per_page size to the server. 
		if ( ( template == 'activity' ) || ( template == 'activity_rows' ) ) {
			
			if ( typeof args === 'undefined' ) {
				args = {};
			}
			
			if ( jQuery('#dashboard-widgets').length ) {
			
				var per_page = jQuery('select#ld-propanel-pagesize').val();
				if ( typeof per_page !== 'undefined' ) {
					args['per_page'] = per_page;
				}
			}
		}
		
		
		$.ajax( {
			url: ld_propanel.ajaxurl,
			method: 'get',
			dataType: 'json',
			data: {
				'action': 'learndash_propanel_template',
				'template': template,
				'filters': currentFilters,
				'container_type': containerType,
				'args' : args,
				'nonce': ld_propanel.nonce,
			},
			success: function ( response ) {
				if ( response.hasOwnProperty( 'success' ) ) {
					element.html( response.data.output );
					$( document ).trigger( 'proPanel.templateLoaded', [ template ] );
				}
			},
			complete: function() {
				hideSpinner( element );
			}
		} );
	}

	function showSpinner( element ) {
		var spinnerExists = element.parents('.postbox').find( 'loading' );

		if ( spinnerExists.length ) {
			return;
		}

		var widgetTitle = element.parents('.postbox').find( 'h2.hndle' );
		widgetTitle.append( '<img src="/wp-admin/images/spinner.gif" class="loading">' );
	}

	function hideSpinner( element ) {
		setTimeout( function() {
			element.parents('.postbox').find( '.loading' ).remove();
		}, 500 );
	}

	/**
	 * Initialize Tablesorter Reporting Tables
	 */
	function loadReportingTable() {

		var pagesize = jQuery('select#ld-propanel-pagesize').val();
		if ( typeof pagesize === 'undefined' ) 
			pagesize = 10;

		proPanelTable = widgetObjects.reporting.find( '.tablesorter' );
		widgetObjects.reporting.find( '.download' ).on( 'click', function ( event ) {
			//proPanelTable.trigger( 'outputTable' );
			event.preventDefault();
		} );

		proPanelTable.tablesorter( {
			checkboxClass: 'checked', // default setting
			widthFixed: false,
			widgets: [ "zebra", "filter", "uitheme", "output" ],
			widgetOptions: {
				filter_external: '.tablesorter-search',
				filter_columnFilters: false,
				filter_filteredRow: 'filtered',
				filter_reset: '.reset'
			}
		} ).tablesorterPager( {
			ajaxUrl: ld_propanel.ajaxurl + '?ld_pp_page={page}&ld_pp_size={size}&{filterList:ld_pp_search}&{sortList:ld_pp_sort}',
			ajaxObject: {
				dataType: 'json',
				method: 'get',
				data: {
					'action': 'learndash_propanel_get_filter_result_rows',
					'nonce' : ld_propanel.nonce,
					'filters' : currentFilters,
					'container_type' : containerType,
				}
			},
			ajaxProcessing: function( data ) {
				// We need the search term to pass in filters, tablesorter doesn't give us this automatically,
				// so let's grab it
				var search = $('.tablesorter-search');
				if ( typeof search !== 'undefined' ) {
					currentFilters.search = search.val();

					search.on( 'change', function() {
						$( document ).trigger( 'proPanel.filterChanged' );
					});
				}
				
				return processAjaxDataForTablesorter( data );
			},
			container: $( '.pager' ),
			output: '{page} / {totalPages} ({totalRows})',
			page: 0,
			savePages : false,
			pageReset: 1,
			size: pagesize,
			cssNext: '.next',
			cssPrev: '.prev',
			cssFirst: '.first',
			cssLast: '.last',
			cssGoto: '.go-to-page',
			cssPageDisplay: '.pagedisplay',
			cssPageSize: '.pagesize'
		} );
	}

	/**
	 * Process returned ajax data for Tablesorter
	 *
	 * @param data
	 * @returns {*[]}
     */
	function processAjaxDataForTablesorter( data ) {
		/**
		 * Grab the ID's of all users regardless of paging
		 */
		if ( data && data.hasOwnProperty( 'user_ids' ) ) {
			allUserIds = data.user_ids;
			setSelectedUsers();
		}

		show_hide_FilterDownloadButton( data );


		/**
		 * This entire block matches up the data returned to order of the columns
		 */
		if ( data && data.hasOwnProperty( 'rows' ) ) {
			var index, rowCount, row, column, dataRows = data.rows,
				totalRows = data.total_rows,
				headers = data.headers,
				headerXref = headers, // cross-reference to match JSON key within data (no spaces)
				rows = [],
				len = dataRows.length; // len should match pager set size (c.size)

			for ( rowCount = 0; rowCount < len; rowCount ++ ) {
				row = [];

				for ( column in dataRows[ rowCount ] ) {
					if ( typeof( column ) === "string" ) {
						index = $.inArray( column, headerXref ); // match the key with the header to get the proper column index

						if ( index >= 0 ) {
							row[ index ] = dataRows[ rowCount ][ column ];
						}
					}
				}

				rows.push( row );
			}

			return [ totalRows, rows, headers ];
		}
	}

	/**
	 * Initialize Select2 dropdowns
	 */
	function loadSelect2s() {
		var currentFilterSelector;

		var typeFilter = $( '#learndash-propanel-reporting .filter-type-select' );
		var userFilter = $( '#learndash-propanel-reporting .user.select2' );
		var courseFilter = $( '#learndash-propanel-reporting .course.select2' );
		var statusFilter = $( '#learndash-propanel-reporting .course.course-status' );
		var reportingActions = $( '#learndash-propanel-reporting .reporting-actions' );
		//var reportingContainer = $( '#learndash-propanel-reporting .propanel-reporting' );

		show_hide_FilterStatusSelector();
		show_hide_FilterActionButton();
		show_hide_FilterDownloadButton();
		
		// The Type Filter
		var select2_type_filter = typeFilter.select2({ containerCssClass : "filter-type-select2" });
//		if ( ( select2_type_filter ) && ( typeof select2_type_filter === 'object' ) ) {
//			select2_type_filter.data('select2').$container.addClass('filter-type-select2');
//		}
		
		
		// The User Filter
		var select2_user_filter = userFilter.select2( {
			ajax: ajaxGetSelect2Data( 'learndash_propanel_user_search' ),
			containerCssClass : "filter-user-select2"
		} );
		//if ( ( select2_user_filter ) && ( typeof select2_user_filter === 'object' ) ) {
		//	select2_user_filter.data('select2').$container.addClass('filter-user-select2');
		//}

		
		// The Course Filter
		var select2_course_filter = courseFilter.select2( {
			ajax: ajaxGetSelect2Data( 'learndash_propanel_course_search' ),
			containerCssClass : "filter-course-select2"
		} );
		//if ( ( select2_course_filter ) && ( typeof select2_course_filter === 'object' ) ) {
		//	select2_course_filter.data('select2').$container.addClass('filter-course-select2');
		//}

		// The Status Filter
		var select2_status_filter = statusFilter.select2({ containerCssClass : "filter-status-select2" });
		//if ( ( select2_status_filter ) && ( typeof select2_status_filter === 'object' ) ) {
		//	select2_status_filter.data('select2').$container.addClass('filter-type-select2');
		//}


		$( '.filter-user-select2' ).hide();
		$( '.filter-course-select2' ).hide();


		typeFilter.on( 'change', function (e) {			
			currentFilters.type = $( this ).val();;
			
			jQuery('.filter-section-users-courses .filter-user-select2').hide();
			jQuery('.filter-section-users-courses .filter-course-select2').hide();
			if ( currentFilters.type == 'course' ) {
				jQuery('.filter-section-users-courses .filter-course-select2').show();
				
				currentFilters.id = courseFilter.val();
			} else if ( currentFilters.type == 'user' ) {
				jQuery('.filter-section-users-courses .filter-user-select2').show();
				currentFilters.id = userFilter.val();
			}
			
			show_hide_FilterStatusSelector();
			show_hide_FilterActionButton();
			show_hide_FilterDownloadButton();
			
		});

		userFilter.on( 'change', function () {
			currentFilters.id = $( this ).val();

			// We set the courseStatus filter because it will default to the 'all' status option and thus will 
			// enable the filter button to be clicked. 
			currentFilters.courseStatus = $( statusFilter ).val();

			show_hide_FilterStatusSelector();
			show_hide_FilterActionButton();
			show_hide_FilterDownloadButton();
		} );

		courseFilter.on( 'change', function () {
			currentFilters.id = $( this ).val();

			// We set the courseStatus filter because it will default to the 'all' status option and thus will 
			// enable the filter button to be clicked. 
			currentFilters.courseStatus = $( statusFilter ).val();

			show_hide_FilterStatusSelector();
			show_hide_FilterActionButton();
			show_hide_FilterDownloadButton();
		} );

		statusFilter.on( 'change', function () {
			currentFilters.courseStatus = $( this ).val();
			show_hide_FilterActionButton();
			show_hide_FilterDownloadButton();
			
		} );
	}
	
	
	function show_hide_FilterStatusSelector() {
		if ( ( currentFilters.id ) && ( currentFilters.id != '' ) )
			jQuery('.filter-section-status').show();
		else 
			jQuery('.filter-section-status').hide();
	}
	
	function show_hide_FilterActionButton() {
		if (( currentFilters.type ) && ( currentFilters.type != '' ) && ( currentFilters.id ) && ( currentFilters.id != '' ) && ( currentFilters.courseStatus ) && ( currentFilters.courseStatus != '' )) {
			jQuery('#table-filters button.filter').attr('disabled', false);
		} else {
			jQuery('#table-filters button.filter').attr('disabled', true);
		}
	}

	function show_hide_FilterDownloadButton( data ) {

		// This function relies on the data from processAjaxDataForTablesorter function. Speficically the 'total_rows' element. 
		// If not provided will cause the button to be disabled. 
		if ( ( typeof data !== 'undefined' ) && ( typeof data.total_rows !== 'undefined' ) && ( parseInt(data.total_rows) != 0 ) ) {
			jQuery('#table-filters button.download-reporting').attr('disabled', false);
		} else {
			jQuery('#table-filters button.download-reporting').attr('disabled', true);
		}
	}



	/**
	 * Populate Select2 dropdowns with data
	 *
	 * @param action
	 * @returns {{url, dataType: string, method: string, delay: number, data: data, processResults: processResults}}
	 */
	function ajaxGetSelect2Data( action ) {
		return {
			url: ld_propanel.ajaxurl,
			dataType: 'json',
			method: 'get',
			delay: 1000,
			cache: true,
			data: function ( params ) {
				return {
					action: action,
					search: params.term || '',
					page: params.page || 1,
					nonce: ld_propanel.nonce,
				};
			},
			processResults: function ( response, params ) {
				params.page = params.page || 1;

				return {
					results: response.data.items,
					pagination: {
						more: ( params.page * 10 ) < response.data.total
					}
				};
			},
		}
	}

	/**
	 * Toggles
	 */
	function propanelToggles() {
		widgetObjects.reporting.on( 'click tap', '.section-toggle', function () {
			var $showThis = $( this ).attr( 'href' );
			$( this ).toggleClass( 'active' ).siblings().removeClass( 'active' );
			$( '' + $showThis + '' ).toggleClass( 'display' ).siblings().removeClass( 'display' );
			return false;
		} );

		widgetObjects.reporting.on( 'click tap', '.close', function () {
			$( '.section-toggle' ).removeClass( 'active' );
			$( '.toggle-section' ).removeClass( 'display' );
			return false;
		} );

	}

	/**
	 * Set Selected Users
	 *
	 * If User, set the single user
	 * If Courses, set all the filtered users.  If users are checked, set those as long
	 * as they are filtered.
	 */
	function setSelectedUsers() {
		var checked;
		var results = [];

		if ( currentFilters.type == 'user' ) {
			selectedUserIds = [];
			selectedUserIds.push( $( '.user.select2' ).val() );
		}

		if ( currentFilters.type == 'course' ) {
			checked = proPanelTable.find( 'tbody tr :checked' );

			if ( checked.length ) {
				results = checked.parents( 'tr' ).not( '.filtered' );
			} else {
				selectedUserIds = allUserIds;
			}

			if ( results.length ) {
				selectedUserIds = results.map( function () {
					return $( this ).find( 'input:first-child' ).attr( 'data-user-id' );
				} ).get();
			}
		}

		// Let everyone know that we've set selected user(s)
		$( document ).trigger( 'proPanel.setSelectedUsers' );
	}

	/**
	 * Email Box only shows when we have users selected
	 */
	function maybeHideEmailBox() {
		if ( selectedUserIds.length ) {
			$( '.email .no-results' ).hide();
			$( '.email .results' ).show();
		} else {
			$( '.email .no-results' ).show();
			$( '.email .results' ).hide();
		}
	}

	/**
	 * Update selected user count in button
	 */
	function updateSelectedCount() {
		$( '#propanel-send-email' ).find( 'span' ).html( selectedUserIds.length );
	}

	/**
	 * Disable Send button unless Subject/Message is not empty
	 */
	function requireEmailFields() {
		$( '#learndash-propanel-reporting #email' ).on( 'keyup', '.subject, .message', function () {
			var subject = $( '#learndash-propanel-reporting #email .subject' ).val();
			var message = $( '#learndash-propanel-reporting #email .message' ).val();
			var sendButton = $( '#propanel-send-email' );

			if ( subject == '' || message == '' ) {
				sendButton.prop( 'disabled', true );
			} else {
				sendButton.prop( 'disabled', false );
			}
		} );
	}

	/**
	 * Email Users
	 *
	 * If rows are checked, grab only those User ID's for rows that are checked and not filtered
	 * If no rows are checked, grab all User ID's for rows that are not filtered
	 */
	function emailUsers() {
		var emailContainer, subject, message, sending, sent, sendButton;

		emailContainer = $( '#learndash-propanel-reporting #email' );
		subject = emailContainer.find( '.subject' ).val();
		message = emailContainer.find( '.message' ).val();

		if ( ! selectedUserIds ) {
			return;
		}

		sending = emailContainer.find( '.sending' );
		sent = emailContainer.find( '.sent' );
		sendButton = emailContainer.find( '#propanel-send-email' );

		sending.show();
		sendButton.prop( 'disabled', true );

		$.ajax( {
			url: ld_propanel.ajaxurl,
			method: 'post',
			dataType: 'json',
			data: {
				'action': 'learndash_propanel_email_users',
				'user_ids': selectedUserIds.join(),
				'subject': subject,
				'message': message,
				'nonce': ld_propanel.nonce,
			},
			success: function ( response ) {
				if ( response.success ) {
					sent.fadeIn();
					setTimeout( function () {
						sent.fadeOut();
						sendButton.prop( 'disabled', false );
					}, 3000 );
				} else {
					alert( response.data.message );
				}
			},
			error: function () {
				alert( ld_propanel_reporting.ajax_email_error );
			},
			complete: function () {
				sending.hide();
			}
		} );
	}

	/**
	 * Load Activity based on current filters
	 */
	function loadActivity() {
		if ( $( document.activeElement ).hasClass( 'course-status' ) ) {
			return;
		}
		
		if ( jQuery('#dashboard-widgets').length ) {
			var activityContainer = $( '#learndash-propanel-activity' ).find( '.inside' );
		} else {
			//$.each( widgetElements, function (widget_id, widgetElement) {
				//console.log('widget_id[%o] widgetElement[%o]', widget_id, widgetElement);
				//});
			//var activityContainer = $( '.learndash-propanel-activity' ).find( '.inside' );
		}
		if ( typeof activityContainer !== 'undefined' ) {
			loadTemplate( activityContainer, 'activity_rows' );
		}
	}

	/**
	 * Process Activity Pagination
	 */
	function processActivityPagination( event ) {
		event.preventDefault();

		template_args = {};
		

		var thisPagination = $(this);
		template_args.paged = thisPagination.attr( 'data-page' );
		
		if ( jQuery('#dashboard-widgets').length ) {
			var activityContainer = $( '#learndash-propanel-activity' ).find( '.inside' );
		} else {
			var activityContainer = $( event.currentTarget ).parents( 'div.learndash-propanel-activity' );
			
			if ( typeof activityContainer !== 'undefined' ) {
				var filters = $(activityContainer).data('filters');
				if (( typeof filters !== 'undefined' ) && ( filters != '')) {
					currentFilters = filters;
					if (( typeof filters['per_page'] !== 'undefined' ) && ( filters['per_page'] != '')) {
						template_args.per_page = filters['per_page'];
					}
				}
			}
			
		}

		if ( typeof activityContainer !== 'undefined' ) {
			loadTemplate( activityContainer, 'activity_rows', template_args );
		}
	}

	/**
	 * Load Trends Chart
	 */
	function trendsBarChart() {
		var ctxProPanelTrends = document.getElementById( "proPanelTrends" ).getContext( "2d" );
		var data = {
			labels: [ "1", "2", "3", "4", "5", "6", "7", ],
			datasets: [
				{
					label: "Week",
					backgroundColor: "#2D97C5",
					borderWidth: 1,
					hoverBackgroundColor: "#2D97C5",
					data: [ 65, 59, 80, 81, 56, 55, 40 ],
				},
				{
					label: "Month",
					backgroundColor: "#5BAED2",
					borderWidth: 1,
					hoverBackgroundColor: "#5BAED2",
					data: [ 40, 34, 65, 66, 36, 21, 10 ],
				},
				{
					label: "6 Months",
					backgroundColor: "#8AC5DF",
					borderWidth: 1,
					hoverBackgroundColor: "#8AC5DF",
					data: [ 25, 27, 55, 44, 25, 10, 8 ],
				}
			]
		};
		var options = {
			scales: {
				yAxes: [
					{
						position: "left",
						scaleLabel: {
							display: true,
							labelString: "# of Enrollments",
							fontColor: "#D3D6D7"
						},
						ticks: {
							beginAtZero: true,
						},
						gridLines: {
							zeroLineColor: "#eeeeee",
							color: "#eeeeee"
						}
					}
				],
				xAxes: [
					{
						position: "bottom",
						scaleLabel: {
							display: true,
							labelString: "Courses",
							fontColor: "#D3D6D7"
						},
						gridLines: {
							display: false,
							zeroLineColor: "#eeeeee",
							color: "#eeeeee"
						}
					}
				]
			},
			tooltips: {
				mode: 'label',
				backgroundColor: "#3B3E44",
				fontFamily: "'Open Sans',sans-serif",
				titleMarginBottom: 15,
				titleFontSize: 18,
				cornerRadius: 4,
				bodyFontSize: 14,
				xPadding: 10,
				yPadding: 15,
				bodySpacing: 10
			},
			legend: {
				display: true,
				labels: {
					boxWidth: 14,
					fontFamily: "'Open Sans',sans-serif"
				}
			}
		};

		new Chart( ctxProPanelTrends, {
			type: 'bar',
			data: data,
			options: options
		} );
	}

	/**
	 * Get data to display progress donut charts based on current filters
	 *
	 * Don't run when the course-status dropdown changes or if current filter type is user
	 */
	function getProgessChartsData( event ) {
		if ( $( document.activeElement ).hasClass( 'course-status' ) ) {
			return;
		}

		if ( currentFilters.type == 'user' ) {
			return;
		}

		var progressChartsContainer = $( '#learndash-propanel-progress-chart' ).find( '.inside' );

		loadTemplate( progressChartsContainer, 'progress-chart-data' );

		$.ajax( {
			url: ld_propanel.ajaxurl,
			method: 'get',
			dataType: 'json',
			data: {
				'action': 'learndash_propanel_get_progress_charts_data',
				'filters': currentFilters,
				'nonce': ld_propanel.nonce,
			},
			success: function ( response ) {
				if ( response && response.hasOwnProperty( 'success') ) {
					setTimeout(function(){
					    buildProgressCharts( response.data );
					}, 500);
					//buildProgressCharts( response.data );
				}
			}
		} );
	}

	/**
	 * Build progress donut charts based on returned ajax data
	 * @param data
     */
	function buildProgressCharts( data ) {
		if ( typeof data.all_progress !== 'undefined' ) {
			drawProgressAllChart( data.all_progress );
		}

		if ( typeof data.all_percentages !== 'undefined' ) {
			drawProgressAllPercentagesChart( data.all_percentages );
		}
	}

	function drawProgressAllChart( chart_data ) {
		if (( typeof chart_data.data.datasets !== 'undefined' ) && ( chart_data.data.datasets.length > 0 )) {
			jQuery('#proPanelProgressAllDefaultMessage').hide();

			var ctxProPanelProgressAll = document.getElementById( "proPanelProgressAll" ).getContext( "2d" );
			if ( typeof ctxProPanelProgressAll !== 'undefined' ) {
				var progressAllData = {
					labels: [],
					datasets: []
				};

				if ( typeof chart_data.data.labels !== 'undefined' ) {
					progressAllData.labels = chart_data.data.labels;
				}

				if ( typeof chart_data.data.datasets !== 'undefined' ) {
					progressAllData.datasets = chart_data.data.datasets;
				}

				var progressAllOptions = {};
				if ( typeof chart_data['options'] !== 'undefined' ) {
					progressAllOptions = chart_data['options'];
				}
		
				new Chart( ctxProPanelProgressAll, {
					type: 'doughnut',
					data: progressAllData,
					options: progressAllOptions
				} );
			}
		} else {
			jQuery('#proPanelProgressAllDefaultMessage').show();
			jQuery('#proPanelProgressAll').hide();
			jQuery('#proPanelProgressAll').css('height', '0');
			jQuery('#proPanelProgressAll').css('width', '0');
		}
	}

	function drawProgressAllPercentagesChart( chart_data ) {

		if (( typeof chart_data.data.datasets !== 'undefined' ) && ( chart_data.data.datasets.length > 0 )) {
			jQuery('#proPanelProgressInMotionDefaultMessage').hide();

			var ctxProPanelProgressInMotion = document.getElementById( "proPanelProgressInMotion" ).getContext( "2d" );
			if ( typeof ctxProPanelProgressInMotion !== 'undefined' ) {

				var progressInMotionData = {
					labels: [],
					datasets: []
				};

				if ( typeof chart_data.data.labels !== 'undefined' ) {
					progressInMotionData.labels = chart_data.data.labels;
				}

				if ( typeof chart_data.data.datasets !== 'undefined' ) {
					progressInMotionData.datasets = chart_data.data.datasets;
				}

				var progressInMotionOptions = {};
				if ( typeof chart_data['options'] !== 'undefined' ) {
					progressInMotionOptions = chart_data['options'];
				}

				new Chart( ctxProPanelProgressInMotion, {
					type: 'doughnut',
					data: progressInMotionData,
					options: progressInMotionOptions
				} );
			}
		} else {
			jQuery('#proPanelProgressInMotionDefaultMessage').show();
			jQuery('#proPanelProgressInMotion').hide();
			jQuery('#proPanelProgressInMotion').css('height', '0');
			jQuery('#proPanelProgressInMotion').css('width', '0');
		}
	}	
	
	function downloadReporting(e) {
		e.stopImmediatePropagation();

		var data_template 	= $(e.target).attr('data-template');
		var data_slug 		= $(e.target).attr('data-slug');
		var data_nonce 		= $(e.target).attr('data-nonce');
		//var updateElement 	= $('span.status', e.target);
		var updateElement 	= e.target;
		
		if ( typeof data_template !== 'undefined' ) {
			
			jQuery(e.target).prop('disabled', true);
				
			var post_data = {
				'init': 1,
				'nonce': data_nonce,
				'slug': data_slug,
				'filters': currentFilters
			}
			
			loadActivityTemplate( data_template, post_data, updateElement );
		}
	}
	
	function filterReporting(e) {
		e.stopImmediatePropagation();

		if (( currentFilters.type ) && ( currentFilters.type != '' ) && ( currentFilters.id ) && ( currentFilters.id != '' ) && ( currentFilters.courseStatus ) && ( currentFilters.courseStatus != '' )) {
			
			var reportingContainer = $( '#learndash-propanel-reporting .propanel-reporting' );

			if ( currentFilters.type == 'course' ) {
				loadTemplate( reportingContainer, 'course-reporting' );
			} else if ( currentFilters.type == 'user' ) {
				loadTemplate( reportingContainer, 'user-reporting' );
			}
			
			$( document ).trigger( 'proPanel.filterChanged' );
		}
	}

	function resetReporting(e) {
		e.stopImmediatePropagation();
		window.location.reload(false); 
	}
	
	function loadReportingTemplate( template, args ) {
		$.ajax( {
			url: ld_propanel.ajaxurl,
			method: 'get',
			dataType: 'json',
			data: {
				'action': 'learndash_propanel_template',
				'template': template,
				'filters': currentFilters,
				'container_type': containerType,
				'args' : args,
				'nonce': ld_propanel.nonce,
			},
			success: function ( response ) {
				if ( response.hasOwnProperty( 'success' ) ) {
					if ( response.data.output != '' ) {
						window.location.href = response.data.output;
					}
				}
			},
			complete: function() {
			}
		});
	}
	
	function downloadActivity(e) {
		e.stopImmediatePropagation();
		var data_template 	= $(e.target).attr('data-template');
		var data_slug 		= $(e.target).attr('data-slug');
		var data_nonce 		= $(e.target).attr('data-nonce');

		//var updateElement 	= $('span.status', e.target);
		var updateElement 	= e.target;
		
		// If we are NOT running under the Dashboard we need to get the filters data from the parent element in order to properly run the AJAX
		if ( !jQuery('#dashboard-widgets').length ) {
			var activityContainer = $( e.currentTarget ).parents( 'div.learndash-propanel-activity' );
			
			if ( typeof activityContainer !== 'undefined' ) {
				var filters = $(activityContainer).data('filters');

				if (( typeof filters !== 'undefined' ) && ( filters != '')) {
					currentFilters = filters;
				}
			}
		}
		
		if ( typeof data_template !== 'undefined' ) {
			
			//jQuery('button.download-activity').prop('disabled', false);
			jQuery(updateElement).prop('disabled', true);
				
			var post_data = {
				'init': 1,
				'nonce': data_nonce,
				'slug': data_slug,
				//'filters': currentFilters
			}
			
			loadActivityTemplate( data_template, post_data, updateElement );
				
		}
	}
	
	function loadActivityTemplate( template, args, updateElement ) {
		
		$.ajax( {
			url: ld_propanel.ajaxurl,
			method: 'get',
			dataType: 'json',
			data: {
				'action': 'learndash_propanel_template',
				'template': template,
				'args' : args,
				'nonce': ld_propanel.nonce,
			},
			success: function ( response ) {
				if ( typeof response !== 'undefined' ) {
					if ( typeof response['data']['output'] !== 'undefined' ) {
						var reply_data = response['data']['output'];
						
						var total_count = 0;
						if ( typeof reply_data['data']['total_count'] !== 'undefined' )
							total_count = parseInt(reply_data['data']['total_count']);
				
						var result_count = 0;
						if ( typeof reply_data['data']['result_count'] !== 'undefined' ) 
							result_count = parseInt(reply_data['data']['result_count']);
				
						if ( result_count < total_count ) {
							
							// Update the progress meter
							if ( typeof updateElement !== 'undefined' ) {
								if (jQuery(updateElement).length) {
					
									if ( typeof reply_data['data']['progress_percent'] !== 'undefined' ) {
										var progress_percent = parseInt(reply_data['data']['progress_percent']);
										jQuery('span', updateElement).html(' '+progress_percent+'%');
									}
								}
							}
							
							loadActivityTemplate( template, reply_data['data'], updateElement );
						} else {
							// Re-enable the buttons
							jQuery(updateElement).prop('disabled', false);

							jQuery('span', updateElement).html('');
							
							if (( typeof reply_data['data']['report_download_link'] !== 'undefined' ) && ( reply_data['data']['report_download_link'] != '' )) {
								window.location.href = reply_data['data']['report_download_link'];
							}
						}
						
					}
				}
			},
			complete: function() {
			}
		});
	}
	
} );