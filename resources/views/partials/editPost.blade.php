<!-- Edit Post Modal -->

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPostForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="edit-description">Description</label>
                        <input type="text" id="edit-description" name="description" class="form-control" required>
                    </div>



                    <input type="hidden" name="category_id" id="edit-category" >


                    <!-- Category Selection List -->
                    <label class="schedule-item" for="categoryListEdit">{{ __('Assign Category') }}:</label>
                    <ul id="categoryListEdit" class="list-group">
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
                    <input type="hidden" name="category_id" id="selectedCategoryIdEdit">

                    <!-- Current Media Preview -->
                    <div id="current-media" class="form-group mb-3">
                        <label>Current Media</label>
                        <div id="mediaCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- Carousel items will be dynamically inserted here -->
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#mediaCarousel"
                                data-bs-slide="prev" style="width: 40px; margin-top: 250px; margin-bottom: 250px;">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#mediaCarousel"
                                data-bs-slide="next" style="width: 40px; margin-top: 250px; margin-bottom: 250px;">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit-photos">Upload New Photos</label>
                        <input type="file" id="edit-photos" name="photos[]" class="form-control" multiple
                            accept="image/*">
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit-videos">Upload New Videos</label>
                        <input type="file" id="edit-videos" name="videos[]" class="form-control" multiple
                            accept="video/*">
                    </div>

                    <input type="hidden" id="post-id" name="post_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-post" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn-post" id="saveChangesBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    function loadCategories(parentId = null) {
        $.ajax({
            url: parentId ?
                    `{{ route('categories.nested', ['id' => ':parentId']) }}`.replace(
                        ':parentId', parentId) : '{{ route('categories.index') }}',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                let categoryHtml = '';
                // Check if response exists and has data
                if (response.data && Array.isArray(response.data)) {
                    if (parentId) {
                        categoryHtml += `
                            <li class="list-group-item category-item back-category"
                                data-category-id="${response.data.parent_id}"
                                data-has-children="false">
                                <span id="backButton" class="float-start">< Back</span>
                            </li>
                        `;
                    }
                    response.data.forEach(function(category) {
                        categoryHtml += `
                            <li class="list-group-item category-item"
                                data-category-id="${category.id}"
                                data-has-children="${category.has_children ? true : false}">
                                ${category.name}
                                ${category.has_children ? '<span class="float-end">></span>' : ''}
                            </li>
                        `;
                    });
                } else {
                    categoryHtml = '<li class="list-group-item">No categories found</li>';
                }

                $('#categoryListEdit').html(categoryHtml);
            },
            error: function(xhr) {
                console.error('Error loading categories:', xhr);
                $('#categoryListEdit').html(
                    '<li class="list-group-item">Error loading categories</li>');
            }
        });
    }

    $(document).on('click', '.category-item', function() {
        const categoryId = $(this).data('category-id');
        const categoryName = $(this).text().trim();

        selectedCategoryId = categoryId;
        $('#selectedCategoryId').val(selectedCategoryId);
        $('#selectedCategoryName').text(categoryName);

        if ($(this).data('has-children')) {
            loadCategories(categoryId);
        }

        // Highlight selected category
        $('.category-item').removeClass('selected');
        $(this).addClass('selected');
    });

    $(document).on('click', '#backButton', function() {
        loadCategories(null);
    });

    $('#selectCategory').click(function() {
        if (selectedCategoryId) {
            $('#selectedCategoryId').val(selectedCategoryId);
            $('#categoryButton').text($('#selectedCategoryName').text());
            $('#categoryModal').modal('hide');
            // Remove modal backdrop manually
            $('.modal-backdrop').remove();
            // Restore body scrolling
            $('body').removeClass('modal-open').css('overflow', '');
            $('body').css('padding-right', '');
        }
    });

    $('#categoryModal').on('show.bs.modal', function() {
        loadCategories();
    });

    $('#categoryModal').on('hidden.bs.modal', function() {
        $('#categoryListEdit').html('');
        $('#categoryButton').removeClass('btn-success').addClass('btn-primary');
        // Remove modal backdrop and restore body state
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('overflow', '');
        $('body').css('padding-right', '');
    });

    // Update category button text when category is selected
    $(document).on('click', '.category-item', function() {
        if (!$(this).data('has-children')) {
            const categoryName = $(this).text().trim();
            $('#selectedCategoryName').text(categoryName).show();
            $('#categoryButton').addClass('btn-success').removeClass('btn-primary');
        }
    });

           // Handle the "Save changes" button click
           $('#saveChangesBtn').on('click', function() {
            console.log('saveChangesBtn clicked');
            var formData = new FormData($('#editPostForm')[0]);
            var postId = $('#post-id').val();
            var description = $('#edit-description').val();
            var status = $('#status').val();
            var category_id = $('#edit-category').val();

            formData.append('post_id', postId);
            formData.append('description', description);
            formData.append('status', status);
            formData.append('category_id', category_id);

            if (!validateUpdateMediaCount(postId)) {
                $('#edit-photos').val('');
                $('#edit-videos').val('');
                return false;
            }

            $.ajax({
                url: '/posts/' + postId + '/update',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response.data.html);
                        $('#post-list').html(response.data.html);

                        // Close modal and show success message
                        $('#editPostModal').modal('hide');
                        Swal.fire({
                            title: "Post updated successfully!",
                            icon: "success"
                        });

                        // Remove modal backdrop manually
                        $('.modal-backdrop').remove();
                        // Restore body scrolling
                        $('body').removeClass('modal-open').css('overflow', '');
                        $('body').css('padding-right', '');
                        initializePostHandlers(); // Reinitialize handlers after updating post
                    } else {
                        Swal.fire({
                            title: "Error updating post!",
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error updating post!",
                        icon: "error"
                    });
                }
            });
        });

         // Function to initialize post handlers
         function initializePostHandlers() {
            // Reinitialize edit buttons
            $('.edit-post-btn').each(function() {
                initializeEditButton(this);
            });

            // Reinitialize archive buttons
            $('.archive-post-btn').each(function() {
                initializeArchiveButton(this);
            });

            // Reinitialize carousels
            $('.carousel').each(function() {
                new bootstrap.Carousel(this);
            });


        }

        function initializeEditButton(button) {
            $(button).off('click').on('click', function(e) {
                e.preventDefault();

                // First, ensure the modal exists by checking the original modal
                var editModal = $('#editPostModal');

                  // If modal doesn't exist, clone it from the template
        if (editModal.length === 0) {
            // Try to find the modal template
            var modalTemplate = $('#editPostModalTemplate');
            if (modalTemplate.length > 0) {
                $('body').append(modalTemplate.html());
                editModal = $('#editPostModal');
            } else {
                console.error('Edit Post Modal template not found');
                return;
            }
        }

                var postId = $(this).data('id');
                var description = $(this).data('description');
                var category = $(this).data('category');
                var media = $(this).data('media');



                // Set values in the edit modal
                $('#post-id').val(postId);
                $('#edit-description').val(description);
                $('#edit-category').val(category);

                // Clear existing carousel items
                $('.carousel-inner').empty();

                // Update carousel with media
                if (media && media.length > 0) {
                    media.forEach(function(item, index) {
                        var slideHtml = `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                        <div class="position-relative">`;

                        if (item.type === 'post_image') {
                            slideHtml += `
                        <img src="{{ asset('storage/photos/') }}/${item.URL}"
                             alt="Post Photo"
                             class="d-block w-100"
                             style="max-width: 150%; height: 500px; object-fit: cover;">`;
                        } else if (item.type === 'post_video') {
                            slideHtml += `
                        <video class="d-block w-100" controls
                               style="max-width: 150%; height: 500px; object-fit: cover;">
                            <source src="{{ asset('storage/videos/') }}/${item.URL}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>`;
                        }

                        slideHtml += `
                        <button type="button"
                                class="btn btn-danger delete-media-btn position-absolute"
                                data-media-id="${item.id}"
                                style="top: 10px; right: 50px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>`;

                        $('.carousel-inner').append(slideHtml);
                    });

                    // Show/hide carousel controls based on media count
                    if (media.length > 1) {
                        $('.carousel-control-prev, .carousel-control-next').show();
                    } else {
                        $('.carousel-control-prev, .carousel-control-next').hide();
                    }
                } else {
                    // If no media, hide controls and show placeholder
                    $('.carousel-control-prev, .carousel-control-next').hide();
                    $('.carousel-inner').html('<div class="text-center p-3">No media available</div>');
                }

                // Initialize carousel
                var carousel = new bootstrap.Carousel(document.querySelector('#mediaCarousel'), {
                    interval: false // Prevent auto-sliding
                });

                // Show the modal
                $('#editPostModal').modal('show');
            });
        }






</script>
