const showRegisterProposalFormBtn = document.getElementById('showRegisterProposalFormBtn');
const cancelRegisterProposalBtn = document.getElementById('cancelRegisterProposalBtn');
const registerProposalFormWrapper = document.getElementById('registerProposalFormWrapper');

showRegisterProposalFormBtn !== null ? showRegisterProposalFormBtn.addEventListener('click', () => showModal(registerProposalFormWrapper)) : null;
cancelRegisterProposalBtn !== null ? cancelRegisterProposalBtn.addEventListener('click', () => hideModal(registerProposalFormWrapper)) : null;

function showModal(modal)
{
    modal.style.display = 'flex';
}

function hideModal(modal)
{
    modal.style.display = 'none';
}