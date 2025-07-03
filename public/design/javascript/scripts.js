document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('scroll-wrapper');
    const header = document.querySelector('.gantt-header');

    const config = window.ganttConfig;
    const dayWidth = config.dayWidth;
    const startDate = new Date(config.startDate);
    const endDate = new Date(config.endDate);

    startDate.setHours(0, 0, 0, 0);
    endDate.setHours(0, 0, 0, 0);

    function positionElements() {
        document.querySelectorAll('.holiday-marker').forEach(marker => {
            const date = new Date(marker.dataset.date);
            date.setHours(0, 0, 0, 0);
            const offset = Math.floor((date - startDate) / (86400 * 1000));
            marker.style.left = `${offset * dayWidth}px`;
        });


    // NEW: Interruptions (vertical lines)
    document.querySelectorAll('.interruption-marker').forEach(marker => {
        const date = new Date(marker.dataset.date);
        date.setHours(0, 0, 0, 0);
        const offset = Math.floor((date - startDate) / (86400 * 1000));
        marker.style.left = `${offset * dayWidth}px`;
    });


        document.querySelectorAll('.formation-bar, .entreprise-period, .interruption, .validation-bar').forEach(el => {
            const start = new Date(el.dataset.start);
            const end = new Date(el.dataset.end);
            start.setHours(0, 0, 0, 0);
            end.setHours(0, 0, 0, 0);

            const offset = Math.floor((start - startDate) / (86400 * 1000));
            const width = Math.floor((end - start) / (86400 * 1000)) + 1;

            el.style.left = `${offset * dayWidth}px`;
            el.style.width = `${width * dayWidth}px`;
        });

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const todayMarker = document.getElementById('today-marker');
        if (today >= startDate && today <= endDate && todayMarker) {
            const offsetToday = Math.floor((today - startDate) / (86400 * 1000));
            todayMarker.style.left = `${offsetToday * dayWidth}px`;
            todayMarker.style.display = 'block';
        } else if (todayMarker) {
            todayMarker.style.display = 'none';
        }
    }

    positionElements();

    wrapper.addEventListener('scroll', () => {
        header.scrollLeft = wrapper.scrollLeft;
    });

    let isDragging = false;
    let startX, scrollLeft;

    wrapper.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX - wrapper.offsetLeft;
        scrollLeft = wrapper.scrollLeft;
        wrapper.style.cursor = 'grabbing';
        e.preventDefault();
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        wrapper.style.cursor = 'grab';
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - wrapper.offsetLeft;
        const walk = (x - startX) * 2;
        wrapper.scrollLeft = scrollLeft - walk;
    });
});

							
