    <div id="category-index">
        <div class="category-index card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="categoriesTable">
                        <thead class="category-table-header">
                            <tr>
                                <th scope="col" style="width: 5%;" class="text-center">#</th>
                                <th scope="col" style="width: 30%;">{{ __('Name') }}</th>
                                <th scope="col" style="width: 30%;">{{ __('Nested Categories') }}</th>
                                <th scope="col" style="width: 15%;" class="text-center">{{ __('Update') }}</th>
                                <th scope="col" style="width: 15%;" class="text-center">{{ __('Delete') }}</th>

                            </tr>
                        </thead>

                        <tbody>
                            @forelse($categories as $category)
                                <tr id="category-{{ $category->id }}" class="text-center">
                                    <td class="text-center">{{ $category->id }}</td>
                                    <td>
                                        <label  id="category-name-{{ $category->id }}" data-name="{{ $category->name }}" for="name">{{ $category->name }}</label>

                                    </td>

                                    <td>
                                        <a href="{{ route('categories.nested', ['id' => $category->id]) }}"
                                            class="btn4">{{ __('Nested Categories') }}</a>

                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="update-category-button btn4"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}">
                                                {{__('Update')}}
                                            </button>

                                        </div>
                                    </td>
                                    <td>
                                        <button class="delete-category-button btn4"
                                                data-id="{{ $category->id }}">{{__('Delete')}}
                                            </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                        No categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </div>

