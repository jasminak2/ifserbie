jQuery(document).ready(function($) { 
	
  $(".ifdate").datepicker({
    dateFormat: 'D, M d, yy',
    showOn: 'button',
    buttonImage: 'http://www.institutfrancais.rs/assets/icon-datepicker.png',
    buttonImageOnly: true,
    numberOfMonths: 3

  });
  
  $('#post').validate({
		
		rules: {
			  
			if_events_startdate: {
				required: true,
				date: true
			},

			if_events_enddate: {
				required: true,
				date: true
			}

		}
		
		
	});


});
