/**
 * Mock Authentication for Static Version
 * Simulates login/logout
 */

const MockAuth = {
    login(username, password) {
        if (username === 'admin' && password === 'admin123') {
            sessionStorage.setItem('stica_user', JSON.stringify({ name: 'Nurse Alya', role: 'admin' }));
            MockDB.logAction('Login', 'Nurse Alya logged in');
            return true;
        }
        return false;
    },

    logout() {
        MockDB.logAction('Logout', 'User logged out');
        sessionStorage.removeItem('stica_user');
        window.location.href = 'index.html';
    },

    checkSession() {
        const user = sessionStorage.getItem('stica_user');
        if (!user && !window.location.href.includes('index.html')) {
            window.location.href = 'index.html';
        }
        return JSON.parse(user);
    },

    getUser() {
        return JSON.parse(sessionStorage.getItem('stica_user'));
    }
};
