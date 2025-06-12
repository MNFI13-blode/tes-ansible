<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex space-x-4">
                        <div class="w-1/5">
                            <a href="{{ route('transaction.create') }}"
                               class="block px-8 py-2 bg-blue-300 text-center text-sm font-semibold mb-2 rounded-md">
                                Add
                            </a>
                        </div>

                        <div class="w-1/5">
                            <button onclick="openModal()"
                                    class="block px-8 py-2 bg-yellow-300 text-center text-sm font-semibold mb-2 rounded-md">
                                Import
                            </button>
                        </div>
                    </div>
                    <br>
                    <table class="w-full border border-gray-300 bg-white shadow-md rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="px-4 py-3 cursor-pointer hover:text-blue-600">Date<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">COA Code<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">COA Name<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Description<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Debit<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Credit<span class="text-xs"> ▼</span></th>
                                <th class="px-4 py-2 cursor-pointer hover:text-blue-600">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal -->
 <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Upload File</h2>
        <form id="uploadForm" enctype="multipart/form-data">
        <div class="mb-4">
            <input required type="file" name="file" id="file"
                   class="block w-full text-sm text-gray-600 border border-gray-300 rounded-md cursor-pointer p-2">
        </div>
        <div class="mb-2">
            <a href="{{ url('data/TemplateImport.xlsx') }}">Click here for download the template</a>
        </div>
        <div class="mt-4 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                Close
            </button>
            <button type="button" id="import-submit" class="px-4 py-2 bg-blue-300 text-gray-800 ml-2 rounded-lg hover:bg-gray-400">
                Submit
            </button>
        </div>
        </form>

    </div>
</div>
    {{-- tambah CSS datatables --}}
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="{{ asset('css/datatables-custom.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">



        <style>
            .dataTables_wrapper .dataTables_filter input {
                @apply px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400;
            }
            .dataTables_wrapper .dataTables_length select {
                @apply px-2 py-1 border border-gray-300 rounded-md;
            }
        </style>
    @endpush

    @push('js')
        {{-- tambah JS datatables --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $("#import-submit").on("click",function(){
                closeModal();
                showLoading();
                let formData = new FormData();
                formData.append("file", $("#file")[0].files[0]);
                $.ajax({
                    type:"POST",
                    url:"{{ route('transaction.import') }}",
                    data:formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success:function(){
                        hideLoading();
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data imported successfully!',
                            icon: 'success'
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr){
                        hideLoading();
                        console.log(xhr)
                        console.log($('meta[name="csrf-token"]').attr('content'));
                        Swal.fire({
                            title: 'Failed!',
                            text: 'Something went errors, please try again!',
                            icon: 'error'
                        })
                    }
                });
            });

            function openModal() {
                document.getElementById('modal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('modal').classList.add('hidden');
            }
            // inisiasi datatables
            $(document).ready(function () {
                $('#transactionTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pagingType: "full_numbers",
                    lengthMenu: [10, 25, 50, 100],
                    ajax: "{{ route('transaction.data') }}",
                    columns: [
                        { data: 'date', name: 'date' },
                        { data: 'coa_code', name: 'chart_of_accounts.code' },
                        { data: 'coa_name', name: 'chart_of_accounts.name' },
                        { data: 'description', name: 'description' },
                        { data: 'debit', name: 'debit' },
                        { data: 'credit', name: 'credit' },
                        { data: 'action', name: 'action', orderable: false, searchable: false },
                    ],
                    dom:
                        "<'flex flex-wrap items-center justify-between mb-4'<'flex items-center space-x-2'l><'flex justify-end'f>>" +
                        "tr" +
                        "<'flex flex-wrap items-center justify-between mt-4'<'text-sm text-gray-600'i><'flex justify-end'p>>",
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        lengthMenu: "Show _MENU_ entries",
                        paginate: {
                            previous: "‹",
                            next: "›",
                            first: "«",
                            last: "»"
                        }
                    },

                    drawCallback: function () {
                        // Style pagination buttons
                        $('.dataTables_paginate').addClass('flex space-x-2');
                        $('.dataTables_paginate a').addClass('px-3 py-1 rounded border border-gray-300 hover:bg-blue-500 hover:text-white');
                        $('.dataTables_paginate .current').addClass('bg-blue-500 font-bold');
                    }
                });
            });

            function handleDelete(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('transaction.destroy', ':id') }}".replace(':id', id),
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                $.ajax({
                                    url: "{{ route('transaction.index') }}",
                                    type: "GET",
                                    success: function(data) {
                                        hideLoading();
                                        $("tbody").html($(data).find("tbody")
                                            .html());
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Your data has been deleted successfully!',
                                            icon: 'success'
                                        })
                                    },
                                    error: function() {
                                        hideLoading();
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Something went errors, please try again later!',
                                            icon: 'error'
                                        })
                                    }
                                });
                            },
                            error: function(xhr) {
                                hideLoading();
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went errors, please try again later!',
                                    icon: 'error'
                                })
                            }
                        });
                    }
                });
            }
        </script>
    @endpush

</x-app-layout>
