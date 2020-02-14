/*
Author       : Dreamguys
Template Name: SmartHR - Bootstrap Admin Template
Version      : 3.2
*/

jQuery(document).ready(function() {
	
	// Variables declarations
	
	var $wrapper = jQuery('.main-wrapper');
	var $pageWrapper = jQuery('.page-wrapper');
	var $slimScrolls = jQuery('.slimscroll');
	
	// Sidebar
	
	var Sidemenu = function() {
		this.$menuItem = jQuery('#sidebar-menu a');
	};
	
	function init() {
		var $this = Sidemenu;
		jQuery('#sidebar-menu a').on('click', function(e) {
			if(jQuery(this).parent().hasClass('submenu')) {
				e.preventDefault();
			}
			if(!jQuery(this).hasClass('subdrop')) {
				jQuery('ul', jQuery(this).parents('ul:first')).slideUp(350);
				jQuery('a', jQuery(this).parents('ul:first')).removeClass('subdrop');
				jQuery(this).next('ul').slideDown(350);
				jQuery(this).addClass('subdrop');
			} else if(jQuery(this).hasClass('subdrop')) {
				jQuery(this).removeClass('subdrop');
				jQuery(this).next('ul').slideUp(350);
			}
		});
		jQuery('#sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
	}
	
	// Sidebar Initiate
	init();
	
	// Mobile menu sidebar overlay
	
	jQuery('body').append('<div class="sidebar-overlay"></div>');
	jQuery(document).on('click', '#mobile_btn', function() {
		$wrapper.toggleClass('slide-nav');
		jQuery('.sidebar-overlay').toggleClass('opened');
		jQuery('html').addClass('menu-opened');
		jQuery('#task_window').removeClass('opened');
		return false;
	});
	
	jQuery(".sidebar-overlay").on("click", function () {
			jQuery('html').removeClass('menu-opened');
			jQuery(this).removeClass('opened');
			$wrapper.removeClass('slide-nav');
			jQuery('.sidebar-overlay').removeClass('opened');
			jQuery('#task_window').removeClass('opened');
	});
	
	// Chat sidebar overlay
	
	jQuery(document).on('click', '#task_chat', function() {
		jQuery('.sidebar-overlay').toggleClass('opened');
		jQuery('#task_window').addClass('opened');
		return false;
	});
	
	// Select 2
	
	if(jQuery('.select').length > 0) {
		jQuery('.select').select2({
			minimumResultsForSearch: -1,
			width: '100%'
		});
	}
	
	// Modal Popup hide show

	if(jQuery('.modal').length > 0 ){
		var modalUniqueClass = ".modal";
		jQuery('.modal').on('show.bs.modal', function(e) {
		  var $element = jQuery(this);
		  var $uniques = jQuery(modalUniqueClass + ':visible').not($(this));
		  if ($uniques.length) {
			$uniques.modal('hide');
			$uniques.one('hidden.bs.modal', function(e) {
			  $element.modal('show');
			});
			return false;
		  }
		});
	}
	
	// Floating Label

	if(jQuery('.floating').length > 0 ){
		jQuery('.floating').on('focus blur', function (e) {
		jQuery(this).parents('.form-focus').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
		}).trigger('blur');
	}
	
	// Sidebar Slimscroll

	if($slimScrolls.length > 0) {
		$slimScrolls.slimScroll({
			height: 'auto',
			width: '100%',
			position: 'right',
			size: '7px',
			color: '#ccc',
			wheelStep: 10,
			touchScrollStep: 100
		});
		var wHeight = jQuery(window).height() - 60;
		$slimScrolls.height(wHeight);
		jQuery('.sidebar .slimScrollDiv').height(wHeight);
		jQuery(window).resize(function() {
			var rHeight = jQuery(window).height() - 60;
			$slimScrolls.height(rHeight);
			jQuery('.sidebar .slimScrollDiv').height(rHeight);
		});
	}
	
	// Page Content Height

	var pHeight =jQuery(window).height();
	$pageWrapper.css('min-height', pHeight);
	jQuery(window).resize(function() {
		var prHeight = jQuery(window).height();
		$pageWrapper.css('min-height', prHeight);
	});
	
	// Date Time Picker
	
	if(jQuery('.datetimepicker').length > 0) {
		jQuery('.datetimepicker').datetimepicker({
			format: 'DD/MM/YYYY',
			icons: {
				up: "fa fa-angle-up",
				down: "fa fa-angle-down",
				next: 'fa fa-angle-right',
				previous: 'fa fa-angle-left'
			}
		});
	}
	
	// Datatable

	if(jQuery('.datatable').length > 0) {
		jQuery('.datatable').DataTable({
			"bFilter": false,
		});
	}
	
	// Tooltip

	if(jQuery('[data-toggle="tooltip"]').length > 0) {
		jQuery('[data-toggle="tooltip"]').tooltip();
	}
	
	// Email Inbox

	if(jQuery('.clickable-row').length > 0 ){
		jQuery(".clickable-row").click(function() {
			window.location = jQuery(this).data("href");
		});
	}

	// Check all email
	
	jQuery(document).on('click', '#check_all', function() {
		jQuery('.checkmail').click();
		return false;
	});
	if(jQuery('.checkmail').length > 0) {
		jQuery('.checkmail').each(function() {
			jQuery(this).on('click', function() {
				if(jQuery(this).closest('tr').hasClass('checked')) {
					jQuery(this).closest('tr').removeClass('checked');
				} else {
					jQuery(this).closest('tr').addClass('checked');
				}
			});
		});
	}
	
	// Mail important
	
	jQuery(document).on('click', '.mail-important', function() {
		jQuery(this).find('i.fa').toggleClass('fa-star').toggleClass('fa-star-o');
	});
	
	// Summernote
	
	if(jQuery('.summernote').length > 0) {
		jQuery('.summernote').summernote({
			height: 200,                 // set editor height
			minHeight: null,             // set minimum height of editor
			maxHeight: null,             // set maximum height of editor
			focus: false                 // set focus to editable area after initializing summernote
		});
	}
	
	// Task Complete
	
	jQuery(document).on('click', '#task_complete', function() {
		jQuery(this).toggleClass('task-completed');
		return false;
	});
	
	// Multiselect

	if(jQuery('#customleave_select').length > 0) {
		jQuery('#customleave_select').multiselect();
	}
	if(jQuery('#edit_customleave_select').length > 0) {
		jQuery('#edit_customleave_select').multiselect();
	}

	// Leave Settings button show
	
	jQuery(document).on('click', '.leave-edit-btn', function() {
		jQuery(this).removeClass('leave-edit-btn').addClass('btn btn-white leave-cancel-btn').text('Cancel');
		jQuery(this).closest("div.leave-right").append('<button class="btn btn-primary leave-save-btn" type="submit">Save</button>');
		jQuery(this).parent().parent().find("input").prop('disabled', false);
		return false;
	});
	jQuery(document).on('click', '.leave-cancel-btn', function() {
		jQuery(this).removeClass('btn btn-white leave-cancel-btn').addClass('leave-edit-btn').text('Edit');
		jQuery(this).closest("div.leave-right").find(".leave-save-btn").remove();
		jQuery(this).parent().parent().find("input").prop('disabled', true);
		return false;
	});
	
	jQuery(document).on('change', '.leave-box .onoffswitch-checkbox', function() {
		var id = jQuery(this).attr('id').split('_')[1];
		if (jQuery(this).prop("checked") == true) {
			jQuery("#leave_"+id+" .leave-edit-btn").prop('disabled', false);
			jQuery("#leave_"+id+" .leave-action .btn").prop('disabled', false);
		}
	    else {
			jQuery("#leave_"+id+" .leave-action .btn").prop('disabled', true);	
			jQuery("#leave_"+id+" .leave-cancel-btn").parent().parent().find("input").prop('disabled', true);
			jQuery("#leave_"+id+" .leave-cancel-btn").closest("div.leave-right").find(".leave-save-btn").remove();
			jQuery("#leave_"+id+" .leave-cancel-btn").removeClass('btn btn-white leave-cancel-btn').addClass('leave-edit-btn').text('Edit');
			jQuery("#leave_"+id+" .leave-edit-btn").prop('disabled', true);
		}
	});
	
	jQuery('.leave-box .onoffswitch-checkbox').each(function() {
		var id = jQuery(this).attr('id').split('_')[1];
		if (jQuery(this).prop("checked") == true) {
			jQuery("#leave_"+id+" .leave-edit-btn").prop('disabled', false);
			jQuery("#leave_"+id+" .leave-action .btn").prop('disabled', false);
		}
	    else {
			jQuery("#leave_"+id+" .leave-action .btn").prop('disabled', true);	
			jQuery("#leave_"+id+" .leave-cancel-btn").parent().parent().find("input").prop('disabled', true);
			jQuery("#leave_"+id+" .leave-cancel-btn").closest("div.leave-right").find(".leave-save-btn").remove();
			jQuery("#leave_"+id+" .leave-cancel-btn").removeClass('btn btn-white leave-cancel-btn').addClass('leave-edit-btn').text('Edit');
			jQuery("#leave_"+id+" .leave-edit-btn").prop('disabled', true);
		}
	});
	
	// Placeholder Hide

	if (jQuery('.otp-input, .zipcode-input input, .noborder-input input').length > 0) {
		jQuery('.otp-input, .zipcode-input input, .noborder-input input').focus(function () {
			jQuery(this).data('placeholder', jQuery(this).attr('placeholder'))
				   .attr('placeholder', '');
		}).blur(function () {
			jQuery(this).attr('placeholder', jQuery(this).data('placeholder'));
		});
	}
	
	// OTP Input
	
	if (jQuery('.otp-input').length > 0) {
		jQuery(".otp-input").keyup(function(e) {
			if ((e.which >= 48 && e.which <= 57) || (e.which >= 96 && e.which <= 105)) {
				jQuery(e.target).next('.otp-input').focus();
			} else if (e.which == 8) {
				jQuery(e.target).prev('.otp-input').focus();
			}
		});
	}
	
	// Small Sidebar

	jQuery(document).on('click', '#toggle_btn', function() {
		if(jQuery('body').hasClass('mini-sidebar')) {
			jQuery('body').removeClass('mini-sidebar');
			jQuery('.subdrop + ul').slideDown();
		} else {
			jQuery('body').addClass('mini-sidebar');
			jQuery('.subdrop + ul').slideUp();
		}
		return false;
	});
	jQuery(document).on('mouseover', function(e) {
		e.stopPropagation();
		if(jQuery('body').hasClass('mini-sidebar') && jQuery('#toggle_btn').is(':visible')) {
			var targ = jQuery(e.target).closest('.sidebar').length;
			if(targ) {
				jQuery('body').addClass('expand-menu');
				jQuery('.subdrop + ul').slideDown();
			} else {
				jQuery('body').removeClass('expand-menu');
				jQuery('.subdrop + ul').slideUp();
			}
			return false;
		}
	});
	
	jQuery(document).on('click', '.top-nav-search .responsive-search', function() {
		jQuery('.top-nav-search').toggleClass('active');
	});
	
	jQuery(document).on('click', '#file_sidebar_toggle', function() {
		jQuery('.file-wrap').toggleClass('file-sidebar-toggle');
	});
	
	jQuery(document).on('click', '.file-side-close', function() {
		jQuery('.file-wrap').removeClass('file-sidebar-toggle');
	});
	
	if(jQuery('.kanban-wrap').length > 0) {
		jQuery(".kanban-wrap").sortable({
			connectWith: ".kanban-wrap",
			handle: ".kanban-box",
			placeholder: "drag-placeholder"
		});
	}

});
    jQuery('#AssignedTo').multiselect({
    columns: 1,
    placeholder: 'Assigned to',
    search: true
});
jQuery('#Followers').multiselect({
    columns: 1,
    placeholder: 'Followers',
    search: true
});
jQuery('#Supervisor').multiselect({
    columns: 1,
    placeholder: 'Supervisor',
    search: true
}); 
jQuery('#manager').multiselect({
    columns: 1,
    placeholder: 'Manager',
    search: true
});
jQuery('#department').multiselect({
    columns: 1,
    placeholder: 'Department',
    search: true
});
jQuery('#Tester').multiselect({
    columns: 1,
    placeholder: 'Tester',
    search: true
});
// Loader

jQuery(window).on ('load', function (){
	jQuery('#loader').delay(100).fadeOut('slow');
	jQuery('#loader-wrapper').delay(500).fadeOut('slow');
});
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}