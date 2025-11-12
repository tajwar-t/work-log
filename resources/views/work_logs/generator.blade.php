@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6 text-gray-900">Work Log Generator</h1>

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Left: Form --}}
        <div class="lg:w-1/2 bg-white p-6 rounded-2xl shadow space-y-6">
            
            <form id="workLogForm" class="space-y-4">
                @csrf

                <div class="grid sm:grid-cols-2 gap-4 space-y-4">
                    {{-- Template Type --}}
                    <div>
                        <label class="block font-semibold mb-1 text-gray-700">Select Template Type:</label>
                        <select name="template_type" id="template_type" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="day_start">Day Start</option>
                            <option value="day_end">Day End</option>
                        </select>
                    </div>

                    {{-- Date Picker --}}
                    <div class="!mt-0">
                        <label class="block font-semibold mb-1 text-gray-700">Select Date:</label>
                        <input type="date" name="log_date" class="w-full border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                {{-- Smart Suggestions --}}
                <div class="space-y-4">
                    <label class="block font-semibold mb-2 text-gray-700">Smart Suggestions:</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-lg" onclick="fillSmartSuggestion(1)">Yesterday's Work</button>
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-lg" onclick="fillSmartSuggestion(2)">2 Days Ago</button>
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-lg" onclick="fillSmartSuggestion(3)">3 Days Ago</button>
                    </div>
                </div>
                <div id="loadingIndicator" class="text-blue-600 mb-4 hidden">Fetching previous work...</div>
                {{-- Sections Wrapper --}}
                <div id="sectionsWrapper" class="space-y-4">
                    {{-- Day Start Section --}}
                    <div id="dayStartSection" class="section">
                        <div class="flex items-center justify-between mt-3 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Last Day Work</h3>
                            <button type="button" onclick="addItem('dayStartLastDayWrapper','day_start_last_day[]')" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">+ Add Item</button>
                        </div>
                        <div id="dayStartLastDayWrapper" class="space-y-2"></div>

                        <div class="flex items-center justify-between mt-3 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Today's Work:</h3>
                            <button type="button" onclick="addItem('dayStartTodayWrapper','day_start_today[]')" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">+ Add Item</button>
                        </div>
                        <div id="dayStartTodayWrapper" class="space-y-2"></div>
                    </div>

                    {{-- Day End Section --}}
                    <div id="dayEndSection" class="section hidden">
                        <div class="flex items-center justify-between mt-3 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Today's Work:</h3>
                            <button type="button" onclick="addItem('dayEndTodayWrapper','day_end_today[]')" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">+ Add Item</button>
                        </div>
                        <div id="dayEndTodayWrapper" class="space-y-2"></div>

                        <div class="flex items-center justify-between mt-3 mb-6">
                            <h3 class="ftext-lg font-semibold text-gray-800">Tomorrow's Work:</h3>
                            <button type="button" onclick="addItem('dayEndTomorrowWrapper','day_end_tomorrow[]')" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">+ Add Item</button>
                        </div>
                        <div id="dayEndTomorrowWrapper" class="space-y-2"></div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-5">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all font-medium text-lg shadow-lg hover:shadow-xl transform hover:scale-105">Generate Template</button>
                    <button type="button" id="saveWorkLog" class="flex-1 flex items-center justify-center gap-2 py-3 px-6 rounded-2xl font-medium text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed bg-gradient-to-r from-purple-500 to-purple-600 text-white hover:from-purple-600 hover:to-purple-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save h-5 w-5" aria-hidden="true"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path><path d="M7 3v4a1 1 0 0 0 1 1h7"></path></svg>
                        Save Work Log
                    </button>
                </div>
            </form>

        </div>

        {{-- Right: Generated Template --}}
        <div class="lg:w-1/2 bg-gray-50 p-6 rounded-2xl shadow space-y-4">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Generated Template:</h2>
            <div id="generatedTemplate" class="p-4 border border-gray-300 rounded-lg bg-white min-h-[400px] max-h-[600px] overflow-auto text-gray-800 font-mono text-sm">
                <div class="text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text h-12 w-12 text-gray-400 mx-auto mb-4" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg><p class="text-lg font-medium">Your generated template will appear here</p><p class="text-sm mt-2">Fill in your work items and click "Generate Template"</p></div>
            </div>
            <button onclick="copyTemplate()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Copy Template</button>
        </div>

    </div>

    {{-- Toast container --}}
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>
</div>

