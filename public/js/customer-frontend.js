(function( $ ) {
	'use strict';

	/**
	 * All of the code for your customer-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    
	 $(document).ready(function () {
	var table = $("#customerTicketList").DataTable({
		"bDestroy": true,
		"responsive": true,
		"bProcessing": true,
		"bServerSide": true,
		"sServerMethod": "POST",
		"sAjaxSource": 'http://planetwebzone.com/wc-plugin/wp-admin/admin-ajax.php',
		"fnServerParams": function (aoData) {
			aoData.push({
				"name": "action",
				"value": "ticketinfocustomer"
			});
			aoData.push({
				"name": "search_subject",
				"value": $("#search_subject").val()
			});
			aoData.push({
				"name": "search_status",
				"value": $("#customerTicketsearch_status").val()
			});
		},
		"pageLength": 50,
		"order": [
			[0, "desc"]
		],
		"columnDefs": [{
			"orderable": false,
			"targets": [1, 2, 3]
		}],
		"language": {
			"emptyTable": "Record not found",
			"search": '',
			"searchPlaceholder": 'Search..',
		},
		"scrollX": true,
		"bFilter": false,
		"bInfo": false
	});
	$('#customerTicketsearch_subject').on('keyup', function () {
		table.draw();
	});
	$('#customerTicketsearch_status').on('change', function () {
		table.draw();
	});
});

})( jQuery );
