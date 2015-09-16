//There's No need to edit anything below this line
tooltip = {
  name : 'apadana-tooltip',
  offsetX : 0,
  offsetY : 15,
  tip : null
}
tooltip.init = function() {
	var tipNameSpaceURI = 'http://www.w3.org/1999/xhtml';
	if(!tipContainerID){ var tipContainerID = 'apadana-tooltip';}
	var tipContainer = document.getElementById(tipContainerID);
	if(!tipContainer) {
		tipContainer = document.createElementNS ? document.createElementNS(tipNameSpaceURI, 'div') : document.createElement('div');
		tipContainer.setAttribute('id', tipContainerID);
		document.getElementsByTagName('body').item(0).appendChild(tipContainer);
	}
	if (!document.getElementById) return;
	this.tip = document.getElementById (this.name);
	if (this.tip) document.onmousemove = function (evt) {tooltip.move (evt)};
}
tooltip.move = function(evt) {
	var x=0, y=0;
	if (document.all) {//IE
		x = (document.documentElement && document.documentElement.scrollLeft) ? document.documentElement.scrollLeft : document.body.scrollLeft;
		y = (document.documentElement && document.documentElement.scrollTop) ? document.documentElement.scrollTop : document.body.scrollTop;
		x += window.event.clientX;
		y += window.event.clientY;

	} else {//Good Browsers
		x = evt.pageX;
		y = evt.pageY;
	}
	this.tip.style.left = (x + this.offsetX) + 'px';
	this.tip.style.top = (y + this.offsetY) + 'px';
}
tooltip.show = function(text) {
	if (!this.tip) {
		tooltip.init();
	};
	if (!this.tip) return;
	this.tip.innerHTML = text;
	this.tip.style.display = 'block';
}
tooltip.hide = function() {
	if (!this.tip) return;
	this.tip.innerHTML = '';
	this.tip.style.display = 'none';
}

$(document).ready(function(){
	$('*[data-tooltip]').on({
		mouseenter: function(e) {
			tooltip.show($(this).attr('data-tooltip'));
		},
		mouseleave: function(e) {
			tooltip.hide();
		}
	});
})