document.addEventListener("DOMContentLoaded", () => {
const form = document.getElementById("participationForm");


form.addEventListener("submit", (e) => {
const email = form.querySelector("input[name='email']").value.trim();
const username = form.querySelector("input[name='username']").value.trim();
const contactMethod = form.querySelector("select[name='contact_method']").value;
const contactValue = form.querySelector("input[name='contact_value']").value.trim();


if (!email || !username || !contactMethod || !contactValue) {
alert("Veuillez remplir tous les champs obligatoires.");
e.preventDefault();
return;
}


// Validation email simple
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
if (!emailRegex.test(email)) {
alert("Veuillez entrer un email valide.");
e.preventDefault();
return;
}
});
});