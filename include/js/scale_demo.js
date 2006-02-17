function scaleIt(v) {
  var scalePhotos = document.getElementsByClassName("scale-image");

  // Remap the 0-1 scale to fit the desired range
  floorSize = .26;
  ceilingSize = 1.0;
  v = floorSize + (v * (ceilingSize - floorSize));

  for (i=0; i < scalePhotos.length; i++) {
    scalePhotos[i].style.width = (v*190)+"px";
  }
}
var demoSlider = new Control.Slider('handle1','track1', 
      {axis:'horizontal', minimum: 0, maximum:200, alignX: 2, increment: 2, sliderValue: 1});

demoSlider.options.onSlide = function(value){
  scaleIt(value);
}
demoSlider.options.onChange = function(value){
  scaleIt(value);
}