{{-- Optional: small fade-in animation --}}
<style>
.fa-solid, .fas {
    font-weight: 400;
}
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>

<script>
const templateTypeSelect = document.getElementById('template_type');
const dayStartSection = document.getElementById('dayStartSection');
const dayEndSection = document.getElementById('dayEndSection');
const loadingIndicator = document.getElementById('loadingIndicator');

// Toggle Day Start / Day End sections
function toggleSections() {
    if (templateTypeSelect.value === 'day_start') {
        dayStartSection.classList.remove('hidden');
        dayEndSection.classList.add('hidden');

        // Clear Day Start inputs
        clearWrapper('dayStartLastDayWrapper');
        clearWrapper('dayStartTodayWrapper');

    } else {
        dayStartSection.classList.add('hidden');
        dayEndSection.classList.remove('hidden');

        // Clear Day End inputs
        clearWrapper('dayEndTodayWrapper');
        clearWrapper('dayEndTomorrowWrapper');
    }
}
// Utility function to clear a wrapper
function clearWrapper(wrapperId) {
    const wrapper = document.getElementById(wrapperId);
    wrapper.innerHTML = '';
    // Add one empty input to keep structure
    let name = wrapperId.includes('LastDay') ? 'day_start_last_day[]' :
               wrapperId.includes('Today') && wrapperId.includes('dayStart') ? 'day_start_today[]' :
               wrapperId.includes('Today') ? 'day_end_today[]' : 'day_end_tomorrow[]';
    addItem(wrapperId, name);
}
templateTypeSelect.addEventListener('change', () => { toggleSections(); fetchPreviousWork(); });
toggleSections();

// Add / Remove Items
function addItem(wrapperId, inputName, value = '') {
    const wrapper = document.getElementById(wrapperId);
    const div = document.createElement('div');
    div.classList.add('flex', 'mb-2', 'items-center');
    div.innerHTML = `
        <input type="text" name="${inputName}" value="${value}" class="w-full border p-2 rounded mr-2" placeholder="Enter work item">
        <span onclick="removeItem(this)" class="text-red-500 cursor-pointer hover:text-red-700 ml-1">
            <i class="fas fa-trash-alt"></i>
        </span>
    `;
    wrapper.appendChild(div);
}

function removeItem(button) { button.parentElement.remove(); }

document.getElementById('saveWorkLog').addEventListener('click', async function() {
    const formData = new FormData(document.getElementById('workLogForm'));
    formData.append('save_only', 1); // Flag to indicate save without generating template

    try {
        const response = await fetch('{{ route("worklog.save.ajax") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showToast('Work log saved successfully!', 3000);
        } else {
            alert('Failed to save work log.');
        }
    } catch (err) {
        console.error(err);
        alert('Error saving work log.');
    }
});


// Populate wrapper while preserving existing user inputs
function populateWrapper(wrapperId, inputName, values = []) {
    const wrapper = document.getElementById(wrapperId);
    const existingValues = Array.from(wrapper.querySelectorAll('input')).map(input => input.value).filter(v => v.trim() !== '');
    
    wrapper.innerHTML = '';

    // Merge existing values with new values (prevent duplicates)
    const mergedValues = [...new Set([...existingValues, ...values])];
    if (mergedValues.length === 0) mergedValues.push('');

    mergedValues.forEach((v, idx) => {
        addItem(wrapperId, inputName, v);
        // Highlight auto-filled items
        if (values.includes(v)) {
            const inputEl = wrapper.querySelectorAll('input')[idx];
            inputEl.style.backgroundColor = '#f0f9ff';
            setTimeout(() => inputEl.style.backgroundColor = '', 2000);
        }
    });
}

