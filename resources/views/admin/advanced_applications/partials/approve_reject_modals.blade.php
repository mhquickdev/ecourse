{{-- Rejection Note Modal --}}
<div id="reject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="display: none;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Reject Application</h3>
            <div class="mt-2 px-7 py-3">
                <form id="reject-form" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="POST"> {{-- Or PUT, depending on how you structure --}}
                    <input type="hidden" name="application_id" id="rejection-application-id">
                    <div class="mb-4 text-left">
                        <label for="rejection_note" class="block text-gray-700 text-sm font-bold mb-2">Rejection Note:</label>
                        <textarea name="rejection_note" id="rejection_note" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 float-left">
                            Submit Rejection
                        </button>
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 float-right" onclick="hideRejectModal()">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Approve Modal with Course Link and Instructions --}}
<div id="approve-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="display: none;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Approve Application</h3>
            <div class="mt-2 px-7 py-3">
                <form id="approve-form" method="POST">
                    @csrf
                     <input type="hidden" name="_method" value="POST"> {{-- Or PUT --}}
                    <input type="hidden" name="application_id" id="approval-application-id">
                    <div class="mb-4 text-left">
                        <label for="course_link" class="block text-gray-700 text-sm font-bold mb-2">Course Link:</label>
                        <input type="url" name="course_link" id="course_link" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4 text-left">
                        <label for="instruction" class="block text-gray-700 text-sm font-bold mb-2">Instructions:</label>
                        <textarea name="instruction" id="instruction" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 float-left">
                            Confirm Approval
                        </button>
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus::ring-gray-500 float-right" onclick="hideApproveModal()">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 