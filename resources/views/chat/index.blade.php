@extends('layouts.app')

@section('title', 'Chat con Productos')

@section('content')
<div class="flex flex-col h-[calc(100vh-8rem)]">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Chat con Productos</h1>
    </div>

    <div class="flex-1 bg-white rounded-lg shadow overflow-hidden flex flex-col">
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="bg-gray-100 rounded-lg px-4 py-2 max-w-2xl">
                    <p class="text-sm text-gray-800">Hola! Preguntame sobre nuestros productos. Puedo ayudarte a encontrar lo que buscas.</p>
                </div>
            </div>
        </div>

        <div class="border-t p-4">
            <form id="chat-form" class="flex gap-2">
                <input
                    type="text"
                    id="chat-input"
                    placeholder="Ej: laptop gamer barata, audifonos bluetooth..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    autocomplete="off"
                >
                <button
                    type="submit"
                    id="send-btn"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Enviar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messagesContainer = document.getElementById('chat-messages');
    const sendBtn = document.getElementById('send-btn');

    const API_URL = '{{ url("/api/search-products") }}';

    const suggestions = [
        'laptop gamer',
        'audifonos bluetooth',
        'mouse inalambrico',
        'teclado mecanico',
        'monitor 4k',
        'tablet para dibujar',
        'camara de seguridad',
        'parlante portatil',
    ];

    function addSuggestionChips() {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex flex-wrap gap-2 ml-11';

        const shuffled = suggestions.sort(() => 0.5 - Math.random()).slice(0, 4);

        shuffled.forEach(text => {
            const chip = document.createElement('button');
            chip.className = 'px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full text-xs transition-colors';
            chip.textContent = text;
            chip.onclick = () => {
                input.value = text;
                form.dispatchEvent(new Event('submit'));
            };
            wrapper.appendChild(chip);
        });

        messagesContainer.appendChild(wrapper);
        scrollToBottom();
    }

    function addMessage(text, isUser) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `flex items-start gap-3 ${isUser ? 'flex-row-reverse' : ''}`;

        const avatarClass = isUser
            ? 'bg-green-100'
            : 'bg-emerald-100';
        const avatarIcon = isUser
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>';

        msgDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full ${avatarClass} flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 ${isUser ? 'text-green-600' : 'text-emerald-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${avatarIcon}
                </svg>
            </div>
            <div class="bg-gray-100 rounded-lg px-4 py-2 max-w-2xl">
                <p class="text-sm text-gray-800 whitespace-pre-wrap">${escapeHtml(text)}</p>
            </div>
        `;

        messagesContainer.appendChild(msgDiv);
        scrollToBottom();
    }

    function addProductCards(products) {
        if (!products || products.length === 0) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'ml-11 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3';

        products.forEach(product => {
            const card = document.createElement('div');
            card.className = 'border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow';
            card.innerHTML = `
                <div class="font-medium text-sm text-gray-900">${escapeHtml(product.name)}</div>
                <div class="text-emerald-600 font-semibold text-sm mt-1">$${Number(product.price).toFixed(2)}</div>
                <div class="text-xs text-gray-500 mt-1">Stock: ${product.stock}</div>
                ${product.description ? `<div class="text-xs text-gray-400 mt-1 line-clamp-2">${escapeHtml(product.description)}</div>` : ''}
            `;
            wrapper.appendChild(card);
        });

        messagesContainer.appendChild(wrapper);
        scrollToBottom();
    }

    function addLoadingIndicator() {
        const msgDiv = document.createElement('div');
        msgDiv.id = 'loading';
        msgDiv.className = 'flex items-start gap-3';
        msgDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="bg-gray-100 rounded-lg px-4 py-3">
                <div class="flex gap-1">
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>
        `;
        messagesContainer.appendChild(msgDiv);
        scrollToBottom();
    }

    function removeLoadingIndicator() {
        const el = document.getElementById('loading');
        if (el) el.remove();
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const query = input.value.trim();
        if (!query) return;

        addMessage(query, true);
        input.value = '';
        sendBtn.disabled = true;
        input.disabled = true;
        addLoadingIndicator();

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ query }),
            });

            const data = await response.json();
            removeLoadingIndicator();

            if (data.success && data.data) {
                addMessage(data.data.answer, false);
                addProductCards(data.data.products);
            } else {
                addMessage('Lo siento, hubo un error al procesar tu pregunta. Intenta de nuevo.', false);
            }
        } catch (error) {
            removeLoadingIndicator();
            addMessage('No pude conectar con el servidor. Verifica tu conexion e intenta de nuevo.', false);
        } finally {
            sendBtn.disabled = false;
            input.disabled = false;
            input.focus();
        }
    });

    addSuggestionChips();
    input.focus();
});
</script>
@endsection
