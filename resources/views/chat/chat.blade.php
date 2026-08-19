<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot Asistente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #chat-box { height: 400px; overflow-y: scroll; background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; }
        .message { margin-bottom: 10px; padding: 10px; border-radius: 8px; max-width: 80%; }
        .user-message { background-color: #0d6efd; color: white; margin-left: auto; text-align: right; }
        .bot-message { background-color: #e9ecef; color: black; margin-right: auto; text-align: left; }
    </style>
</head>
<body class="bg-light p-4">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Asistente de la Tienda (Gemini AI)</h5>
                    <a href="{{ url('/productos') }}" class="btn btn-sm btn-outline-light">Ver Productos</a>
                </div>
                <div class="card-body">
                    <div id="chat-box" class="mb-3 rounded">
                        <div class="message bot-message">
                            ¡Hola! Soy el asistente virtual. Pregúntame sobre los productos, precios, colores o tamaños disponibles en nuestro inventario.
                        </div>
                    </div>

                    <form id="chat-form" class="d-flex">
                        <input type="text" id="user-input" class="form-control me-2" placeholder="Ej: ¿Tienes un collar gris?" required autocomplete="off">
                        <button type="submit" class="btn btn-primary" id="send-btn">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('chat-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const inputElement = document.getElementById('user-input');
        const message = inputElement.value.trim();
        if (!message) return;

        const chatBox = document.getElementById('chat-box');
        const sendBtn = document.getElementById('send-btn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Agregar mensaje del usuario al chat
        chatBox.innerHTML += `<div class="message user-message">${message}</div>`;
        inputElement.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Bloquear input mientras carga
        sendBtn.disabled = true;
        sendBtn.innerHTML = 'Pensando...';

        try {
            const response = await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            // Agregar respuesta del bot
            chatBox.innerHTML += `<div class="message bot-message">${data.reply}</div>`;
        } catch (error) {
            chatBox.innerHTML += `<div class="message bot-message text-danger">Error de conexión.</div>`;
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = 'Enviar';
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
</body>
</html>
