if (
  !(window.CSS && CSS.supports && CSS.supports("--a", 0))
) {
  var p = document.getElementById("preloader");
  if (p) p.style.display = "none";
}
