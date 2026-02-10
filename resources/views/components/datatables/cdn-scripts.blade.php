<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/checkboxes/1.2.13/js/dataTables.checkboxes.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.4.0/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    window.destroy = window.destroy || function(button, url) {
        if (!url) {
            console.error('Destroy URL missing');
            return;
        }

        const confirmation = window.confirm('Are you sure you want to delete this record?');
        if (!confirmation) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';

        const csrf = document.querySelector('meta[name="csrf-token"]');
        if (!csrf) {
            console.error('CSRF token meta tag missing');
            return;
        }

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrf.getAttribute('content');
        form.appendChild(tokenInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    };

    (function($){
        if (!$) { return; }

        $(document).on('click', '.restore-btn', function(e){
            e.preventDefault();

            var $btn = $(this);
            var route = $btn.data('route');
            var id = $btn.data('id');
            var model = $btn.data('model');
            var token = $('meta[name="csrf-token"]').attr('content');

            if (!route || !id || !model) {
                console.error('Restore button missing data attributes.');
                return;
            }
            $btn.prop('disabled', true);
            $.ajax({
                url: route,
                method: 'POST',
                data: {
                    id: id,
                    model: model,
                    _token: token
                },
                success: function () {
                    window.location.reload();
                },
                error: function (xhr) {
                    console.error('Restore failed', xhr.responseText);
                    $btn.prop('disabled', false);
                    alert('Failed to restore record. Please try again.');
                }
            });
        });
    })(window.jQuery);
</script>
