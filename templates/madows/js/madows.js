function toggleTOC() {
  var toc = document.getElementById("toc");

  if (toc.className != "visible")
  {
    toc.className = "visible";
    toc.style.top = window.pageYOffset+20+"px";
    document.getElementById("toggle_toc").className = "active";
  }
  else
  {
    toc.className = "";
    document.getElementById("toggle_toc").className = "";
  }
}