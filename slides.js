var nslide = 1;
slideshow(nslide);

function nextslide(n) {
  slideshow(nslide += n);
}

function currentSlide(n) {
  slideshow(nslide = n);
}

function slideshow(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {nslide = 1}    
  if (n < 1) {nslide = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[nslide-1].style.display = "block";  
  dots[nslide-1].className += " active";
} 

/**Sidebar**/
function sidebar() {
  document.getElementById("mySidenav").style.width = "180px";
  document.getElementById("Push_header").style.paddingLeft = "130px";
  document.getElementById("Push_main").style.paddingLeft = "130px";
  document.getElementById("expand_sidenav").style.paddingLeft = "100px";
  document.getElementById("expand_sidenav").style.opacity = "0";
  document.getElementById("Push_footer").style.marginLeft = "180px";
}

function closesidebar() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("Push_header").style.paddingLeft = "0";
  document.getElementById("Push_main").style.paddingLeft = "0";
  document.getElementById("expand_sidenav").style.paddingLeft = "5px";
  document.getElementById("expand_sidenav").style.paddingTop = "50px";
  document.getElementById("expand_sidenav").style.paddingRight = "15px";
  document.getElementById("expand_sidenav").style.opacity = "100";
  document.getElementById("Push_footer").style.marginLeft = "0";
}
