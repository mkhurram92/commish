<div class="modal fade edit-note-modal" id="edit-note-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route("admin.deals.addNote",['deal_id'=>encrypt($note->deal_id)]) }}" onsubmit="return saveNoteForm(this)" id="add_note_form">
                    @csrf
                    <div class="form-row">
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ $note->id }}"/>
                        <div class="col-12">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Note</label>
                                <input type="text" name="note" id="note" class="form-control" maxlength="255" value="{{ $note->note }}" />
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="mt-1 btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>
