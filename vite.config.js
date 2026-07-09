import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/login.css',
                'resources/css/mainview.css',
                'resources/css/controllerview.css',
                'resources/css/controllerdashboard.css',
                'resources/css/controllerscan.css',
                'resources/css/custom.css',
                'resources/css/dispatcherfleet.css',
                'resources/css/dispatcherrequests.css',
                'resources/css/driverdashboard.css',
                'resources/css/drivertriporder.css',
                'resources/css/driverview.css',
                'resources/css/livemap.css',
                'resources/css/livemapp.css',
                'resources/css/mechanicinspections.css',
                'resources/css/mechanicworkorders.css',
                'resources/css/myshift.css',
                'resources/css/predeparture.css',
                'resources/css/reportfault.css',
                'resources/css/schedules.css',
                'resources/css/tickets.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});