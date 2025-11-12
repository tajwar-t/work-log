@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Work Log History</h1>

    @if($templates->isEmpty())
        <p class="text-gray-600">No work logs generated yet.</p>
    @else
        @foreach($templates as $template)
            <div class="mb-6 border rounded p-4 bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <span class="font-semibold text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $template->template_type)) }}
                        </span>
                        <span class="text-gray-500"> | {{ \Carbon\Carbon::parse($template->log_date)->format('d/m/Y') }}</span>
                    </div>
                    <button onclick="copyTemplate('template-{{ $template->id }}')" class="bg-green-500 text-white px-3 py-1 rounded">Copy</button>
                </div>
                <div>
                    <pre id="template-{{ $template->id }}" class="whitespace-pre-wrap font-mono">{{ $template->content }}</pre>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
function copyTemplate(id) {
    const textarea = document.getElementById(id);
    if(!textarea) return;
    
    const temp = document.createElement('textarea');
    temp.value = textarea.innerText;
    document.body.appendChild(temp);
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);

    alert('Template copied to clipboard!');
}
</script>
@endsection
