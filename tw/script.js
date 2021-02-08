// JavaScript Document
  $(document).ready(function() {
if (pause!='')//Если нажали паузу и закрыли браузер
	{
			$("#begin_work").hide();
			$("#end_work").hide();
			$("#pause").hide();
			$("#end_pause").show();		
	}
else	  
	{
		if (current_work_day!='')//Если нажали капу начала работы и закрыли браузер
			{
				$("#begin_work").hide();
				$("#end_work").show();
				$("#pause").show();
				$("#end_pause").hide();				
			}
	}
		$('#begin_work').click(function() {//Жамкнули капу начала работы

			$.ajax({ 
				url: '/local/components/tw/all_ajax.php', 
				method: 'post',
				data: {id_user: id_user,dest:'begin_day'},
						success: function(html){  
						$("#result").html(html);
						$("#begin_work").hide();
						$("#end_work").show();
						$("#pause").show();
						$("#end_pause").hide();							
						} 
			});
		});	

		$('#end_work').click(function() {//Кончили
		
			$.ajax({ 
				url: '/local/components/tw/all_ajax.php', 
				method: 'post',
				data: {current_work_day: current_work_day,dest:'the_end'},
						success: function(html){  
						$("#result").html(html);
						$("#begin_work").hide();
						$("#end_work").hide();
						$("#pause").hide();
						$("#end_pause").hide();								
						} 
			});
		});	

		$('#pause').click(function() {//Пауза

			$.ajax({ 
				url: '/local/components/tw/all_ajax.php', 
				method: 'post',
				data: {dest:'pause'},
						success: function(html){  
						$("#result").html(html);
						$("#begin_work").hide();
						$("#end_work").hide();
						$("#pause").hide();
						$("#end_pause").show();							
						} 
			});
		});	

		$('#end_pause').click(function() {//Кончили паузу
			
			$.ajax({ 
				url: '/local/components/tw/all_ajax.php', 
				method: 'post',
				data: {dest:'end_pause'},
						success: function(html){  
						$("#result").html(html);
						$("#begin_work").hide();
						$("#end_work").show();
						$("#pause").show();
						$("#end_pause").hide();								
						} 
			});
		});		  
	   });