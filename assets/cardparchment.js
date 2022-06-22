let div = document.getElementById("div");
let response = document.getElementById("response");

div.addEventListener("mouseover", () => {response.style.display = "block";});
div.addEventListener("mouseout", () => {response.style.display = "none";});
/* div.addEventListener("mouseout", () => {helpdisplay.style.display = "none";}); */
