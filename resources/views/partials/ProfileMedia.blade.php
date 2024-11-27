<div id="profile-media">
    <!-- Cover Image -->
    <div class="img-fluid img-thumbnail mt-4 mb-2 position-relative" style="position: relative;">
        @if ($cover_image)
            <img id="coverImage" name="cover_image" src="{{ asset('storage/photos/' . $cover_image->URL) }}" alt="Profile photo"
                 style="width: 100%; height: 250px; z-index: 1">
        @else
            <img id="coverImage" src="{{ asset('/PostBlug/default cover image.jpeg') }}" alt="Default cover image"
                 style="width: 100%; height: 250px; z-index: 1">
        @endif

        <!-- Cover Image Form -->
        @if ($is_owner)
            <img src="{{ asset('PostBlug/takePhoto.png') }}" alt="{{ __('Edit') }}" class="icon4" data-bs-toggle="modal"
            data-bs-target="#backgroundPhotoModal">
        @endif
    </div>





    <!-- Profile Image -->
    <div class="rounded-top  d-flex flex-row" style=" height:80px;">
        <div class="position-relative" style="bottom: 130px; left: 10px; z-index: 1;">
            @if ($profile_image)
                <img id="profileImage" name="profile_image" src="{{ asset('storage/photos/' . $profile_image->URL) }}"
                    alt="Profile photo" class="img-fluid img-thumbnail mt-4 mb-2"
                    style="width: 150px; margin-left: -30px;">
            @else
                <img id="profileImage" src="{{ asset('/PostBlug/default-profile.png') }}" alt="Default profile photo"
                    class="img-fluid img-thumbnail mt-4 mb-2" style="width: 150px; margin-left: -30px;">
            @endif

            <!-- Profile Name -->
            <div
                style="position: relative; top: 10px; font-size: 45px; margin: unset; margin-right: -235px; margin-top: -95px; padding: inherit">
                {{ $name }}</div>


            <!-- Profile Image Form -->
            @if ($is_owner)
                <img src="{{ asset('PostBlug/takePhoto.png') }}" alt="{{ __('Edit') }}" class="icon5" data-bs-toggle="modal" data-bs-target="#profilePhotoModal">
            @endif
        </div>
    </div>


      <!-- Cover Modal -->
      <div class="modal fade" id="backgroundPhotoModal" role="dialog" tabindex="-1" aria-labelledby="backgroundPhotoModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="backgroundPhotoModalLabel">Edit Cover Image</h5>
                </div>
                <form id="backgroundPhotoForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" name="cover_image" id="backgroundPhotoInput" style="display: none;" accept="image/*">
                    <label for="backgroundPhotoInput" class="btn-post" style="cursor: pointer;">Upload New Image</label>
                    <button type="button" class="btn-post" id="removeCoverImage">Remove Image</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Profile Modal -->
    <div class="modal fade" id="profilePhotoModal" role="dialog" tabindex="-1" aria-labelledby="profilePhotoModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profilePhotoModalLabel">Edit Profile Image</h5>
                </div>
                <form id="profilePhotoForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" name="profile_image" id="profilePhotoInput" style="display: none;  " accept="image/*">
                    <label for="profilePhotoInput" class="btn-post" style="cursor: pointer;">Upload New Image</label>
                    <button type="button" class="btn-post" id="removeProfileImage">Remove Image</button>
                </form>
            </div>
        </div>
    </div>




</div>

<script>
    // Background Photo Upload
    var cover_image = document.getElementById('coverImage');
    $('#backgroundPhotoInput').on('change', function() {
        // Create FormData object
        const formData = new FormData();
        var file = $(this)[0].files[0];

        formData.append('cover_image', file);

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');


        // Make AJAX request
        $.ajax({
            url: '{{ route('profile.upload-background-image') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Wait one second then update the cover image
                        console.log(response.data);
                        const imageUrl = response.data;
                        const fullPath = '{{ asset('storage/photos') }}/' + imageUrl;
                        $('#coverImage').attr('src', fullPath);
                        $('#backgroundPhotoModal').hide();
                        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Cover image updated successfully!',
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });

                        // Remove modal backdrop manually
                        $('.modal-backdrop').remove();
                        // Restore body scrolling
                        $('body').removeClass('modal-open').css('overflow', '');
                        $('body').css('padding-right', '');
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message || 'Error updating background image',
                        icon: "error"
                    });
                }
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        Swal.fire({
                            title: "Error",
                            text: errors[key][0],
                            icon: "error"
                        });
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: 'An error occurred while uploading the image',
                        icon: "error"
                    });
                }
            },
            complete: function() {
                // Re-enable the upload button
                $('#backgroundPhotoInput').prop('disabled', false);
            }
        });
    });

