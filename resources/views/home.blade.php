@extends('layouts.PostBlug_header')
@section('title', __('Post Blug'))
@section('content')

<body>
<main>
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @include('partials.posts')
                </div>
            </div>
        </div>
    </section>
</main>



<script>


    $(document).on('click', '[data-user-id]', function() {
        let userId = $(this).data('user-id');
        if (userId) {
            window.location.href = `{{ route('profile.index', ['id' => '']) }}`.replace('profile', 'profile/' + userId);
        } else {
            Swal.fire({
                title: "Error",
                text: 'User ID is not defined.',
                icon: "error"
            });
        }
    });

    window.postLoader = {
            currentPage: 1,
            isLoading: false,
            hasMorePages: true,

            init: function() {
                if (!window.loadEndInitialized) {
                    this.attachScrollHandler();
                    window.loadEndInitialized = true;
                }
            },

            loadMorePosts: function(retryCount = 0) {
                if (this.isLoading || !this.hasMorePages) {
                    if (!this.hasMorePages) {
                        console.log('No more posts');
                    }
                    return;
                }

                this.isLoading = true;

                const home = true;
                const status = 'published';
                const is_owner = false;

                $.ajax({
                    url: '{{ route('posts.load-more') }}',
                    type: 'GET',
                    data: {
                        page: this.currentPage + 1,
                        home: home,
                        status: status,
                        is_owner: is_owner
                    },
                    success: (response) => {
                        console.log('success');
                        if (response.data.html.trim() === '') {
                            this.hasMorePages = false;
                            console.log('no more pages');
                            $('#loading-spinner').hide();

                        } else {
                            this.currentPage++;

                            $('#post-list').append(response.data.html);
                            this.hasMorePages = response.data.hasMorePages;

                        }

                        if (!this.hasMorePages) {
                            $('#loading-spinner').hide();
                        }
                    },
                    error: (xhr) => {
                        console.error('Error loading posts:', xhr);

                        if (retryCount < 3) {
                            setTimeout(() => {
                                this.isLoading = false;
                                this.loadMorePosts(retryCount + 1);
                            }, 1000 * (retryCount + 1));
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: 'Failed to load more posts. Please try again later.',
                                icon: "error"
                            });
                        }
                    },
                    complete: () => {
                        $('#loading-spinner').remove();
                        this.isLoading = false;
                    }
                });
            },

            debounce: function(func, wait) {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            },

            attachScrollHandler: function() {
                $(window).scroll(this.debounce(() => {
                    if (!this.hasMorePages) {
                        $('#loading-spinner').hide();
                        return;
                    }

                    if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                        this.loadMorePosts();
                    }
                }, 250));
            },



        };


          // Initialize when document is ready
          $(document).ready(function() {
            window.postLoader.init();
        });



          // Toggle form visibility
          $(document).on('click', '#toggleCommentForm', function() {
            var postId = $(this).data('id');
            $('#commentForm-' + postId).toggle();
            $('#commentForm2-' + postId).toggle();


            // Hide other comment forms
            $('.commentForm').not('#commentForm-' + postId).hide();
            $('.commentForm').not('#commentForm2-' + postId).hide();
        });

        $(document).off('click', '#replyCommentBtn').on('click', '#replyCommentBtn', function() {
        console.log("reply");
        var commentId = $(this).data('comment-id');
        $('#replyCommentForm-' + commentId).toggle();
    });

        // Add this script to handle the textarea behavior
        document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('keydown', function(e) {
                    if (e.key === ' ') {
                        e.stopPropagation(); // Prevent default space behavior
                    }
                });
            });
            




</script>
@endsection
</body>
