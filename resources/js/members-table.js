// resources/js/members-table.js - CREATE NEW FILE

document.addEventListener('DOMContentLoaded', function() {
    // Table row hover effects
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit after 500ms of no typing
                if (this.value.length > 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }

    // Filter change auto-submit
    const filterSelects = document.querySelectorAll('select[name^="family"], select[name^="status"], select[name^="gender"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Action buttons
    const editButtons = document.querySelectorAll('.edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    editButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const memberId = this.dataset.id;
            // Handle edit action
            console.log('Edit member:', memberId);
        });
    });

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const memberId = this.dataset.id;
            const memberName = this.dataset.name;
            
            if (confirm(`Apakah Anda yakin ingin menghapus ${memberName}?`)) {
                // Handle delete action
                console.log('Delete member:', memberId);
            }
        });
    });

    // Bulk actions
    const selectAllCheckbox = document.querySelector('#selectAll');
    const rowCheckboxes = document.querySelectorAll('input[name="selected[]"]');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('input[name="selected[]"]:checked').length;
        const bulkActions = document.querySelector('#bulkActions');
        
        if (bulkActions) {
            bulkActions.style.display = checkedCount > 0 ? 'block' : 'none';
            bulkActions.querySelector('.selected-count').textContent = checkedCount;
        }
    }
});

// Utility functions
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } text-white`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Teks berhasil disalin!');
    });
}