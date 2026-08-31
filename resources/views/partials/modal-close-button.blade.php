@php
    $onLight = !empty($onLight);
    $closeClass = 'bns-modal-close' . ($onLight ? ' bns-modal-close--on-light' : '');
@endphp
<button
    type="button"
    class="{{ $closeClass }}"
    data-bs-dismiss="modal"
    data-bns-close-modal=""
    aria-label="Close"
    onclick="(function(btn){var m=btn.closest('.modal');if(!m){return false;}if(window.bnsCloseModal){window.bnsCloseModal(m);}else{m.classList.remove('show');m.classList.add('bns-modal-is-closed');m.style.setProperty('display','none','important');document.querySelectorAll('.modal-backdrop').forEach(function(n){n.remove();});document.body.classList.remove('modal-open');document.body.style.removeProperty('overflow');}return false;})(this); return false;"
>
    <svg class="bns-modal-close__icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
        <path fill="currentColor" d="M18.3 5.71a1 1 0 0 0-1.41 0L12 10.59 7.11 5.7A1 1 0 0 0 5.7 7.11L10.59 12l-4.89 4.89a1 1 0 1 0 1.41 1.41L12 13.41l4.89 4.89a1 1 0 0 0 1.41-1.41L13.41 12l4.89-4.89a1 1 0 0 0 0-1.4z"/>
    </svg>
</button>
