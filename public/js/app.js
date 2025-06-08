$(document).ready(function () {
    // Initialize DataTables if tables exist
    if ($('#clientsTable').length) {
        $('#clientsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']]
        });
    }

    if ($('#transactionsTable').length) {
        $('#transactionsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }

    if ($('#reportsTable').length) {
        $('#reportsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']]
        });
    }
});