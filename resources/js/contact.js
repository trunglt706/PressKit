document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contact-form');
    const modal = document.getElementById('contact-status-modal');

    if (!form || !modal) {
        return;
    }

    const modalTitle = document.getElementById('contact-modal-title');
    const modalMessage = document.getElementById('contact-modal-message');
    const modalConfirm = document.getElementById('contact-modal-confirm');
    const modalSpinner = document.getElementById('contact-modal-spinner');
    const modalIcon = document.getElementById('contact-modal-icon');

    const openModal = function (type, title, message) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;

        modalIcon.className = 'material-symbols-outlined contact-modal__icon';

        if (type === 'warning') {
            modalIcon.textContent = 'warning';
            modalIcon.classList.add('is-warning');
        } else if (type === 'loading') {
            modalIcon.textContent = 'hourglass_top';
            modalIcon.classList.add('is-loading');
        } else if (type === 'success') {
            modalIcon.textContent = 'check_circle';
            modalIcon.classList.add('is-success');
        } else {
            modalIcon.textContent = 'error';
            modalIcon.classList.add('is-fail');
        }

        const isLoading = type === 'loading';
        modalSpinner.classList.toggle('hidden', !isLoading);
        modalConfirm.classList.toggle('hidden', isLoading);

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    const closeModal = function () {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    modalConfirm.addEventListener('click', closeModal);

    const validateRequiredFields = function () {
        const requiredFields = form.querySelectorAll('[name="name"], [name="email"], [name="phone"], [name="subject"], [name="message"]');

        for (const field of requiredFields) {
            if (!String(field.value || '').trim()) {
                field.focus();
                return false;
            }
        }

        return true;
    };

    form.addEventListener('submit', function (event) {
        if (!validateRequiredFields()) {
            event.preventDefault();
            openModal('warning', 'Thiếu thông tin', 'Vui lòng nhập đầy đủ nội dung trước khi gửi tin nhắn.');
            return;
        }

        openModal('loading', 'Đang gửi thông tin', 'Vui lòng đợi trong giây lát.');
    });

    const submitStatus = form.dataset.submitStatus || '';
    const submitMessage = form.dataset.submitMessage || '';

    if (submitStatus === 'success') {
        openModal('success', 'Gửi thành công', submitMessage);
    } else if (submitStatus === 'fail') {
        openModal('fail', 'Gửi thất bại', submitMessage);
    }
});
