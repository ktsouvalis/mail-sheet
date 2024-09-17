<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Emails</title>
</head>
<body>
    <h1>Upload XLSX file</h1>

    <form action="{{ route('upload-file') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Select XLSX file:</label>
        <input type="file" name="file" accept=".xlsx" required>
        <button type="submit">Upload Emails</button>
    </form>
    <br><br>

    @if (session()->has('success'))
        <div style="color:green">
            <strong>{{session('success')}}</strong>
        </div>
    @endif
    
    @if(session()->has('warning'))
    <div style="color:red">
        <strong> {{session('warning')}}  </strong>
    </div>
    @endif 
</body>

</html>