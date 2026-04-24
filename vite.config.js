import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/welcome-entry.css',
                'resources/css/admin/admin.css',
                'resources/css/admin/admindashboard.css',
                'resources/css/admin/administrators.css',
                'resources/css/admin/adminposts.css',
                'resources/css/admin/comments.css',
                'resources/css/admin/events.css',
                'resources/css/admin/users.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
