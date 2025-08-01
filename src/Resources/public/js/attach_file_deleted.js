import './../css/attach_file_deleted.css'

document.querySelectorAll('[data-file-input]').forEach(initFileInput);

function initFileInput(container) {
    const fileInput = container.querySelector('.file-input-hidden');
    const fileZone = container.querySelector('.file-zone');
    const fileText = container.querySelector('.file-text');
    const fileIcon = container.querySelector('.input-group-text i');
    const deleteBtn = container.querySelector('.delete-btn');
    const deleteChk = container.querySelector('.form-check-input');

    let selectedFile = null;
    let deleteMode = false;

    // Eventos
    fileInput.addEventListener('change', selectFile);
    fileZone.addEventListener('click', () => fileInput.click());
    deleteBtn.addEventListener('click', toggleDelete);

    // Drag & drop
    fileZone.addEventListener('dragover', e => {
        e.preventDefault();
        fileZone.classList.add('bg-light');
    });

    fileZone.addEventListener('dragleave', () => {
        fileZone.classList.remove('bg-light');
    });

    fileZone.addEventListener('drop', e => {
        e.preventDefault();
        fileZone.classList.remove('bg-light');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            selectFile();
        }
    });

    function selectFile() {
        selectedFile = fileInput.files[0];
        deleteMode = false;

        if (selectedFile) {
            fileText.textContent = selectedFile.name;
            fileIcon.className = getFileIcon(selectedFile.type);
            container.classList.add('has-file');
            container.classList.remove('delete-mode');
            deleteBtn.classList.remove('active');
        } else {
            resetFile();
        }
    }

    function toggleDelete(event) {
        event.preventDefault();
        deleteMode = !deleteMode;
        deleteChk.checked = deleteMode;

        if (deleteMode) {
            container.classList.add('delete-mode');
            deleteBtn.classList.add('active');
            fileInput.value = null;
        } else {
            container.classList.remove('delete-mode');
            deleteBtn.classList.remove('active');
        }
    }

    function resetFile() {
        selectedFile = null;
        deleteMode = false;
        fileText.textContent = fileText.textContent.includes('...') ? fileText.textContent : 'Seleccionar archivo...';
        fileIcon.className = 'fas fa-paperclip';
        container.classList.remove('has-file', 'delete-mode');
        deleteBtn.classList.remove('active');
    }

    // API pública para este input específico
    container.getFile = () => deleteMode ? null : selectedFile;
    container.clearFile = () => {
        fileInput.value = '';
        resetFile();
    };
}

function getFileIcon(type) {
    if (type.includes('image')) return 'fas fa-image text-primary';
    if (type.includes('pdf')) return 'fas fa-file-pdf text-danger';
    if (type.includes('word')) return 'fas fa-file-word text-info';
    if (type.includes('excel')) return 'fas fa-file-excel text-success';
    return 'fas fa-file text-secondary';
}
