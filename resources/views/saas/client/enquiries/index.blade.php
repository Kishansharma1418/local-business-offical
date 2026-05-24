@extends('saas.layouts.client')
@section('title', 'Enquiries')

@section('content')
<h4 class="mb-3">Enquiries</h4>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Name</th><th>Phone</th><th>Email</th><th>Message</th><th>Status</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($enquiries as $e)
                    <tr>
                        <td><b>{{ $e->name }}</b></td>
                        <td>{{ $e->phone }}</td>
                        <td>{{ $e->email ?: '—' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($e->message, 80) }}</td>
                        <td><span class="badge badge-soft-{{ $e->status==='new'?'info':($e->status==='contacted'?'warning':'success') }}">{{ ucfirst($e->status) }}</span></td>
                        <td>{{ $e->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            @php $wa = preg_replace('/\D/', '', $e->phone); @endphp
                            <a href="https://wa.me/{{ $wa }}?text=Hi%20{{ urlencode($e->name) }}%2C%20thanks%20for%20your%20enquiry!" target="_blank" class="btn btn-sm btn-success"><i class="ri-whatsapp-line"></i></a>
                            <form action="{{ route('client.enquiries.status', $e) }}" method="POST" class="d-inline">@csrf
                                <input type="hidden" name="status" value="{{ $e->status==='new' ? 'contacted' : ($e->status==='contacted' ? 'closed' : 'new') }}">
                                <button class="btn btn-sm btn-light" title="Next Status"><i class="ri-arrow-right-line"></i></button>
                            </form>
                            <form action="{{ route('client.enquiries.destroy', $e) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?');">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="ri-delete-bin-line"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $enquiries->links() }}</div>
</div>
@endsection