// Remove Cover Image
$('#removeCoverImage').on('click', function() {


    // Make AJAX request to remove the cover image
    $.ajax({
        url: '{{ route('profile.remove-cover-image') }}',
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                    $('#coverImage').attr('src', '{{ asset('/PostBlug/default cover image.jpeg') }}');
                    $('#backgroundPhotoModal').hide();
                    Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Cover image removed successfully!',
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });

                    // Remove modal backdrop manually
                    $('.modal-backdrop').remove();
                    // Restore body scrolling
                    $('body').removeClass('modal-open').css('overflow', '');
                    $('body').css('padding-right', '');
            } else {
                Swal.fire({
                    title: "Error",
                    text: response.message || 'Error removing cover image',
                    icon: "error"
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'there is no cover image',
        });
        },
        complete: function() {

        }
    });
});


    // Profile Photo Upload
    var profile_image = document.getElementById('profileImage');
    $('#profilePhotoInput').on('change', function() {
        // Create FormData object
        const formData = new FormData();
        var file = $(this)[0].files[0];

        formData.append('profile_image', file);

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');



        // Disable the upload button
        $(this).prop('disabled', true);


        // Make AJAX request
        $.ajax({
            url: '{{ route('profile.upload-profile-image') }}',
            type: 'POST',
            data: formData,

            processData: false,
            contentType: false,

            success: function(response) {
                if (response.success) {

                    // Update the profile image
                    const imageUrl = response.data.photo_path;
                    const fullPath = '{{ asset('storage/photos') }}/' + imageUrl;
                    $('#profileImage').attr('src', fullPath);
                    $('#profilePhotoModal').hide();
                   // Show success message with SweetAlert2
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Profile image updated successfully!',
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });
                    $('#post-list').html(response.data.html); // Update task list
                    // Remove modal backdrop manually
                    $('.modal-backdrop').remove();
                    // Restore body scrolling
                    $('body').removeClass('modal-open').css('overflow', '');
                    $('body').css('padding-right', '');

                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.message || 'Error updating profile image',
                        icon: "error"
                    });
                }
            },
            error: function(xhr) {
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        Swal.fire({
                            title: "Error",
                            text: errors[key][0],
                            icon: "error"
                        });
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: 'An error occurred while uploading the image',
                        icon: "error"
                    });
                }
            },
            complete: function() {


                // Re-enable the upload button
                $('#profilePhotoInput').prop('disabled', false);

                // Reset the form
                $('#profilePhotoForm')[0].reset();
            }
        });
    });

// Remove Profile Image
$('#removeProfileImage').on('click', function() {


    // Make AJAX request to remove the profile image
    $.ajax({
        url: '{{ route('profile.remove-profile-image') }}',
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                    $('#profileImage').attr('src', '{{ asset('/PostBlug/default-profile.png') }}');
                    $('#profilePhotoModal').hide();
                    Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Profile image removed successfully!',
            timer: 1000,
            timerProgressBar: true,
            showConfirmButton: false
        });
                    // Remove modal backdrop manually
                    $('.modal-backdrop').remove();
                    // Restore body scrolling
                    $('body').removeClass('modal-open').css('overflow', '');
                    $('body').css('padding-right', '');
            } else {
                Swal.fire({
                    title: "Error",
                    text: response.message || 'Error removing profile image',
                    icon: "error"
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'there is no profile image',
        });
        },
        complete: function() {

        }
    });
});
</script>
