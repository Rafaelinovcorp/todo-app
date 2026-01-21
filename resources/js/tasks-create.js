document.addEventListener('DOMContentLoaded', () => {
    const radios = document.querySelectorAll('input[name="time_type"]');
    const singleTime = document.getElementById('single-time');
    const rangeTime = document.getElementById('range-time');

    if (!radios.length) return; // segurança

    function updateTimeFields() {
        const value = document.querySelector('input[name="time_type"]:checked')?.value;

        singleTime?.classList.add('hidden');
        rangeTime?.classList.add('hidden');

        document.querySelectorAll('input[type="time"]').forEach(input => {
            input.value = '';
        });

        if (value === 'single') {
            singleTime?.classList.remove('hidden');
        }

        if (value === 'range') {
            rangeTime?.classList.remove('hidden');
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', updateTimeFields);
    });
});
