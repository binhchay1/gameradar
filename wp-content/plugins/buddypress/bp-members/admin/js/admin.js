window.bp=window.bp||{},bp.clear=e=>{const t=document.getElementById(e);if(!t)return;const i=t.querySelector("input:checked"),l=t.querySelectorAll("option");i&&(i.checked=""),l&&l.forEach((e=>{e.selected=!1}))},document.querySelectorAll(".visibility-toggle-link").forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault();const t=e.target,i=t.closest(".field-visibility-settings-toggle"),l=i.nextElementSibling;t.setAttribute("aria-expanded",!0),i.style.display="none",l.style.display="block"}))})),document.querySelectorAll(".field-visibility-settings-close").forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault();const t=e.target.closest(".field-visibility-settings"),i=t.previousElementSibling,l=t.querySelector("input:checked").nextElementSibling.innerHTML;t.style.display="none",i.querySelector(".visibility-toggle-link").setAttribute("aria-expanded",!1),i.querySelector(".current-visibility-level").innerHTML=l,i.style.display="block"}))}));