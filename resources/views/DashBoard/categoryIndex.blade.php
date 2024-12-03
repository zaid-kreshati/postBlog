@extends('DashBoard.header')

@section('title', 'Categories')

@section('content')

    <body>
        @csrf
        <!-- Search Input Field -->
        <div class="search-container">
            <input type="text" id="searchBox" placeholder="{{ __('search_categories') }}" autocomplete="off">
            <img src="{{ asset('PostBlug/searchIcon.png') }}" alt="{{ __('search_icon_alt') }}">
        </div>

        <div class="category-container">
            <!-- Button to Open Create Category Modal -->
            <button id="openCreateCategoryModal" class="btn4">{{ __('Create New Category') }}</button>

            <!-- Horizontal Schedule Container -->
            <div class="table-container">
                @include('DashBoard.partials.categoryIndex', ['categories' => $categories])
            </div>
            <input type="hidden" id="parent_id" data-parent-id="{{ $id }}"  value="{{ $id }}">

 <!-- Pagination -->
 <div class="d-flex justify-content-center">
    <div id="pagination-link">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>




        </div>

        <!-- Create Category Modal -->
        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryModalLabel">Create Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createCategoryForm">
                            @csrf
                            <div class="mb-3">
                                <label for="createCategoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="createCategoryName" name="name">
                            </div>
                            <input type="hidden" name="id" id="id" value="{{ $id }}">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Category Modal -->
        <div class="modal fade" id="updateCategoryModal" tabindex="-1" aria-labelledby="updateCategoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateCategoryModalLabel">Update Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateCategoryForm">
                            @csrf
                            @method('POST')
                            <input type="hidden" id="updateCategoryId" name="id" value="{{ $id }}">
                            <div class="mb-3">
                                <label for="updateCategoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="updateCategoryName" name="name">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>



    </body>





    <script>
        $(document).ready(function () {
            // CSRF setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Open Create Modal
            $('#openCreateCategoryModal').click(function () {
                $('#createCategoryModal').modal('show');
            });

            // Handle Create Category
            $('#createCategoryForm').submit(function (e) {
                e.preventDefault();
                const categoryName = $('#createCategoryName').val();
                const id = $('#id').val();

                $.ajax({
                    url: '{{ route('categories.store') }}',
                    method: 'POST',
                    data: {
                        name: categoryName,
                        id:id
                    },
                    success: function (response) {
                        if (response.success) {
                            console.log(response.data);
                            Swal.fire({
                                title: '{{ __('category_created_successfully') }}',
                                icon: 'success'
                            });
                            $('#category-index').html(response.data.html);
                            $('#pagination-link').html(response.data.pagination);
                            $('#createCategoryName').val('');
                            $('#createCategoryModal').modal('hide');
                        } else {
                            Swal.fire({
                                title: '{{ __('failed_to_create_category') }}',
                                icon: 'error'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: '{{ __('error_occurred') }}',
                            icon: 'error'
                        });
                    }
                });
            });

            // Open Update Modal
            $(document).on('click', '.update-category-button', function () {
                const categoryId = $(this).data('id');
                const categoryName = $(this).data('name');



                $('#updateCategoryId').val(categoryId);
                $('#updateCategoryName').val(categoryName);
                $('#updateCategoryModal').modal('show');
            });

            // Handle Update Category
            $('#updateCategoryForm').submit(function (e) {
                e.preventDefault();
                const categoryId = $('#updateCategoryId').val();
                const categoryName = $('#updateCategoryName').val();
                $.ajax({
                    url: '{{ route('categories.update', ':id') }}'.replace(':id', categoryId),
                    method: 'POST',
                    data: {name: categoryName},
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: '{{ __('category_updated_successfully') }}',
                                icon: 'success'
                            });

                            $('#category-name-'+categoryId).text(categoryName);
                            $('#updateCategoryModal').modal('hide');
                        } else {
                            Swal.fire({
                                title: '{{ __('failed_to_update_category') }}',
                                icon: 'error'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: '{{ __('error_occurred') }}',
                            icon: 'error'
                        });
                    }
                });
            });
        });

        $(document).on('click', '.delete-category-button', function(){
            const categoryId = $(this).data('id');
            console.log(categoryId);
            $.ajax({
                url: '{{ route('categories.destroy', ':id') }}'.replace(':id', categoryId),
                method: 'DELETE',
                success: function (response) {
                    console.log(response);
                    $('#category-'+categoryId).remove();
                    $('#pagination-link').html(response.data.pagination);
                    Swal.fire({
                        title: '{{ __('category_deleted_successfully') }}',
                        icon: 'success'
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: '{{ __('error_occurred') }}',
                        icon: 'error'
                    });
                }
            });
        });

        // Handle Pagination Links Click
       $(document).on('click', '.pagination a', function(e) {
           e.preventDefault();
           const page = $(this).attr('href').split('page=')[1];
           const parent_id = $('#parent_id').data('parent-id');
           console.log(parent_id);
           console.log(page);


           fetchCategory(parent_id, page);
       });

       // Search Box Input Event
       const searchBox = $('#searchBox');


       searchBox.on('input', function() {
           const query = this.value.toLowerCase().trim();

               $.ajax({
                   url: '{{ route('categories.search') }}',
                   type: 'POST',
                   data: { search: query },
                   success: function(response) {
                       if (response.success) {
                           $('#category-index').html(response.data);
                       } else {
                           categoriesTableBody.html('<tr><td colspan="4">No categories found.</td></tr>');
                       }
                   },
                   error: function() {
                       alert('An error occurred while searching. Please try again.');
                   }
               });

       });

       // Function to fetch categories for a specific page
       function fetchCategory(parent_id, page) {
           $.ajax({
               url: '{{ route('categories.paginate') }}',
               type: 'POST',
               data: {
                   parent_id: parent_id,
                   page: page
               },
               success: function(response) {
                console.log(response);
                $('#pagination-link').html(response.data.pagination);
                   $('#category-index').html(response.data.html);  // Only update the tbody

               },
               error: function() {
                   alert('An error occurred while fetching categories. Please try again.');
               }
           });
       }

    </script>

@endsection
