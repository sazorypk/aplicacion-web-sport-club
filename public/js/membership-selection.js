const plan = document.getElementById('plan_id');
const disciplineField = document.getElementById('discipline-field');
const disciplineHint = document.getElementById('discipline-hint');
const allDisciplinesMessage = document.getElementById('all-disciplines-message');

function updateDisciplineSelection() {
    const selected = plan.selectedOptions[0];
    const includesAllDisciplines = selected?.dataset.allDisciplines === '1';
    const limit = Number(selected?.dataset.disciplineLimit || 0);
    disciplineField.hidden = includesAllDisciplines;
    disciplineField.disabled = includesAllDisciplines;
    allDisciplinesMessage.hidden = !includesAllDisciplines;
    disciplineHint.textContent = limit ? `Selecciona ${limit} ${limit === 1 ? 'disciplina' : 'disciplinas'}.` : 'Selecciona primero un plan.';
}

plan.addEventListener('change', updateDisciplineSelection);
updateDisciplineSelection();
