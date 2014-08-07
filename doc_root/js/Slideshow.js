function Slideshow(containerId,baseUri) {
	this.container = $('#'+containerId);
	this.imgPool = {};
	this.active = 'HCSlideshowT';
	this.next = 0;
	this.baseUri = baseUri;
	this.first = new Image();
	this.params = {"fade":200,"delay":4000};
	
	this.Init = function(){
		if(this.imgPool.length < 2) {
			console.log("Slideshow.Init() : must contain at least 2 values.");
		}
		this.container.css({'position': 'relative'});
		this.container.append("<div class=\"HCSlideshowB\"></div><div class=\"HCSlideshowT\"></div>");
		var self = this;
		this.first.onload = function(){
			self.container.find('.HCSlideshowT').css({'background-image': 'url('+self.first.src+')' });
			self.container.find('.HCSlideshowT').fadeIn(self.params.fade);
			setTimeout(function(){
				self.container.find('.HCSlideshowB').css({'display': 'block'});
			}, self.params.fade + 50);
		};
		this.first.src = baseUri+this.imgPool[this.next];
		this.next = this.next + 1;
		this.loadNext();
		setInterval(function(){
			self.ImgChange();
		}, this.params.delay + this.params.fade);
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
			this.container.find('.HCSlideshowT').fadeOut(this.params.fade,function() { self.loadNext(); });
			this.active = 'HCSlideshowB';
		} else {
			this.container.find('.HCSlideshowT').fadeIn(this.params.fade,function() { self.loadNext(); });
			this.active = 'HCSlideshowT';
		}
	}
}