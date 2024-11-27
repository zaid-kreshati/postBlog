// $(document).on('keydown', 'textarea[name="text"]', function(e) {
    //     if (e.key === 'Enter' && !e.shiftKey) {
    //         e.preventDefault();
    //         var postId = $(this).closest('form').find('input[name="post_id"]').val();
    //         var text = $(this).val();

    //         let formData = {
    //             _token: '{{ csrf_token() }}',
    //             post_id: postId,
    //             text: text
    //         };

    //         $.ajax({
    //             url: '{{ route('comment.store') }}',
    //             type: 'POST',
    //             data: formData,
    //             success: function(response) {
    //                 if (response.success) {
    //                     // Add new comment to the comments section
    //                     let html = `
    //                     <div class="comments-section">
    //                         <div class="comment">
    //                             <div class="comment-header">
    //                                 ${response.data.personal_image ?
    //                                     `<img src="{{ asset('storage/photos/') }}/${response.data.personal_image.URL}"
    //                                          alt="Profile photo"
    //                                          class="img-fluid rounded-circle"
    //                                          style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">` :
    //                                     `<img src="{{ asset('/PostBlug/default-profile .png') }}"
    //                                          alt="Profile photo"
    //                                          class="img-fluid rounded-circle"
    //                                          style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">`
    //                                 }
    //                                 <div style="right: 750px; font-size: 15px; margin-top: -40px; position: relative;">
    //                                     ${response.data.name}
    //                                 </div>
    //                             </div>
    //                             <div class="comment-content">
    //                                 ${response.data.comment.text}
    //                             </div>
    //                             <div class="comment-actions">
    //                                 <button class="comment-btn reply-btn" id="replyCommentBtn" data-comment-id="${response.data.comment.id}" >
    //                                     <i class="fas fa-reply"></i>
    //                                     <div>{{ __('Reply') }}</div>
    //                                 </button>
    //                             </div>

    //                             <div class="comment-replies"></div>
    //                         </div>

    //                            <!-- Nested Comment Form -->
    //                     <form action="{{ route('comment.store.nested') }}" method="POST" id="replyCommentForm"
    //                          class="comment-form" style="display: none;">
    //                         @csrf
    //                         <div class="nested-comment-form">
    //                             @include('partials.nested-comment', ['comment' => response.data.nestedComments])
    //                         </div>

    //                             <label for="nested-comment">{{ __('Reply') }}:</label>
    //                             <input type="hidden" name="parent_id" id="comment-id" value="${response.data.comment.id}">
    //                             <input type="hidden" name="post_id" id="post-id" value="${postId}">
    //                             <textarea name="nested_text" class="comment-input" placeholder="Write a comment..."
    //                                     id="nested-comment-${response.data.comment.id}"
    //                                 rows="2">{{ old('nested_text') }}</textarea>
    //                             </form>


    //                     </div>`;

    //                     // Insert the new comment after the comment form
    //                     $('#commentForm-' + postId).append(html);

    //                     $('textarea[id="comment-textarea->' + postId + '"]').val('');


    //                     // Clear the input
    //                     $(this).val('');

    //                 } else {
    //                     alert('Error submitting comment: ' + response.message);
    //                 }
    //             },
    //             error: function(xhr) {
    //                 alert('Error submitting comment. Please try again.');
    //             }
    //         });
    //     }
    // });
