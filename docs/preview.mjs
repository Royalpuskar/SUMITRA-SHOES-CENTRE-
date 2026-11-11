const buttons=[...document.querySelectorAll(".filter")],cards=[...document.querySelectorAll(".demo-product")],search=document.getElementById("search"),count=document.getElementById("count");let category="All";
function refresh(){const q=search.value.trim().toLowerCase();let shown=0;for(const card of cards){const match=(category==="All"||card.dataset.category===category)&&card.dataset.search.includes(q);card.hidden=!match;if(match)shown++}count.textContent=shown+" of "+cards.length+" styles";}
for(const button of buttons)button.addEventListener("click",()=>{category=button.dataset.category;for(const b of buttons){b.classList.toggle("active",b===button);b.classList.toggle("btn-secondary",b!==button)}refresh()});
search.addEventListener("input",refresh);refresh();
