    <div class="modal fade" id="imagesModal" tabindex="-1" aria-labelledby="imagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="imagesModalLabel">Images</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-success" id="upload-images-btn">
                                    <i class="bx bx-plus"></i> Upload Images
                                </button>
                                <div id="pending-upload-wrapper" class="d-none align-items-center gap-2">
                                    <small class="text-muted" id="pending-upload-text"></small>
                                    <button type="button" class="btn btn-primary btn-sm" id="save-uploaded-images">
                                        <span class="default-label"><i class="bx bx-save"></i> Save to Media</span>
                                        <span class="spinner-border spinner-border-sm d-none" id="save-upload-spinner" role="status"></span>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">Uploads use the Department selected in the form.</small>
                        </div>
                        <div class="col-md-6 mt-2 mt-md-0">
                            <div class="input-group">
                                <span class="input-group-text">Search:</span>
                                <input type="text" class="form-control" id="image-search" placeholder="Search images...">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 50px;">Select</th>
                                    <th style="width: 150px;">Image</th>
                                    <th>Title</th>
                                    <th style="width: 120px;">Width (px)</th>
                                    <th style="width: 120px;">Height (px)</th>
                                </tr>
                            </thead>
                            <tbody id="images-tbody">
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="insert-selected-images">Done</button>
                </div>
            </div>
        </div>
    </div>

    <input type="file" id="image-upload-input" multiple accept="image/*" style="display: none;">

    <script>
        let selectedImages = [];
        let pendingUploads = [];
        let ckeditorInstance = null;

        $(document).ready(function() {
            let checkInterval = setInterval(function() {
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['body-editor']) {
                    ckeditorInstance = CKEDITOR.instances['body-editor'];
                    clearInterval(checkInterval);
                }
            }, 200);
        });

        $('#imagesModal').on('show.bs.modal', function() {
            loadImages();
        });

        function loadImages() {
            const search = $('#image-search').val();
            const companyId = $('select[name="company_id"]').val();

            $.ajax({
                url: "{{ route('settings.pages.images') }}",
                type: 'GET',
                data: {
                    search: search,
                    company_id: companyId
                },
                success: function(response) {
                    const tbody = $('#images-tbody');
                    tbody.empty();

                    if (response.length === 0) {
                        tbody.append('<tr><td colspan="3" class="text-center text-muted">No images found</td></tr>');
                        return;
                    }

                    response.forEach(function(image) {
                        const isSelected = selectedImages.includes(image.id);
                        const widthVal = image.width || '';
                        const heightVal = image.height || '';
                        const row = `
                            <tr>
                                <td class="text-center">
                                    <div class="form-check">
                                        <input class="form-check-input image-checkbox" type="checkbox" value="${image.id}" ${isSelected ? 'checked' : ''}>
                                    </div>
                                </td>
                                <td>
                                    <img src="${image.thumbnail || image.url}" alt="${image.title}" class="img-thumbnail" style="max-width: 100px; max-height: 100px; object-fit: cover;" onerror="this.src='{{ asset('assets/img/placeholder.png') }}'">
                                </td>
                                <td>
                                    <input type="text" class="form-control image-title" value="${image.title}" data-id="${image.id}">
                                </td>
                                <td>
                                    <input type="number" min="1" class="form-control image-width" value="${widthVal}" placeholder="auto">
                                </td>
                                <td>
                                    <input type="number" min="1" class="form-control image-height" value="${heightVal}" placeholder="auto">
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                }
            });
        }

        $(document).on('change', '.image-checkbox', function() {
            const imageId = $(this).val();
            if ($(this).is(':checked')) {
                if (!selectedImages.includes(imageId)) {
                    selectedImages.push(imageId);
                }
            } else {
                selectedImages = selectedImages.filter(id => id !== imageId);
            }
        });

        $('#image-search').on('keyup', function() {
            clearTimeout($(this).data('timeout'));
            const timeout = setTimeout(loadImages, 400);
            $(this).data('timeout', timeout);
        });

        $('#upload-images-btn').on('click', function() {
            $('#image-upload-input').click();
        });

        $('#image-upload-input').on('change', function() {
            const files = Array.from(this.files);
            if (!files.length) {
                return;
            }

            pendingUploads = files;
            $('#pending-upload-text').text(`${files.length} file(s) ready to upload`);
            $('#pending-upload-wrapper').removeClass('d-none');
        });

        $('#save-uploaded-images').on('click', function() {
            if (!pendingUploads.length) {
                return;
            }

            const companyId = $('select[name="company_id"]').val();
            if (!companyId) {
                alert('Select a department before uploading images.');
                return;
            }

            const formData = new FormData();
            formData.append('company_id', companyId);
            pendingUploads.forEach(file => formData.append('images[]', file));

            $('#save-upload-spinner').removeClass('d-none');
            $('#save-uploaded-images .default-label').addClass('d-none');

            $.ajax({
                url: "{{ route('settings.pages.images.upload') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    pendingUploads = [];
                    $('#pending-upload-wrapper').addClass('d-none');
                    $('#save-upload-spinner').addClass('d-none');
                    $('#save-uploaded-images .default-label').removeClass('d-none');
                    $('#image-upload-input').val('');
                    loadImages();
                },
                error: function() {
                    alert('Failed to upload images.');
                    $('#save-upload-spinner').addClass('d-none');
                    $('#save-uploaded-images .default-label').removeClass('d-none');
                }
            });
        });

        $('#insert-selected-images').on('click', function() {
            if (!ckeditorInstance) {
                return;
            }

            const rows = $('#images-tbody tr');
            rows.each(function() {
                const checkbox = $(this).find('.image-checkbox');
                if (checkbox.length && checkbox.is(':checked')) {
                    const imageId = checkbox.val();
                    const url = $(this).find('img').attr('src');
                    const title = $(this).find('.image-title').val() || 'Inserted Image';
                    const width = $(this).find('.image-width').val();
                    const height = $(this).find('.image-height').val();

                    const widthAttr = width ? ` width="${width}"` : '';
                    const heightAttr = height ? ` height="${height}"` : '';

                    ckeditorInstance.insertHtml(`<img src="${url}" alt="${title}"${widthAttr}${heightAttr} />`);
                }
            });

            $('#imagesModal').modal('hide');
        });
    </script>
