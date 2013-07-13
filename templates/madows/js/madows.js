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

function toggleDocuments() {
  var documents = document.getElementById("documents");

  if (documents.className != "visible")
  {
    documents.className = "visible";
    documents.style.top = window.pageYOffset+20+"px";
    document.getElementById("toggle_documents").className = "active";
  }
  else
  {
    documents.className = "";
    document.getElementById("toggle_documents").className = "";
  }
}