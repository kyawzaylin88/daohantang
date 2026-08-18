document.addEventListener('DOMContentLoaded', function () {
    const quizForm = document.getElementById('quizForm');
    if (!quizForm || !window.LESSON_CONFIG) return;

    quizForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn = document.getElementById('submitQuizBtn');
        const feedback = document.getElementById('quizFeedback');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Checking...';

        const formData = new FormData(quizForm);

        try {
            const response = await fetch(window.LESSON_CONFIG.submitUrl, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            feedback.classList.remove('d-none', 'alert-success', 'alert-danger');
            feedback.classList.add(data.success ? 'alert-success' : 'alert-danger');
            feedback.textContent = data.message;

            if (data.success) {
                quizForm.querySelectorAll('input').forEach(function (input) {
                    input.disabled = true;
                });
                btn.classList.add('d-none');

                const nextBtn = document.getElementById('nextStepBtn');
                if (nextBtn) {
                    if (data.lessonComplete) {
                        nextBtn.className = 'btn btn-success';
                        nextBtn.disabled = false;
                        nextBtn.innerHTML = '<i class="fas fa-flag-checkered me-1"></i>Back to Course';
                        if (nextBtn.tagName === 'A') {
                            nextBtn.href = window.LESSON_CONFIG.courseUrl;
                        } else {
                            nextBtn.onclick = function () {
                                window.location.href = window.LESSON_CONFIG.courseUrl;
                            };
                        }
                    } else if (data.nextStep) {
                        const nextUrl = window.LESSON_CONFIG.baseUrl + '&step=' + data.nextStep;
                        if (nextBtn.tagName === 'A') {
                            nextBtn.href = nextUrl;
                            nextBtn.className = 'btn btn-success';
                        } else {
                            nextBtn.className = 'btn btn-success';
                            nextBtn.disabled = false;
                            nextBtn.innerHTML = 'Next <i class="fas fa-chevron-right ms-1"></i>';
                            nextBtn.onclick = function () {
                                window.location.href = nextUrl;
                            };
                        }
                    }
                }

                setTimeout(function () {
                    if (data.nextStep && !data.lessonComplete) {
                        window.location.href = window.LESSON_CONFIG.baseUrl + '&step=' + data.nextStep;
                    } else if (data.lessonComplete) {
                        window.location.href = window.LESSON_CONFIG.courseUrl;
                    }
                }, 1500);
            }
        } catch (err) {
            feedback.classList.remove('d-none');
            feedback.classList.add('alert-danger');
            feedback.textContent = 'An error occurred. Please try again.';
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Quiz';
    });
});
