// JavaScript Document
  $(document).ready(function() {
    
	 

	$('#section> option').click(function() {//

			let id = $("#section").val(); //Выбранный раздел
			let iblock_id = $("#iblock_id").val(); //Разделы айблока
			let number_tree = $("#number_tree").val(); //
			$.ajax({ 
				url: '/local/components/varton_catalog_menu/output_menu.php', 
				method: 'post',
				data: {section: id,iblock_id:iblock_id,number_tree:number_tree},

						success: function(html){  
						$("#result").html(html);
		
						} 
			});		
		
		});

	   });