<div style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>New Contact Message</h2>
    <p><strong>Name:</strong> {{ $data['name'] ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}</p>
    <p><strong>Category:</strong> {{ $data['category'] ?? 'N/A' }}</p>
    <p><strong>Message:</strong></p>
    <div style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
        {!! nl2br(e($data['message'] ?? 'No message content')) !!}
    </div>
</div>