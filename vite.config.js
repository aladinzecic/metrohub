import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/login.css',
                'resources/css/mainview.css',
                'resources/css/controllerview.css',
                'resources/css/controllerdashboard.css',
                'resources/css/dispatcherrequests.css',
                'resources/css/dispatcherfleet.css',
                'resources/css/livemapp.css',
                'resources/css/drivers.css',
                'resources/css/dashboard.css',
                'resources/css/myshift.css',
                'resources/css/predeparture.css',
                'resources/css/faults.css',
                'resources/css/workorders.css',
                'resources/css/inspections.css',
                'resources/css/tickets.css',
                'resources/css/schedules.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});