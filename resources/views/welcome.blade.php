<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Atgadnis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- NAVBAR -->
<nav class="flex justify-between items-center p-6 bg-white shadow">
    <h1 class="text-xl font-bold">📌 Atgadnis</h1>

    <div class="space-x-4">
        @auth
            <span class="text-gray-800">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-red-500">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Login</a>
            <a href="{{ route('register') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Register</a>
        @endauth
    </div>
</nav>
    <!-- BODY -->
    <section class="text-center py-20">
        <h2 class="text-4xl font-bold mb-4">
            Pārvaldi savus uzdevumus viegli 🚀
        </h2>

    </section>

    <!-- FEATURES -->
    <section class="grid grid-cols-3 gap-6 p-10">

        <!-- Uzdevumi -->
        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="font-bold text-xl mb-2">📋 Uzdevumi</h3>
            <ul class="mt-4 text-left">
                @foreach($tasks as $task)
                    <li>- {{ $task->title }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Termiņi -->
        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="font-bold text-xl mb-2">📅 Termiņi</h3>
            <ul class="mt-4 text-left">
                @foreach($tasks as $task)
                    @if($task->due_date)
                        <li>- {{ $task->due_date }}: {{ $task->title }}</li>
                    @endif
                @endforeach
            </ul>
        </div>

        <!-- Favorīti -->
        <div class="bg-white p-6 rounded shadow text-center">
            <h3 class="font-bold text-xl mb-2">⭐ Favorīti</h3>
            <ul class="mt-4 text-left">
                @foreach($tasks as $task)
                    @if($task->is_favorite)
                        <li>- {{ $task->title }}</li>
                    @endif
                @endforeach
            </ul>
        </div>

    </section>
<section class="p-10">

    <h2 class="text-2xl font-bold mb-4">Mani uzdevumi</h2>

    <!-- ADD TASK -->
    <form method="POST" action="{{ route('tasks.store') }}" class="mb-6">
        @csrf
        <input name="title" placeholder="Jauns uzdevums..." class="border p-2 w-1/2">
        <input name="due_date" type="date" class="border p-2">
        <button class="bg-blue-500 text-white px-4 py-2 rounded">
            Pievienot
        </button>
    </form>

        <!-- TASKS SECTION -->
    @if($tasks->isEmpty())
        <p>Nav uzdevumu</p>
    @else
        @foreach($tasks as $task)
            <div class="bg-white p-4 mb-2 rounded shadow flex justify-between">
                <div>
                    <form method="POST" action="/tasks/{{ $task->id }}">
                        @csrf
                        @method('PATCH')
                        <input name="title" value="{{ $task->title }}" class="border p-2 w-full mb-2">
                        <input name="due_date" type="date" value="{{ $task->due_date }}" class="border p-2 w-full mb-2">
                        <button class="bg-green-500 text-white px-4 py-2 rounded">Atjaunināt</button>
                    </form>
                    @if($task->is_favorite)
                        <div class="text-yellow-500 mt-2">⭐ Favorīts</div>
                    @endif
                </div>
                <div class="flex gap-2">
                    <!-- TOGGLE -->
                    <form method="POST" action="/tasks/{{ $task->id }}/toggle">
                        @csrf
                        @method('PATCH')
                        <button class="text-blue-500">✓</button>
                    </form>
                    <!-- DELETE -->
                    <form method="POST" action="/tasks/{{ $task->id }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500">✕</button>
                    </form>
                    <!-- FAVORITE -->
                    <form method="POST" action="/tasks/{{ $task->id }}/favorite">
                        @csrf
                        @method('PATCH')
                        <button class="text-yellow-500">⭐</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

</section>

    <!-- FOOTER -->
    <footer class="text-center p-6 text-gray-500">
    </footer>

</body>
</html>