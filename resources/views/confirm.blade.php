<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm and Send Emails</title>
</head>
<body>
    @if(session('non_emails'))
        Οι παρακάτω διευθύνσεις <strong>δεν είναι έγκυρες</strong><br>
        <table>
            
        @foreach(session('non_emails') as $non_email)
            <tr><td>{{$non_email}}<td></tr>
        @endforeach
            
        </table>
    @endif
    <br>

    @if(session('emails'))
        Βρέθηκαν οι παρακάτω {{ session('emailCount')}} <strong>έγκυρες</strong> διευθύνσεις:<br>
        <table>
            
        @foreach(session('emails') as $email)
            <tr><td>{{$email['email']}}<td></tr>
        @endforeach
            
        </table>
        <br>
        <p><a href="{{route('preview-emails')}}" target="_blank">Preview Emails</a></p>
        <br>
        <form action="{{ route('send-emails') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <button type="submit">Send Emails</button>
        </form>
    @else
        <div style="color:red"><strong>Δε βρέθηκαν έγκυρες διευθύνσεις email</strong></div>
    @endif
</body>
</html>