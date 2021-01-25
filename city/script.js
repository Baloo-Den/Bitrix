// JavaScript Document
  $(document).ready(function() {
    
	  $("#myModalBox").modal('show');

		$('#city').text(city);	  
		$('#modal-body_city').text(city);	  
		$('#tel').text(tel);		  
	  	$('#adress').text(adress);
		 
		
	$('#open_form').click(function() {//Нажали кнопку «Нет»
			$("#myModalBox").modal('hide'); 	
		});
		$('#text').bind('input', function(){ 
			$.ajax({ 
				url: '/local/components/city/change_city.php', 
				method: 'post',
				data: {text: $('#text').val(),hblockId:id_block},

						success: function(html){  
						$("#result").html(html);
		
						} 
			});

		});	
	   });