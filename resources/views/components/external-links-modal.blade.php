@props([
'moduleName' => '', // e.g., 'tender', 'job', 'gallery'
'title' => 'Manage External Links' // Modal title
])
<div class="modal fade external-links-modal" id="externalLinksModal_{{ $moduleName }}" tabindex="-1" aria-labelledby="externalLinksModalLabel_{{ $moduleName }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="externalLinksModalLabel_{{ $moduleName }}">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="externalLinksForm_{{ $moduleName }}">
                    <input type="hidden" name="module" value="{{ $moduleName }}">
                    <div id="linksContainer_{{ $moduleName }}"></div>

                    <button type="button" class="btn btn-sm btn-success mt-2" id="addLinkRow_{{ $moduleName }}">
                        <i class="bx bx-plus"></i> Add Another Link
                    </button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveLinks_{{ $moduleName }}">Save Links</button>
            </div>
        </div>
    </div>
</div>