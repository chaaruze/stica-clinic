/**
 * Mock Database for STICA Clinic Static Version
 * Uses localStorage to persist data.
 */

const MockDB = {
    // --- INITIALIZATION ---
    init() {
        if (!localStorage.getItem('stica_students')) this.seedStudents();
        if (!localStorage.getItem('stica_employees')) this.seedEmployees();
        if (!localStorage.getItem('stica_medicines')) this.seedMedicines();
        if (!localStorage.getItem('stica_logs')) this.seedLogs();
    },

    // --- SEED DATA ---
    seedStudents() {
        const students = [
            { id: '0200019234', lastName: 'Dela Cruz', firstName: 'Juan', middleName: 'Santos', course: 'BSIT', contact: '09123456789' },
            { id: '0200019235', lastName: 'Santos', firstName: 'Maria', middleName: 'Reyes', course: 'BSCS', contact: '09234567890' },
            { id: '0200019236', lastName: 'Gonzales', firstName: 'Andres', middleName: 'Cruz', course: 'BSHM', contact: '09345678901' },
            { id: '0200019237', lastName: 'Reyes', firstName: 'Gabriela', middleName: 'Luna', course: 'BSBA', contact: '09456789012' },
            { id: '0200019238', lastName: 'Bautista', firstName: 'Jose', middleName: 'Mercado', course: 'BSIT', contact: '09567890123' }
        ];
        localStorage.setItem('stica_students', JSON.stringify(students));
    },

    seedEmployees() {
        const employees = [
            { id: '2023001', lastName: 'Mendoza', firstName: 'Robert', middleName: 'Lim', position: 'Faculty', contact: '09998887777' },
            { id: '2023002', lastName: 'Garcia', firstName: 'Elena', middleName: 'Tan', position: 'Admin Staff', contact: '09887776666' },
            { id: '2023003', lastName: 'Torres', firstName: 'Miguel', middleName: 'V', position: 'Security', contact: '09776665555' }
        ];
        localStorage.setItem('stica_employees', JSON.stringify(employees));
    },

    seedMedicines() {
        const medicines = [
            { id: 1, name: 'Paracetamol 500mg', stock: 150, unit: 'tablet', expiration: '2026-12-31', status: 'In Stock' },
            { id: 2, name: 'Mefenamic Acid 500mg', stock: 80, unit: 'capsule', expiration: '2025-10-15', status: 'In Stock' },
            { id: 3, name: 'Cetirizine 10mg', stock: 5, unit: 'tablet', expiration: '2024-05-20', status: 'Low Stock' },
            { id: 4, name: 'Amoxicillin 500mg', stock: 0, unit: 'capsule', expiration: '2025-01-01', status: 'Out of Stock' },
            { id: 5, name: 'Bioflu', stock: 200, unit: 'tablet', expiration: '2027-02-28', status: 'In Stock' }
        ];
        localStorage.setItem('stica_medicines', JSON.stringify(medicines));
    },

    seedLogs() {
        const logs = [
            { id: 1, dateTime: '2026-01-19 08:30:00', user: 'Nurse Alya', action: 'Login', details: 'User logged in successfully' },
            { id: 2, dateTime: '2026-01-19 09:15:00', user: 'Nurse Alya', action: 'Add Student', details: 'Added new student: Juan Dela Cruz' },
            { id: 3, dateTime: '2026-01-19 10:00:00', user: 'Nurse Alya', action: 'Dispense', details: 'Dispensed Paracetamol to Student 0200019234' }
        ];
        localStorage.setItem('stica_logs', JSON.stringify(logs));
    },

    // --- GENERIC CRUD ---
    get(table) {
        return JSON.parse(localStorage.getItem('stica_' + table)) || [];
    },

    add(table, item) {
        const data = this.get(table);
        data.push(item);
        localStorage.setItem('stica_' + table, JSON.stringify(data));
        this.logAction('Add ' + getKey(table), `Added record to ${table}`);
    },

    update(table, idField, idValue, newItem) {
        let data = this.get(table);
        const index = data.findIndex(item => item[idField] == idValue);
        if (index !== -1) {
            data[index] = { ...data[index], ...newItem };
            localStorage.setItem('stica_' + table, JSON.stringify(data));
            this.logAction('Update ' + getKey(table), `Updated record in ${table}`);
        }
    },

    delete(table, idField, idValue) {
        let data = this.get(table);
        data = data.filter(item => item[idField] != idValue);
        localStorage.setItem('stica_' + table, JSON.stringify(data));
        this.logAction('Delete ' + getKey(table), `Deleted record from ${table}`);
    },

    logAction(action, details) {
        const logs = this.get('logs');
        const now = new Date();
        const timestamp = now.getFullYear() + '-' +
            String(now.getMonth() + 1).padStart(2, '0') + '-' +
            String(now.getDate()).padStart(2, '0') + ' ' +
            String(now.getHours()).padStart(2, '0') + ':' +
            String(now.getMinutes()).padStart(2, '0') + ':' +
            String(now.getSeconds()).padStart(2, '0');

        logs.unshift({
            id: Date.now(),
            dateTime: timestamp,
            user: 'Guest User',
            action: action,
            details: details
        });
        localStorage.setItem('stica_logs', JSON.stringify(logs));
    }
};

function getKey(table) {
    if (table === 'students' || table === 'employees') return 'Record';
    if (table === 'medicines') return 'Medicine';
    return 'Item';
}

// Auto-init on load
MockDB.init();
