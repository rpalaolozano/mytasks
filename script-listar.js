async function toggleCompleted(id, completed) {
  await fetch('/api/tasks.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id, completed })
  });
  location.reload();
}

window.onload = async () => {
  const res = await fetch('/api/tasks.php');
  const tasks = await res.json();
  const list = document.getElementById('taskList');
  tasks.forEach(task => {
    const item = document.createElement('li');
    item.className = 'list-group-item d-flex justify-content-between align-items-center';
    item.innerHTML = `
      <span style="${task.completed ? 'text-decoration: line-through; color: gray;' : ''}">
        <strong>${task.title}</strong><br>${task.description}<br><em>Usuario: ${task.user}</em>
      </span>
      <input type="checkbox" ${task.completed ? 'checked' : ''} onchange="toggleCompleted(${task.id}, this.checked)">
    `;
    list.appendChild(item);
  });
};
