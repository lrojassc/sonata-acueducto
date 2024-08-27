import './bootstrap.js';
import './styles/app.css';
import 'datatables.net';
import 'datatables.net-dt';

$('#datatable_invoices_by_user').DataTable({
    language: {
        url: '//cdn.datatables.net/plug-ins/2.0.5/i18n/es-ES.json',
    }
})

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
