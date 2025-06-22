import{m as d}from"./vendor-3267c44a.js";window.Alpine=d;window.toggleClass=function(e,n){e.classList.toggle(n)};window.openModal=function(e){document.getElementById(e).classList.remove("hidden"),document.body.classList.add("overflow-hidden")};window.closeModal=function(e){document.getElementById(e).classList.add("hidden"),document.body.classList.remove("overflow-hidden")};window.showToast=function(e,n="info"){const s=document.getElementById("toast-container")||(()=>{const o=document.createElement("div");return o.id="toast-container",o.className="fixed top-4 right-4 z-50 space-y-2",document.body.appendChild(o),o})(),t=document.createElement("div"),l={success:"bg-green-100 border-green-400 text-green-700",error:"bg-red-100 border-red-400 text-red-700",warning:"bg-yellow-100 border-yellow-400 text-yellow-700",info:"bg-blue-100 border-blue-400 text-blue-700"}[n]||"bg-gray-100 border-gray-400 text-gray-700";t.className=`max-w-sm w-full ${l} border-l-4 p-4 shadow-lg rounded-md animate-slide-down`,t.innerHTML=`
        <div class="flex justify-between items-center">
            <p class="text-sm font-medium">${e}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-current hover:opacity-75">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `,s.appendChild(t),setTimeout(()=>t.remove(),5e3)};window.copyToClipboard=function(e){navigator.clipboard.writeText(e).then(()=>{showToast("Copié dans le presse-papiers !","success")})};d.start();
