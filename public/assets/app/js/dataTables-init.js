/** Data table  */
$('.bootstrap-datatable').DataTable({
    lengthMenu: [
        [5, 10, 20, 50, -1],
        [5, 10, 20, 50, $('.all').val()]
    ],
    responsive: true,
    rowReorder: {
        selector: 'td:nth-child(2)'
    },
    "language": {
        "lengthMenu": $('.lengthMenu').val(),
        "zeroRecords": $('.zeroRecords').val(),
        "emptyTable": $('.emptyTable').val(),
        "info": $('.info').val(),
        "infoEmpty": $('.infoEmpty').val(),
        "infoFiltered": $('.infoFiltered').val(),
        "search": $('.search').val(),
        "paginate": {
            "first": $('.first').val(),
            "last": $('.last').val(),
            "next": $('.next').val(),
            "previous": $('.previous').val()
        },
    }
});