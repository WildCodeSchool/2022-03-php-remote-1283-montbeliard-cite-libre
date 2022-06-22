let div = document.getElementById("div");
let p = document.getElementById("p");

div.addEventListener("mouseover", () => {p.style.display = "block";});
div.addEventListener("mouseout", () => {p.style.display = "none";});
