<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Client</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Optional: Custom Tailwind Config --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#4F46E5',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-tight min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-md rounded-lg p-8 max-w-lg w-full text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Approve this Client?</h2>
        
        <p class="text-gray-600 mb-6 text-lg">
            You're about to approve <span class="font-semibold text-indigo-600">{{ $client->name }}</span><br>
            <small class="text-sm text-gray-400">{{ $client->email }}</small>
        </p>

        <form action="{{ route('clients.approve', $client->id) }}" method="POST">
            @csrf
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full shadow-md transition duration-300">
                ✅ Approve Client
            </button>
        </form>

        <a href="{{ url()->previous() }}" class="block mt-6 text-sm text-gray-500 hover:text-gray-700 hover:underline">
            ⬅️ Cancel and go back
        </a>
    </div>

</body>
</html>
