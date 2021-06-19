    @csrf
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ $project->title }}" class="form-control" placeholder="Project title">
        @error('title')
            <p class="alert alert-danger py-2 text-center">{{ $message }}</p>
        @enderror
    </div> 
    <div class="form-group">
      <label for="description">Description</label>
      <textarea  name="description" id="description" class="form-control" placeholder="project description" >{{ $project->description }}</textarea>
        @error('description')
            <p class="alert alert-danger py-2 text-center">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group">
      <label for="notes">Notes</label>
      <textarea  name="notes" id="notes" class="form-control" placeholder="here you can add notes about your tasks or you may add them later" >{{ $project->notes }}</textarea>
        @error('notes')
            <p class="alert alert-danger py-2 text-center">{{ $message }}</p>
        @enderror
    </div>
    {{-- end of modal body section --}}
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
    </div>
</form>
