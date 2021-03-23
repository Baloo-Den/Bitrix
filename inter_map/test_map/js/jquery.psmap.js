/**
 * 
 */

$.fn.psMap = function(newparams) {
	
	var params = { 
		zoomMax : 5,
		zoomMin : 1, 
		zoom : 1,
		zoomSteps : 20,
		offsetStep : 50,
		
		dataSource: false,

		navs : '<div class="psMapNavs">'
				+ '<div class="navbtn navbtn-large zoomIn"></div>'
				+ '<div class="navbtn navbtn-large zoomOut"></div>'
				+ '<div class="navbtn up"></div>'
				+ '<div class="navbtn down"></div>'
				+ '<div class="navbtn left"></div>'
				+ '<div class="navbtn right"></div>'
				+ '<div class="navbtn reset"></div>' + '</div>'
	};

	var map = $(this);
	
	var mapLayer = map.find('img.psMapLayer');
	//mapWrapper = map.closest('.psMapWrapper');
	var mapOuter = mapLayer.closest(".psMapOuter");
	var mapCaret = mapLayer.closest(".psMapCaret");
	var mapAreas = mapLayer.next('svg');
	
	var navs;
	var mapData = $('');

	// maplayer real size
	var img = mapLayer[0]; // Get my img elem

	var maplayer_real_width, maplayer_real_height, maplayer_ratio;
	
	var centerX, centerY;
	var mapLayerX, mapLayerY;
	var dragStartX, dragStartY;
	
	var refreshLayerState = function()
	{
		//var mapLayerOffset = mapCaret.position();
		
		mapLayerX = - mapCaret.scrollLeft();
		mapLayerY = - mapCaret.scrollTop();
	}

	var setOffset = function(x, y, animate) {
		hideLabel();
		//mapLayer.stop();
		refreshLayerState();

		
		if (x > 0)
			x = 0;

		if (x + mapLayer.width() < map.width())
			x = map.width() - mapLayer.width();

		if (y > 0)
			y = 0;

		if (y + mapLayer.height() < map.height())
			y = map.height() - mapLayer.height();
		
		mapLayerX = x;
		mapLayerY = y;

		
		mapCaret.stop().scrollTop(-y).scrollLeft(-x);
	}

	var stepOffset = function(stepsX, stepsY) {
		var x = mapLayerX - params.offsetStep * stepsX;
		var y = mapLayerY - params.offsetStep * stepsY;

		setOffset(x, y);
	}
	
	var curzoom = 0;

	var setZoom = function(zoom, animate) {
		hideLabel();
		//mapLayer.stop();
		refreshLayerState();
		
		if (zoom > params.zoomMax)
			zoom = params.zoomMax;
		if (zoom < params.zoomMin)
			zoom = params.zoomMin;

		var mapWidth = map.width();
		var mapHeight = map.height();
		var mapRatio = mapWidth / mapHeight;

		var mapLayerWidth = maplayer_real_width;
		var mapLayerHeight = maplayer_real_width;
		var mapLayerRatio = maplayer_ratio;

		var startWidth = mapWidth;
		var startHeight = startWidth / maplayer_ratio;

		if (mapLayerRatio > mapRatio) {
			startHeight = mapHeight;
			startWidth = startHeight * maplayer_ratio;
		}

		var newWidth = startWidth * zoom;
		var newHeight = startHeight * zoom;

		//var x = (mapWidth - newWidth) / 2;
		//var y = (mapHeight - newHeight) / 2;
		
		var z = newWidth/mapLayer.width();
		
		var x = mapLayerX + (centerX - mapLayerX) * (1 - z); 
		var y = mapLayerY + (centerY - mapLayerY) * (1 - z); 
		
		if (x > 0)
			x = 0;

		if (x + newWidth < map.width())
			x = map.width() - newWidth;

		if (y > 0)
			y = 0;

		if (y + newHeight < map.height())
			y = map.height() - newHeight;
		
		//setOffset(x, y);		
		
		mapLayerX = x;
		mapLayerY = y;
		params.zoom = zoom;
		

		mapLayer.css({
			width : newWidth + 'px',
			height : newHeight + 'px'
		});
		mapAreas.css({
			width : newWidth + 'px',
			height : newHeight + 'px'
		});
		mapCaret.scrollTop(-y).scrollLeft(-x);
		
		curzoom = zoom;

	}
	
	var setCenter = function()
	{
		centerX = map.width() / 2;
		centerY = map.height() / 2;		
	}

	var stepZoom = function(stepsNum) {
		var step = (params.zoomMax - params.zoomMin) / params.zoomSteps;
		zoom = params.zoom + step * stepsNum;

		setZoom(zoom);
	}
	
	var showLabel = function(data, x, y)
	{
		//console.log(data);
		var label = $("<div class='pslabel' style='display:none;'></div>");
		
		var title = $(data).find('t');
		
		
		
		if(title.length)
			{
				title = $('<div class="title green">' + title.text() + '</div>')
			}
		else
			{
				var status = $(data).find('st');
				if(status.length) status = status.text();
				
				if(status == 0) title = $('<div class="title sold">продан</div>');
				else if(status == 1) title = $('<div class="title free">свободен</div>');
				else if(status == 2) title = $('<div class="title reserved">зарезервирован</div>');
			}

		if(title.length) label.append(title);
		
		
		
		var image = $(data).find('im');
		if(image.length)
			{	//console.log($(image).text());
				image = $("<img src='"+ image.text() +"' />");
				
				label.append(image);
			}
		
		var n = $(data).find('n');
		if(n.length)
			{
				var table = $('<table></table>');
				
				n = $("<tr><td nowrap>Участок</td><td nowrap>№ <big>"+ n.text() +"</big></td></tr>");
				table.append(n);
				
				var s = $(data).find('s');
				if(s.length)
					{
						s = s.text();
						s = $("<tr><td nowrap>Площадь</td><td nowrap>"+ s +" соток</td></tr>")
						table.append(s);
					}
				
				var cnd = $(data).find('cnd');
				var ccom = $(data).find('ccom');
				if(cnd.length)
					{
						cnd = cnd.text();
						
						if(ccom.length)
							cnd = parseInt(cnd) + parseInt(ccom.text());
						
						if(status > 0)
						{
							cnd = $("<tr><td nowrap>ЦЕНА</td><td nowrap><big>"+ cnd +"</big> рублей</td></tr>")
							table.append(cnd);
						}
						
						//else
						//	cnd = $("<tr><td nowrap>ЦЕНА</td><td nowrap> участок продан</td></tr>")
						
						
					}
				
				label.append(table);
			}
		
		
		
		hideLabel();
		
		$("html").append(label);
		label.css({left: x, top: y});
		label.fadeIn(100);
	}
	
	var hideLabel = function()
	{
		$("html").find('.pslabel').remove();
	}
	
	var zooming = false;
	
	var mapInit = function()
	{
		mapAreas.find('.area')
			.on('mouseenter click', function(event){
				
				if(zooming) return;
 															 
				event.stopPropagation();
				//console.log($(this).attr('id'), $(this).prevAll().length, event.pageX, event.pageY);	
				var id=$(this).prevAll().length;
				
				
				var target = $(this).attr('data-target');
				if(target){
					var data = mapData.find(target);
					//if(data.length) console.log($(data));
				}else{
					var data = mapData.find('d').eq(id);
					//if(data.length) console.log($(data[id]));
				}
				
					var offset = map.offset();
					var x = event.pageX + 15;
					var y = event.pageY + 15;
					showLabel(data,x,y);

				
			})
			.on('click', function(event){
				//console.log($(this).attr('id'), $(this).prevAll().length, event.pageX, event.pageY);
				event.stopPropagation();

			})
			.on('mousedown', function(event){
				//event.stopPropagation();
			})
			.on('mouseleave', function(event){
				event.stopPropagation();
			});;
			
		
		if(params.dataSource)
		$.ajax({
			url: params.dataSource,
			dataType: 'text',
			success: function(text)
			{
				
				text = text.replace( /img>/g, 'im>');
				
				console.log(text);
				
				mapData = $(text);
				
				var items = mapAreas.find('path.area');
				//console.log(items);
				if(items.length)
				mapData.find('d').each(function(a){
					var id = $(this).find('n').text() - 1;
					//console.log( id);
					var status = $(this).find('st').text();
					//console.log( items[id]);
					if(id<244)
					{
						if(status == '0') $(items[id]).attr('class','area sold');
					else if(status == '2') $(items[id]).attr('class','area reserved');					
					}
					else
						{
							
						if(status == '0') $(items[id-243]).attr('class','area sold');
					else if(status == '2') $(items[id-243]).attr('class','area reserved');							
						}
				});
				
				mapAreas.fadeIn(500);
				
			}
		})
	}
	
	var navHideTimeout;
	
	var init = function(params) {
		
				
		
		navs = $(params.navs);
		
		map.append(navs);

		navs.find('.navbtn.left').on('click', function(e) {
			setCenter();
			stepOffset(-1, 0);
		});

		navs.find('.navbtn.right').on('click', function(e) {
			setCenter();
			stepOffset(1, 0);
		});

		navs.find('.navbtn.up').on('click', function(e) {
			setCenter();
			stepOffset(0, -1);
		});

		navs.find('.navbtn.down').on('click', function(e) {
			setCenter();
			stepOffset(0, 1);
		});

		navs.find('.navbtn.reset').on('click', function(e) {
			setCenter();
			setZoom(params.zoomMin);
		});

		navs.find('.navbtn.zoomIn').on('click', function(e) {
			setCenter();
			stepZoom(1);
		});

		navs.find('.navbtn.zoomOut').on('click', function(e) {
			setCenter();
			stepZoom(-1);
		});
		
		navs.on('mousemove', function(event){
			//event.stopPropagation();
		});

		


		
		var startzom, distance, scaling;
		
		map.off()	
		.on('mousewheel', function(event) {
			//event.stopPropagation();
			centerX = event.pageX - map.offset().left;
			centerY = event.pageY - map.offset().top;
			stepZoom(event.deltaY);
		})
		.on('scroll', function(event){
			hideLabel();			
		})
		.on('draginit', function(event) {
			refreshLayerState();
			dragStartX = mapLayerX-16;
			dragStartY = mapLayerY;
		})
		.on('dragstart', function(event) {
			map.css('cursor', 'move');
		})
		.on('drag', function(event, dd) {
			//console.log(event);
			//console.log(dd);
			//console.log(event.cursorOffsetX, event.cursorOffsetY);
			setOffset(dragStartX + dd.deltaX, dragStartY + dd.deltaY - 8); 
		})
		.on('dragend', function(event) {
			map.css('cursor', 'default');
		})
		.on('mouseleave', function(event){
			navHideTimeout = window.setTimeout(function(){navs.fadeOut();}, 3000);
			hideLabel();
		})
		.on('mouseenter', function(event){
			if(navHideTimeout) window.clearInterval(navHideTimeout);
			navs.fadeIn();
		})
		.on('click', function(event){
			hideLabel();
		})
		.bind('touchstart', function(e){
			
			e = e.originalEvent;
			
			if(e.touches.length > 1)
			{
				zooming = true;
				
				scaling = true;
				e.preventDefault();
				startzoom = curzoom;
				distance =
				Math.sqrt(
					(e.touches[0].pageX-e.touches[1].pageX) * (e.touches[0].pageX-e.touches[1].pageX) +
					(e.touches[0].pageY-e.touches[1].pageY) * (e.touches[0].pageY-e.touches[1].pageY));
			};
			                              
		})
		.bind('touchmove', function(e) {
			
			e = e.originalEvent;
			
			if(e.touches.length > 1 && scaling == true)
			{
				e.preventDefault();
			    
				zooming = true;
				 
				var dist =
				Math.sqrt(
					(e.touches[0].pageX-e.touches[1].pageX) * (e.touches[0].pageX-e.touches[1].pageX) +
					(e.touches[0].pageY-e.touches[1].pageY) * (e.touches[0].pageY-e.touches[1].pageY));
					
				
				var zoom = startzoom * dist/distance;
				
				
				if (zoom > params.zoomMax || zoom < params.zoomMin) return;
				setCenter();			
				setZoom(zoom);
			}
		})
		.bind('touchend', function(e){
			zooming = false;					   
			distance = 1;
			scaling = false;
		})
		.bind( 'mousewheel DOMMouseScroll', function ( e ) {
		    var e0 = e.originalEvent,
	        delta = e0.wheelDelta || -e0.detail;
		    this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
		    e.preventDefault();
	    });

		mapCaret.on('scroll', function(e){
			
			hideLabel();
		})
		
		mapLayer.on('mousedown', function(event) {
			//event.preventDefault();
			// event.stopPropagation();
		});
		


		
		mapLayer.show();
		
		mapInit();		
	}
	

	// Inits
	if (typeof newparams != 'undefined')
		params = $.extend(params, newparams);

	var img = $("<img/>");
	 // Make in memory copy of image to avoid css issues
	img.attr("src", mapLayer.attr("src")).appendTo(map).css({opacity: 0, width: "100%"});
	mapLayer.hide();
	
	
	img.get(0).onload = function() {
		maplayer_real_width = this.width; // Note: $(this).width() will not
		maplayer_real_height = this.height; // work for in memory images.
		maplayer_ratio = maplayer_real_width / maplayer_real_height;
		$(this).remove();

		init(params);
		
		$('window').resize(function(e){
			setZoom(1);
		});
		window.setTimeout(function(){setZoom(1);}, 300); 
	
		//mapLayer.show();
	};
	
}