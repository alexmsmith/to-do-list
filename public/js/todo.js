// Fetch existing todo items for task list
axios.get('/api/get')
    .then(response => {
        const todoItems = response.data;
        const taskList = document.getElementById('task-list');

        if (todoItems && todoItems.length > 0) {
            taskList.innerHTML = `
                <div class="task-list__header">
                    <div class="task-list__col">#</div>
                    <div class="task-list__col">Task</div>
                </div>
            `;
            todoItems.forEach((todoItem) => {
                const taskItem = document.createElement('div');
                const descriptionStyle = todoItem.to_do_status_id === 2 ? 'text-decoration: line-through;' : '';

                taskItem.className = 'task-list__item';
                taskItem.innerHTML = `
                    <div class="task-list__id">${todoItem.id}</div>
                    <div class="task-list__description" style="${descriptionStyle}">${todoItem.task_description}</div>
                    <div class="task-list__actions">
                        <button class="action-btn action-btn--complete" aria-label="Complete">✓</button>
                        <button class="action-btn action-btn--delete" aria-label="Delete">✕</button>
                    </div>
                `;
                
                taskList.appendChild(taskItem);
            });
        } else {
            taskList.innerHTML = "There are currently no tasks items available.";
        }
    })
    .catch(error => {
        console.error('Error retrieving todo items: ', error.response?.data || error);
        alert('Failed to retrieve todo items.');
    });

document.getElementById('add-task-btn').addEventListener('click', function() {
    const todoName = document.getElementById('task-input').value.trim();

    if (!todoName) {
        alert('Please enter a task name.');
        return;
    }

    // Create a new todo item for the task list
    axios.post('/api/create', { task_description: todoName })
    .then(response => {
        const todoItem = response.data; // Adjust if your API returns the item directly

        // Clear input
        document.getElementById('task-input').value = '';

        const taskList = document.getElementById('task-list');

        // If there are no tasks yet, add the header
        if (!taskList.querySelector('.task-list__item')) {
            taskList.innerHTML = `
                <div class="task-list__header">
                    <div class="task-list__col">#</div>
                    <div class="task-list__col">Task</div>
                </div>
            `;
        }

        // Create the new task item
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

// Change status to 'Completed'
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("action-btn--complete")) {
        const taskItem = e.target.closest(".task-list__item");
        const taskId = taskItem.querySelector(".task-list__id").textContent;
        const taskDescription = taskItem.querySelector(".task-list__description");

        axios.put(`/api/complete/${taskId}`)
            .then(response => {
                taskDescription.style.textDecoration = "line-through";
            })
            .catch(error => {
                console.error("Error completing todo item:", error.response?.data || error);
                alert("Failed to complete todo.");
            });
    }
});

// Delete a todo item from the list
document.addEventListener('click', function (e) {
    if (e.target.classList.contains("action-btn--delete")) {
        const todoItem = e.target.closest(".task-list__item");
        const todoId = todoItem.querySelector(".task-list__id").textContent;
        
        axios.put(`/api/delete/${todoId}`)
            .then(response => {
                todoItem.remove();
                const taskList = document.getElementById('task-list');
                const todoItems = taskList.querySelector('.task-list__item');
                if (!todoItems) {
                    taskList.innerHTML = "There are currently no tasks items available.";
                }
            })
            .catch(error => {
                console.error("Error deleting todo item:", error.response?.data || error);
                alert("Failed to delete todo item"); 
            });
    }
});