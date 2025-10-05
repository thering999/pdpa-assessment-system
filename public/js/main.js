// Main JavaScript for PDPA Assessment System

document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions with AJAX
    const forms = document.querySelectorAll('form[data-ajax]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = this.action;
            
            fetch(action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.message) {
                        showAlert(data.message, 'success');
                    }
                } else {
                    showAlert(data.message || 'เกิดข้อผิดพลาด', 'error');
                }
            })
            .catch(error => {
                showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            });
        });
    });
});

// Show alert message
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Save assessment answer
function saveAnswer(assessmentId, questionId, answer) {
    const formData = new FormData();
    formData.append('assessment_id', assessmentId);
    formData.append('question_id', questionId);
    formData.append('answer', answer);
    
    fetch('api.php?action=save_answer', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            showAlert('ไม่สามารถบันทึกคำตอบได้', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving answer:', error);
    });
}

// Complete assessment
function completeAssessment(assessmentId) {
    if (!confirm('คุณต้องการส่งแบบประเมินหรือไม่? คุณจะไม่สามารถแก้ไขได้อีก')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('assessment_id', assessmentId);
    
    fetch('api.php?action=complete_assessment', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'result.php?id=' + assessmentId;
        } else {
            showAlert('ไม่สามารถบันทึกผลการประเมินได้', 'error');
        }
    })
    .catch(error => {
        showAlert('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
    });
}

// Calculate score color
function getScoreClass(percentage) {
    if (percentage >= 80) return 'score-excellent';
    if (percentage >= 60) return 'score-good';
    if (percentage >= 40) return 'score-fair';
    return 'score-poor';
}

// Format date
function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('th-TH', options);
}

// Auto-save answers when radio buttons change
document.addEventListener('change', function(e) {
    if (e.target.matches('input[name^="question_"]')) {
        const questionId = e.target.name.replace('question_', '');
        const assessmentId = document.querySelector('[data-assessment-id]')?.dataset.assessmentId;
        
        if (assessmentId) {
            saveAnswer(assessmentId, questionId, e.target.value);
        }
    }
});
