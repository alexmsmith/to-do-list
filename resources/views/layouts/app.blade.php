<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MLP To-Do</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div class="container">
        @include('partials.header')

        <div class="content">
            @yield('content')
        </div>

        @include('partials.footer')
    </div>
</body>
</html>

<script>
document.getElementById('add-task-btn').addEventListener('click', function() {
    const todoName = document.getElementById('task-input').value.trim();

    if (!todoName) {
        alert('Please enter a task name.');
        return;
    }

    axios.post('/api/create', { task_description: todoName })
        .then(response => {
            const todoItem = response.data;
            console.log(todoItem);

            document.getElementById('task-input').value = '';

            const taskList = document.getElementById('task-list');
            const newTaskItem = document.createElement('div');
            newTaskItem.className = 'task-list__item';
            newTaskItem.innerHTML = `
                <div class="task-list__id">${todoItem.data.id}</div>
                <div class="task-list__description">${todoItem.data.task_description}</div>
                <div class="task-list__actions">
                    <button class="action-btn action-btn--complete" aria-label="Complete">✓</button>
                    <button class="action-btn action-btn--delete" aria-label="Delete">✕</button>
                </div>
            `;
            taskList.appendChild(newTaskItem);
        })
        .catch(error => {
            console.error('Error creating task:', error.response?.data || error);
            alert('Failed to add task.');
        });
});
</script>