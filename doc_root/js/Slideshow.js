function Slideshow(containerId,baseUri) {
	this.container = $('#'+containerId);
	this.imgPool = JSON.parse(this.container.find('#pool').val());
	this.active = 'HCSlideshowT';
	this.next = 0;
	this.baseUri = baseUri;
	this.first = new Image();
	
	this.Init = function(){
		this.container.css({'position': 'relative'});
		var self = this;
		this.first.onload = function(){
			self.container.find('.HCSlideshowT').css({'background-image': 'url('+self.first.src+')' });
			self.container.find('.HCSlideshowT').fadeIn(200);
			setTimeout(function(){
				self.container.find('.HCSlideshowB').css({'display': 'block'});
			}, 250);
		};
		this.first.src = baseUri+this.imgPool[this.next];
		this.next = this.next + 1;
		this.loadNext();
		setInterval(function(){
			self.ImgChange();
		}, 4000);
	}
	
	this.loadNext = function(){
		if(this.active == 'HCSlideshowT'){
			this.container.find('.HCSlideshowB').css({'background-image': 'url("'+this.baseUri+this.imgPool[this.next]+'")' });
		} else {
			this.container.find('.HCSlideshowT').css({'background-image': 'url("'+this.baseUri+this.imgPool[this.next]+'")' });
		}
		console.log("Loading "+this.next);
	}
	
	this.ImgChange = function(){
		this.next = this.next + 1;
		if(this.next >= this.imgPool.length){
			this.next = 0;
		}
		var self = this;
		if(this.active == 'HCSlideshowT'){
			this.container.find('.HCSlideshowT').fadeOut('slow',function() { self.loadNext(); });
			this.active = 'HCSlideshowB';
		} else {
			this.container.find('.HCSlideshowT').fadeIn('slow',function() { self.loadNext(); });
			this.active = 'HCSlideshowT';
		}
	}
}