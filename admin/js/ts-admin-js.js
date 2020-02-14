(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
    
		jQuery(document).ready(function() {
   
        var table = jQuery("#ticketListing").DataTable({ 
            "bDestroy": true,   
            "responsive": true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": './admin-ajax.php',
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value": "ticketmanage" } );
                aoData.push( { "name": "email_search", "value": $("#search_email").val() } );
                aoData.push( { "name": "last_name", "value": $("#search_last_name").val() } );
            },
            "pageLength": 50,
             "order": [ [5,'asc']],  /*[ 0, "desc" ],*/ 
              "columnDefs": [
                { "orderable": false, "targets": [1,4,6,7] }
              ],  

        
             "language": {
                "emptyTable":"Record not found",
                "search": '',
                "searchPlaceholder": 'Search..',  
            },
            "scrollX": true,
            "bFilter": false,
            "bInfo": false
        }); 
        
        jQuery('#search_email').on('keyup', function(){
            table.draw();
        });
        jQuery('#search_last_name').on('keyup', function(){
            table.draw();
        });
        /* *************************  USER ACCESS PAGE************************ */
       
        var table = $("#AdminHistoryList").DataTable({ 
            "bDestroy": true,   
            "responsive": true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": './admin-ajax.php',
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value": "ticketmanagehistory" } );
                aoData.push( { "name": "email_search", "value": $("#search_email").val() } );
                aoData.push( { "name": "last_name", "value": $("#search_last_name").val() } );
            },
            "pageLength": 50,
            "order": [[ 5, "asc" ],[ 0, "desc" ]],  
            "columnDefs": [
                { "orderable": false, "targets": [1,4,6,7] }
              ],

        
             "language": {
                "emptyTable":"Record not found",
                "search": '',
                "searchPlaceholder": 'Search..',  
            },
            "scrollX": true,
            "bFilter": false,
            "bInfo": false
        }); 
        
        $('#search_email').on('keyup', function(){
            table.draw();
        });
        $('#search_last_name').on('keyup', function(){
            table.draw();
        });
    
 
        
        
        /* *************************  CANNED TICKET  PAGE************************ */
            var table = $("#cannedList").DataTable({ 
            "bDestroy": true,   
            "responsive": true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": './admin-ajax.php',
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value": "cannedmanage" } );
            },
            "pageLength": 50,
            "order": [[ 0, "desc" ]],  
            "columnDefs": [
                { "orderable": false, "targets": [2] }
              ],

        
             "language": {
                "emptyTable":"Record not found",
                "search": '',
                "searchPlaceholder": 'Search..',  
            },
            "scrollX": true,
            
        }); 
        /* *************************  USER ACCESS PAGE************************ */
         jQuery(document).ready(function($){	
         $(document).ready(function() {    $('#reportListAccess').DataTable();} );	
         }); 
    
        /* ************************* LISTING PAGE************************ */ 
         
 
        var table = $("#reportList").DataTable({ 
            "bDestroy": true,   
            "responsive": true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": './admin-ajax.php',
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value": "ticketmanage" } );
                aoData.push( { "name": "email_search", "value": $("#search_email").val() } );
                aoData.push( { "name": "last_name", "value": $("#search_last_name").val() } );
            },
            "pageLength": 50,
            "order": [ [5,'asc']],  /*[ 0, "desc" ],*/ 
            "columnDefs": [
                { "orderable": false, "targets": [1,4,6,7] }
              ],

        
             "language": {
                "emptyTable":"Record not found",
                "search": '',
                "searchPlaceholder": 'Search..',  
            },
            "scrollX": true,
            "bFilter": false,
            "bInfo": false
        }); 
        
        $('#search_email').on('keyup', function(){
            table.draw();
        });
        $('#search_last_name').on('keyup', function(){
            table.draw();
        });
    
 
        /* ************************* Merge Ticket PAGE************************ */ 
 
    $("#users").change(function(){
       var options ='<option value="">--Choose--</option>';
       var $this = $(this);
       $.ajax({
           type:"post",
           dataType:"json",
           data:{
               action:"usersticket",
               id:$this.val()
           },
           url:'./admin-ajax.php',
           success:function(data){
               $(".tickets").html('');
            $.each(data, function(index) {
                options+='<option value="'+data[index].id+'">'+data[index].category+' - '+data[index].subject+' </option>';   
                console.log("ID "+data[index].id);
            });
            $(".tickets").append(options);
           }
       });
    });
 /* ******************************  MANGER (AGENT) FUNCTIONS *****************************/
   

        var table = $("#ManagerTicketListing").DataTable({ 

            "bDestroy": true,   

            "responsive": true,

            "bProcessing": true,

            "bServerSide": true,

            "sServerMethod": "POST",

            "sAjaxSource": './admin-ajax.php',

            "fnServerParams": function ( aoData ) {

                aoData.push( { "name": "action", "value": "ticketmanagemanager" } );

                aoData.push( { "name": "email_search", "value": $("#manager_tick_search_email").val() } );

                aoData.push( { "name": "last_name", "value": $("#manager_tick_search_last_name").val() } );

            },

            "pageLength": 50,

            "order": [ [5,'asc']],  /*[ 0, "desc" ],*/ 

            "columnDefs": [

                { "orderable": false, "targets": [1,4,6] }

              ],



        

             "language": {

                "emptyTable":"Record not found",

                "search": '',

                "searchPlaceholder": 'Search..',  

            },

            "scrollX": true,

            "bFilter": false,

            "bInfo": false

        }); 

        

        $('#manager_tick_search_email').on('keyup', function(){

            table.draw();

        });

        $('#manager_tick_search_last_name').on('keyup', function(){

            table.draw();

        });

    /* *******************Manger History MANGER******************************/
            var table = $("#manger-history-list").DataTable({ 
            "bDestroy": true,   
            "responsive": true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": './admin-ajax.php',
            "fnServerParams": function ( aoData ) {
                aoData.push( { "name": "action", "value": "ticketmanagehistory_executive" } );
                aoData.push( { "name": "email_search", "value": $("#manger_history_search_email").val() } );
                aoData.push( { "name": "last_name", "value": $("#manger_history_search_last_name").val() } );
            },
            "pageLength": 50,
            "order": [[ 5, "asc" ],[ 0, "desc" ]],  
            "columnDefs": [
                { "orderable": false, "targets": [1,4,6,7] }
              ],

        
             "language": {
                "emptyTable":"Record not found",
                "search": '',
                "searchPlaceholder": 'Search..',  
            },
            "scrollX": true,
            "bFilter": false,
            "bInfo": false
        }); 
        
        $('#manger_history_search_email').on('keyup', function(){
            table.draw();
        });
        $('#manger_history_search_last_name').on('keyup', function(){
            table.draw();
        });
    /* *******************upLoad Image Trigger MANGER******************************/
    $(".showonhover").click(function(){		
                    $("#uploadchatImage").trigger('click');	
     });      
 
    
 });

})( jQuery );
