document.querySelectorAll('.custom-tabs').forEach(tabs => {

    const buttons = tabs.querySelectorAll('.tab-btn');
    const contents = tabs.querySelectorAll('.tab-content');

    buttons.forEach(button => {
        button.addEventListener('click', () => {

            buttons.forEach(btn => btn.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));

            button.classList.add('active');
            tabs.querySelector('#' + button.dataset.tab).classList.add('active');
        });
    });

});