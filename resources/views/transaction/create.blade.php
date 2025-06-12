<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('transaction.index') }}"
                        class="px-8 py-2 bg-blue-300 text-center text-sm font-semibold mb-2">Back</a>

                    <div class="flex gap-2 mt-4 w-full">
                        <div class="w-1/4">
                            <x-input-label>Date</x-input-label>
                            <x-text-input class="w-full" id="date" type="date"></x-text-input>
                        </div>

                        <div class="w-1/4">
                            <x-input-label>Chart Of Account</x-input-label>
                            <x-select-input class="w-full" id="coa" :options="$coaOptions" />
                        </div>

                        <div class="w-1/4">
                            <x-input-label>Debit</x-input-label>
                            <x-text-input class="w-full" id="debit" type="number" aria-valuemin="0" value="0"></x-text-input>
                        </div>

                        <div class="w-1/4">
                            <x-input-label>Credit</x-input-label>
                            <x-text-input class="w-full" id="credit" type="number" aria-valuemin="0" value="0"></x-text-input>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4 w-full">
                        <div class="w-full">
                            <x-input-label>Description</x-input-label>
                            <x-textarea-input class="w-full" id="description" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-3">
                        <button class="px-8 py-2 bg-yellow-400 text-center text-sm font-semibold"
                            id="addBtn">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {

                $("#addBtn").on("click", function() {
                    const date = $("#date").val();
                    const coa = $("#coa").val();
                    const debit = $("#debit").val();
                    const credit = $("#credit").val();
                    const description = $("#description").val();

                    showLoading();
                    $.ajax({
                        type: "POST",
                        url: "{{ route('transaction.store') }}",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            date: date,
                            coa_code: coa,
                            debit: debit,
                            credit: credit,
                            description: description,
                        },
                        success: function(response) {
                            hideLoading();
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your data has been added successfully!',
                                icon: 'success'
                            })
                            $("#date").val("");
                            $("#description").val("");
                            $("#debit").val(0);
                            $("#credit").val(0);
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = "";

                                $.each(errors, function(key, value) {
                                    errorMessages += value[0] +
                                        "\n";
                                });

                                hideLoading();
                                Swal.fire({
                                    title: 'Validation Errors!',
                                    text: errorMessages,
                                    icon: 'error'
                                })
                            } else {
                                hideLoading();
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went errors, please try again later!',
                                    icon: 'error'
                                })
                            }
                        }
                    });
                });

            });
        </script>
    @endpush
</x-app-layout>
