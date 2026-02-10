<!-- Media Modal -->
<div class="modal fade" id="mediaManagerModal" tabindex="-1" aria-labelledby="mediaManagerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="mediaManagerLabel">Media Library</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3 align-items-center">
                    {{-- Company / Department --}}
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="form-group">
                            <label class="form-label">Website / Department</label>

                            @php
                            $user = auth()->user();
                            $isSuperAdmin = $user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin();
                            @endphp

                            @if($isSuperAdmin)
                            <select id="media-company-id" class="form-control">
                                <option value="">All Web Sites</option>
                                @foreach($companies ?? [] as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Super admin can pick any website.</small>
                            @else
                            <input type="hidden" id="media-company-id" value="{{ $user->company_id ?? '' }}">
                            <p class="mb-0 text-muted">
                                {{ $user->company->title ?? 'Your Web Site' }}
                            </p>
                            <small class="text-muted">You are limited to your own website media.</small>
                            @endif
                        </div>
                    </div>

                    {{-- Upload --}}
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 mt-md-4">
                            <button type="button" class="btn btn-success" id="upload-media-btn">
                                <i class="bx bx-plus"></i> Upload
                            </button>
                            <div id="pending-upload-wrapper" class="d-none align-items-center gap-2">
                                <small class="text-muted" id="pending-upload-text"></small>
                                <button type="button" class="btn btn-primary btn-sm" id="save-uploaded-media">
                                    <span class="default-label"><i class="bx bx-save"></i> Save to Media</span>
                                    <span class="spinner-border spinner-border-sm d-none" id="save-upload-spinner"
                                        role="status"></span>
                                </button>
                            </div>
                        </div>
                        {{-- Resize Options (shown after file selection) --}}
                        <div id="resize-options-wrapper" class="d-none mt-2">
                            <div class="card border-info">
                                <div class="card-body p-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="enable-resize">
                                        <label class="form-check-label" for="enable-resize">
                                            <strong>Resize Images Before Upload</strong>
                                        </label>
                                    </div>
                                    <div id="resize-dimensions" class="mt-2 d-none">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small">Max Width (px)</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="resize-width" placeholder="1920" value="1920" min="100">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">Max Height (px)</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="resize-height" placeholder="1080" value="1080" min="100">
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            Images will maintain aspect ratio
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="col-md-4 mt-2 mt-md-0">
                        <div class="input-group mt-3 mt-md-4">
                            <span class="input-group-text">Search:</span>
                            <input type="text" class="form-control" id="media-search" placeholder="Search media...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width:50px;">Select</th>
                                <th style="width:150px;">Preview</th>
                                <th>Title</th>
                                <th style="width:120px;">Width (px)</th>
                                <th style="width:120px;">Height (px)</th>
                                <th style="width:100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="media-tbody">
                            <tr>
                                <td colspan="6" class="text-center">
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
                <button type="button" class="btn btn-primary" id="media-insert-selected">Done</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Resize Modal -->
<div class="modal fade" id="imageResizeModal" tabindex="-1" aria-labelledby="imageResizeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="imageResizeLabel">Resize Image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Original Image</h6>
                        <div class="border rounded p-2 mb-2" style="min-height: 200px;">
                            <img id="resize-original-img" src="" alt="Original" class="img-fluid">
                        </div>
                        <div id="resize-original-info" class="small text-muted"></div>
                    </div>
                    <div class="col-md-6">
                        <h6>Resized Preview</h6>
                        <div class="border rounded p-2 mb-2" style="min-height: 200px;">
                            <canvas id="resize-preview-canvas" class="img-fluid"></canvas>
                        </div>
                        <div id="resize-preview-info" class="small text-muted"></div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">New Width (px)</label>
                        <input type="number" class="form-control" id="resize-new-width" min="50">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Height (px)</label>
                        <input type="number" class="form-control" id="resize-new-height" min="50">
                    </div>
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="resize-keep-aspect" checked>
                    <label class="form-check-label" for="resize-keep-aspect">
                        Maintain Aspect Ratio
                    </label>
                </div>

                <div class="mt-3">
                    <label class="form-label">Quality (%)</label>
                    <input type="range" class="form-range" id="resize-quality" min="10" max="100" value="85" step="5">
                    <div class="text-center"><span id="resize-quality-value">85</span>%</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-resized-image">
                    <span class="default-label">Save Resized Image</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden file input 
<input type="file" id="media-upload-input" multiple accept="image/*" style="display:none;">-->
<!-- Hidden file input -->
<input type="file" id="media-upload-input" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" style="display:none;">

@push('scripts')
<script>
    // ===== GLOBAL CONTEXT =====
    let mediaManagerContext = {
        mode: null,
        targetInput: null,
        previewTarget: null,
        ckeditorInstance: null,
    };

    let selectedMediaIds = [];
    let pendingUploads = [];
    let currentResizeImage = null;

    const filePlaceholder = @json(asset('assets/img/placeholder.png'));
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    const videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];
    const documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

    // ===== OPEN MODAL FROM ANY BUTTON =====
    $(document).on('click', '.open-media-manager', function() {
        const $btn = $(this);
        const mode = $btn.data('mode') || 'single';
        const targetInput = $btn.data('target-input') || null;
        const ckId = $btn.data('ckeditor-instance') || null;

        mediaManagerContext.mode = mode;
        mediaManagerContext.targetInput = targetInput;
        mediaManagerContext.previewTarget = $btn.data('preview-target') || null;

        const formCompanySelector = $btn.data('company-select') || 'select[name="company_id"]';
        const $formCompany = $(formCompanySelector);
        if ($formCompany.length && $('#media-company-id').is('select')) {
            $('#media-company-id').val($formCompany.val());
        }

        if (mode === 'ckeditor' && ckId && typeof CKEDITOR !== 'undefined') {
            mediaManagerContext.ckeditorInstance = CKEDITOR.instances[ckId] || null;
        } else {
            mediaManagerContext.ckeditorInstance = null;
        }

        selectedMediaIds = [];
        $('#media-search').val('');

        $('#mediaManagerModal').modal('show');
        loadMedia();
    });

    // ===== LOAD MEDIA =====
    function loadMedia() {
        const search = $('#media-search').val();
        const companyId = $('#media-company-id').val();

        $.ajax({
            url: "{{ route('settings.media.images') }}",
            type: 'GET',
            data: {
                search: search,
                company_id: companyId
            },
            success: function(response) {
                const tbody = $('#media-tbody');
                tbody.empty();

                if (!response || response.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center text-muted">No files found</td></tr>');
                    return;
                }

                response.forEach(function(file) {
                    const isSelected = selectedMediaIds.includes(String(file.id));
                    const widthVal = file.width || '';
                    const heightVal = file.height || '';
                    const ext = (file.extension || '').toLowerCase();

                    const isImage = imageExtensions.includes(ext);
                    const isVideo = videoExtensions.includes(ext);
                    const isDocument = documentExtensions.includes(ext);

                    let previewHtml = '';
                    if (isImage) {
                        const src = file.thumbnail || file.url || filePlaceholder;
                        previewHtml = `<img src="${src}" alt="${file.title}" class="img-thumbnail" style="max-width:100px; max-height:100px; object-fit:cover;" onerror="this.src='${filePlaceholder}'">`;
                    } else if (isVideo) {
                        const src = file.thumbnail || file.url || filePlaceholder;
                        previewHtml = `<video src="${src}" class="img-thumbnail" style="max-width:100px; max-height:100px; object-fit:cover;" controls></video>`;
                    } else if (isDocument) {
                        previewHtml = `<div class="d-flex flex-column align-items-center"><img src="${filePlaceholder}" alt="${file.title}" class="img-thumbnail mb-1" style="max-width:80px; max-height:80px; object-fit:cover;"><small class="text-muted">${ext.toUpperCase()}</small></div>`;
                    } else {
                        previewHtml = `<div class="d-flex flex-column align-items-center"><img src="${filePlaceholder}" alt="${file.title}" class="img-thumbnail mb-1" style="max-width:80px; max-height:80px; object-fit:cover;"><small class="text-muted">${ext.toUpperCase() || 'FILE'}</small></div>`;
                    }

                    const resizeBtn = isImage && ext !== 'svg' ?
                        `<button type="button" class="btn btn-sm btn-info resize-media-btn me-1" data-id="${file.id}" data-url="${file.url}" title="Resize"><i class="bx bx-expand"></i></button>` : '';

                    const row = `<tr data-extension="${ext}" data-file-url="${file.url || ''}" data-file-id="${file.id}">
                                                            <td class="text-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input media-checkbox" type="checkbox" value="${file.id}" ${isSelected ? 'checked' : ''}>
                                                                </div>
                                                            </td>
                                                            <td>${previewHtml}</td>
                                                            <td>
                                                                <input type="text" class="form-control media-title" value="${file.title || ''}" data-id="${file.id}">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" class="form-control media-width" value="${widthVal}" placeholder="auto">
                                                            </td>
                                                            <td>
                                                                <input type="number" min="1" class="form-control media-height" value="${heightVal}" placeholder="auto">
                                                            </td>
                                                            <td class="text-center">
                                                                 <!--${resizeBtn}-->
                                                                <button type="button" class="btn btn-sm btn-danger delete-media-btn" data-id="${file.id}" title="Delete">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>`;

                    tbody.append(row);
                });
            },
            error: function(xhr) {
                $('#media-tbody').html('<tr><td colspan="6" class="text-center text-danger">Error loading media</td></tr>');
                console.error('Error loading media:', xhr);
            }
        });
    }

    // Search + company change
    $('#media-search').on('keyup', function() {
        clearTimeout(window.mediaSearchTimeout);
        window.mediaSearchTimeout = setTimeout(loadMedia, 500);
    });

    $('#media-company-id').on('change', function() {
        loadMedia();
    });

    // Selection
    $(document).on('change', '.media-checkbox', function() {
        const id = $(this).val();
        if ($(this).is(':checked')) {
            if (!selectedMediaIds.includes(id)) {
                selectedMediaIds.push(id);
            }
        } else {
            selectedMediaIds = selectedMediaIds.filter(x => x !== id);
        }

        if (mediaManagerContext.mode === 'single' && selectedMediaIds.length > 1) {
            const keep = selectedMediaIds[selectedMediaIds.length - 1];
            selectedMediaIds = [keep];
            $('.media-checkbox').each(function() {
                $(this).prop('checked', $(this).val() === keep);
            });
        }
    });

    // ===== DELETE MEDIA =====
    $(document).on('click', '.delete-media-btn', function() {
        const mediaId = $(this).data('id');
        const row = $(this).closest('tr');
        const title = row.find('.media-title').val() || 'this file';

        if (!confirm(`Are you sure you want to delete "${title}"? This action cannot be undone.`)) {
            return;
        }

        $.ajax({
            url: "{{ route('settings.media.images.delete', ':id') }}".replace(':id', mediaId),
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message || 'Media deleted successfully.');
                loadMedia();
            },
            error: function(xhr) {
                let message = 'Delete failed. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            }
        });
    });

    // ===== RESIZE IMAGE =====
    $(document).on('click', '.resize-media-btn', function() {
        const mediaId = $(this).data('id');
        const imageUrl = $(this).data('url');

        currentResizeImage = {
            id: mediaId,
            url: imageUrl
        };

        const img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = function() {
            $('#resize-original-img').attr('src', imageUrl);
            $('#resize-original-info').text(`Original: ${img.width} x ${img.height} px`);
            $('#resize-new-width').val(img.width);
            $('#resize-new-height').val(img.height);

            updateResizePreview();
            $('#imageResizeModal').modal('show');
        };
        img.src = imageUrl;
    });

    $('#resize-new-width, #resize-new-height').on('input', function() {
        if ($('#resize-keep-aspect').is(':checked')) {
            const img = document.getElementById('resize-original-img');
            const aspectRatio = img.naturalWidth / img.naturalHeight;

            if (this.id === 'resize-new-width') {
                const newWidth = parseInt($(this).val());
                $('#resize-new-height').val(Math.round(newWidth / aspectRatio));
            } else {
                const newHeight = parseInt($(this).val());
                $('#resize-new-width').val(Math.round(newHeight * aspectRatio));
            }
        }
        updateResizePreview();
    });

    $('#resize-quality').on('input', function() {
        $('#resize-quality-value').text($(this).val());
        updateResizePreview();
    });

    $('#resize-keep-aspect').on('change', function() {
        if ($(this).is(':checked')) {
            $('#resize-new-width').trigger('input');
        }
    });

    function updateResizePreview() {
        const img = document.getElementById('resize-original-img');
        const canvas = document.getElementById('resize-preview-canvas');
        const ctx = canvas.getContext('2d');

        const newWidth = parseInt($('#resize-new-width').val()) || img.naturalWidth;
        const newHeight = parseInt($('#resize-new-height').val()) || img.naturalHeight;
        const quality = parseInt($('#resize-quality').val()) / 100;

        canvas.width = newWidth;
        canvas.height = newHeight;

        ctx.drawImage(img, 0, 0, newWidth, newHeight);

        $('#resize-preview-info').text(`Preview: ${newWidth} x ${newHeight} px (${quality * 100}% quality)`);
    }

    $('#save-resized-image').on('click', function() {
        const canvas = document.getElementById('resize-preview-canvas');
        const quality = parseInt($('#resize-quality').val()) / 100;

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('media_id', currentResizeImage.id);
            formData.append('image', blob, 'resized.jpg');

            const saveButton = $('#save-resized-image');
            saveButton.prop('disabled', true);
            saveButton.find('.default-label').addClass('d-none');
            saveButton.find('.spinner-border').removeClass('d-none');

            $.ajax({
                url: "{{ route('settings.media.images.resize') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message || 'Image resized successfully.');
                    $('#imageResizeModal').modal('hide');
                    loadMedia();
                },
                error: function(xhr) {
                    let message = 'Resize failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                },
                complete: function() {
                    saveButton.prop('disabled', false);
                    saveButton.find('.default-label').removeClass('d-none');
                    saveButton.find('.spinner-border').addClass('d-none');
                }
            });
        }, 'image/jpeg', quality);
    });

    // ===== RESIZE DURING UPLOAD =====
    $('#enable-resize').on('change', function() {
        if ($(this).is(':checked')) {
            $('#resize-dimensions').removeClass('d-none');
        } else {
            $('#resize-dimensions').addClass('d-none');
        }
    });

    function resizeImageFile(file, maxWidth, maxHeight) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth || height > maxHeight) {
                        const ratio = Math.min(maxWidth / width, maxHeight / height);
                        width = Math.round(width * ratio);
                        height = Math.round(height * ratio);
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function(blob) {
                        resolve(new File([blob], file.name, {
                            type: 'image/jpeg'
                        }));
                    }, 'image/jpeg', 0.85);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    // Insert selected
    $('#media-insert-selected').on('click', function() {
        if (selectedMediaIds.length === 0) {
            alert('Please select at least one file.');
            return;
        }

        const rows = $('#media-tbody tr');
        const selectedFiles = selectedMediaIds.map(function(id) {
            const row = rows.find(`.media-checkbox[value="${id}"]`).closest('tr');
            const title = row.find('.media-title').val() || '';
            const widthVal = parseInt(row.find('.media-width').val(), 10);
            const heightVal = parseInt(row.find('.media-height').val(), 10);
            const widthAttr = Number.isFinite(widthVal) && widthVal > 0 ? ` width="${widthVal}"` : '';
            const heightAttr = Number.isFinite(heightVal) && heightVal > 0 ? ` height="${heightVal}"` : '';
            const ext = (row.data('extension') || '').toLowerCase();
            const fileUrl = row.data('file-url');

            return {
                id,
                title,
                widthAttr,
                heightAttr,
                ext,
                fileUrl
            };
        });

        if (mediaManagerContext.mode === 'ckeditor' && mediaManagerContext.ckeditorInstance) {
            insertIntoCkeditor(selectedFiles);
        } else {
            insertIntoFormInputs(selectedFiles);
        }

        selectedMediaIds = [];
        $('#mediaManagerModal').modal('hide');
    });

    function insertIntoCkeditor(files) {
        const editor = mediaManagerContext.ckeditorInstance;
        if (!editor) {
            alert('CKEditor is not ready yet.');
            return;
        }

        files.forEach(function(file) {
            if (!file.fileUrl) return;

            const isImage = imageExtensions.includes(file.ext);
            const isVideo = videoExtensions.includes(file.ext);
            const isDocument = documentExtensions.includes(file.ext);

            let html = '';

            if (isImage) {
                html = `<img src="${file.fileUrl}" alt="${file.title}"${file.widthAttr}${file.heightAttr} />`;
            } else if (isVideo) {
                html = `<video controls${file.widthAttr}${file.heightAttr}><source src="${file.fileUrl}" type="video/${file.ext === 'mov' ? 'quicktime' : file.ext}">${file.title || 'Your browser does not support the video tag.'}</video>`;
            } else if (isDocument) {
                html = `<p><a href="${file.fileUrl}" target="_blank" rel="noopener">${file.title || 'Download document'} (${file.ext.toUpperCase()})</a></p>`;
            } else {
                html = `<p><a href="${file.fileUrl}" target="_blank" rel="noopener">${file.title || 'Download file'}${file.ext ? ' (' + file.ext.toUpperCase() + ')' : ''}</a></p>`;
            }

            editor.insertHtml(html);
        });
    }

    function insertIntoFormInputs(files) {
        if (!mediaManagerContext.targetInput) {
            console.warn('No targetInput provided for media manager.');
            return;
        }

        const selector = mediaManagerContext.targetInput;
        const ids = files.map(f => f.id);

        if (mediaManagerContext.mode === 'multiple') {
            const $el = $(selector);
            if ($el.is('select[multiple]')) {
                $el.val(ids).trigger('change');
            } else {
                $el.val(ids.join(',')).trigger('change');
            }
        } else {
            const firstId = ids[0];
            $(selector).val(firstId).trigger('change');
        }

        if (mediaManagerContext.previewTarget) {
            const $preview = $(mediaManagerContext.previewTarget);
            if (!$preview.length) {
                console.warn('Preview target not found:', mediaManagerContext.previewTarget);
                return;
            }

            const file = files[0];
            if (!file || !file.fileUrl) {
                $preview.html('<span class="text-muted">No media selected</span>');
                return;
            }

            const ext = (file.ext || '').toLowerCase();
            const isImage = imageExtensions.includes(ext);
            const isVideo = videoExtensions.includes(ext);
            const isDocument = documentExtensions.includes(ext);

            let html = '';

            if (isImage) {
                html = `<div class="position-relative d-inline-block"><img src="${file.fileUrl}" alt="${file.title}" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;"></div>`;
            } else if (isVideo) {
                html = `<div class="mt-2"><video src="${file.fileUrl}" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;" controls></video><div class="small text-muted mt-1">${file.title || ''} (${ext.toUpperCase()})</div></div>`;
            } else if (isDocument) {
                html = `<div class="mt-2"><a href="${file.fileUrl}" target="_blank" rel="noopener">${file.title || 'View document'} (${ext.toUpperCase()})</a><div class="small text-muted mt-1"></div></div>`;
            } else {
                html = `<div class="mt-2"><a href="${file.fileUrl}" target="_blank" rel="noopener">${file.title || 'View file'}${ext ? ' (' + ext.toUpperCase() + ')' : ''}</a><div class="small text-muted mt-1"></div></div>`;
            }

            $preview.html(html);
        }
    }

    // Upload
    function resetPendingUploads() {
        pendingUploads = [];
        $('#media-upload-input').val('');
        $('#pending-upload-wrapper').addClass('d-none').removeClass('d-flex');
        $('#pending-upload-text').text('');
        $('#resize-options-wrapper').addClass('d-none');
        $('#enable-resize').prop('checked', false);
        $('#resize-dimensions').addClass('d-none');
    }

    $('#upload-media-btn').on('click', function() {
        $('#media-upload-input').click();
    });

    $('#media-upload-input').on('change', function(e) {
        pendingUploads = Array.from(e.target.files);
        if (pendingUploads.length === 0) {
            resetPendingUploads();
            return;
        }

        // Check if any images are selected
        const hasImages = pendingUploads.some(file => file.type.startsWith('image/'));

        $('#pending-upload-text').text(`${pendingUploads.length} file(s) selected`);
        $('#pending-upload-wrapper').removeClass('d-none').addClass('d-flex');

        if (hasImages) {
            $('#resize-options-wrapper').removeClass('d-none');
        }
    });

    $('#save-uploaded-media').on('click', async function() {
        if (pendingUploads.length === 0) return;

        const companyId = $('#media-company-id').val();

        // For super admin, require company selection
        @if($isSuperAdmin)
        if (!companyId) {
            alert('Please select a Company before uploading media.');
            return;
        }
        @endif

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        if (companyId) {
            formData.append('company_id', companyId);
        }

        // Check if resize is enabled
        const shouldResize = $('#enable-resize').is(':checked');
        const maxWidth = parseInt($('#resize-width').val()) || 1920;
        const maxHeight = parseInt($('#resize-height').val()) || 1080;
        formData.append('should_resize', shouldResize);
        formData.append('max_width', maxWidth);
        formData.append('max_height', maxHeight);
        const saveButton = $('#save-uploaded-media');
        const spinner = $('#save-upload-spinner');
        saveButton.prop('disabled', true);
        saveButton.find('.default-label').addClass('d-none');
        spinner.removeClass('d-none');

        try {
            // Process files (resize if needed)
            // Process files (resize if needed)
            for (let i = 0; i < pendingUploads.length; i++) {
                const file = pendingUploads[i];
                // Only resize images
                if (shouldResize && file.type.startsWith('image/') && !file.type.includes('svg')) {
                    const resizedFile = await resizeImageFile(file, maxWidth, maxHeight);
                    formData.append(`images[${i}]`, resizedFile);
                } else {
                    formData.append(`images[${i}]`, file);
                }
            }


            $.ajax({
                url: "{{ route('settings.media.images.upload') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    resetPendingUploads();
                    alert(response.message || 'Media uploaded successfully.');
                    loadMedia();
                },
                error: function(xhr) {
                    let message = 'Upload failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                },
                complete: function() {
                    saveButton.prop('disabled', false);
                    saveButton.find('.default-label').removeClass('d-none');
                    spinner.addClass('d-none');
                }
            });
        } catch (error) {
            console.error('Error processing files:', error);
            alert('Error processing files. Please try again.');
            saveButton.prop('disabled', false);
            saveButton.find('.default-label').removeClass('d-none');
            spinner.addClass('d-none');
        }
    });

    $('#mediaManagerModal').on('hidden.bs.modal', function() {
        selectedMediaIds = [];
        resetPendingUploads();
    });
</script>
@endpush