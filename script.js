document.getElementById('taskForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const title = document.getElementById('title').value;
  const description = document.getElementById('description').value;
  const user = document.getElementById('user').value;

  await fetch('/api/tasks.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title, description, user })
  });

  alert("Tarea guardada correctamente.");
  window.location.href = "listar.html";
});
