function updateDateTime() {
    const now = new Date();

    const options = {
        weekday: 'long',
        month: 'long',
        day: 'numeric'
    };

    const date = now.toLocaleDateString('en-US', options);

    const time = now.toLocaleTimeString('en-GB', {
        hour: '2-digit',
        minute: '2-digit'
    });

    const element = document.getElementById('dateTime');

    if (element) {
        element.textContent = `${date}  ·  ${time}`;
    }
}

updateDateTime();
setInterval(updateDateTime, 1000);