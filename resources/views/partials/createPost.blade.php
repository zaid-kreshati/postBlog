<!-- New Post Button -->
<button id="toggleFormButton" class="btn-post" data-bs-toggle="modal" data-bs-target="#newPostModal">{{ __(' New Post') }}</button>

<!-- New Post Modal -->
<div class="modal fade" id="newPostModal" tabindex="-1" aria-labelledby="newPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="width: 70%; margin: 0 auto;">
            <div class="modal-header  text-white" style="background-color: #000;">
                <h5 class="modal-title" id="newPostModalLabel">Create New Post</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="postForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="post-list">
                        <div class="schedule-item">
                            <label for="description">{{ __(' Description') }}:</label>
                            <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}">
                        </div>

                        <!-- Category Selection List -->
                        <label class="schedule-item" for="categoryList">{{ __('Assign Category') }}:</label>
                        <ul id="categoryList" class="list-group">
                            @foreach ($Categories as $category)
                                <li class="list-group-item category-item d-flex justify-content-between align-items-center hover-highlight"
                                    id="category-item-{{ $category->id }}"
                                    value="{{ $category->id }}"
                                    data-category-id="{{ $category->id }}"
                                    data-has-children="{{ $category->children->count() > 0 }}">
                                    {{ $category->name }}
                                    @if ($category->children->count() > 0)
                                        <i class="fas fa-chevron-right"></i>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" name="category_id" id="selectedCategoryId">

                        <!-- Photo Upload -->
                        <div class="form-group mt-3">
                            <label for="photo">Upload Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" multiple>
                        </div>

                        <!-- Video Upload -->
                        <div class="form-group mt-3">
                            <label for="video">Upload Video:</label>
                            <input type="file" name="video" id="video" accept="video/*" multiple>
                        </div>

                        <!-- Tag Someone -->
                        <div class="mt-3 form-group">
                            <label for="UsersDropdown">{{ __('Tag Someone') }}:</label>
                            <select id="UsersDropdown"
                                    class="schedule-item form-control select2"
                                    name="user_ids[]"
                                    multiple="multiple">
                                @foreach ($Users as $user)
                                    <option class="schedule-item" value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Submit Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn-post" name="status" id="status" value="published" data-status="published">Publish</button>
                            <button type="submit" class="btn-post" name="status" value="draft" data-status="draft">Save as Draft</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#UsersDropdown').select2({
            dropdownParent: $('#newPostModal'),
            width: '100%'
        });
    });
</script>

<style>
    .hover-highlight:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .category-item.selected {
        background-color: #e9ecef;
    }

    #selectedCategoryName {
        display: inline-block;
        padding: 0.5em 1em;
    }
</style>
