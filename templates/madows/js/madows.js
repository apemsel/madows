function toggleDisplay(targetId) {
  var target = document.getElementById(targetId);
  
  if (target.style.display == "none")
  {
    target.style.display = "block";
  }
  else
  {
    target.style.display ="none";
  }
}