<!-- Modal -->
<div class="modal fade" id="carModel" tabindex="-1" role="dialog" aria-labelledby="carModelTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carModelTitle">{{ __('trans.all car model') }}</h5>
                <div id="messagesSection">
{{--                    <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="show"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('trans.close') }}</button>
                <button id="save_data" type="button" class="btn btn-primary">{{ __('trans.save') }}</button>
            </div>
        </div>
    </div>
</div>

