const showRegisterProposalFormBtn = document.querySelector('#showRegisterProposalFormBtn');
const cancelRegisterProposalBtn = document.querySelector('#cancelRegisterProposalBtn');
const formWrapper = document.querySelector('.formWrapper');

showRegisterProposalFormBtn !== null ? showRegisterProposalFormBtn.addEventListener('click', () => showModal(formWrapper)) : null;
cancelRegisterProposalBtn !== null ? cancelRegisterProposalBtn.addEventListener('click', () => hideModal(formWrapper)) : null;

function showModal(modal)
{
    modal.style.display = 'flex';
}

function hideModal(modal)
{
    modal.style.display = 'none';
}