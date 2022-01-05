$('#carouselExampleControls').on('slide.bs.carousel', function (e) {
var inner = document.querySelector('.carousel-inner');
var controls = document.querySelectorAll('.controls');
  if (e.direction === 'left') {
		controls[0].className = 'controls class-active';
  }
  if (e.direction === 'right') {
		controls[1].className = 'controls class-active'
  }
  
  if (e.relatedTarget == inner.lastElementChild) {
    controls[1].className = 'controls class-fade'
  }
  if (e.relatedTarget == inner.firstElementChild) {
    controls[0].className = 'controls class-fade'
  }
})