// Initialize empty inputs
['dayStartLastDayWrapper','dayStartTodayWrapper','dayEndTodayWrapper','dayEndTomorrowWrapper'].forEach(id => {
    let name = id.includes('LastDay') ? 'day_start_last_day[]' :
               id.includes('Today') && id.includes('dayStart') ? 'day_start_today[]' :
               id.includes('Today') ? 'day_end_today[]' : 'day_end_tomorrow[]';
    addItem(id, name);
});

// --- Fetch Previous Work ---
async function fetchPreviousWork() {
    const type = templateTypeSelect.value;
    const date = document.querySelector('input[name="log_date"]').value;

    loadingIndicator.classList.remove('hidden');
    console.log("🟢 fetchPreviousWork()", { type, date });

    try {
        const response = await fetch('{{ route("worklog.fetch.previous") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ template_type: type, log_date: date })
        });

        console.log("🔵 Response status:", response.status);

        if (!response.ok) return console.error('Server responded with:', response.status);

        const data = await response.json();
        if (type === 'day_start') {
            populateWrapper('dayStartLastDayWrapper', 'day_start_last_day[]', data.lastDayWork || []);
            populateWrapper('dayStartTodayWrapper', 'day_start_today[]', data.todayWork || []);
        } else {
            populateWrapper('dayEndTodayWrapper', 'day_end_today[]', data.todayWork || []);
            populateWrapper('dayEndTomorrowWrapper', 'day_end_tomorrow[]', data.tomorrowWork || []);
        }

    } catch (err) {
        console.error('🔥 Fetch failed:', err);
    } finally {
        loadingIndicator.classList.add('hidden');
    }
}
document.querySelector('input[name="log_date"]').addEventListener('change', fetchPreviousWork);
document.addEventListener('DOMContentLoaded', fetchPreviousWork);

// --- Smart Suggestions ---
// Smart Suggestions
async function fillSmartSuggestion(daysAgo) {
    const type = templateTypeSelect.value;
    const dateInput = document.querySelector('input[name="log_date"]');
    const selectedDate = new Date(dateInput.value);
    const targetDate = new Date(selectedDate);
    targetDate.setDate(selectedDate.getDate() - daysAgo);
    const dateStr = targetDate.toISOString().split('T')[0];

    loadingIndicator.classList.remove('hidden');

    try {
        const response = await fetch('{{ route("worklog.fetch.previous") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ template_type: type, log_date: dateStr })
        });

        if (!response.ok) return console.error('Smart suggestion error', response.status);

        const data = await response.json();

        if (type === 'day_start') {
            populateWrapper('dayStartLastDayWrapper', 'day_start_last_day[]', data.lastDayWork || []);
            populateWrapper('dayStartTodayWrapper', 'day_start_today[]', data.todayWork || []);
        } else {
            populateWrapper('dayEndTodayWrapper', 'day_end_today[]', data.todayWork || []);
            populateWrapper('dayEndTomorrowWrapper', 'day_end_tomorrow[]', data.tomorrowWork || []);
        }

    } catch(err) { 
        console.error(err); 
    } finally { 
        loadingIndicator.classList.add('hidden'); 
    }
}

// --- Generate Template ---
document.getElementById('workLogForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch('{{ route("worklog.generate.ajax") }}',{
        method:'POST',
        headers:{ 'X-CSRF-TOKEN': '{{ csrf_token() }}','Accept':'application/json' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            const container = document.getElementById('generatedTemplateContainer');
            const templateDiv = document.getElementById('generatedTemplate');
            templateDiv.innerHTML = data.template; // insert HTML
            //container.classList.remove('hidden');
        } else {
            alert('Error generating template.');
        }
    })
});

// --- Copy Template ---
function copyTemplate() {
    const template = document.getElementById('generatedTemplate');
    
    // Create a temporary textarea to copy HTML content with styles
    const temp = document.createElement('textarea');
    temp.value = template.innerHTML;
    document.body.appendChild(temp);
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);

    showToast('Template copied with styles!');
}

// Toast function
function showToast(message, duration = 3000) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'bg-green-600 text-white px-4 py-2 rounded shadow-lg animate-fade-in';
    toast.innerText = message;

    container.appendChild(toast);

    // Remove after duration
    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition', 'duration-500');
        setTimeout(() => toast.remove(), 500);
    }, duration);
}
</script>
@endsection
